@extends('layouts.app')

@section('content')

    <nav class="app-navbar navbar navbar-expand-lg ">
        <div class="container ">
            <!-- Brand / Logo -->
            <div class="brand">
                <a href="/index" class="brand-link" aria-label="الرئيسية">
                    <img src="{{ asset('/logo.jpeg') }}" alt="سَل" class="brand-logo">
                </a>
            </div>

            <!-- Toggler button (show only if user not logged in) -->

            @if (!Auth::user())
                <a href="/login">
                    <img src="{{ asset('/join.jpeg') }}" alt="join" class="brand-logo">
                </a>
            @else
                <button class=" btn-signin" data-bs-toggle="modal" data-bs-target="#exampleModallogout">
                    <img src="logout.jpeg" alt="signout" class="brand-logo">
                </button>
            @endif

            {{-- <!-- Nav menu -->
            @if (!Auth::user())
                <div id="nav-menu" class="nav-menu collapse navbar-collapse" role="menu">
                    <div class="auth-actions d-flex">
                        <a href="/login" class="btn btn-signin me-2">تسجيل الدخول</a>
                        <a href="/register" class="btn btn-signup">تسجّل حساب جديد</a>
                    </div>
                </div>
            @endif --}}

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
            <button class="tab-btn " data-tab="ask" onclick="showTab(event, 'ask')">إرسال سؤال</button>
            <button class="tab-btn" data-tab="answers" onclick="showTab(event, 'answers')">الإجابات</button>
            @auth
                @if (Auth::user()->privilege_level === 2)
                    <button class="tab-btn" data-tab="admin" onclick="showTab(event, 'admin')">الإدارة</button>
                @endif
            @endauth
        </nav>

        <!-- Ask Question Tab -->
        <div id="ask-tab" class="tab-content ">
            <div class="card">
                <h2>اطرح سؤالك</h2>
                <form action="{{ route('questions.store') }}" id="question-form" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea id="question" name="content" placeholder="اكتب سؤالك هنا..." maxlength="1000" required></textarea>
                        @error('content')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="char-counter">
                            <span id="char-count">0</span> / 1000 حرف
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">إرسال </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Answers Tab -->
        <div id="answers-tab" class="tab-content">
            <form action="{{ route('questions.search') }}" method="GET" id="searchFormPublic" class="public-search">
                <div class="search-box">
                    @csrf
                    <input name="search" type="text" id="search-input-public"
                        placeholder="ابحث في الأسئلة والإجابات العامة...">
                    <button type="submit" class="btn btn-search">بحث</button>
                </div>
            </form>
            <form action="{{ route('questions.visitorSearch') }}" method="GET" id="searchFormMine"
                class="mine-search hidden">
                <div class="search-box">
                    @csrf
                    <input name="search" type="text" id="search-input-mine"
                        placeholder="ابحث في الأسئلة والإجابات الخاصة...">
                    <button type="submit" class="btn btn-search">بحث</button>
                </div>
            </form>
            <div class="d-flex gap-2 mb-3">
                <button type="button" id="mine" class="btn btn-primary">خاصَّة</button>
                <button type="button" id="all" class="btn btn-light">عامَّة</button>
            </div>
            <div id="answers-container-public" class="hidden">
                @if ($answeredQuestions->count() > 0)
                    @foreach ($answeredQuestions as $answeredQuestion)
                        <div class="answer-item">
                            <div class="question-section">
                                <span class="question-label">السؤال:</span>
                                <div class="question-text">{{ $answeredQuestion['content'] }}</div>
                            </div>
                            <div class="answer-section">
                                <span class="answer-label">الإجابة:</span>
                                <div class="answer-text">{{ $answeredQuestion['answer'] }}</div>
                            </div>
                            <div class="answer-meta d-flex justify-content-between align-items-center">
                                @auth
                                    @if (Auth::user()->privilege_level === 2)
                                        <i class="fa-solid fa-trash icon-trash" data-bs-toggle="modal"
                                            data-bs-target="#exampleModalDeleteUnanswer{{ $answeredQuestion['id'] }}"></i>
                                    @endif
                                @endauth
                                <span class="answer-date">{{ $answeredQuestion['created_at']->format('Y-m-d \\\ H:i:s') }}</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div id="empty-state-public" class="empty-state">
                        <p>لا توجد إجابات منشورة حالياً</p>
                        <p style="font-size: 0.9rem; color: #bbb;">سيتم عرض الإجابات هنا بمجرد نشرها</p>
                    </div>
                @endif
            </div>
            <div id="answers-container-mine" class="">
                <p style="font-size: 0.9rem; color: #6b6565;">ملحوظة : لن تتمكّن من الوصول لأسئلتك إذا استخدمت جهازًا أو
                    متصفحًا مختلفًا.</p>

                @if ($answeredUserQuestions->count() > 0)
                    @foreach ($answeredUserQuestions as $answeredUserQuestion)
                            <div class="answer-item">
                                <div class="question-section">
                                    <span class="question-label">السؤال:</span>
                                    <div class="question-text">{{ $answeredUserQuestion['content'] }}</div>
                                </div>
                                <div class="answer-section">
                                    <span class="answer-label">الإجابة:</span>
                                    <div class="answer-text">{{ $answeredUserQuestion['answer'] }}</div>
                                </div>
                                <div class="answer-meta d-flex justify-content-between align-items-center">
                                    @auth
                                        @if (Auth::user()->privilege_level === 2)
                                            <i class="fa-solid fa-trash icon-trash" data-bs-toggle="modal"
                                                data-bs-target="#exampleModalDeleteUnanswer{{ $answeredUserQuestion['id'] }}"></i>
                                        @endif
                                    @endauth
                                    <span
                                        class="answer-date">{{ $answeredUserQuestion['created_at']->format('Y-m-d \\\ H:i:s') }}</span>
                                </div>
                            </div>
                    @endforeach
                @else
                    <div id="empty-state-mine" class="empty-state">
                        <p>لا توجد إجابات خاصة منشورة حالياً</p>
                        <p style="font-size: 0.9rem; color: #bbb;">سيتم عرض الإجابات الخاصة هنا بمجرد نشرها</p>
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
                            @if ($unAnsweredQuestions->count() > 0)
                                @foreach ($questions as $question)
                                    @if ($question['is_answered'] == false)
                                        <div id="admin-questions" class="admin-questions">
                                            <!-- Admin questions will be displayed here -->
                                            <div class="admin-question-item unanswered">
                                                <div class="admin-question-header">
                                                    <div class="admin-question-info">
                                                        <div class="admin-question-text">{{ $question['content'] }}</div>
                                                        <div class="admin-question-meta">
                                                            {{ $question['created_at']->format('Y-m-d \\\ H:i:s') }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="admin-answer-section">
                                                    <form action="{{ route('questions.update', $question->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <textarea id="answer-mfo8361a3ug8ylu88uy" name="answer" placeholder="اكتب الإجابة هنا..." required></textarea>
                                                        <div class="admin-actions">
                                                            <button type="submit" class="btn btn-success">حفظ
                                                                الإجابة</button>
                                                            <a class="btn btn-danger" data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalDeleteUnanswer{{ $question['id'] }}">حذف
                                                                السؤال</a>


                                                        </div>
                                                    </form>


                                                </div>

                                            </div>
                                        </div>
                                    @endif
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

    <!-- pending Message -->
    @if (session('status'))
        <div id="flash-message" class="message pending">
            <span class="message-text">{{ session('status') }}</span>
        </div>
    @endif

    <!-- Modal logout -->
    <div class="modal fade" id="exampleModallogout" tabindex="-1" aria-labelledby="exampleModalLabellogout"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title fs-5" id="exampleModalLabellogout">تسجيل الخروح </h1>
                </div>
                <div class="modal-body">
                    هل أنت متأكد أنك تريد تسجيل الخروج؟
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <a href="{{ route('Auth.logout') }}" class="btn btn-primary">تسجيل الخروج</a>
                </div>
            </div>
        </div>
    </div>


    @foreach ($questions as $question)
        <!-- Modal deleteUnanswer -->
        <div class="modal fade" id="exampleModalDeleteUnanswer{{ $question['id'] }}" tabindex="-1"
            aria-labelledby="exampleModalDeleteUnanswer{{ $question['id'] }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h1 class="modal-title fs-5" id="exampleModalDeleteUnanswer{{ $question['id'] }}">حذف السؤال
                        </h1>
                    </div>
                    <div class="modal-body">
                        هل أنت متأكد أنك تريد حذف السؤال؟
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <form class="model-form" action={{ route('questions.destroy', $question['id']) }} method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">حذف
                                السؤال</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
