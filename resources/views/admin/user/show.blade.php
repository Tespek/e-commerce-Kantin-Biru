@extends('layouts.admin')
@section('title', 'Detail User')
@section('page-title', 'Detail User')

@section('content')
<div class="max-w-2xl space-y-5">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-4 mb-5">
            <div class="w-16 h-16 rounded-full bg-blue-700 text-white text-2xl flex items-center justify-center font-bold">
                {{ strtoupper(substr($user->nama, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">{{ $user->nama }}</h2>
                <span class="text-xs px-2.5 py-0.5 rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><p class="text-gray-500">Email</p><p class="font-medium">{{ $user->email }}</p></div>
            <div><p class="text-gray-500">No. HP</p><p class="font-medium">{{ $user->no_hp ?? '-' }}</p></div>
            <div class="col-span-2"><p class="text-gray-500">Alamat</p><p class="font-medium">{{ $user->alamat ?? '-' }}</p></div>
            <div><p class="text-gray-500">Bergabung</p><p class="font-medium">{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d M Y') : '-' }}</p></div>
            <div><p class="text-gray-500">Total Pesanan</p><p class="font-medium">{{ $user->orders_count }}</p></div>
        </div>

        <div class="flex gap-3 mt-5">
            <a href="{{ route('admin.user.edit', $user->id_user) }}"
               class="bg-blue-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-800 transition">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('admin.user.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-300 transition">
                Kembali
            </a>
        </div>
    </div>

    @if($orders->isNotEmpty())
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-bold text-gray-800 mb-4">5 Pesanan Terakhir</h2>
            <div class="space-y-2">
                @foreach($orders as $order)
                    <div class="flex items-center justify-between text-sm py-2 border-b last:border-0">
                        <div>
                            <p class="font-medium">#{{ $order->id_order }}</p>
                            <p class="text-gray-500 text-xs">{{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('d M Y') : '-' }}</p>
                        </div>
                        <p class="font-semibold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                        <span class="px-2.5 py-0.5 rounded-full text-xs {{ match($order->status) { 'selesai' => 'bg-green-100 text-green-700', 'dibatalkan' => 'bg-red-100 text-red-700', default => 'bg-yellow-100 text-yellow-700' } }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
