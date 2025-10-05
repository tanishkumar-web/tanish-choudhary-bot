<?php
// config.php - Configuration file for PHP Telegram Bot

// Bot Configuration
define('BOT_TOKEN', 'YOUR_BOT_TOKEN_HERE'); // Replace with your actual bot token
define('ADMIN_IDS', ['YOUR_TELEGRAM_ID']); // Replace with your Telegram user ID

// Database
define('USERS_FILE', 'users.json');

// Logging
define('LOG_FILE', 'logs/telegram_bot.log');

// Paths
define('DATA_DIR', 'data');
define('LOGS_DIR', 'logs');

// Create necessary directories
if (!file_exists(DATA_DIR)) {
    mkdir(DATA_DIR, 0755, true);
}

if (!file_exists(LOGS_DIR)) {
    mkdir(LOGS_DIR, 0755, true);
}

// Create logs file if it doesn't exist
if (!file_exists(LOG_FILE)) {
    file_put_contents(LOG_FILE, '');
}

?>