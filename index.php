<?php
// index.php - Complete PHP Telegram Bot Implementation

// Load configuration and modules
require_once 'config.php';
require_once 'bot_functions.php';
require_once 'games.php';
require_once 'admin.php';

// Handle different types of requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle Telegram webhook requests
    handleTelegramWebhook();
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle web requests
    handleWebRequests();
}

function handleTelegramWebhook() {
    // Get the raw POST data
    $input = file_get_contents('php://input');
    
    // Log the request for debugging
    logMessage("Webhook received: " . $input);
    
    // Decode the JSON data
    $update = json_decode($input, true);
    
    // Process the update
    if ($update) {
        processUpdate($update);
    }
    
    // Return a success response
    http_response_code(200);
    echo json_encode(['status' => 'ok']);
}

function handleWebRequests() {
    $requestUri = $_SERVER['REQUEST_URI'];
    
    // Handle health check requests
    if ($requestUri === '/healthz') {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'healthy', 'message' => 'Telegram bot is running']);
        return;
    }
    
    // Handle set webhook request
    if ($requestUri === '/setwebhook') {
        $result = setWebhook();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['success' => $result]);
        return;
    }
    
    // Default response for web requests
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>PHP Telegram Bot</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; background-color: #f5f5f5; }
            .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            h1 { color: #2c3e50; }
            .status { padding: 10px; border-radius: 4px; margin: 10px 0; }
            .running { background-color: #d4edda; color: #155724; }
            .endpoint { background-color: #f8f9fa; padding: 15px; margin: 10px 0; border-left: 4px solid #007bff; }
            .button { background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; margin: 5px 0; }
            .button:hover { background-color: #0056b3; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>PHP Telegram Bot Server</h1>
            <div class="status running">Status: Running</div>
            
            <h2>Bot Information</h2>
            <p>This server hosts a PHP-based Telegram bot.</p>
            
            <h2>Endpoints</h2>
            <div class="endpoint">
                <strong>POST /</strong> - Telegram webhook endpoint
            </div>
            <div class="endpoint">
                <strong>GET /healthz</strong> - Health check endpoint
            </div>
            <div class="endpoint">
                <strong>GET /setwebhook</strong> - Set Telegram webhook (first time setup)
            </div>
            
            <h2>Setup Instructions</h2>
            <ol>
                <li>Set your Telegram bot webhook to point to this URL</li>
                <li>Configure environment variables for bot token and other settings</li>
                <li>Ensure proper file permissions for data storage</li>
            </ol>
            
            <h2>Bot Commands</h2>
            <ul>
                <li><strong>/start</strong> - Start the bot and get welcome message</li>
                <li><strong>/help</strong> - Show help information</li>
                <li><strong>/info</strong> - Get user information</li>
                <li><strong>/settings</strong> - Access bot settings</li>
                <li><strong>/games</strong> - Play games</li>
                <li><strong>/admin</strong> - Admin dashboard (admin only)</li>
            </ul>
            
            <a href="/setwebhook" class="button">Set Webhook</a>
        </div>
    </body>
    </html>
    <?php
}

function processUpdate($update) {
    // Log the update
    logMessage("Processing update: " . json_encode($update));
    
    // Handle different types of updates
    if (isset($update['message'])) {
        handleMessage($update['message']);
    } else if (isset($update['callback_query'])) {
        handleCallbackQuery($update['callback_query']);
    }
}

function handleMessage($message) {
    $chatId = $message['chat']['id'];
    $userId = $message['from']['id'];
    $firstName = $message['from']['first_name'] ?? 'User';
    $username = $message['from']['username'] ?? '';
    
    // Initialize bot
    $bot = new TelegramBot();
    
    // Save user data
    $bot->saveUser($userId, $firstName, $username);
    
    // Handle commands
    if (isset($message['text'])) {
        $text = $message['text'];
        
        if ($text === '/start') {
            sendWelcomeMessage($chatId, $firstName, $bot);
        } else if ($text === '/help') {
            sendHelpMessage($chatId, $bot);
        } else if ($text === '/info') {
            sendUserInfo($chatId, $message['from'], $bot);
        } else if ($text === '/settings') {
            sendSettings($chatId, $bot);
        } else if ($text === '/games') {
            sendGamesMenu($chatId, $bot);
        } else if ($text === '/admin') {
            $admin = new BotAdmin($bot);
            $admin->showDashboard($chatId);
        } else {
            // Echo message for demonstration
            $bot->sendMessage($chatId, "You said: " . $text);
        }
    }
}

function handleCallbackQuery($callbackQuery) {
    $callbackId = $callbackQuery['id'];
    $chatId = $callbackQuery['message']['chat']['id'];
    $data = $callbackQuery['data'];
    $userId = $callbackQuery['from']['id'];
    
    // Initialize bot
    $bot = new TelegramBot();
    
    // Initialize games and admin
    $games = new BotGames($bot);
    $admin = new BotAdmin($bot);
    
    // Handle different callback data
    if ($data === 'help') {
        sendHelpMessage($chatId, $bot);
    } else if ($data === 'settings') {
        sendSettings($chatId, $bot);
    } else if ($data === 'games') {
        sendGamesMenu($chatId, $bot);
    } else if ($data === 'admin_dashboard') {
        $admin->showDashboard($chatId);
    } else if ($data === 'admin_stats') {
        $admin->showStatistics($chatId);
    } else if ($data === 'admin_users') {
        $admin->showUserManagement($chatId);
    } else if ($data === 'admin_list_users') {
        $admin->listUsers($chatId);
    } else if ($data === 'admin_broadcast') {
        $admin->broadcastMessage($chatId);
    } else if ($data === 'game_rps') {
        $games->playRockPaperScissors($chatId);
    } else if ($data === 'game_dice') {
        $games->rollDice($chatId);
    } else if ($data === 'game_coin') {
        $games->tossCoin($chatId);
    } else if ($data === 'game_guess') {
        // For number guessing game, we would need to implement session handling
        $bot->sendMessage($chatId, "Number guessing game would be implemented here.");
    } else if (strpos($data, 'rps_') === 0) {
        // Rock Paper Scissors choice
        $choice = substr($data, 4); // Remove 'rps_' prefix
        $games->playRockPaperScissors($chatId, $choice);
    }
    
    // Answer the callback query
    $bot->answerCallbackQuery($callbackId);
}

function sendWelcomeMessage($chatId, $firstName, $bot) {
    $text = "✨ Welcome, $firstName!\n\n"
          . "🔐 Your PHP Telegram bot is now active!\n\n"
          . "Available Commands:\n"
          . "🆘 /help - Show help information\n"
          . "ℹ️ /info - Get your user information\n"
          . "🎮 /games - Play mini games\n"
          . "⚙️ /settings - Access bot settings\n"
          . "👑 /admin - Admin dashboard (admins only)\n\n"
          . "Enjoy using this PHP-based Telegram bot!";
    
    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => 'Help', 'callback_data' => 'help'],
                ['text' => 'Games', 'callback_data' => 'games']
            ],
            [
                ['text' => 'Settings', 'callback_data' => 'settings']
            ]
        ]
    ];
    
    $bot->sendMessage($chatId, $text, $keyboard);
}

function sendHelpMessage($chatId, $bot) {
    $text = "🤖 <b>PHP Telegram Bot Help</b>\n\n"
          . "This bot demonstrates a complete PHP implementation for Telegram.\n\n"
          . "<b>Commands:</b>\n"
          . "/start - Start the bot\n"
          . "/help - Show this help message\n"
          . "/info - Get your user information\n"
          . "/games - Play mini games\n"
          . "/settings - Access bot settings\n"
          . "/admin - Admin dashboard (admin only)\n\n"
          . "<b>Features:</b>\n"
          . "• Webhook handling\n"
          . "• User data storage\n"
          . "• Interactive keyboards\n"
          . "• Callback queries\n"
          . "• Games module\n"
          . "• Admin panel\n"
          . "• Health monitoring";
    
    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => '⬅️ Back', 'callback_data' => 'help']
            ]
        ]
    ];
    
    $bot->sendMessage($chatId, $text, $keyboard, 'HTML');
}

function sendUserInfo($chatId, $user, $bot) {
    $text = "👤 <b>User Information</b>\n\n"
          . "ID: " . $user['id'] . "\n"
          . "First Name: " . ($user['first_name'] ?? 'N/A') . "\n"
          . "Last Name: " . ($user['last_name'] ?? 'N/A') . "\n"
          . "Username: " . ($user['username'] ?? 'N/A') . "\n"
          . "Language: " . ($user['language_code'] ?? 'N/A');
    
    $bot->sendMessage($chatId, $text, null, 'HTML');
}

function sendSettings($chatId, $bot) {
    $text = "⚙️ <b>Bot Settings</b>\n\n"
          . "This is a demonstration of bot settings.\n"
          . "In a full implementation, you could configure:\n"
          . "• Notification preferences\n"
          . "• Language settings\n"
          . "• Privacy options";
    
    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => '⬅️ Back', 'callback_data' => 'settings']
            ]
        ]
    ];
    
    $bot->sendMessage($chatId, $text, $keyboard, 'HTML');
}

function sendGamesMenu($chatId, $bot) {
    $text = "🎮 <b>Games Menu</b>\n\n"
          . "Select a game to play:";
    
    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => 'Rock Paper Scissors 🪨📄✂️', 'callback_data' => 'game_rps']
            ],
            [
                ['text' => 'Dice Roll 🎲', 'callback_data' => 'game_dice']
            ],
            [
                ['text' => 'Coin Toss 🪙', 'callback_data' => 'game_coin']
            ],
            [
                ['text' => 'Number Guessing 🔢', 'callback_data' => 'game_guess']
            ],
            [
                ['text' => '⬅️ Back', 'callback_data' => 'games']
            ]
        ]
    ];
    
    $bot->sendMessage($chatId, $text, $keyboard, 'HTML');
}

function setWebhook() {
    $botToken = getenv('TELEGRAM_BOT_TOKEN') ?: BOT_TOKEN;
    $renderUrl = getenv('RENDER_EXTERNAL_URL');
    
    if (!$botToken) {
        logMessage("Error: Bot token not configured");
        return false;
    }
    
    if (!$renderUrl) {
        // Try to get the current URL
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $renderUrl = $protocol . '://' . $host;
    }
    
    $webhookUrl = $renderUrl; // Webhook points to root
    
    $url = "https://api.telegram.org/bot{$botToken}/setWebhook?url=" . urlencode($webhookUrl);
    
    $result = file_get_contents($url);
    $response = json_decode($result, true);
    
    logMessage("Webhook set result: " . $result);
    
    return $response['ok'] ?? false;
}

function logMessage($message) {
    $logFile = LOG_FILE;
    
    // Write log entry
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}
?>