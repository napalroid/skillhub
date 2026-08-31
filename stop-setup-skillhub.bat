@echo off
title SkillHub - Stop All Services
color 0C

echo ========================================
echo  SkillHub - Stopping All Services
echo ========================================
echo.

echo Stopping Reverb WebSocket Server...
taskkill /FI "WINDOWTITLE eq SkillHub - Reverb WebSocket*" /F >nul 2>&1

echo Stopping Queue Worker...
taskkill /FI "WINDOWTITLE eq SkillHub - Queue Worker*" /F >nul 2>&1

echo Stopping Laravel Server...
taskkill /FI "WINDOWTITLE eq SkillHub - Laravel Server*" /F >nul 2>&1

echo Stopping ngrok Tunnel...
taskkill /FI "WINDOWTITLE eq SkillHub - ngrok Tunnel*" /F >nul 2>&1
taskkill /IM ngrok.exe /F >nul 2>&1

echo.
echo ========================================
echo  ALL SERVICES STOPPED
echo ========================================
echo.
echo All SkillHub services have been terminated.
echo.
pause
