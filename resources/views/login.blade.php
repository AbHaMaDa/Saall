@extends('layouts.app')





@section('content')
    <div class="signing-container">
        <h2> دخول الادارة </h2>
        <p class="text-center" style="font-size: 0.9rem; color: #bbb;">الإدارة خاصة بالشيخ و المسؤلين فقط .</p>
        <form action="{{ route('Auth.login') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="أدخل البريد الإلكتروني"
                    required>
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">كلمة المرور</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="أدخل كلمة المرور"
                    required>
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-signing">تسجيل</button>
            <p class="form-text mt-3"><a href="/register">طلب الإنضمام للادارة ! </a></p>
            <p class="form-text mt-3"><a href="/index">الصفحة الرئيسية</a></p>
        </form>
    </div>
@endsection
