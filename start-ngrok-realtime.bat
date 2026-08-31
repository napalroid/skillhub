@echo off
title SkillHub - Ngrok Setup (2 Tunnels)
color 0A

echo ========================================
echo  SkillHub - Ngrok Real-Time Setup
echo ========================================
echo.
echo Starting services with ngrok tunnels...
echo.

REM Start Reverb WebSocket Server
echo [1/5] Starting Reverb WebSocket Server...
start "SkillHub - Reverb WebSocket" cmd /k "cd /d "%~dp0" && echo [REVERB] Starting on ws://127.0.0.1:8080 && php artisan reverb:start"
timeout /t 3 /nobreak >nul

REM Start Queue Worker
echo [2/5] Starting Queue Worker...
start "SkillHub - Queue Worker" cmd /k "cd /d "%~dp0" && echo [QUEUE] Processing broadcasts and jobs && php artisan queue:work --tries=3 --timeout=90"
timeout /t 2 /nobreak >nul

REM Start Laravel Server
echo [3/5] Starting Laravel Development Server...
start "SkillHub - Laravel Server" cmd /k "cd /d "%~dp0" && echo [LARAVEL] Starting on http://127.0.0.1:8000 && php artisan serve"
timeout /t 3 /nobreak >nul

REM Start ngrok tunnel for Laravel (port 8000)
echo [4/5] Starting ngrok tunnel for Laravel...
start "SkillHub - ngrok Laravel" cmd /k "cd /d "%~dp0" && echo [NGROK LARAVEL] Tunneling port 8000... && ngrok http 8000"
timeout /t 3 /nobreak >nul

REM Start ngrok tunnel for Reverb (port 8080)
echo [5/5] Starting ngrok tunnel for Reverb WebSocket...
start "SkillHub - ngrok Reverb" cmd /k "cd /d "%~dp0" && echo [NGROK REVERB] Tunneling port 8080... && ngrok http 8080"
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
echo   [4] ngrok Laravel: Check window for HTTPS URL
echo   [5] ngrok Reverb: Check window for WSS URL
echo.
echo ========================================
echo  IMPORTANT - UPDATE .env:
echo ========================================
echo.
echo 1. Check both ngrok windows for URLs
echo 2. Copy Laravel ngrok URL (https://xxx.ngrok-free.app)
echo 3. Copy Reverb ngrok URL (https://yyy.ngrok-free.app)
echo 4. Update .env file:
echo    APP_URL=https://xxx.ngrok-free.app
echo    REVERB_HOST=yyy.ngrok-free.app
echo    VITE_REVERB_HOST=yyy.ngrok-free.app
echo 5. Run: php artisan config:clear
echo 6. Rebuild: npm run build
echo.
echo ========================================
echo.
pause
