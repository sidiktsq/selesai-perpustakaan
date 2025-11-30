<?php

namespace App\Policies;

use App\Models\Dashboard;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DashboardPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view dashboards
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Dashboard $dashboard): bool
    {
        return true; // All authenticated users can view a dashboard
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admin users can create dashboards
        return $user->is_admin === true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Dashboard $dashboard): bool
    {
        // Only admin users can update dashboards
        return $user->is_admin === true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Dashboard $dashboard): bool
    {
        // Only admin users can delete dashboards
        return $user->is_admin === true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Dashboard $dashboard): bool
    {
        return $user->is_admin === true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Dashboard $dashboard): bool
    {
        return $user->is_admin === true;
    }
}