@echo off
setlocal EnableExtensions
title SkillHub - Development Launcher
color 0A

REM Run from the project directory even when invoked from another terminal path.
set "PROJECT_DIR=%~dp0"
pushd "%PROJECT_DIR%" >nul || (
    echo [ERROR] Project directory cannot be opened.
    exit /b 1
)

echo.
echo =====================================================
echo   SkillHub - Development and Midtrans Launcher
echo =====================================================
echo.

REM Required executables and dependencies.
where php >nul 2>&1 || (echo [ERROR] PHP was not found in PATH.& goto :failure)
where npm >nul 2>&1 || (echo [ERROR] Node.js/npm was not found in PATH.& goto :failure)
if not exist "artisan" (echo [ERROR] Laravel artisan file was not found.& goto :failure)
if not exist "vendor\autoload.php" (echo [ERROR] Composer dependencies are missing. Run composer install first.& goto :failure)
if not exist "node_modules" (echo [ERROR] Node dependencies are missing. Run npm install first.& goto :failure)

REM Ensure CLI processes use the current .env values before they start.
echo [Setup] Clearing cached Laravel configuration...
php artisan config:clear >nul 2>&1
call :configure_realtime local
if errorlevel 1 goto :failure

call :port_in_use 8080
if errorlevel 1 (
    call :reverb_is_healthy
    if errorlevel 1 (
        echo [ERROR] Port 8080 is occupied, but it is not a healthy SkillHub Reverb server.
        echo         Run scripts\check-reverb.ps1 to inspect it, then free port 8080 and rerun the launcher.
        goto :failure
    )
    echo [1/6] Reverb already listens on ws://127.0.0.1:8080
) else (
    echo [1/6] Starting Reverb WebSocket server...
    start "SkillHub - Reverb WebSocket" /D "%PROJECT_DIR%" cmd /d /k scripts\start-reverb.bat
    timeout /t 2 /nobreak >nul
    call :reverb_is_healthy
    if errorlevel 1 (
        echo [ERROR] Reverb did not pass its WebSocket readiness check. Check the Reverb window.
        goto :failure
    )
)

call :window_exists "SkillHub - Queue Worker"
if errorlevel 1 (
    echo [2/5] Queue worker is already running.
) else (
    echo [2/5] Starting queue worker...
    start "SkillHub - Queue Worker" /D "%PROJECT_DIR%" cmd /k "title SkillHub - Queue Worker & echo [QUEUE] Processing broadcasts and jobs & php artisan queue:work --tries=3 --timeout=90"
)

call :port_in_use 8001
if errorlevel 1 (
    echo [3/6] Laravel already listens on http://127.0.0.1:8001
) else (
    echo [3/6] Starting Laravel server behind the local proxy...
    start "SkillHub - Laravel Server" /D "%PROJECT_DIR%" cmd /k "title SkillHub - Laravel Server & echo [LARAVEL] http://127.0.0.1:8001 & php artisan serve --host=127.0.0.1 --port=8001"
)

REM The existing ngrok endpoint targets port 8000. Keep that endpoint and run
REM one local proxy there: HTTP goes to Laravel :8001, WebSocket /app/* goes to
REM Reverb :8080. This avoids creating a second ngrok endpoint altogether.
call :port_in_use 8000
if errorlevel 1 (
    call :window_exists "SkillHub - ngrok Proxy"
    if errorlevel 1 (
        echo [4/6] SkillHub proxy already listens on http://127.0.0.1:8000
    ) else (
        echo [ERROR] Port 8000 is used by a process outside this launcher.
        echo         Close the old Laravel server on port 8000, then run this launcher again.
        goto :failure
    )
) else (
    echo [4/6] Starting shared HTTP and WebSocket proxy on port 8000...
    start "SkillHub - ngrok Proxy" /D "%PROJECT_DIR%" cmd /k "title SkillHub - ngrok Proxy & echo [PROXY] http://127.0.0.1:8000 ^> Laravel :8001 + Reverb :8080 & node scripts\ngrok-reverb-proxy.mjs"
)

REM ngrok pages cannot load assets from a local Vite development server.
REM Build static assets and remove Vite's hot-file so Blade uses public/build.
echo [5/6] Building frontend assets for the ngrok URL...
call npm run build
if errorlevel 1 (
    echo [ERROR] Frontend build failed. The launcher cannot continue safely.
    goto :failure
)
if exist "public\hot" del /q "public\hot"

REM The Midtrans/Laravel ngrok endpoint is deliberately NOT started here.
REM It already exists at the fixed notification URL in .env. Starting it again
REM produces ERR_NGROK_334 (the endpoint is already online).
echo [6/6] Keeping the existing Laravel/Midtrans ngrok tunnel unchanged.

call :configure_realtime public
if errorlevel 1 (
    echo [WARNING] Existing ngrok tunnel was not found at localhost:8000.
    echo           Start your configured Midtrans ngrok tunnel, then run this launcher again.
) else (
    echo       Realtime is configured through the existing public ngrok endpoint.
)

echo.
echo =====================================================
echo   READY
echo =====================================================
echo   App:        http://127.0.0.1:8000
echo   WebSocket:  wss:// existing public ngrok endpoint/app/...
echo   Assets:     public/build (same origin as Laravel/ngrok)
echo.
echo This launcher uses built assets so ngrok does not request localhost:5173.
echo Chat and notifications use the same ngrok endpoint as the application.
echo Keep the service windows open while developing.
echo.
start "" "http://127.0.0.1:8000"
popd >nul
exit /b 0

:port_in_use
powershell -NoProfile -ExecutionPolicy Bypass -Command "if (Get-NetTCPConnection -LocalPort %~1 -State Listen -ErrorAction SilentlyContinue) { exit 1 } exit 0" >nul 2>&1
exit /b %errorlevel%

:window_exists
tasklist /v /fi "WINDOWTITLE eq %~1" /nh 2>nul | findstr /i /c:"%~1" >nul
if errorlevel 1 (exit /b 0) else exit /b 1

:configure_realtime
powershell -NoProfile -ExecutionPolicy Bypass -File "%PROJECT_DIR%scripts\configure-realtime-tunnel.ps1" -Mode %~1 >nul 2>&1
exit /b %errorlevel%

:reverb_is_healthy
powershell -NoProfile -ExecutionPolicy Bypass -File "%PROJECT_DIR%scripts\check-reverb.ps1" >nul 2>&1
exit /b %errorlevel%

:failure
echo.
echo Launcher stopped. Resolve the error above, then run this file again.
popd >nul
exit /b 1
