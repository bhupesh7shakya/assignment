<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Artist;
use App\Models\Genre;
use App\Models\Music;
use App\Models\User;
use App\Policies\ArtistPolicy;
use App\Policies\GenrePolicy;
use App\Policies\MusicPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Artist::class => ArtistPolicy::class,
        Music::class => MusicPolicy::class,
        Genre::class => GenrePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Gate::define('manage-users', function (User $user) {
        //     return $user->role === 'super_admin';
        // });

        // Gate::define('manage-artists', function (User $user) {
        //     return in_array($user->role, ['super_admin', 'artist_manager']);
        // });

        // Gate::define('manage-songs', function (User $user) {
        //     return $user->role === 'artist';
        // });
    }
}
