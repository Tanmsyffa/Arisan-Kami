@extends('layouts.app')

@section('content')
<h1 class="text-xl font-bold mb-4">Tambah Grup Arisan</h1>

<form method="POST" action="{{ route('admin.groups.store') }}" class="max-w-md">
    @csrf
    <label>Nama Grup</label>
    <input name="name" class="w-full border p-2 mb-3" required>

    <label>Nominal Iuran</label>
    <input name="amount" type="number" class="w-full border p-2 mb-3" required>

    <button class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
</form>
@endsection
