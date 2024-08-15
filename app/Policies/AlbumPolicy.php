<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlbumPolicy
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
        return in_array($user->role, ['super_admin', 'artist_manager','artist']);
    }

    public function create(User $user)
    {
        // Only artist_manager can create artists
        return $user->role === 'artist';
    }

    public function update(User $user,)
    {
        // Only artist can update artists
        return $user->role === 'artist';
    }

    public function delete(User $user,)
    {
        // Only artist can delete artists
        return $user->role === 'artist';
    }

    public function importExport(User $user)
    {
        // Only artist_manager can import/export artists
        return $user->role === 'artist';
    }
}
