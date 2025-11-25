@echo off
echo ========================================
echo   KHOI DONG WEBSITE CODEIGNITER
echo ========================================
echo.

REM Kiem tra PHP
php -v >nul 2>&1
if errorlevel 1 (
    echo [LOI] PHP chua duoc cai dat hoac chua co trong PATH
    echo Vui long cai dat PHP hoac them PHP vao PATH
    pause
    exit /b 1
)

echo [INFO] Dang khoi dong server...
echo [INFO] Server se chay tai: http://localhost:8000
echo [INFO] Nhan Ctrl+C de dung server
echo.

REM Chuyen den thu muc Website
cd /d "%~dp0"

REM Khoi dong PHP built-in server voi router
php -S localhost:8000 router.php

pause

