@extends('layouts.app')

@section('title', 'Author Management')

@section('content')
    <div x-data="{ isAddOpen: false, isEditOpen: false, isDeleteOpen: false, selectedAuthor: null }" 
        x-on:keydown.escape="isAddOpen = false; isEditOpen = false; isDeleteOpen = false"
        class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <!-- Breadcrumb Start -->
        <div x-data="{ pageName: `Data Penulis` }">
            @include('partials.breadcrumb')
        </div>
        <!-- Breadcrumb End -->

        <div class="space-y-5 sm:space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex flex-row justify-between items-center px-5 py-4 sm:px-6 sm:py-5">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        Data Penulis
                    </h3>
                    <button @click="isAddOpen = true" aria-label="Tambah penulis baru"
                        class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white transition rounded-lg bg-light-500 shadow-theme-xs hover:bg-brand-600">
                        Tambah
                        <svg class="text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 12h14m-7 7V5" />
                        </svg>
                    </button>
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
                                                    Nama Penulis
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Jumlah Buku
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Tanggal Dibuat
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
                                    @forelse ($authors as $a)
                                        <tr>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                        {{ $authors->firstItem() + $loop->index }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                        {{ $a->name }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    {{ $a->books->count() }} Buku
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                        {{ $a->created_at->format('d M Y') }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center gap-4">
                                                    <button @click="selectedAuthor = { id: '{{ $a->id }}', name: '{{ addslashes($a->name) }}', desc: '{{ addslashes($a->desc ?? '') }}' }; isEditOpen = true"
                                                        aria-label="Edit penulis {{ $a->name }}"
                                                        class="text-theme-xs font-medium text-brand-500 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
                                                        Edit
                                                    </button>
                                                    <button @click="selectedAuthor = { id: '{{ $a->id }}', name: '{{ addslashes($a->name) }}', desc: '{{ addslashes($a->desc ?? '') }}' }; isDeleteOpen = true"
                                                        aria-label="Hapus penulis {{ $a->name }}"
                                                        class="text-theme-xs font-medium text-error-500 hover:text-error-700 dark:text-error-400 dark:hover:text-error-300">
                                                        Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-5 py-4 sm:px-6 text-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    Belum ada penulis yang ditambahkan.
                                                </p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if (isset($authors) && $authors instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="flex items-center justify-center mt-8 mx-auto">
                            {{ $authors->links('vendor.pagination.custom') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Add Modal Popup -->
        <div x-cloak x-show="isAddOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" role="dialog" aria-modal="true" aria-labelledby="add-modal-title">
            <div @click.outside="isAddOpen = false"
                class="relative w-full max-w-md mx-4 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center">
                    <h3 id="add-modal-title" class="text-base font-medium text-gray-800 dark:text-white/90">
                        Tambah Penulis
                    </h3>
                    <button @click="isAddOpen = false" aria-label="Tutup modal"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('admin.authors.store') }}" method="POST">
                    @csrf
                    <div class="p-5 sm:p-6 space-y-6">
                        <div>
                            <label for="add-author-name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Nama Penulis
                            </label>
                            <input type="text" id="add-author-name" name="name" placeholder="Masukkan nama penulis"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('name')
                                <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="add-author-desc" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Deskripsi Penulis
                            </label>
                            <input type="text" id="add-author-desc" name="desc" placeholder="Masukkan deskripsi penulis"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('desc')
                                <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-5 py-4 sm:px-6 sm:py-5 border-t border-gray-200 dark:border-gray-800 flex justify-end gap-3">
                        <button type="button" @click="isAddOpen = false"
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

        <!-- Edit Modal Popup -->
        <div x-cloak x-show="isEditOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" role="dialog" aria-modal="true" aria-labelledby="edit-modal-title">
            <div @click.outside="isEditOpen = false"
                class="relative w-full max-w-md mx-4 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center">
                    <h3 id="edit-modal-title" class="text-base font-medium text-gray-800 dark:text-white/90">
                        Edit Penulis
                    </h3>
                    <button @click="isEditOpen = false" aria-label="Tutup modal"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form x-bind:action="selectedAuthor ? '{{ route('admin.authors.update', ':id') }}'.replace(':id', selectedAuthor.id) : ''" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-5 sm:p-6 space-y-6">
                        <div>
                            <label for="edit-author-name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Nama Penulis
                            </label>
                            <input type="text" id="edit-author-name" name="name" x-bind:value="selectedAuthor ? selectedAuthor.name : ''"
                                placeholder="Masukkan nama penulis"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('name')
                                <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="edit-author-desc" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Deskripsi Penulis
                            </label>
                            <input type="text" id="edit-author-desc" name="desc" x-bind:value="selectedAuthor ? selectedAuthor.desc : ''"
                                placeholder="Masukkan deskripsi penulis"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('desc')
                                <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-5 py-4 sm:px-6 sm:py-5 border-t border-gray-200 dark:border-gray-800 flex justify-end gap-3">
                        <button type="button" @click="isEditOpen = false"
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

        <!-- Delete Modal Popup -->
        <div x-cloak x-show="isDeleteOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
            <div @click.outside="isDeleteOpen = false"
                class="relative w-full max-w-md mx-4 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center">
                    <h3 id="delete-modal-title" class="text-base font-medium text-gray-800 dark:text-white/90">
                        Hapus Penulis
                    </h3>
                    <button @click="isDeleteOpen = false" aria-label="Tutup modal"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form x-bind:action="selectedAuthor ? '{{ route('admin.authors.destroy', ':id') }}'.replace(':id', selectedAuthor.id) : ''" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="p-5 sm:p-6">
                        <p class="text-sm text-gray-700 dark:text-gray-400">
                            Apakah Anda yakin ingin menghapus penulis <span x-text="selectedAuthor ? selectedAuthor.name : ''"></span>?
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