<?php

namespace App\Policies;

use App\Models\Artist;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArtistPolicy
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
        return $user->role === 'artist_manager';
    }

    public function update(User $user,)
    {
        // Only artist_manager can update artists
        return $user->role === 'artist_manager';
    }

    public function delete(User $user,)
    {
        // Only artist_manager can delete artists
        return $user->role === 'artist_manager';
    }

    public function importExport(User $user)
    {
        // Only artist_manager can import/export artists
        return $user->role === 'artist_manager';
    }

    public function listSongs(User $user, Artist $artist)
    {
        // super_admin and artist_manager can view artist's songs
        return in_array($user->role, ['super_admin', 'artist_manager']);
    }


}
