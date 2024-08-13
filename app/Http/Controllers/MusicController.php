<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Shared\SharedController;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\Music;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class MusicController extends SharedController
{
    public $title ="Music";
    public $class_instance=Music::class;
    public $route_name="musics";
    public $rules=[
        "title"=>["required","max:20",],
        "album_name"=>["required","max:20",],
        "genre_id"=>["required","exists:genres,id"],
        "artist_id"=>["required","exists:artists,id"],
    ];
    public $table_headers=["title","album_name","genre_id","artist",];
    public $columns=["title","album_name","genre.name","artist.name",];

    public function index(Request $request)
    {
        // return     $data = $this->class_instance::all();

        // return $this->currentFiscalYear();

        $artist_id = $request->artist_id;
        if (!Session::has($artist_id)) {
            // dd(Session::has($artist_id));
            Session::put('artist_id', $artist_id);
        }
        if (Gate::denies('viewAny',$this->class_instance)) {
            abort(403,"You do not have permssion for this action");
        }
        if(isset($this->relation)){
            $data = $this->class_instance::with($this->relation)->get();
        }else{
        if (Session::has('artist_id')) {
            $data = $this->class_instance::where('artist_id',Session::get('artist_id'))->get();
        }else{
            $data=$this->class_instance::all();

        }
        }
        if ($request->ajax()) {
            // dd($data);
            Session::remove('artist_id');

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
                                <!-- <a href="' . route("{$this->route_name}.show", "{$row['id']}") . '">
                                    <button  type="button" class="btn btn-primary" ><i class="fa fa-eye"></i></button>
                                 </a>-->
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

    public function createForm($data = null,$method='post',$action='store')
    {
        $this->form = [
            'route' => route($this->route_name . '.'.$action,(isset($data->id)?$data->id:null)),
            'method' => $method,
            'fields' =>
            [
                [
                    ['type'=>'text','name'=>'title','label'=>'Title','value'=>(isset($data->title))?$data->title:null,'placeholder'=>'Title',],
                    ['type'=>'text','name'=>'album_name','label'=>'Album Name','value'=>(isset($data->album_name))?$data->album_name:null,'placeholder'=>'Album name',],
                ],
                [

                    ['options'=>
                      Genre::all()->pluck('name','id')
                    , 'name' => 'genre_id', 'label' => 'Genre', 'value' => (isset($data->genre_id)) ? $data->genre : null,],
                    ['options'=>
                      Artist::all()->pluck('name','id')
                    , 'name' => 'artist_id', 'label' => 'Artist', 'value' => (isset($data->artist_id)) ? $data->artist : null,],
                ],
            ]
        ];

    }
}
