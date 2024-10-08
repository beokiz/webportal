<?php

namespace App\Imports;

use App\Models\Kita;
use App\Models\TrainingProposal;
use App\Models\User;
use App\Services\Items\KitaItemService;
use App\Services\Items\RoleItemService;
use App\Services\Items\TrainingProposalItemService;
use App\Services\Items\UserItemService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

/**
 * Trainings Import
 *
 * @package \App\Imports
 */
class TrainingProposalsImport implements ToCollection
{
    /**
     * Handle the imported data from the Excel file as a collection.
     *
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        // Skip the header row (assuming it is the first row)
        $rows = $rows->skip(1);

        foreach ($rows as $row) {
            // Process each row of data
            $this->processRow($row->toArray());
        }
    }

    /**
     * Process each row individually.
     *
     * @param array $row
     */
    private function processRow(array $row)
    {
        $isDevMode = config('app.env') !== 'production';

        $kitaItemService             = app(KitaItemService::class);
        $roleItemService             = app(RoleItemService::class);
        $trainingProposalItemService = app(TrainingProposalItemService::class);
        $userItemService             = app(UserItemService::class);

        // Disable the observer for models
        Kita::flushEventListeners();
        TrainingProposal::flushEventListeners();
        User::flushEventListeners();

        // Find 'manager' Role for Users
        $managerRole = $roleItemService->findByName(config('permission.project_roles.manager'));

        /*
         * Import data
         */
        // Process data if the following columns are not empty: 'to import', 'name', 'zip_code'
        if (!empty($row[0]) && !empty($row[2]) && !empty($row[5]) && !empty($row[6])) {
            /*
             * Create Kita
             */
            $zipCodeData = explode(' ', $row[6]);

            $kitaArgs = [
                'approved'       => true,
                'name'           => $row[2],
                'number'         => !empty($row[3]) ? $row[3] : null,
                'city'           => !empty($zipCodeData[1]) ? $zipCodeData[1] : null,
                'district'       => !empty($row[4]) ? $row[4] : null,
                'street'         => $row[5],
                'zip_code'       => !empty($zipCodeData[0]) ? $zipCodeData[0] : null,
                'other_operator' => !empty($row[7]) ? $row[7] : null,
            ];

            if (!empty($kitaArgs['number'])) {
                $kita = $kitaItemService->findByNumber($kitaArgs['number']);
            }

            if (empty($kita)) {
                $kita = $kitaItemService->create($kitaArgs);
            }

            /*
             * Create Kita manager User
             * Process data if the following columns are not empty: 'first_name', 'last_name', 'email'
             */
            if (!empty($kita) && !empty($row[9]) && !empty($row[10]) && !empty($row[11])) {
                $userArgs = [
                    'first_name'        => $row[9],
                    'last_name'         => $row[10],
                    'email'             => $isDevMode ? "test_{$row[11]}" : $row[11],
                    'phone_number'      => !empty($row[12]) ? $row[12] : null,
                    'email_verified_at' => Carbon::now(),
                    'password'          => Str::random(20),
                ];

                $user = $userItemService->findByEmail($userArgs['email']);

                if (empty($user)) {
                    $user = $userItemService->create(array_merge($userArgs, [
                        'role' => $managerRole->id,
                    ]));
                }

                // Add User to Kita
                $kitaItemService->updateAttachedUsers($kita->id, [$user->id], false, !$isDevMode);
            }

            /*
             * Create Kita Training proposal
             * Process data if the following columns are not empty: 'participant_count', 'first_date_1', 'second_date_1', 'first_date_2', 'second_date_2'
             */
            $hasFirstTrainingProposalArgs  = (!empty($row[15]) && !empty($row[16]));
            $hasSecondTrainingProposalArgs = (!empty($row[17]) && !empty($row[18]));

            if (!empty($kita) && !empty($row[14]) && ($hasFirstTrainingProposalArgs || $hasSecondTrainingProposalArgs)) {
                $commonTrainingProposalArgs = [
                    'participant_count' => $row[14],
                    'street'            => $kita->street,
                    'house_number'      => $kita->house_number,
                    'zip_code'          => $kita->zip_code,
                    'city'              => $kita->city,
                    'district'          => $kita->district,
                    'notes'             => !empty($row[13]) ? $row[13] : null,
                ];

                if ($hasFirstTrainingProposalArgs) {
                    $firstTrainingProposalArgs = [
                        'first_date'  => Carbon::instance(ExcelDate::excelToDateTimeObject($row[15])),
                        'second_date' => Carbon::instance(ExcelDate::excelToDateTimeObject($row[16])),
                        'location' => $kita->name,
                    ];

                    $firstTrainingProposal = $trainingProposalItemService->create(array_merge($commonTrainingProposalArgs, $firstTrainingProposalArgs));

                    // Add Kita to Training proposals
                    $trainingProposalItemService->updateAttachedKitas($firstTrainingProposal->id, [$kita->id]);
                }

                if ($hasSecondTrainingProposalArgs) {
                    $secondTrainingProposalArgs = [
                        'first_date'  => Carbon::instance(ExcelDate::excelToDateTimeObject($row[17])),
                        'second_date' => Carbon::instance(ExcelDate::excelToDateTimeObject($row[18])),
                        'location' => $kita->name,
                    ];

                    $secondTrainingProposal = $trainingProposalItemService->create(array_merge($commonTrainingProposalArgs, $secondTrainingProposalArgs));

                    // Add Kita to Training proposals
                    $trainingProposalItemService->updateAttachedKitas($secondTrainingProposal->id, [$kita->id]);
                }
            }
        }
    }
}
