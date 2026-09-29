@echo off
setlocal EnableExtensions
title SkillHub - Reverb WebSocket
cd /d "%~dp0.."

echo [REVERB] Starting ws://127.0.0.1:8080
echo [REVERB] Keep this window open while testing real-time chat.
echo.
php artisan reverb:start --host=127.0.0.1 --port=8080 --debug

echo.
echo [ERROR] Reverb stopped. Read the error above, then close this window.
pause
