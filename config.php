<?php
// config.php - Configuration file for PHP Telegram Bot

// Bot Configuration - Using environment variables with fallbacks
define('BOT_TOKEN', getenv('TELEGRAM_BOT_TOKEN') ?: 'YOUR_BOT_TOKEN_HERE'); // Set via environment variable
define('ADMIN_IDS', [getenv('ADMIN_TELEGRAM_ID') ?: 'YOUR_TELEGRAM_ID']); // Set via environment variable

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

// Create logs directory and file if they don't exist
if (!file_exists(dirname(LOG_FILE))) {
    mkdir(dirname(LOG_FILE), 0755, true);
}

if (!file_exists(LOG_FILE)) {
    file_put_contents(LOG_FILE, '');
}

// Function to check if a user is admin
function isAdmin($userId) {
    $adminIds = ADMIN_IDS;
    return in_array((string)$userId, $adminIds);
}
?>