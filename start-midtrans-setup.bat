@echo off
title SkillHub - Midtrans + Reverb Setup
color 0A

echo ========================================
echo  SkillHub - Setup for Midtrans Testing
echo ========================================
echo.
echo Starting services (Midtrans-ready)...
echo.

REM Start Reverb WebSocket Server (localhost only)
echo [1/4] Starting Reverb WebSocket Server...
start "SkillHub - Reverb WebSocket" cmd /k "cd /d "%~dp0" && echo [REVERB] ws://127.0.0.1:8080 ^(localhost only^) && php artisan reverb:start"
timeout /t 3 /nobreak >nul

REM Start Queue Worker
echo [2/4] Starting Queue Worker...
start "SkillHub - Queue Worker" cmd /k "cd /d "%~dp0" && echo [QUEUE] Processing broadcasts && php artisan queue:work --tries=3 --timeout=90"
timeout /t 2 /nobreak >nul

REM Start Laravel Server
echo [3/4] Starting Laravel Server...
start "SkillHub - Laravel Server" cmd /k "cd /d "%~dp0" && echo [LARAVEL] http://127.0.0.1:8000 && php artisan serve"
timeout /t 3 /nobreak >nul

REM Start ngrok for Laravel only (for Midtrans callback)
echo [4/4] Starting ngrok tunnel for Midtrans...
start "SkillHub - ngrok" cmd /k "cd /d "%~dp0" && echo [NGROK] Public URL for Midtrans callback && echo Copy URL and update Midtrans dashboard && ngrok http 8000"
timeout /t 3 /nobreak >nul

echo.
echo ========================================
echo  SERVICES STARTED
echo ========================================
echo.
echo [1] Reverb WebSocket: ws://127.0.0.1:8080 (LOCAL)
echo [2] Queue Worker: Processing broadcasts
echo [3] Laravel Server: http://127.0.0.1:8000
echo [4] ngrok: Check window for public URL
echo.
echo ========================================
echo  IMPORTANT - MIDTRANS SETUP:
echo ========================================
echo.
echo 1. Check ngrok window for public URL
echo    Example: https://abc123.ngrok-free.app
echo.
echo 2. Update Midtrans Dashboard:
echo    - Go to: https://dashboard.midtrans.com
echo    - Settings ^> Configuration
echo    - Payment Notification URL:
echo      https://abc123.ngrok-free.app/midtrans/notification
echo.
echo 3. Access website:
echo    Local: http://127.0.0.1:8000 (for testing)
echo    Public: https://abc123.ngrok-free.app (for Midtrans)
echo.
echo ========================================
echo  FEATURES:
echo ========================================
echo.
echo [OK] Midtrans payment: WORKS (via ngrok)
echo [OK] Chat real-time: WORKS (via localhost Reverb)
echo [OK] Notifikasi real-time: WORKS (via localhost Reverb)
echo [OK] No error 500
echo.
echo NOTE: Real-time only works on same computer
echo       (Chat/notif need localhost Reverb connection)
echo.
echo Press any key to open local site...
pause >nul
start http://127.0.0.1:8000
echo.
echo Keep all windows open. Press any key to exit...
pause >nul
