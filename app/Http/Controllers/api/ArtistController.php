<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\Artist;

class ArtistController extends Controller
{
    //
    /**
     * @OA\Get(
     *     path="/artists",
     *     summary="Получает список исполнителей",
     *     tags={"Artists"},
     *     @OA\Response(response=200, description="List of artists"),
     *     
     * )
     */
    public function index()
    {
        $artists = Artist::all();
        return response()->json($artists);
    }

    /**
     * @OA\Post(
     *     path="/artist",
     *     summary="Добавляет исполнителя",
     *     tags={"Artists"},
     *     @OA\Response(response=201, description="Artist added"),
     *     
     * )
     */
    public function store(Request $request)
    {
        $artist = new Artist;
        $artist->name = $request->name;
        $artist->save();
        //if($album->save()){
            return response()->json([
                "message" => "Artist added"
            ], 201);
        //} 
    }
    /**
     * @OA\Get(
     *     path="/artist/{id}",
     *     summary="Получает исполнителя по id",
     *     tags={"Artists"},
     *     @OA\Response(response=201, description="Returns an artist"),
     *     @OA\Response(response=400, description="Artist not found"),
     * )
     */
    public function show($id)
    {
        $artist = Artist::find($id);
        if(!empty($artist))
        {
            return response()->json($artist);
        }
        else
        {
            return response()->json([
                "message" => "Artist not found"
            ]);
        }
    }
    /**
     * @OA\Put(
     *     path="/artist/{id}",
     *     summary="Обновляет данные исполнителя по id",
     *     tags={"Artists"},
     *     @OA\Response(response=201, description="Artist updated"),
     *     @OA\Response(response=404, description="Artist not found"),
     * )
     */
    public function update(Request $request, $id)
    {
        if(Artist::where('id', $id)->exists()){
            $artist = Artist::find($id); 
            $artist->name = is_null($request->name) ? $artist->name : $request->name;
            $artist->save();
            return response()->json([
                "message" => "Artist updated"
            ], 201);
        }
        else{
            return response()->json([
                "message" => "Artist not found"
            ]. 404);
        }
    }

    /**
     * @OA\Delete(
     *     path="/artist/{id}",
     *     summary="Удаялет данные исполнителя по id",
     *     tags={"Artists"},
     *     @OA\Response(response=201, description="Artist deleted"),
     *     @OA\Response(response=404, description="Artist not found"),
     * )
     */
    public function destroy($id)
    {
        if(Artist::where('id', $id)->exists()){
            $artist = Artist::find($id); 
            $artist->delete(); 

            if(Album::where('artist_id', $id)->exists()){
                Album::where('artist_id', $id)->delete();
            }

            return response()->json([
                "message" => "Artist deleted"
            ], 201);
        }
        else{
            return response()->json([
                "message" => "Artist not found"
            ], 404);
        }
    }
}
