<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\Items;

use App\Interfaces\FileGenerators\PdfGeneratorServiceInterface;
use App\Models\Evaluation;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Evaluation Item Service
 *
 * @package \App\Services\Items
 */
class EvaluationItemService extends BaseItemService
{
    /**
     * EvaluationItemService constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array $args
     * @return mixed
     */
    public function collection(array $args = [])
    {
        /*
         * Define params
         */
        $params  = $this->prepareCollectionParams($args);
        $filters = Arr::except($args, array_keys((array) $params));

        /*
         * Filter & order query
         */
        $query = Evaluation::query()->filter($filters)
            ->customOrderBy($params->order_by ?? 'id', $params->sort === 'desc');

        /*
         * Return results
         */
        if ($params->paginated) {
            $result = $query->paginateFilter($params->per_page);

            // Check if table is totally empty && add additional params
            $result->additionalMeta = [];

            if ($result->isEmpty()) {
                $result->additionalMeta['is_totally_empty'] = !Evaluation::query()->exists();
            }

            return $result;
        } else {
            return $query->get();
        }
    }

    /**
     * @param int  $id
     * @param bool $throwExceptionIfFail
     * @return Evaluation|null
     */
    public function find(int $id, bool $throwExceptionIfFail = true) : ?Evaluation
    {
        return $throwExceptionIfFail
            ? Evaluation::findOrFail($id)
            : Evaluation::find($id);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function exportInPdf(int $id)
    {
        $pdfGeneratorService = app(PdfGeneratorServiceInterface::class);
        $domainItemService   = app(DomainItemService::class);

        $item = $this->find($id, true);

        $pdfHeaderFooterData = [
            'header' => [
                'current_time' => Carbon::now()->format('Y-m-d H:i e'),
            ],
            'footer' => [
                'display_document_meta' => true,
            ],
        ];

        $domainsArr = array_column($item->data, 'milestones', 'domain');

        return $pdfGeneratorService->createFromBlade(
            'file-templates.pdf.evaluation',
            [
                'item'    => $item,
                'domains' => $domainItemService->collection(['only' => array_keys($domainsArr)], [
                    'subdomains' => function ($query) {
                        $query->orderBy('order')->with(['milestones']);
                    },
                ]),
            ],
            [
                'file_name'     => "evaluation_{$item->uuid}",
                'margin-top'    => 15,
                'margin-right'  => 10,
                'margin-bottom' => 15,
                'margin-left'   => 10,
                'header-html'   => view('layouts.pdf-components.pdf-file-spatie-header', ['headerData' => $pdfHeaderFooterData['header']])->render(),
                'footer-html'   => view('layouts.pdf-components.pdf-file-spatie-footer', ['footerData' => $pdfHeaderFooterData['footer']])->render(),
            ],
            false
        );
    }

    /**
     * @param array $attributes
     * @return ?Evaluation
     */
    public function create(array $attributes) : ?Evaluation
    {
        $this->prepareAttributes($attributes);

        $item = Evaluation::create($attributes);

        if ($item->exists) {
            $this->updateRelations($item, $attributes);

            return $item;
        } else {
            return null;
        }
    }

    /**
     * @param int   $id
     * @param array $attributes
     * @return bool|Evaluation
     */
    public function update(int $id, array $attributes)
    {
        $item = $this->find($id, true);

        $this->prepareAttributes($attributes);

        if ($item->update($attributes)) {
            $this->updateRelations($item, $attributes);

            return $item;
        } else {
            return false;
        }
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function save(array $attributes)
    {
        if (!empty($attributes['id'])) {
            $item = $this->find($attributes['id']);

            return $this->update($item->id, $attributes);
        } else {
            return $this->create($attributes);
        }
    }

    /**
     * @param int $id
     * @return bool|null
     */
    public function delete(int $id) : ?bool
    {
        return $this->find($id)->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | Additional methods
    |--------------------------------------------------------------------------
    */
    /**
     * @param array $attributes
     * @return void
     */
    protected function prepareAttributes(array &$attributes) : void
    {
        if (empty($attributes['uuid'])) {
            $attributes['uuid'] = Str::uuid();
        }

        if (!empty($attributes['user'])) {
            $attributes['user_id'] = $attributes['user'];

            unset($attributes['user']);
        }

        if (!empty($attributes['kita'])) {
            $attributes['kita_id'] = $attributes['kita'];

            unset($attributes['kita']);
        }

        if (!empty($attributes['ratings'])) {
            $attributes['data'] = $this->calculateRating(
                $attributes['ratings'],
                (!empty($attributes['age']) && $attributes['age'] === '4.5'),
                !empty($attributes['is_daz'])
            );
        }
    }

    /**
     * @param Evaluation $item
     * @param array      $attributes
     * @return void
     */
    protected function updateRelations(Evaluation $item, array $attributes) : void
    {
        //
    }

    /**
     * @param array $data
     * @param bool  $isAge4
     * @param bool  $isDaz
     * @return array
     */
    public function calculateRating(array $data, bool $isAge4 = false, bool $isDaz = false) : array
    {
        $result = [];

        $domainsArr = array_column($data, 'milestones', 'domain');

        if (!empty($domainsArr)) {
            $domainItemService = app(DomainItemService::class);

            $domains = $domainItemService->collection(['only' => array_keys($domainsArr)], [
                'subdomains' => function ($query) {
                    $query->orderBy('order')->with(['milestones']);
                },
            ]);

            $domains->map(function ($domain) use ($isAge4, $isDaz, $domainsArr, &$result) {
                /*
                 * Prepare data
                 */
                $domainMilestones = $domainsArr[$domain->id];
                $milestonesArr    = array_column($domainMilestones, 'value', 'id');

                $domainResult = [
                    'domain'     => $domain->id,
                    'rating'     => 0,
                    'color'      => null,
                    'milestones' => $domainsArr[$domain->id],
                ];

                /*
                 * Calculate all milestones rating
                 */
                $domain->subdomains->map(function ($subdomain) use ($isDaz, $milestonesArr, &$domainResult) {
                    $subdomain->milestones->map(function ($milestone) use ($isDaz, $milestonesArr, &$domainResult) {
                        $milestoneRating = 0;

                        if (!empty($milestonesArr[$milestone->id])) {
                            $multiplier = $isDaz ? $milestone->emphasis_daz : $milestone->emphasis;

                            $milestoneRating = $milestonesArr[$milestone->id] * $multiplier;
                        }

                        $domainResult['rating'] += $milestoneRating;
                    });
                });

                /*
                 * Get domain color by milestones rating
                 */
                if ($isAge4) {
                    $redThreshold    = $isDaz ? $domain->age_4_red_threshold_daz : $domain->age_4_red_threshold;
                    $yellowThreshold = $isDaz ? $domain->age_4_yellow_threshold_daz : $domain->age_4_yellow_threshold;
                } else {
                    $redThreshold    = $isDaz ? $domain->age_2_red_threshold_daz : $domain->age_2_red_threshold;
                    $yellowThreshold = $isDaz ? $domain->age_2_yellow_threshold_daz : $domain->age_2_yellow_threshold;
                }

                if ($domainResult['rating'] <= $redThreshold) {
                    $domainResult['color'] = 'red';
                } else if ($domainResult['rating'] > $redThreshold && $domainResult['rating'] <= $yellowThreshold) {
                    $domainResult['color'] = 'yellow';
                } else {
                    $domainResult['color'] = 'green';
                }

                $result[] = $domainResult;
            });
        }

        return $result;
    }
}
