@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Manajemen Grup Arisan</h1>

<a href="{{ route('groups.create') }}" class="mb-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded">
    + Tambah Grup
</a>

@if (session('success'))
    <div class="bg-green-100 text-green-700 border p-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<table class="w-full table-auto border-collapse border">
    <thead>
        <tr class="bg-gray-200">
            <th class="border px-4 py-2">Nama</th>
            <th class="border px-4 py-2">Iuran</th>
            <th class="border px-4 py-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($groups as $group)
        <tr>
            <td class="border px-4 py-2">{{ $group->name }}</td>
            <td class="border px-4 py-2">Rp{{ number_format($group->amount) }}</td>
            <td class="border px-4 py-2">
                <a href="{{ route('groups.edit', $group) }}" class="text-blue-600 mr-2">Edit</a>
                <form method="POST" action="{{ route('groups.destroy', $group) }}" class="inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Hapus grup ini?')" class="text-red-600">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
