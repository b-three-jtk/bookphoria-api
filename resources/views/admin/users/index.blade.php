@extends('layouts.app')

@section('title', 'User Management')

@section('content')
    <div x-data="{ isEditOpen: false, isDeleteOpen: false, selectedUser: null }" 
         class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <!-- Breadcrumb Start -->
        <div x-data="{ pageName: `Data Pengguna` }">
            @include('partials.breadcrumb')
        </div>
        <!-- Breadcrumb End -->

        <div class="space-y-5 sm:space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-5 py-4 sm:px-6 sm:py-5 flex justify-between items-center">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        Data Pengguna
                    </h3>
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
                                                    User
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
                                                    Tanggal Bergabung
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Status
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
                                <!-- table body start -->
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    @forelse ($users as $u)
                                        <tr>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-10 h-10 overflow-hidden rounded-full">
                                                            <img src="{{ $u->avatar ? $u->avatar : './images/user/user-17.jpg' }}" alt="brand" />
                                                        </div>
                                                        <div>
                                                            <span
                                                                class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                                                {{ $u->first_name }} {{ $u->last_name }}
                                                            </span>
                                                            <span
                                                                class="block text-gray-500 text-theme-xs dark:text-gray-400">
                                                                {{ "@".$u->username }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                        {{ $u->books->count() }} Buku
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                        {{ $u->created_at->format('d M Y') }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <p
                                                        class="rounded-full bg-success-50 px-2 py-0.5 text-theme-xs font-medium text-success-700 dark:bg-success-500/15 dark:text-success-500">
                                                        Active
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center gap-4">
                                                    <button @click="selectedUser = { id: '{{ $u->id }}', first_name: '{{ addslashes($u->first_name) }}', last_name: '{{ addslashes($u->last_name) }}', username: '{{ addslashes($u->username) }}', email: '{{ addslashes($u->email) }}' }; isEditOpen = true"
                                                        aria-label="Edit pengguna {{ $u->first_name }} {{ $u->last_name }}"
                                                        class="text-theme-xs font-medium text-yellow-500 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-300">
                                                        Edit
                                                    </button>
                                                    <button @click="selectedUser = { id: '{{ $u->id }}', name: '{{ addslashes($u->first_name . ' ' . $u->last_name) }}' }; isDeleteOpen = true"
                                                        aria-label="Hapus pengguna {{ $u->first_name }} {{ $u->last_name }}"
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
                                                    Belum ada pengguna yang ditambahkan.
                                                </p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal Popup -->
        <div x-cloak x-show="isEditOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" role="dialog" aria-modal="true" aria-labelledby="edit-modal-title">
            <div @click.outside="isEditOpen = false" class="relative w-full max-w-md mx-4 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center">
                    <h3 id="edit-modal-title" class="text-base font-medium text-gray-800 dark:text-white/90">
                        Edit Pengguna
                    </h3>
                    <button @click="isEditOpen = false" aria-label="Tutup modal"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('admin.users.update', ':id') }}'.replace(':id', selectedUser.id) : ''" method="POST" class="p-5 sm:p-6 space-y-6">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="edit-user-first_name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nama Depan
                        </label>
                        <input type="text" id="edit-user-first_name" name="first_name" x-model="selectedUser.first_name" placeholder="Masukkan nama depan"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        @error('first_name')
                            <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="edit-user-last_name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nama Belakang
                        </label>
                        <input type="text" id="edit-user-last_name" name="last_name" x-model="selectedUser.last_name" placeholder="Masukkan nama belakang"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        @error('last_name')
                            <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="edit-user-username" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Username
                        </label>
                        <input type="text" id="edit-user-username" name="username" x-model="selectedUser.username" placeholder="Masukkan username"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        @error('username')
                            <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="edit-user-email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Email
                        </label>
                        <input type="email" id="edit-user-email" name="email" x-model="selectedUser.email" placeholder="Masukkan email"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        @error('email')
                            <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-3">
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
            <div @click.outside="isDeleteOpen = false" class="relative w-full max-w-md mx-4 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center">
                    <h3 id="delete-modal-title" class="text-base font-medium text-gray-800 dark:text-white/90">
                        Hapus Pengguna
                    </h3>
                    <button @click="isDeleteOpen = false" aria-label="Tutup modal"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('admin.users.destroy', ':id') }}'.replace(':id', selectedUser.id) : ''" method="POST" class="p-5 sm:p-6">
                    @csrf
                    @method('DELETE')
                    <p class="text-sm text-gray-700 dark:text-gray-400">
                        Apakah Anda yakin ingin menghapus pengguna <span x-text="selectedUser.name"></span>?
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                    <div class="mt-6 flex justify-end gap-3">
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