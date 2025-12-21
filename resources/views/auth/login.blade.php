@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', 'Selamat Datang')

@section('title', 'Login - PUSDATIN')

@push('css')
<style>
    body.login-page {
        background: #0f172a; /* gelap, selaras sidebar */
        color: #e5e7eb;
    }
    .login-box, .card {
        border-radius: 14px;
        border: 1px solid #1f2937;
        box-shadow: 0 10px 40px rgba(0,0,0,0.35);
    }
    .card-primary.card-outline {
        border-top: 3px solid #2563eb;
    }
    .btn-primary {
        background-color: #2563eb;
        border-color: #2563eb;
    }
    .btn-primary:hover {
        background-color: #1d4ed8;
        border-color: #1d4ed8;
    }
    .input-group-text {
        background: #111827;
        color: #9ca3af;
        border-color: #1f2937;
    }
    .form-control {
        background: #0b1221;
        border-color: #1f2937;
        color: #e5e7eb;
    }
    .form-control:focus {
        border-color: #2563eb;
        box-shadow: none;
    }
    a {
        color: #93c5fd;
    }
    a:hover {
        color: #bfdbfe;
    }
</style>
@endpush

@section('auth_body')
    <form action="{{ route('login.process') }}" method="POST">
        @csrf

        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
        </div>
        @error('email')
            <div class="text-danger mb-2 small">{{ $message }}</div>
        @enderror

        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>
        @error('password')
            <div class="text-danger mb-2 small">{{ $message }}</div>
        @enderror

        <div class="row mb-2">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt mr-1"></i> Login
                </button>
            </div>
        </div>

        <div class="text-center text-muted" style="font-size: 12px;">
            Login ini digunakan untuk <strong>Staff</strong>, <strong>Manager</strong>, dan <strong>Superadmin</strong>.
        </div>
    </form>
@endsection

@section('auth_footer')
    <div class="text-center text-muted small">
        PUSDATIN · {{ now()->year }}
    </div>
@endsection
