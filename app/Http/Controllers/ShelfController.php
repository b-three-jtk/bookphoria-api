<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        
        // Handle image upload (file or base64)
        if ($request->has('image')) {
            $validated['image'] = $this->handleImageUpload($request);
        }

        $shelf = Shelf::create($validated);

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

    private function handleImageUpload(Request $request)
    {
        // Handle file upload
        if ($request->hasFile('image')) {
            return $request->file('image')->store('shelves', 'public');
        }
        
        // Handle base64 string
        $base64 = $request->input('image');
        
        if (preg_match('/^data:image\/(\w+);base64,/', $base64, $matches)) {
            $data = substr($base64, strpos($base64, ',') + 1);
            $extension = $matches[1];
        } else {
            $data = $base64;
            $extension = 'jpg';
        }

        $imageData = base64_decode($data);
        $fileName = 'shelves/'.Str::uuid().'.'.$extension;
        
        Storage::disk('public')->put($fileName, $imageData);
        
        return $fileName;
    }
}
