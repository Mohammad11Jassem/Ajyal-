<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> {{-- مهم للموبايل --}}
    <title>نجاح الدفع</title>
    <style>
        body {
            font-family: "Cairo", Arial, sans-serif;
            background: linear-gradient(135deg, #e0f7e9, #c8f0d8);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 15px;
        }
        .card {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 450px;
            width: 100%;
            animation: fadeIn 0.8s ease-in-out;
        }
        .icon {
            font-size: 60px;
            color: #28a745;
            margin-bottom: 15px;
        }
        h1 {
            color: #28a745;
            margin-bottom: 20px;
            font-size: 26px;
        }
        p {
            color: #444;
            font-size: 16px;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: #28a745;
            color: #fff;
            font-weight: bold;
            border-radius: 30px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(40,167,69,0.3);
        }
        .btn:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(40,167,69,0.4);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* 📱 تحسين للموبايل */
        @media (max-width: 480px) {
            .card {
                padding: 25px;
            }
            .icon {
                font-size: 45px;
            }
            h1 {
                font-size: 20px;
            }
            p {
                font-size: 14px;
            }
            .btn {
                padding: 10px 20px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="icon">✅</div>
        <h1>تمت عملية الدفع بنجاح</h1>
        <p>شكراً لك على إتمام الدفع.<br>
           تم تسجيل العملية في النظام بنجاح.<br>
           يمكنك الآن العودة إلى التطبيق ومتابعة استخدامه.</p>
        {{-- <a href="{{ url('/') }}" class="btn">العودة للتطبيق</a> --}}
    </div>

</body>
</html>
