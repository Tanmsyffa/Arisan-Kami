@extends('layouts.app')

@section('content')
<h1 class="text-xl font-bold mb-4">Edit Grup Arisan</h1>

<form method="POST" action="{{ route('groups.update', $group) }}" class="max-w-md">
    @csrf
    @method('PUT')

    <label>Nama Grup</label>
    <input name="name" class="w-full border p-2 mb-3" value="{{ old('name', $group->name) }}" required>

    <label>Nominal Iuran</label>
    <input name="amount" type="number" class="w-full border p-2 mb-3" value="{{ old('amount', $group->amount) }}" required>

    <button class="bg-blue-600 text-white px-4 py-2 rounded">Perbarui</button>
</form>
@endsection
