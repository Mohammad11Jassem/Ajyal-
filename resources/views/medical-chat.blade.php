<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المساعد الطبي الذكي</title>
    <style>
        * {
            box-sizing: border-box;
        }

        @keyframes medical-spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
    @vite(['resources/js/medical-chat.jsx'])
</head>
<body style="margin:0;">
    <div id="medical-chat-root"></div>
</body>
</html>
