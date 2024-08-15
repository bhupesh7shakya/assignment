<aside class="navbar navbar-vertical navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark my-3 fs-1">
            <a href="{{ route('admin.dashboard') }}">
                AMS
            </a>
        </h1>

        @php
            $links=[
                [
                    'name'=>'dashboard',
                    'route'=>'admin.dashboard',
                ],

                [
                    'name'=>'genres',
                    'route'=>'genres.index',
                ],

                [
                    'name'=>'users',
                    'route'=>'users.index',
                ],

                [
                    'name'=>'artists',
                    'route'=>'artists.index',
                ],
                [
                    'name'=>'musics',
                    'route'=>'musics.index',
                ],
                [
                    'name'=>'albums',
                    'route'=>'albums.index',
                ],


                ]
        @endphp

        <div class="collapse navbar-collapse" id="navbar-menu">
            <ul class="navbar-nav pt-lg-3">

                @foreach ($links as $link)
                <li class="nav-item m-2">
                    <a class="nav-link" href="{{ route($link['route']) }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/home -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-dashboard"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <desc>Download more icon variants from https://tabler-icons.io/i/dashboard</desc>
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <circle cx="12" cy="13" r="2"></circle>
                                <line x1="13.45" y1="11.55" x2="15.5" y2="9.5"></line>
                                <path d="M6.4 20a9 9 0 1 1 11.2 0z"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title fs-2">
                            {{ucfirst($link['name'])}}
                        </span>
                    </a>
                </li>
                @endforeach

            </ul>
        </div>

    </div>
</aside>
