<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Shared\SharedController;
use App\Models\Artist;
use Illuminate\Http\Request;

class ArtistController extends SharedController
{
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
}
