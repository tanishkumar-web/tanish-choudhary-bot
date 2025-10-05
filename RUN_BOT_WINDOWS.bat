@echo off
title PHP Telegram Bot Setup and Run Script

echo ======================================================
echo PHP Telegram Bot - Setup and Run Script
echo ======================================================
echo.

echo Checking system requirements...
echo.

echo 1. You need to have Docker Desktop installed to run this bot.
echo    Download it from: https://www.docker.com/products/docker-desktop
echo.

echo 2. Alternatively, you can deploy directly to Render.com
echo    Visit: https://render.com/
echo.

echo Setup Instructions:
echo ==================
echo 1. Get a Telegram Bot Token:
echo    - Open Telegram and search for @BotFather
echo    - Send /newbot command and follow instructions
echo    - Copy the HTTP API token you receive
echo.
echo 2. Configure your bot:
echo    - Edit config.php and replace 'YOUR_BOT_TOKEN_HERE' with your actual token
echo    - Replace 'YOUR_TELEGRAM_ID' with your Telegram user ID
echo.
echo 3. Run using Docker:
echo    - Install Docker Desktop first
echo    - Open a terminal in this directory
echo    - Run: docker-compose up
echo.
echo 4. Deploy to Render.com:
echo    - Push this repository to GitHub
echo    - Create a new Web Service on Render
echo    - Select Docker environment
echo    - Set environment variables:
echo      TELEGRAM_BOT_TOKEN=your_actual_bot_token
echo      RENDER_EXTERNAL_URL=https://your-service-name.onrender.com
echo.
echo 5. Set webhook:
echo    - After deployment, visit: https://your-service-name.onrender.com/setwebhook
echo    - Or manually set it using:
echo      https://api.telegram.org/bot[YOUR_BOT_TOKEN]/setWebhook?url=https://your-service-name.onrender.com
echo.

echo For more detailed instructions, check the README.md file.
echo.

pause