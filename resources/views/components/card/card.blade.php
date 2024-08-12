<div class="card border-2" style="
/* @if (!isset($title['route'])) width:30rem @endif */
">
    <div class="card-body">
        <div class="card-title ">

            @if (is_array($title))
                <b></b><h4 class="text-center display-6 ">
                    {{ $title['title'] }}
                </h4></b>
            @can('create', $title['model'])

                @isset($title['route'])
                    <a class="float-end mx-4 my-3" href="{{ $title['route'] }}">Add {{ $title['title'] }}</a>
                @endisset
            @endcan


            @else
                <b>
                    <h1 class="text-center ">
                        {{ $title }}
                    </h1>
                </b>
            @endif
        </div>
        <div class="px-4">
            {{ $slot }}

        </div>
    </div>
</div>
