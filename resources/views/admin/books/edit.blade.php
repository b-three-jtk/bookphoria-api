@extends('layouts.app')

@section('title', 'Edit Book')

@section('content')
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <!-- Breadcrumb Start -->
        <div x-data="{ pageName: `Edit Buku` }">
            @include('partials.breadcrumb')
        </div>
        <!-- Breadcrumb End -->

        <div class="space-y-5 sm:space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex flex-row justify-between items-center px-5 py-4 sm:px-6 sm:py-5">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        Edit Buku
                    </h3>
                </div>
                <div class="overflow-hidden rounded-xl border p-10 border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <form class="flex flex-col gap-8" action="{{ route('admin.books.update', $book->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="edit-book-cover" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Cover Buku
                            </label>
                            <input type="file" id="edit-book-cover" name="cover"
                                class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:text-white/90 dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400" />
                            <p class="text-theme-xs text-gray-500 mt-1.5">Biarkan kosong jika tidak ingin mengganti cover.</p>
                            @error('cover')
                                <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="edit-book-title" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Judul Buku
                            </label>
                            <input type="text" id="edit-book-title" name="title" value="{{ old('title', $book->title) }}" placeholder="Masukkan judul buku"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('title')
                                <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="edit-book-synopsis" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Sinopsis Buku
                            </label>
                            <textarea placeholder="Masukkan sinopsis" type="text" name="synopsis" id="edit-book-synopsis" rows="6"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('synopsis', $book->synopsis) }}</textarea>
                            @error('synopsis')
                                <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="edit-book-authors" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Penulis
                            </label>
                            <select id="edit-book-authors" name="authors[]" multiple
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                @foreach ($authors as $author)
                                    <option value="{{ $author->id }}" {{ in_array($author->id, $book->authors->pluck('id')->toArray()) ? 'selected' : '' }}>
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('authors')
                                <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="edit-book-publisher" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Penerbit Buku
                            </label>
                            <input type="text" id="edit-book-publisher" name="publisher" value="{{ old('publisher', $book->publisher) }}" placeholder="Masukkan penerbit buku"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('publisher')
                                <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="edit-book-published_date" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Tanggal Terbit
                            </label>
                            <input type="date" id="edit-book-published_date" name="published_date" value="{{ old('published_date', $book->published_date) }}"
                                placeholder="Select date"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                onclick="this.showPicker()" />
                            @error('published_date')
                                <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="edit-book-isbn" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                ISBN
                            </label>
                            <input type="text" id="edit-book-isbn" name="isbn" value="{{ old('isbn', $book->isbn) }}" placeholder="Masukkan ISBN" maxlength="13"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('isbn')
                                <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="edit-book-pages" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Jumlah Halaman
                            </label>
                            <input type="number" id="edit-book-pages" name="pages" value="{{ old('pages', $book->pages) }}" placeholder="Masukkan jumlah halaman"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('pages')
                                <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="edit-book-genres" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Genre
                            </label>
                            <select id="edit-book-genres" name="genres[]" multiple
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                @foreach ($genres as $genre)
                                    <option value="{{ $genre->id }}" {{ in_array($genre->id, $book->genres->pluck('id')->toArray()) ? 'selected' : '' }}>
                                        {{ $genre->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('genres')
                                <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="px-5 py-4 sm:px-6 sm:py-5 border-t border-gray-200 dark:border-gray-800 flex justify-end gap-3">
                            <button type="button" onclick="window.history.back()"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                                Batal
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-brand-500 rounded-lg shadow-theme-xs hover:bg-brand-600">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection