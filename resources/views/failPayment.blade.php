<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فشل الدفع</title>
    <style>
        body {
            font-family: "Cairo", Arial, sans-serif;
            background: linear-gradient(135deg, #f8d7da, #f5c6cb); /* ألوان حمراء */
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
            color: #dc3545; /* لون أحمر للفشل */
            margin-bottom: 15px;
        }
        h1 {
            color: #dc3545;
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
            background: #dc3545;
            color: #fff;
            font-weight: bold;
            border-radius: 30px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(220,53,69,0.3);
        }
        .btn:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(220,53,69,0.4);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* تحسين للموبايل */
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
        <div class="icon">❌</div>
        <h1>فشل عملية الدفع</h1>
        <p>عذرًا، لم تتم عملية الدفع بنجاح.<br>
           يرجى التحقق من بياناتك أو المحاولة مرة أخرى.<br>
           إذا استمرت المشكلة، يرجى التواصل مع الدعم الفني.
           <br>
        نود أن نعلمك انه لم تتم عملية التحويل.</p>
        {{-- <a href="{{ url('/') }}" class="btn">المحاولة مرة أخرى</a> --}}
    </div>

</body>
</html>
