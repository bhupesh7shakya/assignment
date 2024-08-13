<?php

namespace App\Http\Controllers;

use App\Exports\ArtistExport;
use App\Http\Controllers\Shared\SharedController;
use App\Imports\ArtistImport;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Yajra\DataTables\Facades\DataTables;

class ArtistController extends SharedController
{

    public function index(Request $request)
    {
        // return $this->currentFiscalYear();
        if (Gate::denies('viewAny',$this->class_instance)) {
            abort(403,"You do not have permssion for this action");
        }
        if ($request->ajax()) {
            if(isset($this->relation)){
                $data = $this->class_instance::with($this->relation)->get();
            }else{

                $data = $this->class_instance::all()
                ->where('is_deleted', 0);
            }

            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $delete_button='
                <button  type="button" class="btn btn-danger tabler-card" disabled ><i class="ti ti-trash"></i></i></button>';
                if (!Gate::denies('delete',$this->class_instance)) {
                        $delete_button='
                                   <button onclick="del(' . $row['id'] . ')" type="button" class="btn btn-danger" ><i class="ti ti-trash"></i></i></button>

                        ';

                    }
                $edit_button='
                     <a href="#" disabled>
                                     <button  type="button" class="btn btn-primary" disabled  ><i class="ti ti-edit"></i></button>
                                </a>

                ';
                if (!Gate::denies('update',$this->class_instance)) {

                    $edit_button='
                             <a href="' . route("{$this->route_name}.edit", "{$row['id']}") . '" >
                                     <button  type="button" class="btn btn-primary"  ><i class="ti ti-edit"></i></button>
                                </a>';

                }
                    return '<div class="text-center">
                                '.$edit_button.'
                                <a href="' .route("musics.index",) . '?artist_id='.$row['id'].'">
                                    <button  type="button" class="btn btn-primary" ><i class="fa fa-eye">🎵</i></button>
                                 </a>
            '.$delete_button.'
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
    public $title ="Artist";
    public $class_instance=Artist::class;
    public $route_name="artists";
    public $rules=[
        "name"=>["required","unique:artists,name","max:20",],
        "dob"=>["required"],
        "first_release_year"=>["required","date",],
        "no_of_albums_released"=>["required","integer",],
        "gender"=>["required"],
    ];
    public $table_headers=["name","dob","gender","first_release_year","no_of_albums_released"];
    public $columns=["name","dob","gender","first_release_year","no_of_albums_released"];

    public function createForm($data = null,$method='post',$action='store')
    {
        $this->form = [
            'route' => route($this->route_name . '.'.$action,(isset($data->id)?$data->id:null)),
            'method' => $method,
            'fields' =>
            [
                [
                    ['type'=>'text','name'=>'name','label'=>'Name','value'=>(isset($data->name))?$data->name:null,'placeholder'=>'Name',],
                    ['type'=>'date','name'=>'dob','label'=>'Date Of Birth','value'=>(isset($data->dob))?$data->dob:null,'placeholder'=>'Date of Birth',],
                ],
                [
                    ['type'=>'date','name'=>'first_release_year','label'=>'First Release Year','value'=>(isset($data->first_release_year))?$data->first_release_year:null,'placeholder'=>'First Release Year',],
                    ['type'=>'number','name'=>'no_of_albums_released','label'=>'No of Albums Released','value'=>(isset($data->no_of_albums_released))?$data->no_of_albums_released:null,'placeholder'=>'No of Albums Released',],
                ],[

                    ['options'=>[
                        'm'=>'male',
                        'f'=>'female',
                        'o'=>'other',
                    ], 'name' => 'gender', 'label' => 'gender', 'value' => (isset($data->gender)) ? $data->gender : null,],
                    []
                ],
            ]
        ];

    }

    public function export(){
        return Excel::download(new ArtistExport,'artist.csv');
    }

    public function import(Request $request)
    {
        $validator=Validator::make(
            $request->all(),
            [
                "file"=>["required","file"],
            ]
        );


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
