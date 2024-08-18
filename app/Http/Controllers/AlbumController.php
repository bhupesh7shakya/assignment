<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Shared\SharedController;
use App\Models\Album;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AlbumController extends SharedController
{
    //
    public $title = "Album";
    public $class_instance = Album::class;
    public $route_name = "albums";
    public $rules = [
        "name" => ["required", "max:20",],
    ];
    public $table_headers = ["album_name", "music_count"];
    public $columns = ["name", "music_count"];

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
            if (Session::has('artist_id')) {
                $data = $this->class_instance::where('artist_id', Session::get('artist_id'));
                if (Auth::user()->role != "super_admin") {
                    $data = $data->where('user_id', Auth::user()->id);
                }
                $data->get();
            } else {
                $data = $this->class_instance::withCount('music');
                // dd(Auth::user()->role );
                // Apply the role-based filter if the user is not a super admin
                if (!in_array(Auth::user()->role, ["super_admin", "artist_manager"])) {
                    $data = $data->where('user_id', Auth::user()->id);
                }

                // Get the results
                $data = $data->get();
            }
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
        // dd($validator->validated());
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $validated = $validator->validated();
        $validated['user_id'] = (Auth::user()->role == "super_admin") ? $request->user_id : Auth::user()->id;
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
                    ['type' => 'text', 'name' => 'name', 'label' => 'name', 'value' => (isset($data->name)) ? $data->name : null, 'placeholder' => 'name',],
                    (Auth::user()->role == "super_admin") ?
                        [
                            'options' =>
                            Artist::all()->pluck('name', 'user_id'),
                            'name' => 'user_id',
                            'label' => 'Of Artist',
                            'value' => (isset($data->user_id)) ? $data->user_id : null,
                        ]
                        : []
                ],
            ]
        ];
    }
}
