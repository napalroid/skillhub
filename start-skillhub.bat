@echo off
title SkillHub Launcher
cd /d "D:\xampp\htdocs\UKK MODE SERIUS\skillhub"

echo [1/4] Menjalankan Laravel (port 8001)...
start "SkillHub Web (8001)" cmd /k 

"php artisan serve --host=127.0.0.1 --port=8001"

echo [2/4] Menjalankan Reverb WebSocket (port 8080)...
start "SkillHub Reverb (8080)" cmd /k 

"php artisan reverb:start"

echo [3/4] Menjalankan Proxy ngrok (port 8002)...
start "SkillHub Proxy (8002)" cmd /k 

"node scripts/ngrok-reverb-proxy.mjs"

echo [4/4] Menjalankan tunnel ngrok (domain tetap)...
start "SkillHub Ngrok" cmd /k 

"ngrok http --domain=elastic-landmass-shortcut.ngrok-free.dev 8002"

echo.
echo Semua layanan sudah dijalankan di 4 jendela terpisah.
echo   Web    : http://127.0.0.1:8001
echo   Proxy  : http://127.0.0.1:8002  (tujuan ngrok)
echo   WS     : ws://127.0.0.1:8080
echo   Public : https://elastic-landmass-shortcut.ngrok-free.dev
echo.
echo Jangan lupa: XAMPP - MySQL harus running.
echo.
pause
