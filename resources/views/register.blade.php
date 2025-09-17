@extends('layouts.app')





@section('content')
    <div class="signing-container">
        <h2>تسجيل حساب جديد</h2>
        <form action="" method="POST">
            @csrf
            <div class="mb-3 ">
                <label for="name" class="form-label">الاسم</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="أدخل الاسم" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <input type="email" class="form-control" id="email" name="email"
                    placeholder="أدخل البريد الإلكتروني" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">كلمة المرور</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="أدخل كلمة المرور"
                    required>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    placeholder="أعد إدخال كلمة المرور" required>
            </div>
            <button type="submit" class="btn btn-signing">تسجيل</button>
            <p class="form-text mt-3">لديك حساب؟ <a href="/login">تسجيل الدخول</a></p>
            <p class="form-text mt-3"><a href="/index">الصفحة الرئيسية</a></p>

        </form>
    </div>
@endsection
