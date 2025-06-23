@extends('layouts.app')

@section('title', 'Tambah Pembayaran Manual')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.payments.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Grup Arisan</label>
                <select name="group_id" class="form-select" required>
                    <option value="">-- Pilih Grup --</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Pengguna</label>
                <select name="user_id" class="form-select" required>
                    <option value="">-- Pilih Pengguna --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Periode</label>
                <input type="text" name="period" class="form-control" value="{{ old('period', now()->format('Y-m')) }}" required>
            </div>

            <div class="mb-3">
                <label>Jumlah (Rp)</label>
                <input type="number" name="amount" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Status</label>
                <select name="payment_status" class="form-select" required>
                    <option value="pending">Pending</option>
                    <option value="paid">Lunas</option>
                    <option value="failed">Gagal</option>
                    <option value="challenge">Challenge</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        </form>
    </div>
</div>
@endsection
