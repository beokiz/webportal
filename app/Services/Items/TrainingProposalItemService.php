<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\Items;

use App\Models\TrainingProposal;
use Illuminate\Support\Arr;

/**
 * Training Proposal Item Service
 *
 * @package \App\Services\Items
 */
class TrainingProposalItemService extends BaseItemService
{
    /**
     * TrainingProposalItemService constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array $args
     * @param array $with
     * @return mixed
     */
    public function collection(array $args = [], array $with = [])
    {
        /*
         * Define params
         */
        $params  = $this->prepareCollectionParams($args);
        $filters = Arr::except($args, array_keys((array) $params));

        /*
         * Filter & order query
         */
        $query = TrainingProposal::query()->filter($filters)
            ->with(array_merge($with, ['multiplier']))
            ->customOrderBy($params->order_by ?? 'id', $params->sort === 'desc');

        /*
         * Return results
         */
        if ($params->paginated) {
            $result = $query->paginateFilter($params->per_page);

            // Check if table is totally empty && add additional params
            $result->additionalMeta = [];

            if ($result->isEmpty()) {
                $result->additionalMeta['is_totally_empty'] = !TrainingProposal::query()->exists();
            }

            return $result;
        } else {
            return $query->get();
        }
    }

    /**
     * @param int  $id
     * @param bool $throwExceptionIfFail
     * @return TrainingProposal|null
     */
    public function find(int $id, bool $throwExceptionIfFail = true) : ?TrainingProposal
    {
        return $throwExceptionIfFail
            ? TrainingProposal::findOrFail($id)
            : TrainingProposal::find($id);
    }

    /**
     * @param array $attributes
     * @return ?TrainingProposal
     */
    public function create(array $attributes) : ?TrainingProposal
    {
        $this->prepareAttributes($attributes);

        $item = TrainingProposal::create($attributes);

        if ($item->exists) {
            return $item;
        } else {
            return null;
        }
    }

    /**
     * @param int   $id
     * @param array $attributes
     * @return bool
     */
    public function update(int $id, array $attributes) : bool
    {
        $item = $this->find($id);

        $this->prepareAttributes($attributes);

        if ($item->update($attributes)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int    $id
     * @param string $token
     * @return bool
     */
    public function confirm(int $id, string $token) : bool
    {
        if (!empty($token)) {
            $trainingItemService = app(TrainingItemService::class);

            $item = $this->find($id)->loadMissing(['kitas', 'trainingProposalConfirmations']);

            $trainingProposalConfirmation = $item->trainingProposalConfirmations->where('token', $token)->first();

            if (!empty($trainingProposalConfirmation) && $item->kitas->contains('id', $trainingProposalConfirmation->kita_id)) {
                if ($item->status !== TrainingProposal::STATUS_CONFIRMED) {
                    $item->update(['status' => TrainingProposal::STATUS_CONFIRMED]);
                }

                // If the Kita manager has confirmed his participation, we add the Kita to the training
                $trainings = $trainingItemService->collection([
                    'paginated'         => false,
                    'training_proposal' => $item->id,
                ]);

                if (!empty($trainings) && $trainings->isNotEmpty()) {
                    $trainingItemService->updateAttachedKitas(
                        $trainings->first()->id,
                        [$trainingProposalConfirmation->kita_id],
                        false
                    );
                }

                return $trainingProposalConfirmation->update(['confirmed' => true]);
            }
        }

        return false;
    }

    /**
     * @param int   $id
     * @param array $kitas
     * @param bool  $removeKitas
     * @return bool|null
     */
    public function updateAttachedKitas(int $id, array $kitas, bool $removeKitas = false) : ?bool
    {
        $item = $this->find($id);

        if (!empty($kitas)) {
            if ($removeKitas) {
                $item->kitas()->detach($kitas);
            } else {
                $trainingKitas = $item->kitas->pluck('id');

                foreach ($kitas as $userId) {
                    $userId = (int) $userId;

                    if (!$trainingKitas->contains($userId)) {
                        $trainingKitas->add($userId);
                    }
                }

                if (!empty($trainingKitas)) {
                    $item->kitas()->sync($trainingKitas);
                }
            }

            return true;
        }

        return false;
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
        //
    }
}
