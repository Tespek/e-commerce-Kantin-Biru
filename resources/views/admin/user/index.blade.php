@extends('layouts.admin')
@section('title', 'Kelola User')
@section('page-title', 'Kelola User')

@section('content')
<form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-5 flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-40">
        <label class="block text-xs font-medium text-gray-500 mb-1">Cari</label>
        <input type="text" name="search" value="{{ request('search') }}"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
            placeholder="Nama atau email...">
    </div>
    <div class="min-w-32">
        <label class="block text-xs font-medium text-gray-500 mb-1">Role</label>
        <select name="role" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">Semua</option>
            <option value="pelanggan" {{ request('role') === 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
    </div>
    <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-800 transition">
        <i class="fas fa-filter mr-1"></i>Filter
    </button>
    <a href="{{ route('admin.user.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-300 transition">Reset</a>
</form>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-5 py-3 text-left">Nama</th>
                <th class="px-5 py-3 text-left">Email</th>
                <th class="px-5 py-3 text-left">No. HP</th>
                <th class="px-5 py-3 text-left">Role</th>
                <th class="px-5 py-3 text-left">Bergabung</th>
                <th class="px-5 py-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-blue-700 text-white text-xs flex items-center justify-center font-bold flex-shrink-0">
                                {{ strtoupper(substr($user->nama, 0, 1)) }}
                            </div>
                            <span class="font-medium">{{ $user->nama }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $user->email }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $user->no_hp ?? '-' }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500">
                        {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d M Y') : '-' }}
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.user.show', $user->id_user) }}"
                               class="bg-blue-100 text-blue-700 px-3 py-1 rounded text-xs hover:bg-blue-200 transition">
                                <i class="fas fa-eye mr-1"></i>Detail
                            </a>
                            <a href="{{ route('admin.user.edit', $user->id_user) }}"
                               class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded text-xs hover:bg-yellow-200 transition">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            @if($user->id_user !== auth()->id())
                                <form method="POST" action="{{ route('admin.user.destroy', $user->id_user) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus user ini?')"
                                        class="bg-red-100 text-red-700 px-3 py-1 rounded text-xs hover:bg-red-200 transition">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">Tidak ada user ditemukan</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-3 border-t">{{ $users->links() }}</div>
</div>
@endsection
