<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ouvrages;
use Illuminate\Auth\Access\HandlesAuthorization;

class OuvragePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        // Tous les utilisateurs authentifiés peuvent voir la liste des ouvrages
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ouvrages  $ouvrage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Ouvrages $ouvrage)
    {
        // Tous les utilisateurs authentifiés peuvent voir un ouvrage
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // Seuls les administrateurs peuvent créer des ouvrages
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ouvrages  $ouvrage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Ouvrages $ouvrage)
    {
        // Seuls les administrateurs peuvent mettre à jour des ouvrages
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ouvrages  $ouvrage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Ouvrages $ouvrage)
    {
        // Seuls les administrateurs peuvent supprimer des ouvrages
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ouvrages  $ouvrage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Ouvrages $ouvrage)
    {
        // Seuls les administrateurs peuvent restaurer des ouvrages
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ouvrages  $ouvrage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Ouvrages $ouvrage)
    {
        // Seuls les administrateurs peuvent supprimer définitivement des ouvrages
        return $user->role === 'admin';
    }
}
