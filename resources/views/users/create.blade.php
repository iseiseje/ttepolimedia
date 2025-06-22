<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700">Nama</label>
                            <input type="text" name="name" class="w-full border rounded px-3 py-2" required value="{{ old('name') }}">
                            @error('name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">Email</label>
                            <input type="email" name="email" class="w-full border rounded px-3 py-2" required value="{{ old('email') }}">
                            @error('email')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">Password</label>
                            <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
                            @error('password')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">Role</label>
                            <select name="role" class="w-full border rounded px-3 py-2" required>
                                <option value="">Pilih Role</option>
                                <option value="admin" @if(old('role')=='admin') selected @endif>Admin</option>
                                <option value="dosen" @if(old('role')=='dosen') selected @endif>Dosen</option>
                                <option value="guest" @if(old('role')=='guest') selected @endif>Guest</option>
                            </select>
                            @error('role')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">Simpan</button>
                            <a href="{{ route('users.index') }}" class="ml-2 text-gray-600 hover:underline">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 