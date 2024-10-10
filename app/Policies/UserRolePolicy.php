<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Policies;

use App\Models\SurveyTimePeriod;
use App\Models\Training;
use App\Models\TrainingProposal;
use App\Models\User;
use Illuminate\Support\Carbon;

/**
 * User Role Policy
 *
 * @package \App\Policies
 */
class UserRolePolicy extends BasePolicy
{
    /*
    |--------------------------------------------------------------------------
    | Base methods
    |--------------------------------------------------------------------------
    */
    /**
     * @param User  $user
     * @param mixed $roles
     * @return bool
     */
    public function authorizeRoleAccess(User $user, $roles) : bool
    {
        return $user->hasAnyRole($roles);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function authorizeSuperAdminAccess(User $user) : bool
    {
        return $this->authorizeRoleAccess($user, config('permission.project_roles.super_admin'));
    }

    /**
     * @param User $user
     * @return bool
     */
    public function authorizeAdminAccess(User $user) : bool
    {
        $roles = config('permission.project_roles');

        return $this->authorizeRoleAccess($user, [$roles['super_admin'], $roles['admin']]);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function authorizeMonitorAccess(User $user) : bool
    {
        return $this->authorizeRoleAccess($user, config('permission.project_roles.monitor'));
    }

    /**
     * @param User $user
     * @return bool
     */
    public function authorizeMonitorOeAccess(User $user) : bool
    {
        return $this->authorizeRoleAccess($user, config('permission.project_roles.monitor_oe'));
    }

    /**
     * @param User $user
     * @return bool
     */
    public function authorizeManagerAccess(User $user) : bool
    {
        return $this->authorizeRoleAccess($user, config('permission.project_roles.manager'));
    }

    /**
     * @param User $user
     * @return bool
     */
    public function authorizeUserMultiplierAccess(User $user) : bool
    {
        return $this->authorizeRoleAccess($user, config('permission.project_roles.user_multiplier'));
    }

    /**
     * @param User $user
     * @return bool
     */
    public function authorizeEmployerAccess(User $user) : bool
    {
        return $this->authorizeRoleAccess($user, config('permission.project_roles.employer'));
    }

    /*
    |--------------------------------------------------------------------------
    | Special methods
    |--------------------------------------------------------------------------
    */
    /**
     * @param User $user
     * @return bool
     */
    public function authorizeAccessToUsers(User $user) : bool
    {
        $roles = config('permission.project_roles');

        return $this->authorizeRoleAccess($user, [$roles['super_admin'], $roles['admin'], $roles['manager']]);
    }

    /**
     * @param User $user
     * @param int  $userId
     * @return bool
     */
    public function authorizeAccessToSingleUser(User $user, int $userId) : bool
    {
        $roles = config('permission.project_roles');

        if ($this->authorizeRoleAccess($user, [$roles['super_admin'], $roles['admin']])) {
            return true;
        }

        if ($this->authorizeRoleAccess($user, [$roles['manager']])) {
            $canAccess = false;

            $user->kitas->each(function ($kita) use (&$canAccess, $userId) {
                if ($kita->users->contains('id', $userId)) {
                    $canAccess = true;
                    return $canAccess;
                }
            });

            return $canAccess;
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function authorizeAccessToKitas(User $user) : bool
    {
        $roles = config('permission.project_roles');

        if ($this->authorizeRoleAccess($user, [$roles['super_admin'], $roles['admin'], $roles['manager']])) {
            return true;
        }

        if ($this->authorizeRoleAccess($user, [$roles['user_multiplier']])) {
            $user->loadMissing(['operators']);

            return $user->operators->where('self_training', true)->count() > 0;
        }

        return false;
    }

    /**
     * @param User $user
     * @param int  $kitaId
     * @return bool
     */
    public function authorizeAccessToSingleKita(User $user, int $kitaId) : bool
    {
        $roles = config('permission.project_roles');

        if ($this->authorizeRoleAccess($user, [$roles['super_admin'], $roles['admin']])) {
            return true;
        }

        if ($this->authorizeRoleAccess($user, [$roles['manager']])) {
            $user->loadMissing(['kitas']);

            return $user->kitas->contains('id', $kitaId);
        }

        if ($this->authorizeRoleAccess($user, [$roles['user_multiplier']])) {
            $user->loadMissing(['operators.kitas', 'trainings.kitas', 'trainingProposals.kitas']);

            $isMultiOperatorKita = optional($user->operators)
                ->where('self_training', true)
                ->pluck('kitas.*.id')
                ->flatten()
                ->unique()
                ->contains($kitaId);

            $isMultiTrainingKita = optional($user->trainings)->pluck('kitas.*.id')
                ->flatten()
                ->unique()
                ->contains($kitaId);

            $isMultiTrainingProposalKita = optional($user->trainingProposals)->pluck('kitas.*.id')
                ->flatten()
                ->unique()
                ->contains($kitaId);

            return $isMultiOperatorKita || $isMultiTrainingKita || $isMultiTrainingProposalKita;
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function authorizeAccessToEvaluations(User $user) : bool
    {
        $roles = config('permission.project_roles');

        return $this->authorizeRoleAccess($user, [$roles['super_admin'], $roles['manager'], $roles['employer']]);
    }

    /**
     * @param User $user
     * @param int  $evaluationId
     * @return bool
     */
    public function authorizeAccessToSingleEvaluation(User $user, int $evaluationId) : bool
    {
        $roles = config('permission.project_roles');

        if ($this->authorizeRoleAccess($user, [$roles['super_admin']])) {
            return true;
        }

        if ($this->authorizeRoleAccess($user, [$roles['manager'], $roles['employer']])) {
            return $user->evaluations->contains('id', $evaluationId);
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function authorizeAccessToManageEvaluation(User $user) : bool
    {
        $roles = config('permission.project_roles');

        return $this->authorizeRoleAccess($user, [$roles['manager'], $roles['employer']]);
    }

    /**
     * @param User $user
     * @param int  $evaluationId
     * @return bool
     */
    public function authorizeAccessToManageSingleEvaluation(User $user, int $evaluationId) : bool
    {
        $roles = config('permission.project_roles');

        if ($this->authorizeRoleAccess($user, [$roles['manager'], $roles['employer']])) {
            return $user->evaluations->contains('id', $evaluationId);
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function authorizeAccessToExport(User $user) : bool
    {
        $roles = config('permission.project_roles');

        return $this->authorizeRoleAccess($user, [$roles['super_admin'], $roles['monitor'], $roles['monitor_oe']]);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function authorizeAccessToSurveyTimePeriods(User $user) : bool
    {
        $roles = config('permission.project_roles');

        return $this->authorizeRoleAccess($user, [$roles['super_admin'], $roles['admin']]);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function authorizeAccessToYearlyEvaluations(User $user) : bool
    {
        $roles = config('permission.project_roles');

        if ($this->authorizeRoleAccess($user, [$roles['manager']])) {
            $today = Carbon::today();

            $isSurveyPeriod = SurveyTimePeriod::where('survey_start_date', '<=', $today)
                ->where('survey_end_date', '>=', $today)
                ->where('year', $today->year)
                ->count();

            return $isSurveyPeriod > 0;
        }

        return $this->authorizeRoleAccess($user, [$roles['super_admin'], $roles['admin']]);
    }

    /**
     * @param User $user
     * @param int  $kitaId
     * @return bool
     */
    public function authorizeAccessToSingleYearlyEvaluations(User $user, int $kitaId) : bool
    {
        $roles = config('permission.project_roles');

        if ($this->authorizeRoleAccess($user, [$roles['super_admin']])) {
            return true;
        }

        if ($this->authorizeRoleAccess($user, [$roles['manager']])) {
            $today = Carbon::today();

            $isSurveyPeriod = SurveyTimePeriod::where('survey_start_date', '<=', $today)
                ->where('survey_end_date', '>=', $today)
                ->where('year', $today->year)
                ->count();

            return $user->kitas->pluck('id')->contains($kitaId) && $isSurveyPeriod > 0;
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function authorizeAccessToSettings(User $user) : bool
    {
        $roles = config('permission.project_roles');

        return $this->authorizeRoleAccess($user, [$roles['super_admin'], $roles['admin']]);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function authorizeAccessToDownloadableFiles(User $user) : bool
    {
        $roles = config('permission.project_roles');

        return $this->authorizeRoleAccess($user, [$roles['super_admin'], $roles['admin']]);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function authorizeAccessToDownloadArea(User $user) : bool
    {
        $roles = config('permission.project_roles');

        return $this->authorizeRoleAccess($user, [$roles['manager'], $roles['employer']]);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function authorizeAccessToOperators(User $user) : bool
    {
        $roles = config('permission.project_roles');

        return $this->authorizeRoleAccess($user, [$roles['super_admin'], $roles['admin']]);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function authorizeAccessToTrainings(User $user) : bool
    {
        $roles = config('permission.project_roles');

        return $this->authorizeRoleAccess($user, [$roles['super_admin'], $roles['admin'], $roles['user_multiplier']]);
    }

    /**
     * @param User     $user
     * @param Training $training
     * @return bool
     */
    public function authorizeAccessToSingleTraining(User $user, Training $training) : bool
    {
        $roles = config('permission.project_roles');

        if ($this->authorizeRoleAccess($user, [$roles['super_admin'], $roles['admin']])) {
            return true;
        }

        if ($this->authorizeRoleAccess($user, [$roles['user_multiplier']])) {
            return $training->multi_id === $user->id;
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function authorizeAccessToTrainingProposals(User $user) : bool
    {
        $roles = config('permission.project_roles');

        return $this->authorizeRoleAccess($user, [$roles['super_admin'], $roles['admin'], $roles['user_multiplier']]);
    }

    /**
     * @param User             $user
     * @param TrainingProposal $trainingProposal
     * @return bool
     */
    public function authorizeAccessToSingleTrainingProposal(User $user, TrainingProposal $trainingProposal) : bool
    {
        $roles = config('permission.project_roles');

        if ($this->authorizeRoleAccess($user, [$roles['super_admin'], $roles['admin']])) {
            return true;
        }

        if ($this->authorizeRoleAccess($user, [$roles['user_multiplier']])) {
            return $trainingProposal->multi_id === $user->id;
        }

        return false;
    }
}
