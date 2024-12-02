<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('song_albums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('song_id')->consrained();
            $table->foreignId('album_id')->consrained();
            $table->integer('number_in_album');
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('song_albums');
    }
};
