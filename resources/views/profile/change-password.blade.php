@extends('adminlte::page')

@section('title', 'Ubah Password')

@section('content_header')
    <h1>Ubah Password</h1>
@endsection

@section('content')

{{-- Alert sukses --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

{{-- Alert error --}}
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('profile.update-password') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="current_password">Password Lama</label>
                <input type="password" 
                       name="current_password" 
                       id="current_password" 
                       class="form-control @error('current_password') is-invalid @enderror" 
                       required 
                       autocomplete="current-password">
                @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="new_password">Password Baru</label>
                <input type="password" 
                       name="new_password" 
                       id="new_password" 
                       class="form-control @error('new_password') is-invalid @enderror" 
                       required 
                       autocomplete="new-password"
                       minlength="8">
                @error('new_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Minimal 8 karakter</small>
            </div>

            <div class="mb-3">
                <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                <input type="password" 
                       name="new_password_confirmation" 
                       id="new_password_confirmation" 
                       class="form-control" 
                       required 
                       autocomplete="new-password"
                       minlength="8">
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-key"></i> Ubah Password
                </button>
                <a href="{{ route('profile.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

@endsection

