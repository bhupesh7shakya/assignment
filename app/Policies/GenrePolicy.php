<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GenrePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user)
    {
        // super_admin and artist_manager can list artists
        return in_array($user->role, ['super_admin', 'artist_manager']);
    }

    public function create(User $user)
    {
        // Only artist_manager can create artists
        return $user->role === 'super_admin';
    }

    public function update(User $user,)
    {
        // Only super_admin can update super_admins
        return $user->role === 'super_admin';
    }

    public function delete(User $user,)
    {
        // Only super_admin can delete super_admins
        return $user->role === 'super_admin';
    }

    public function importExport(User $user)
    {
        // Only super_admin_manager can import/export super_admins
        return $user->role === 'super_admin';
    }
}
