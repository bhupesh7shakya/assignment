<?php

namespace App\Http\Controllers;

use App\Exports\ArtistExport;
use App\Http\Controllers\Shared\SharedController;
use App\Imports\ArtistImport;
use App\Models\Album;
use App\Models\Artist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Yajra\DataTables\Facades\DataTables;

class ArtistController extends SharedController
{

    public function index(Request $request)
    {

        // return $this->currentFiscalYear();
        if (Gate::denies('viewAny', $this->class_instance)) {
            abort(403, "You do not have permssion for this action");
        }
        if ($request->ajax()) {


            $data = $this->class_instance::getAllRecord();


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
                                <a href="' . route("musics.index",) . '?artist_id=' . $row['id'] . '">
                                    <button  type="button" class="btn btn-primary" ><i class="fa fa-eye">🎵</i></button>
                                 </a>
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
    public $title = "Artist";
    public $class_instance = Artist::class;
    public $route_name = "artists";
    public $rules = [
        "name" => ["required", "unique :artists,name", "max:20",],
        "first_name" => ["required", "max:20",],
        "last_name" => ["required", "max:20",],
        "dob" => ["required"],
        "first_release_year" => ["required", "date",],
        "gender" => ["required"],
        "email" => ['required', 'email', 'unique:users,email'],
        "password" => ['required', 'min:6', 'max:255', 'unique:users,password'],
        "address" => ['required', 'min:3', 'max:255'],
        "phone" => ['required', 'regex:/^(98|97)\d{8}$/']
    ];
    public $table_headers = ["name", "dob", "gender", "first_release_year",];
    public $columns = ["name", "dob", "gender", "first_release_year"];


    public function store(Request $request)
    {
        if (Gate::denies('create', $this->class_instance)) {
            abort(403, "You do not have permssion for this action");
        }
        $validator = Validator::make($request->all(), $this->rules);
        // dd($validator->validated());
        if ($validator->fails()) {
            // dd($validator->errors());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $validated = $validator->validated();
        if ($object = $this->class_instance::create($validated)) {
            $validated['password'] = Hash::make($validated['password']);
            $validated['role'] = "artist";
            $user = User::create($validated);
            $object->user_id = $user->id;
            $object->save();
            session()->flash("success", "Data inserted successfully");
            return redirect()->route("{$this->route_name}.index");
        } else {
            session()->flash("error", "Server Error Code 500");
            return redirect()->route("{$this->route_name}.index");
        }
    }

    public function createForm($data = null, $method = 'post', $action = 'store')
    {
        $form = [
            'route' => route($this->route_name . '.' . $action, (isset($data->id) ? $data->id : null)),
            'method' => $method,
            'fields' =>
            [
                [
                    ['type' => 'text', 'name' => 'name', 'label' => 'Artist Name', 'value' => (isset($data->name)) ? $data->name : null, 'placeholder' => 'Artist Name',],
                    ['type' => 'text', 'name' => 'first_name', 'label' => 'First Name', 'value' => (isset($data->first_name)) ? $data->first_name : null, 'placeholder' => 'First Name',],
                    ['type' => 'text', 'name' => 'last_name', 'label' => 'last_name', 'value' => (isset($data->last_name)) ? $data->last_name : null, 'placeholder' => 'last_name',],
                ],
                [
                    ['type' => 'date', 'name' => 'first_release_year', 'label' => 'First Release Year', 'value' => (isset($data->first_release_year)) ? $data->first_release_year : null, 'placeholder' => 'First Release Year',],
                    ['type' => 'text', 'name' => 'address', 'label' => 'Address', 'value' => (isset($data->address)) ? $data->address : null, 'placeholder' => 'Address',],
                ],
                [

                    ['options' => [
                        'm' => 'male',
                        'f' => 'female',
                        'o' => 'other',
                    ], 'name' => 'gender', 'label' => 'gender', 'value' => (isset($data->gender)) ? $data->gender : null,],

                    ['type' => 'number', 'name' => 'phone', 'label' => 'Phone', 'value' => (isset($data->phone)) ? $data->phone : null, 'placeholder' => 'phone',],
                ],
                [
                    ['type' => 'text', 'name' => 'email', 'label' => 'Email', 'value' => (isset($data->email)) ? $data->email : null, 'placeholder' => 'Email',],
                    ['type' => 'password', 'name' => 'password', 'label' => 'Password', 'value' => (isset($data->password)) ? $data->password : null, 'placeholder' => 'Password',],

                ],
                [
                    ['type' => 'date', 'name' => 'dob', 'label' => 'Date Of Birth', 'value' => (isset($data->dob)) ? $data->dob : null, 'placeholder' => 'Date of Birth',],

                    // (Auth::user()->role == "super_admin")?

                    //         ['options' =>
                    //           User::where('role','artist_manager')->pluck('email','id')
                    //         , 'name' => 'user_id', 'label' => 'Assign Manager', 'value' => (isset($data->user_id)) ? $data->user_id : null,]
                    //     :[]
                    // dd($this->form);

                ]
            ]
        ];
        $this->form = $form;
        // dd($form);
        // dd(Auth::user()->role);
    }

    public function export()
    {
        return Excel::download(new ArtistExport, 'artist.csv');
    }

    public function import(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "file" => ["required", "file", "max:20480"],
            ]
        );

        // return $request->file('file');
        if ($validator->fails()) {
            // dd($validator->errors());

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        Excel::import(new ArtistImport, $request->file('file')->store('temp'));
        return back()->with('success', 'Users imported successfully!');
    }
}
