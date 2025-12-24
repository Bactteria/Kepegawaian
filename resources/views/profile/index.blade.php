@extends('layouts.admin')

@section('title', 'User Profile')

@section('content')

<style>
    .profile-header {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .profile-header img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
    }
    .profile-card {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        margin-top: 20px;
    }
    .profile-card h4 {
        font-weight: bold;
        margin-bottom: 20px;
    }
    .edit-btn {
        float: right;
    }
</style>

<div class="container-fluid">

    {{-- HEADER PROFIL --}}
    <div class="profile-header">
        <img src="{{ asset('uploads/user/'.$user->foto) }}" alt="Foto Profil">

        <div>
            <h4 class="mb-1">{{ $user->nama }}</h4>
            <p class="text-muted mb-0">{{ $user->jabatan }}</p>
            <small class="text-secondary">{{ $user->email }}</small>
        </div>

        <button class="btn btn-outline-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editProfileModal">
            Edit
        </button>
    </div>

    {{-- PERSONAL INFORMATION --}}
    <div class="profile-card">
        <h4>Personal Information</h4>

        <div class="row">
            <div class="col-md-6 mb-3">
                <small class="text-muted">Nama</small>
                <div>{{ $user->nama }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <small class="text-muted">Email</small>
                <div>{{ $user->email }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <small class="text-muted">Telepon</small>
                <div>{{ $user->telepon }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <small class="text-muted">Jabatan</small>
                <div>{{ $user->jabatan }}</div>
            </div>

            <div class="col-12 mb-3">
                <small class="text-muted">Alamat</small>
                <div>{{ $user->alamat }}</div>
            </div>
        </div>
    </div>

</div>


{{-- MODAL EDIT --}}
<div class="modal fade" id="editProfileModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title">Edit Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" value="{{ $user->nama }}">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}">
            </div>

            <div class="mb-3">
                <label>Telepon</label>
                <input type="text" name="telepon" class="form-control" value="{{ $user->telepon }}">
            </div>

            <div class="mb-3">
                <label>Jabatan</label>
                <input type="text" name="jabatan" class="form-control" value="{{ $user->jabatan }}">
            </div>

            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control">{{ $user->alamat }}</textarea>
            </div>

            <div class="mb-3">
                <label>Foto Baru</label>
                <input type="file" name="foto" class="form-control">
            </div>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>

      </form>

    </div>
  </div>
</div>

@endsection
