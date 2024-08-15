@extends('admin.layouts.main')


@section('admin-content')
@can('viewAny', $data['model'])

    @if (isset($data['no_create']) &&$data['no_create'] == true)
        <x-card.card :title="['title' => $data['title'],'model'=>$data['model']]" >

            {{-- start card --}}
            {{-- start table --}}
            <x-table.table :tableHeaders="$data['table_headers']" />

            {{-- end table --}}
        </x-card.card>
        {{-- end of card --}}
    @else
        <x-card.card :title="['title' => $data['title'], 'route' => route($data['route_name'] . '.create'),'model'=>$data['model']]">
            {{-- start card --}}
            @can('importExport',$data['model'])
            @if ($data['route_name']=="artists")
            <br>
            <br>
            <br>
            <div class="p-3 d-flex justify-content-between">

                <a class="btn btn-primary" href="{{route($data['route_name'].'.export')}}"/>Export</a>

                <form action="{{route($data['route_name'].'.import')}}" enctype="multipart/form-data" method="post">
                    @csrf
                    <input type="file" name="file" id="" class="form">
                @error("file")
                    <br>
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <button type="submit" class="btn btn-primary">Import</button>

                </form>

            </div>
            <br>
            @endif

            @endcan

            {{-- start table --}}
            <x-table.table :tableHeaders="$data['table_headers']" />

            {{-- end table --}}
        </x-card.card>
        {{-- end of card --}}
    @endif
    @else
    <div class="container d-flex justify-content-center align-content-center" style="height:20vw">
        <div class="message-box">
            <h1 class="display-4">Access Denied</h1>
            <p class="lead">You do not have permission to view this page.</p>
        </div>
    </div>
    @endcan
@endsection
{{-- custom script start --}}
@section('admin-scripts')
    {{-- datatable --}}
    <x-script.datatable-script :options="[
        'route' => route($data['route_name'] . '.index'),
        'columns' => $data['columns'],
    ]" />
    {{-- datatable --}}
    {{-- </x-script.datatable-script> --}}
    {{-- alert --}}
    <x-script.alert></x-script.alert>
    {{-- alert --}}
    <x-script.delete-script route="{{ route($data['route_name'] . '.destroy', '') }}" />
@endsection
{{-- custom script ends --}}
