@extends('layouts.app')

@section('content')
    <div class="error-page">
        <div class="error-card">
            <div class="error-illustration">
                <span class="error-code">404</span>
                <div class="error-orbit">
                    <i class="fa-solid fa-question"></i>
                </div>
            </div>

            <h1 class="error-title">الصفحة غير موجودة</h1>
            <p class="error-subtitle">
                يبدو أن الصفحة التي تبحث عنها قد أُزيلت أو لم تكن موجودة من الأساس.
            </p>

            <div class="error-actions">
                <a href="{{ url('/') }}" class="btn btn-primary">
                    <i class="fa-solid fa-house"></i>
                    العودة للرئيسية
                </a>
                <button type="button" class="btn btn-secondary" onclick="history.back()">
                    <i class="fa-solid fa-arrow-right"></i>
                    الرجوع للخلف
                </button>
            </div>
        </div>
    </div>

    <style>
        .error-page {
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .error-card {
            background: #fffaf3;
            border-radius: 24px;
            padding: 50px 40px;
            text-align: center;
            max-width: 560px;
            width: 100%;
            box-shadow: 0 20px 50px rgba(139, 69, 19, 0.15);
            border: 1px solid #f0e3d3;
            animation: fadeIn 0.6s ease;
        }

        .error-illustration {
            position: relative;
            display: inline-block;
            margin-bottom: 24px;
        }

        .error-code {
            font-size: clamp(6rem, 18vw, 9rem);
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(135deg, #8b4513, #c97a4a);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            font-family: "Cairo", sans-serif;
            letter-spacing: -4px;
        }

        .error-orbit {
            position: absolute;
            top: 50%;
            left: -10px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f3c548, #e89817);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            box-shadow: 0 8px 20px rgba(232, 152, 23, 0.4);
            animation: bounce 2.2s ease-in-out infinite;
        }

        .error-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #5a4a3a;
            margin: 8px 0 12px;
        }

        .error-subtitle {
            color: #7a6754;
            font-size: 1.05rem;
            line-height: 1.7;
            margin-bottom: 28px;
        }

        .error-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .error-actions .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(-50%) scale(1); }
            50% { transform: translateY(-65%) scale(1.05); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 480px) {
            .error-card { padding: 35px 22px; }
            .error-title { font-size: 1.4rem; }
            .error-subtitle { font-size: 0.95rem; }
            .error-actions .btn { width: 100%; justify-content: center; }
            .error-orbit { width: 44px; height: 44px; font-size: 1.2rem; left: -6px; }
        }
    </style>
@endsection
