<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Shared\SharedController;
use App\Models\Album;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\Music;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PharIo\Manifest\Author;
use Yajra\DataTables\Facades\DataTables;

class MusicController extends SharedController
{
    public $title = "Music";
    public $class_instance = Music::class;
    public $route_name = "musics";
    public $rules = [
        "title" => ["required", "max:20",],
        "album_id" => ["required", "max:20", "exists:albums,id"],
        "genre_id" => ["required", "exists:genres,id"],
        "artist_id"=>["exists:artists,id"],
    ];
    public $table_headers = ["title", "album_name", "genre_name", "artist",];
    public $columns = ["title", "album_name", "genre_name", "artist_name",];

    public function index(Request $request)
    {
        // return     $data = $this->class_instance::all();

        // return $this->currentFiscalYear();

        $artist_id = $request->artist_id;
        if (!Session::has($artist_id)) {
            // dd(Session::has($artist_id));
            Session::put('artist_id', $artist_id);
        }
        if (Gate::denies('viewAny', $this->class_instance)) {
            abort(403, "You do not have permssion for this action");
        }
        if (isset($this->relation)) {
            $data = $this->class_instance::with($this->relation)->get();
        } else {

            $data=$this->class_instance::getRecords();
            // if (Session::has('artist_id')) {
            //     $data = $this->class_instance::getAllRecords();
            //     if (Auth::user()->role != "super_admin") {
            //         $data = $data->where('user_id', Auth::user()->id);
            //     }
            //     $data = $data->get();
            // } else {
            //     if (Auth::user()->role != "super_admin") {
            //         $userId = Auth::id();

            //         // Step 2: Get the IDs of artists associated with the authenticated user
            //         $artistIds = Artist::where('user_id', $userId)->pluck('id')->toArray();

            //         // Step 3: Combine the user ID and artist IDs into a single array
            //         $ids = array_merge([$userId], $artistIds);

            //         // Step 4: Query the model based on the combined IDs
            //         $data = $this->class_instance::with('album')->whereIn('artist_id', $ids)->get();
            //     } else {
            //         // dd(Auth::user()->role!="super_admin");
            //         $data = $this->class_instance::with('album')->get();
            //     }
            // }
        }
        if ($request->ajax()) {
            // dd($data);
            Session::remove('artist_id');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $delete_button = '
                <button  type="button" class="btn btn-danger tabler-card" disabled ><i class="ti ti-trash"></i></i></button>';
                    if (!Gate::denies('delete', $this->class_instance)) {
                        $delete_button = '
                                   <button onclick="del(' . $row['id'] . ')" type="button" class="btn btn-danger" ><i class="ti ti-trash"></i></i></button>

                        ';
                    }
                    $edit_button = '
                     <a href="#" disabled>
                                     <button  type="button" class="btn btn-primary" disabled  ><i class="ti ti-edit"></i></button>
                                </a>

                ';
                    if (!Gate::denies('update', $this->class_instance)) {

                        $edit_button = '
                             <a href="' . route("{$this->route_name}.edit", "{$row['id']}") . '" >
                                     <button  type="button" class="btn btn-primary"  ><i class="ti ti-edit"></i></button>
                                </a>';
                    }
                    return '<div class="text-center">
                                ' . $edit_button . '
                                <!-- <a href="' . route("{$this->route_name}.show", "{$row['id']}") . '">
                                    <button  type="button" class="btn btn-primary" ><i class="fa fa-eye"></i></button>
                                 </a>-->
            ' . $delete_button . '
                            </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $data['title'] = $this->title;
        $data['route_name'] = $this->route_name;
        $data['table_headers'] = $this->table_headers;
        $data['columns'] = $this->columns;
        $data['model'] = $this->class_instance;
        return view($this->view_path . '.index', compact('data'));
    }


    public function store(Request $request)
    {
        if (Gate::denies('create', $this->class_instance)) {
            abort(403, "You do not have permssion for this action");
        }
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            // dd($validator->errors());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $validated = $validator->validated();
        // dd(Auth::user()->id);
        Artist::all()->where('user_id', Auth::user()->id);
        $validated['artist_id'] = (Auth::user()->role=="super_admin")?$request->artist_id:Artist::all()->where('user_id', Auth::user()->id)->first()->id;
        // return $validated;
        if ($this->class_instance::create($validated)) {
            session()->flash("success", "Data inserted successfully");
            return redirect()->route("{$this->route_name}.index");
        } else {
            session()->flash("error", "Server Error Code 500");
            return redirect()->route("{$this->route_name}.index");
        }
    }


    public function createForm($data = null, $method = 'post', $action = 'store')
    {
        $this->form = [
            'route' => route($this->route_name . '.' . $action, (isset($data->id) ? $data->id : null)),
            'method' => $method,
            'fields' =>
            [
                [
                    ['type' => 'text', 'name' => 'title', 'label' => 'Title', 'value' => (isset($data->title)) ? $data->title : null, 'placeholder' => 'Title',],
                    [
                        'options' => (Auth::user()->role == "super_admin") ?
                            Album::all()->pluck('name', 'id') :
                            Album::all()->where('user_id', Auth::user()->id)->pluck('name', 'id'),
                        'name' => 'album_id',
                        'label' => 'Album',
                        'value' => (isset($data->album_id)) ? $data->album_id : null,
                    ],
                ],
                [

                    [
                        'options' =>
                        Genre::all()->pluck('name', 'id'),
                        'name' => 'genre_id',
                        'label' => 'Genre',
                        'value' => (isset($data->genre_id)) ? $data->genre_id : null,
                    ],
                    // ['options'=>
                    //   Artist::where('user_id',Auth::user()->id)->pluck('name','id')
                    // , 'name' => 'artist_id', 'label' => 'Artist', 'value' => (isset($data->artist_id)) ? $data->artist : null,],

                    (Auth::user()->role == "super_admin") ?
                        [
                            'options' =>
                            Artist::all()->pluck('name', 'id'),
                            'name' => 'artist_id',
                            'label' => 'Assign Artist',
                            'value' => (isset($data->artist_id)) ? $data->artist_id : null,
                        ]
                        : []
                ],
            ]
        ];
    }
}
