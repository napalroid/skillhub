@echo off
title SkillHub - Starting All Services

echo ========================================
echo   SKILLHUB - Starting All Services
echo ========================================
echo.

echo [1/5] Starting Laravel Server...
start "AKTIF LARAVEL" cmd /k "cd /d "%~dp0" && php artisan serve --host=127.0.0.1 --port=8001"

ping 127.0.0.1 -n 3 > nul

echo [2/5] Starting Reverb (WebSocket)...
start "AKTIF REVERB" cmd /k "cd /d "%~dp0" && php artisan reverb:start --host=0.0.0.0 --port=8080"

ping 127.0.0.1 -n 3 > nul

echo [3/5] Starting Queue Worker...
start "AKTIF QUEUE" cmd /k "cd /d "%~dp0" && php artisan queue:work"

ping 127.0.0.1 -n 3 > nul

echo [4/5] Starting Ngrok-Reverb Proxy...
start "AKTIF PROXY" cmd /k "cd /d "%~dp0" && node scripts/ngrok-reverb-proxy.mjs"

ping 127.0.0.1 -n 3 > nul

echo [5/5] Starting Ngrok Tunnel...
start "AKTIF NGROK" cmd /k "ngrok http --domain=elastic-landmass-shortcut.ngrok-free.dev 8002"

ping 127.0.0.1 -n 4 > nul

echo.
echo ========================================
echo   ALL SERVICES STARTED!
echo ========================================
echo.
echo Terminal yang terbuka:
echo   - AKTIF LARAVEL  : PHP Server (port 8001)
echo   - AKTIF REVERB   : WebSocket Server (port 8080)
echo   - AKTIF QUEUE    : Background Jobs
echo   - AKTIF PROXY    : WebSocket Proxy
echo   - AKTIF NGROK    : Public Tunnel
echo.
echo Window ini akan tertutup dalam 5 detik...
ping 127.0.0.1 -n 6 > nul
exit
