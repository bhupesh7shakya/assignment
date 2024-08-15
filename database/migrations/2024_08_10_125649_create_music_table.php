<?php

use App\Models\Album;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums',function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->foreignIdFor(User::class)->nullable(true);
            $table->timestamps();

        });

        Schema::create('music', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Artist::class);
            $table->string('title');
            $table->foreignIdFor(Album::class)->constrained()->references('id')->on('albums')->onDelete('cascade');
            $table->foreignIdFor(Genre::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('albums');
        Schema::dropIfExists('music');
    }
};
