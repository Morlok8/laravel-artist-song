<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\Song;
use Illuminate\Support\Facades\DB;


class AlbumController extends Controller
{
    //
    /**
     * @OA\Get(
     *     path="/album",
     *     summary="Получант список альбомов",
     *     tags={"Albums"},
     *     @OA\Response(response=200, description="List of songs"),
     *     
     * )
     */
    public function index()
    {
        $albums = Album::all();
        return response()->json($albums);
    }
    /**
     * @OA\Post(
     *     path="/album",
     *     summary="Добавляет альбом. Данные передаются в body запроса в формате {'artist_id': id испольнителя, "release_year": год выпуска}",
     *     tags={"Albums"},
     *     @OA\Response(response=201, description="Album added"),
     *     
     * )
     */
    public function store(Request $request)
    {
        $album = new Album;
        $album->artist_id = $request->artist_id;
        $album->release_year = $request->release_year;
        $album->save();
        return response()->json([
            "message" => [
                "Album added"
            ]
        ], 201);
    }
    /**
     * @OA\Get(
     *     path="/album/{id}",
     *     summary="Получает альбом по id",
     *     tags={"Albums"},
     *     @OA\Response(response=201, description="Shows an album"),
     *     @OA\Response(response=404, description="Album not found"),
     * )
     */
    public function show($id)
    {
        $album = Album::find($id);
        if(!empty($album))
        {
            return response()->json($album);
        }
        else
        {
            return response()->json([
                "message" => "Album not found"
            ]);
        }
    }
    /**
     * @OA\Put(
     *     path="/album/{id}",
     *     summary="Обновляет альбом по id",
     *     tags={"Albums"},
     *     @OA\Response(response=201, description="Shows an album"),
     *     @OA\Response(response=404, description="Album not found"),
     * )
     */
    public function update(Request $request, $id)
    {
        if(Album::where('id', $id)->exists()){
            $album = Album::find($id); 
            $album->artist_id = is_null($request->artist_id) ? $album->artist_id : $request->artist_id;
            $album->release_year = is_null($request->release_year) ? $album->release_year : $request->release_year;
            $album->save();

            return response()->json([
                "message" => "Album updated"
            ], 201);
        }
        else{
            return response()->json([
                "message" => "Album not found"
            ]. 404);
        }
    }
    /**
     * @OA\Get(
     *     path="/album/{id}/songs",
     *     summary="Получает список песен в альбоме по id",
     *     tags={"Albums"},
     *     @OA\Response(response=201, description="Shows a list of songs for an album"),
     * )
     */
    public function get_songs($id)
    {
        if(!empty(DB::table('song_albums')->where('album_id', '=', $id)->get()))
        {
            $songs = DB::table('song_albums')->where('album_id', '=', $id)->get();
            $song_response = [];
            foreach($songs as $key=>$value){
                $song = Song::where('id', $value->song_id)->first(); 
                $song_response[] = array(
                    "song_id" => $value->id,
                    "song_name" => $song->name,
                    "number_in_album" => $value->number_in_album
                );
            }   
        }
        
        return response()->json([
            "message" => $song_response
        ]);  
    }

    /**
     * @OA\Post(
     *     path="/album/{id}/songs",
     *     summary="Добавляет песню в альбом по id альбома (id песни передается в body запроса в виде {'song_id': id-песни}))",
     *     tags={"Albums"},
     *     @OA\Response(response=201, description="Song is added succesuflly"),
     *     @OA\Response(response=404, description="Error"),
     *     @OA\Response(response=403, description="Song doesn't exist, try another one"),
     * )
     */
    public function add_song(Request $request, $id)
    {
        if(Song::where('id', $request->song_id)->exists()){
            $album = Album::where('id', $id)->first(); 
            $songs = DB::table('song_albums')->where('album_id', '=', $id)->orderBy('number_in_album','DESC')->first(); 
            $song = Song::where('id', $request->song_id)->first(); 
            
            if(DB::table('song_albums')->where('album_id', '=', $id)->where('song_id', '=', $request->song_id)->exists())
                $response = "Song is already added";
            else{
                if(!is_null($songs))
                    $number_in_album = $songs->number_in_album + 1;
                else 
                    $number_in_album = 1;

                if(DB::table('song_albums')->insert([
                    'song_id' => $request->song_id,
                    'album_id' => $album->id,
                    'number_in_album' => $number_in_album
                ])){
                        $response = "Song is added succesuflly";
                        $code = 201;
                    }
                    
                else {
                    $response = "Error";
                    $code = 404;
                }
                    
            }  
        }
        else{
            $response = "Song doesn't exist, try another one";
            $code = 404;
        }       
        return response()->json([
            "message" => $response
        ]);  
    }

    /**
     * @OA\Delete(
     *     path="/album/{id}/song/{id}",
     *     summary="Удаляет песню из альбома по id",
     *     tags={"Albums"},
     *     @OA\Response(response=201, description="Song succesully deleted from the album"),
     *     @OA\Response(response=404, description="Song doesn't exist in this album"),
     * )
     */
    public function remove_song($id, $song_id)
    {
        if(DB::table('song_albums')->where('album_id', '=', $id)->where('song_id', '=', $song_id)->exists()){
            if(DB::table('song_albums')->where('album_id', '=', $id)->where('song_id', '=', $song_id)->delete()) 
                $response = "Song succesully deleted from the album";
            else
                $response = "Something went wrong, try again later";    
        }
        else{
            $response = "Song doesn't exist in this album";
        }
        
        return response()->json([
            "message" => $response
        ]); 
    }

    /**
     * @OA\Delete(
     *     path="/album/{id}/",
     *     summary="Удаляет альбом по id",
     *     tags={"Albums"},
     *     @OA\Response(response=201, description="Album deleted"),
     *     @OA\Response(response=404, description="Album not found"),
     * )
     */
    public function destroy($id)
    {
        if(Album::where('id', $id)->exists()){
            $album = Album::find($id); 
            $album->delete(); 

            if(DB::table('song_albums')->where('album_id', '=', $id)->exists()){
                DB::table('song_albums')->where('album_id', '=', $id)->delete(); 
            }

            return response()->json([
                "message" => "Album deleted"
            ], 200);
        }
        else{
            return response()->json([
                "message" => "Album not found"
            ], 404);
        }
    }
}
