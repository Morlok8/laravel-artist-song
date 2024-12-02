<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\Album;
use Illuminate\Support\Facades\DB;


class SongController extends Controller
{
    //
    /**
     * @OA\Get(
     *     path="/songs",
     *     summary="Получает список песен",
     *     tags={"Songs"},
     *     @OA\Response(response=200, description="List of songs"),
     *     
     * )
     */
    public function index()
    {
        $songs = Song::all();
        return response()->json($songs);
    }

    /**
     * @OA\Post(
     *     path="/song",
     *     summary="Добавляет песню",
     *     tags={"Songs"},
     *     @OA\Response(response=201, description="Song added"),
     *     
     * )
     */
    public function store(Request $request)
    {
        $song = new Song;
        $song->name = $request->name;
        $song->save();
        //if($album->save()){
            return response()->json([
                "message" => "Song added",
            ], 201);
        //} 
    }

    /**
     * @OA\Get(
     *     path="/song/{id}",
     *     summary="Получает песню по id",
     *     tags={"Songs"},
     *     @OA\Response(response=201, description="Returns a song"),
     *     @OA\Response(response=400, description="Song not found"),
     * )
     */
    public function show($id)
    {
        $song = Song::find($id);
        if(!empty($song))
        {
            return response()->json($song);
        }
        else
        {
            return response()->json([
                "message" => "Song not found"
            ]);
        }
    }
    /**
     * @OA\Put(
     *     path="/song/{id}",
     *     summary="Обновляет песню по id",
     *     tags={"Songs"},
     *     @OA\Response(response=201, description="Song updated"),
     *     @OA\Response(response=404, description="Song not found"),
     * )
     */
    public function update(Request $request, $id)
    {
        if(Song::where('id', $id)->exists()){
            $song = Song::find($id); 
            $song->name = is_null($request->name) ? $artist->name : $request->name;
            $song->save();
            return response()->json([
                "message" => "Song updated"
            ], 201);
        }
        else{
            return response()->json([
                "message" => "Song not found"
            ]. 404);
        }
    }
    /**
     * @OA\Delete(
     *     path="/song/{id}",
     *     summary="Удаляет песню по id",
     *     tags={"Songs"},
     *     @OA\Response(response=201, description="Song deleted"),
     *     @OA\Response(response=404, description="Song not found"),
     * )
     */
    public function destroy($id)
    {
        if(Song::where('id', $id)->exists()){
            $song = Song::find($id); 
            $song->delete(); 

            if(Album::where('artist_id', $id)->exists()){
                Album::where('artist_id', $id)->delete();
            }

            if(DB::table('song_albums')->where('song_id', '=', $id)->exists()){
                DB::table('song_albums')->where('song_id', '=', $id)->delete(); 
            }

            return response()->json([
                "message" => "Song deleted"
            ], 200);
        }
        else{
            return response()->json([
                "message" => "Song not found"
            ], 404);
        }
    }
}
