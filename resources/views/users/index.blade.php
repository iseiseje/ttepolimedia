<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('users.create') }}" class="mb-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Tambah User</a>
                    @if(session('success'))
                        <div class="mb-4 text-green-600">{{ session('success') }}</div>
                    @endif
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-medium text-gray-500 uppercase">Nama</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-medium text-gray-500 uppercase">Role</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 border-b border-gray-500">{{ $user->name }}</td>
                                        <td class="px-6 py-4 border-b border-gray-500">{{ $user->email }}</td>
                                        <td class="px-6 py-4 border-b border-gray-500">{{ ucfirst($user->role) }}</td>
                                        <td class="px-6 py-4 border-b border-gray-500">
                                            <a href="{{ route('users.edit', $user) }}" class="text-blue-600 hover:text-blue-900 mr-2">Edit</a>
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 