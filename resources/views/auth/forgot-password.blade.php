@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 500px; margin-top: 50px;">
    <h3 class="text-center mb-4">نسيت كلمة المرور ؟</h3>



@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif


    <form method="POST" action="{{route('password.email')}}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input id="email" type="email" name="email" class="form-control" required autofocus>
            @error('email')
                <div class="text-danger mt-2 small">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">إرسال رابط إعادة التعيين</button>
        <a class="btn btn-light w-100 my-3 text-dark" href="{{route('login')}}">رجوع</a>
    </form>
</div>
@endsection
