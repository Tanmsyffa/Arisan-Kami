@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Daftar Grup Arisan</h1>
        <a href="{{ route('groups.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Buat Grup Baru
        </a>
    </div>

    @if($groups->isEmpty())
        <div class="alert alert-info">
            Belum ada grup arisan. Silakan buat grup baru!
        </div>
    @else
        <div class="row">
            @foreach($groups as $group)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">{{ $group->name }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <span class="badge bg-{{ $group->isActive() ? 'success' : 'secondary' }}">
                                    {{ $group->status }}
                                </span>
                                <span class="badge bg-info ms-2">
                                    {{ $group->members_count }} Anggota
                                </span>
                            </div>
                            
                            <p class="card-text">
                                <i class="fas fa-money-bill-wave me-1"></i>
                                Iuran: Rp {{ number_format($group->amount, 0, ',', '.') }}
                            </p>
                            
                            <p class="card-text">
                                <i class="fas fa-user me-1"></i>
                                Pemilik: {{ $group->owner->name }}
                            </p>
                            
                            <p class="card-text">
                                <i class="fas fa-calendar me-1"></i>
                                Dibuat: {{ $group->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="card-footer bg-white">
                            <a href="{{ route('groups.show', $group) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i> Detail
                            </a>
                            
                            @if($group->isOwner(auth()->id()))
                                <a href="{{ route('groups.edit', $group) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $groups->links() }}
        </div>
    @endif
</div>
@endsection