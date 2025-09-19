@extends('layouts.app')

@section('content')

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
                    @if (!Auth::user())
                        <a href="/login" class="btn btn-signin">تسجيل الدخول</a>
                        <a href="/register" class="btn btn-signup">تسجّل حساب جديد</a>
                    @else
                        <a href="{{ route('Auth.logout') }}" class="btn btn-signin">تسجيل خروج</a>
                    @endif


                </div>
            </div>
            <button id="nav-toggle" class="nav-toggle" aria-expanded="false" aria-controls="nav-menu" aria-label="قائمة">
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
            @auth
                @if (Auth::user()->privilege_level === 2)
                    <button class="tab-btn" onclick="showTab(event, 'admin')">الإدارة</button>
                @endif
            @endauth
        </nav>

        <!-- Ask Question Tab -->
        <div id="ask-tab" class="tab-content active">
            <div class="card">
                <h2>اطرح سؤالك</h2>
                <form action="{{ route('questions.store') }}" id="question-form" method="POST">
                    @csrf

                    <div class="form-group">
                        <textarea id="question" name="content" placeholder="اكتب سؤالك هنا..." maxlength="800" required></textarea>
                        @error('content')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
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
            <form action="{{ route('questions.search') }}" method="GET" id="searchForm">
                <div class="search-box">
                    @csrf
                    <input name="search" type="text" id="search-input" placeholder="ابحث في الأسئلة والإجابات...">
                    <button type="submit" class="btn btn-search">بحث</button>
                </div>
            </form>
            <div id="answers-container">
                @if ($questions->count() > 0)
                    @foreach ($questions as $question)
                        @if ($question['is_answered'] == true)
                            <div class="answer-item">
                                <div class="question-section">
                                    <span class="question-label">السؤال:</span>
                                    <div class="question-text">{{ $question['content'] }}</div>
                                </div>
                                <div class="answer-section">
                                    <span class="answer-label">الإجابة:</span>
                                    <div class="answer-text">{{ $question['answer'] }}</div>
                                </div>
                                <div class="answer-meta">
                                    <span class="answer-date">{{ $question['created_at'] }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="empty-state">
                        <p>لا توجد إجابات منشورة حالياً</p>
                        <p style="font-size: 0.9rem; color: #bbb;">سيتم عرض الإجابات هنا بمجرد نشرها</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Admin Tab -->
        @auth

            @if (Auth::user()->privilege_level === 2)
                <div id="admin-tab" class="tab-content">
                    <div class="card">

                        <div id="admin-panel" class="admin-panel ">
                            <div class="admin-header">
                                <h2>إدارة الأسئلة</h2>
                            </div>
                            @if ($questions->count() > 0)
                                @foreach ($questions as $question)
                                    <div id="admin-questions" class="admin-questions">
                                        <!-- Admin questions will be displayed here -->
                                        <div class="admin-question-item @if (!$question['is_answered'] == true) unanswered @endif">
                                            <div class="admin-question-header">
                                                <div class="admin-question-info">
                                                    <div class="admin-question-text">{{ $question['content'] }}</div>
                                                    <div class="admin-question-meta">
                                                        {{ $question['created_at'] }}
                                                        @if ($question['is_answered'] == true)
                                                            | <strong>تم الرد</strong>
                                                        @else
                                                            | <strong>لم يتم الرد</strong>
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="admin-answer-section">
                                                <form action="{{ route('questions.update', $question->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    @if (!$question['is_answered'] == true)
                                                        <textarea id="answer-mfo8361a3ug8ylu88uy" name="answer" placeholder="اكتب الإجابة هنا..."></textarea>
                                                    @else
                                                        <div
                                                            style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 10px 0;">
                                                            {{ $question['answer'] }}
                                                        </div>
                                                    @endif
                                                    <div class="admin-actions">
                                                        @if (!$question['is_answered'] == true)
                                                            <button type="submit" class="btn btn-success">حفظ
                                                                الإجابة</button>
                                                        @endif
                                                </form>

                                                <form action={{ route('questions.destroy', $question['id']) }} method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">حذف
                                                        السؤال</button>
                                                </form>

                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="empty-state">
                                    <p>لا توجد اسئلة حاليا </p>
                                    <p style="font-size: 0.9rem; color: #bbb;">سيتم عرض الاسئلة هنا بمجرد ارسالها</p>
                                </div>
                            @endif

                        </div>


                    </div>
                </div>
            @endif
        @endauth
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
