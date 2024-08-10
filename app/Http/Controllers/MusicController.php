<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Shared\SharedController;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\Music;
use Illuminate\Http\Request;

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
    public $table_headers=["title","album_name","genre_id","artist_id",];
    public $columns=["title","album_name","genre.name","artist.name",];

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
