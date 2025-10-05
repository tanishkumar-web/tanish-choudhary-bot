@echo off
title PHP Telegram Bot - Local Server

echo ======================================================
echo PHP Telegram Bot - Local Development Server
echo ======================================================
echo.

echo Checking if PHP is installed...
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: PHP is not installed or not in PATH.
    echo.
    echo Please install PHP 7.4 or higher from https://windows.php.net/download/
    echo.
    echo After installation, make sure to add PHP to your system PATH.
    echo.
    pause
    exit /b
)

echo PHP version:
php --version
echo.

echo Starting PHP built-in server...
echo.
echo The bot will be available at: http://localhost:8080
echo.
echo Press Ctrl+C to stop the server.
echo.

php -S localhost:8080 -t .