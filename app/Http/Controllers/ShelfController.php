<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Shelf;
use App\Models\Book;
use App\Http\Requests\StoreShelfRequest;
use App\Http\Requests\UpdateShelfRequest;
use App\Http\Requests\AddBookToShelfRequest;

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
        try {
            $validated = $request->validated();
            $validated['user_id'] = $request->user()->id;

            $imagePath = null; // Default null

            // Handle image upload hanya jika benar-benar ada dan tidak kosong
            if ($request->has('image') && !empty($request->input('image'))) {
                $imagePath = $this->handleImageUpload($request);

                if ($imagePath === null) {
                    throw new \Exception("Gagal memproses gambar");
                }

                $validated['image'] = $imagePath;
            }

            // Pastikan field 'desc' terisi
            $validated['desc'] = $validated['desc'] ?? null;

            $shelf = Shelf::create($validated);

            return response()->json([
                "success" => true,
                "message" => "Shelf created successfully",
                "data" => [
                    "id" => $shelf->id,
                    "image_url" => $imagePath ? Storage::url($imagePath) : null
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Failed to create shelf: " . $e->getMessage()
            ], 500);
        }
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
    public function update(Request $request)
    {
        //
        $user = auth()->user();

        $shelf = Shelf::find($request->id)->where('user_id', $user->id)->first();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
        ]);

        // Handle image upload jika ada
        if ($request->hasFile('image')) {
            $imagePath = $this->handleImageUpload($request);

            if ($imagePath === null) {
                return response()->json([
                    'message' => 'Failed to upload image'
                ], 500);
            }

            $validated['image'] = $imagePath;
        } else {
            $validated['image'] = $shelf->image;
        }

        $shelf->update($validated);

        return response()->json([
            'message' => 'Shelf updated successfully',
            'shelf' => $shelf
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

        if (!$shelf) {
        return response()->json([
            'message' => 'Shelf not found'
        ], 404);
        }

        $shelf->delete();
        return response()->json([
            "message" => "Shelf deleted successfully."
        ], 200);
    }

    private function handleImageUpload(Request $request): ?string
    {
        try {
            // 1. Handle File Upload (Multipart)
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                
                // Validasi ekstensi file
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                $extension = $file->getClientOriginalExtension();
                
                if (!in_array(strtolower($extension), $allowedExtensions)) {
                    throw new \Exception("Ekstensi file tidak didukung. Gunakan: " . implode(', ', $allowedExtensions));
                }

                // Validasi ukuran file (max 2MB)
                if ($file->getSize() > 2048 * 1024) {
                    throw new \Exception("Ukuran file maksimal 2MB");
                }

                return $file->store('shelves', 'public');
            }

            // 2. Handle Base64 String
            if ($request->filled('image')) {
                $base64 = $request->input('image');
                
                // Ekstrak data dan ekstensi dari base64
                if (preg_match('/^data:image\/(\w+);base64,/', $base64, $matches)) {
                    $data = substr($base64, strpos($base64, ',') + 1);
                    $extension = $matches[1];
                } else {
                    $data = $base64;
                    $extension = 'jpg'; // Default jika tidak ada ekstensi
                }

                // Validasi ekstensi
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($extension, $allowedExtensions)) {
                    throw new \Exception("Ekstensi base64 tidak didukung");
                }

                $imageData = base64_decode($data);
                
                // Validasi ukuran data (max 2MB)
                if (strlen($imageData) > 2048 * 1024) {
                    throw new \Exception("Ukuran gambar base64 maksimal 2MB");
                }

                $fileName = 'shelves/'.Str::uuid().'.'.$extension;
                Storage::disk('public')->put($fileName, $imageData);
                
                return $fileName;
            }

            return null;

        } catch (\Exception $e) {
            \Log::error('Image Upload Error: ' . $e->getMessage());
            return null;
        }
    }

    public function addBook(AddBookToShelfRequest $request, Shelf $shelf)
    {
        $bookId = $request->book_id;

        // Cegah duplikasi
        if ($shelf->books()->where('book_id', $bookId)->exists()) {
            return response()->json(['message' => 'Buku sudah ada di rak ini.'], 409);
        }

        // Tambahkan ke shelf
        $shelf->books()->attach($bookId);

        return response()->json(['message' => 'Buku berhasil ditambahkan ke rak.'], 201);
    }

    public function removeBook(Request $request, Shelf $shelf, Book $book)
    {
        $this->authorize('update', $shelf);

        // Hapus relasi dari tabel pivot
        $shelf->books()->detach($book->id);

        return response()->json(['message' => 'Buku berhasil dihapus dari rak.']);
    }
}
