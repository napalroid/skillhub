@echo off
title SkillHub - Stopping All Services

echo ========================================
echo   SKILLHUB - Stopping All Services
echo ========================================
echo.

echo Closing all SkillHub services...
taskkill /FI "WINDOWTITLE eq AKTIF LARAVEL*" /F > nul 2>&1
taskkill /FI "WINDOWTITLE eq AKTIF REVERB*" /F > nul 2>&1
taskkill /FI "WINDOWTITLE eq AKTIF QUEUE*" /F > nul 2>&1
taskkill /FI "WINDOWTITLE eq AKTIF PROXY*" /F > nul 2>&1
taskkill /FI "WINDOWTITLE eq AKTIF NGROK*" /F > nul 2>&1

echo.
echo ========================================
echo   ALL SERVICES STOPPED!
echo ========================================
echo.
echo Window ini akan tertutup dalam 3 detik...
ping 127.0.0.1 -n 4 > nul
exit
