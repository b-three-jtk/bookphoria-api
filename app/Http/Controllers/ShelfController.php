<?php

namespace App\Http\Controllers;

use App\Models\Shelf;
use App\Http\Requests\StoreShelfRequest;
use App\Http\Requests\UpdateShelfRequest;

class ShelfController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $shelves = Shelf::all();

        return response()->json([
            "message" => "Shelves retrieved",
            "shelves" => $shelves
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreShelfRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreShelfRequest $request)
    {
        //
        $shelf = Shelf::create($request->validated());

        return response()->json([
            "message" => "New Shelf added",
            "shelf" => $shelf
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shelf  $shelf
     * @return \Illuminate\Http\Response
     */
    public function show(Shelf $shelf)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shelf  $shelf
     * @return \Illuminate\Http\Response
     */
    public function edit(Shelf $shelf)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateShelfRequest  $request
     * @param  \App\Models\Shelf  $shelf
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateShelfRequest $request, Shelf $shelf)
    {
        //
        $shelf->update($request->validated());

        return response()->json([
            "message" => "Shelf updated",
            "shelf" => $shelf
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shelf  $shelf
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $shelf = Shelf::find($id);
        $shelf->delete();

        return response()->json([
            "message" => "Shelf deleted"
        ], 200);
    }
}
