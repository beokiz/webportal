<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TrainingProposalsImport;
use Illuminate\Support\Facades\Storage;

/**
 * Import Training Proposals Data Command
 *
 * @package \App\Console\Commands
 */
class ImportTrainingProposalsDataCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'excel:import {filename}';

    /**
     * @var string
     */
    protected $description = 'Import trainings data from an Excel file using the specified file name
                                    {filename : The name of the Excel file}';

    /**
     * @return int
     */
    public function handle() : int
    {
        // Get the file name and disk option from the command line arguments
        $filename = $this->argument('filename');
        $disk = 'import_files';

        // Check if the file exists in the specified disk
        if (!Storage::disk($disk)->exists($filename)) {
            $this->error(__('commands.import_trainings.file_not_exist_message', ['filename' => $filename]));

            return Command::FAILURE;
        }

        // Display a message indicating the start of the import process
        $this->info(__('commands.import_trainings.start_message', ['filename' => $filename]));

        // Retrieve the file from the specified disk
        $filePath = Storage::disk($disk)->path($filename);

        // Perform the import using the DataImport class
        Excel::import(new TrainingProposalsImport, $filePath);

        // Display a success message after the import completes
        $this->info(__('commands.import_trainings.success_message', ['filename' => $filename]));

        return Command::SUCCESS;
    }
}
