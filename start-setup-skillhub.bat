@echo off
title SkillHub - Complete Setup
color 0A

echo ========================================
echo  SkillHub - Complete Development Setup
echo ========================================
echo.
echo Starting all required services...
echo.

REM Check if ngrok is installed
where ngrok >nul 2>&1
if %errorlevel% neq 0 (
    echo [WARNING] ngrok not found in PATH
    echo Please install ngrok: https://ngrok.com/download
    echo.
)

REM Start Reverb WebSocket Server
echo [1/4] Starting Reverb WebSocket Server...
start "SkillHub - Reverb WebSocket" cmd /k "cd /d "%~dp0" && echo [REVERB] Starting on ws://127.0.0.1:8080 && php artisan reverb:start"
timeout /t 2 /nobreak >nul

REM Start Queue Worker
echo [2/4] Starting Queue Worker...
start "SkillHub - Queue Worker" cmd /k "cd /d "%~dp0" && echo [QUEUE] Processing broadcasts and jobs && php artisan queue:work --tries=3 --timeout=90"
timeout /t 2 /nobreak >nul

REM Start Laravel Server
echo [3/4] Starting Laravel Development Server...
start "SkillHub - Laravel Server" cmd /k "cd /d "%~dp0" && echo [LARAVEL] Starting on http://127.0.0.1:8000 && php artisan serve"
timeout /t 3 /nobreak >nul

REM Start ngrok tunnel
echo [4/4] Starting ngrok tunnel...
start "SkillHub - ngrok Tunnel" cmd /k "cd /d "%~dp0" && echo [NGROK] Creating public tunnel... && ngrok http 8000"
timeout /t 3 /nobreak >nul

echo.
echo ========================================
echo  ALL SERVICES STARTED
echo ========================================
echo.
echo Services running:
echo   [1] Reverb WebSocket: ws://127.0.0.1:8080
echo   [2] Queue Worker: Processing broadcasts
echo   [3] Laravel Server: http://127.0.0.1:8000
echo   [4] ngrok Tunnel: Check ngrok window for public URL
echo.
echo ========================================
echo  IMPORTANT:
echo ========================================
echo.
echo 1. Check ngrok window for public HTTPS URL
echo 2. Access local: http://127.0.0.1:8000
echo 3. Access public: [ngrok URL from window]
echo 4. Real-time features: ENABLED
echo 5. All windows must stay open
echo.
echo Press any key to open local site in browser...
pause >nul
start http://127.0.0.1:8000
echo.
echo Setup complete! Keep this window open.
echo Press any key to exit and close all services...
pause >nul
