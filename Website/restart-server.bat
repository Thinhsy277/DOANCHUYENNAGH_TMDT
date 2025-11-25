@echo off
echo ========================================
echo   KHOI DONG LAI SERVER
echo ========================================
echo.

REM Dung tat ca PHP processes
echo [INFO] Dang dung cac PHP processes cu...
taskkill /F /IM php.exe >nul 2>&1
timeout /t 2 /nobreak >nul

REM Chuyen den thu muc Website (QUAN TRONG!)
cd /d "%~dp0"

REM Kiem tra router.php
if not exist "router.php" (
    echo [LOI] Khong tim thay file router.php!
    echo Thu muc hien tai: %CD%
    echo.
    echo Vui long dam bao ban dang o dung thu muc Website!
    pause
    exit /b 1
)

REM Hien thi thong tin
echo [INFO] Thu muc hien tai: %CD%
echo [INFO] File router.php: %CD%\router.php
echo [INFO] Dang khoi dong server tai: http://localhost:8000
echo [INFO] Nhan Ctrl+C de dung server
echo.

REM Khoi dong server
php -S localhost:8000 router.php

pause

