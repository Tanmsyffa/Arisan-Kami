@extends('layouts.app')

@section('title', 'Arisan Saya')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Daftar Arisan Saya</h2>

    @if($groups->isEmpty())
        <div class="alert alert-info">Anda belum bergabung dengan arisan manapun.</div>
    @else
        <div class="list-group">
            @foreach($groups as $group)
                <a href="{{ route('arisan.groups.show', $group->id) }}" class="list-group-item list-group-item-action">
                    <h5 class="mb-1">{{ $group->nama }}</h5>
                    <small>{{ $group->peserta_count }} peserta</small>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
