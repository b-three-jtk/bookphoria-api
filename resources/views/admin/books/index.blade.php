@extends('layouts.app')

@section('title', 'Book Management')

@section('content')
    <div x-data="{ isDeleteOpen: false, selectedBook: null }" 
        x-on:keydown.escape="isDeleteOpen = false"
        class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <!-- Breadcrumb Start -->
        <div x-data="{ pageName: `Data Buku` }">
            @include('partials.breadcrumb')
        </div>
        <!-- Breadcrumb End -->

        <div class="space-y-5 sm:space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex flex-row justify-between items-center px-5 py-4 sm:px-6 sm:py-5">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        Data Buku
                    </h3>
                    <a href="{{ route('admin.books.create') }}" aria-label="Tambah buku baru"
                        class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white transition rounded-lg bg-light-500 shadow-theme-xs hover:bg-brand-600">
                        Tambah
                        <svg class="text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 12h14m-7 7V5" />
                        </svg>
                    </a>
                </div>
                <div class="p-5 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <div
                        class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                        <div class="max-w-full overflow-x-auto">
                            <table class="min-w-full">
                                <!-- table header start -->
                                <thead>
                                    <tr class="border-b border-gray-100 dark:border-gray-800">
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    No
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Buku
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Penulis
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Tanggal Terbit
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    ISBN
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Jumlah Halaman
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Genre
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Aksi
                                                </p>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <!-- table header end -->
                                <!-- table body start -->
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    @forelse ($books as $b)
                                        <tr>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                        {{ $books->firstItem() + $loop->index }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-14 overflow-hidden rounded-md">
                                                            <img src="{{ $b->cover ? $b->cover : './images/book/book-1.jpg' }}"
                                                                alt="Cover {{ $b->title }}" />
                                                        </div>
                                                        <div>
                                                            <span
                                                                class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                                                {{ $b->title }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                        @foreach ($b->authors as $author)
                                                            {{ $author->name }}@if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                        {{ $b->published_date }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                        {{ $b->isbn }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                        {{ $b->pages }} Halaman
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                        @foreach ($b->genres as $genre)
                                                            {{ $genre->name }}@if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center gap-4">
                                                    <a href="{{ route('admin.books.edit', $b->id) }}"
                                                        class="text-theme-xs font-medium text-brand-500 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
                                                        Edit
                                                    </a>
                                                    <button @click="selectedBook = { id: '{{ $b->id }}', title: '{{ addslashes($b->title) }}' }; isDeleteOpen = true"
                                                        aria-label="Hapus buku {{ $b->title }}"
                                                        class="text-theme-xs font-medium text-error-500 hover:text-error-700 dark:text-error-400 dark:hover:text-error-300">
                                                        Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="px-5 py-4 sm:px-6 text-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    Belum ada buku yang ditambahkan.
                                                </p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if (isset($books) && $books instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="flex items-center justify-center mt-8 mx-auto">
                            {{ $books->links('vendor.pagination.custom') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Delete Modal Popup -->
        <div x-cloak x-show="isDeleteOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
            <div @click.outside="isDeleteOpen = false"
                class="relative w-full max-w-md mx-4 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center">
                    <h3 id="delete-modal-title" class="text-base font-medium text-gray-800 dark:text-white/90">
                        Hapus Buku
                    </h3>
                    <button @click="isDeleteOpen = false" aria-label="Tutup modal"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form x-bind:action="selectedBook ? '{{ route('admin.books.destroy', ':id') }}'.replace(':id', selectedBook.id) : ''" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="p-5 sm:p-6">
                        <p class="text-sm text-gray-700 dark:text-gray-400">
                            Apakah Anda yakin ingin menghapus buku <span x-text="selectedBook ? selectedBook.title : ''"></span>?
                            Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>
                    <div class="px-5 py-4 sm:px-6 sm:py-5 border-t border-gray-200 dark:border-gray-800 flex justify-end gap-3">
                        <button type="button" @click="isDeleteOpen = false"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                            Batal
                        </button>
                        <button type="submit"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-error-500 rounded-lg shadow-theme-xs hover:bg-error-600">
                            Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection