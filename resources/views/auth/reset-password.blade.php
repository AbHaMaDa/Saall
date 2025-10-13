@extends('layouts.app')

@section('content')
    <div class="signing-container">
        <h2>اعد تعيين كلمة المرور</h2>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf

            {{-- Hidden token --}}
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Email field --}}
            <div class="mb-3">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <input type="email" class="form-control" id="email" name="email"
                    placeholder="أدخل البريد الإلكتروني" value="{{ old('email', $email ?? '') }}" required>
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password field --}}
            <div class="mb-3">
                <label for="password" class="form-label">كلمة المرور</label>
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="أدخل كلمة المرور الجديدة" required>
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Confirm password --}}
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    placeholder="أعد إدخال كلمة المرور" required>
                @error('password_confirmation')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-signing">تعيين</button>
        </form>
    </div>
@endsection
