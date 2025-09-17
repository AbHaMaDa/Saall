


@extends('layouts.app')

@section( 'content')

    <nav class="app-navbar">
        <div class="container">
            <div class="brand">
                <!-- ضع ملف اللوجو في public/images/logo.png أو غيّر المسار -->
                <a href="" class="brand-link" aria-label="الرئيسية">
                    <img src="{{ asset('/icon2.png') }}" alt="سَل" class="brand-logo">
                </a>
            </div>



            <div id="nav-menu" class="nav-menu" role="menu">
                <div class="auth-actions">
                    <a href="/login" class="btn btn-signin">تسجيل الدخول</a>
                    <a href="/register" class="btn btn-signup">تسجّل حساب جديد</a>
                </div>
            </div>

            <button id="nav-toggle" class="nav-toggle" aria-expanded="false" aria-controls="nav-menu"
                aria-label="قائمة">
                <span class="hamburger"></span>
            </button>
        </div>
    </nav>

    <div class="container">
        <!-- Header -->
        <header class="header">
            <h1 class="app-title">سَل</h1>
            <p class="app-subtitle">منصة الأسئلة والفتاوى الشرعية</p>
        </header>

        <!-- Navigation Tabs -->
        <nav class="nav-tabs">
            <button class="tab-btn active" onclick="showTab(event, 'ask')">إرسال سؤال</button>
            <button class="tab-btn" onclick="showTab(event, 'answers')">الإجابات</button>
            <button class="tab-btn" onclick="showTab(event, 'admin')">الإدارة</button>
        </nav>

        <!-- Ask Question Tab -->
        <div id="ask-tab" class="tab-content active">
            <div class="card">
                <h2>اطرح سؤالك</h2>
                <form id="question-form">
                    <div class="form-group">
                        <textarea id="question" placeholder="اكتب سؤالك هنا..." maxlength="800" required></textarea>
                        <div class="char-counter">
                            <span id="char-count">0</span> / 800 حرف
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">إرسال السؤال</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Answers Tab -->
        <div id="answers-tab" class="tab-content">
            <div class="search-box">
                <input type="text" id="search-input" placeholder="ابحث في الأسئلة والإجابات...">
                <button onclick="searchAnswers()" class="btn btn-search">بحث</button>
            </div>
            <div id="answers-list" class="answers-list">
                <!-- Answers will be displayed here -->
            </div>
        </div>

        <!-- Admin Tab -->
        <div id="admin-tab" class="tab-content">
            <div class="card">
                <div id="admin-login" class="admin-login">
                    <h2>دخول الإدارة</h2>
                    <div class="form-group">
                        <label for="admin-pin">PIN</label>
                        <input type="password" id="admin-pin" placeholder="أدخل رمز الدخول">
                    </div>
                    <button onclick="adminLogin()" class="btn btn-primary">دخول</button>
                </div>

                <div id="admin-panel" class="admin-panel hidden">
                    <div class="admin-header">
                        <h2>إدارة الأسئلة</h2>
                        <button onclick="adminLogout()" class="btn btn-secondary">خروج</button>
                    </div>
                    <div id="admin-questions" class="admin-questions">
                        <!-- Admin questions will be displayed here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        <div id="success-message" class="message success hidden">
            <span class="message-text"></span>
        </div>

        <!-- Error Message -->
        <div id="error-message" class="message error hidden">
            <span class="message-text"></span>
        </div>
    </div>


@endsection
