<?php
// bot_functions.php - Additional bot functions and features

class TelegramBot {
    private $botToken;
    private $usersFile;
    private $logsFile;
    
    public function __construct() {
        $this->botToken = getenv('TELEGRAM_BOT_TOKEN') ?: BOT_TOKEN;
        $this->usersFile = USERS_FILE;
        $this->logsFile = LOG_FILE;
    }
    
    // Send a message to a chat
    public function sendMessage($chatId, $text, $keyboard = null, $parseMode = null) {
        if (!$this->botToken) {
            $this->log("Error: Bot token not configured");
            return false;
        }
        
        $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";
        
        $data = [
            'chat_id' => $chatId,
            'text' => $text
        ];
        
        if ($keyboard) {
            $data['reply_markup'] = json_encode($keyboard);
        }
        
        if ($parseMode) {
            $data['parse_mode'] = $parseMode;
        }
        
        return $this->makeRequest($url, $data);
    }
    
    // Edit a message
    public function editMessageText($chatId, $messageId, $text, $keyboard = null, $parseMode = null) {
        if (!$this->botToken) {
            $this->log("Error: Bot token not configured");
            return false;
        }
        
        $url = "https://api.telegram.org/bot{$this->botToken}/editMessageText";
        
        $data = [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $text
        ];
        
        if ($keyboard) {
            $data['reply_markup'] = json_encode($keyboard);
        }
        
        if ($parseMode) {
            $data['parse_mode'] = $parseMode;
        }
        
        return $this->makeRequest($url, $data);
    }
    
    // Answer a callback query
    public function answerCallbackQuery($callbackId, $text = '', $showAlert = false) {
        if (!$this->botToken) {
            return false;
        }
        
        $url = "https://api.telegram.org/bot{$this->botToken}/answerCallbackQuery";
        
        $data = [
            'callback_query_id' => $callbackId
        ];
        
        if ($text) {
            $data['text'] = $text;
        }
        
        if ($showAlert) {
            $data['show_alert'] = true;
        }
        
        return $this->makeRequest($url, $data);
    }
    
    // Get user profile photos
    public function getUserProfilePhotos($userId, $offset = 0, $limit = 100) {
        if (!$this->botToken) {
            return false;
        }
        
        $url = "https://api.telegram.org/bot{$this->botToken}/getUserProfilePhotos";
        
        $data = [
            'user_id' => $userId,
            'offset' => $offset,
            'limit' => $limit
        ];
        
        return $this->makeRequest($url, $data);
    }
    
    // Save user data
    public function saveUser($userId, $firstName, $username = '', $lastName = '') {
        // Read existing users
        $usersData = $this->readUsersData();
        
        // Update or add user
        $usersData['users'][$userId] = [
            'id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $username,
            'last_seen' => date('Y-m-d H:i:s'),
            'join_date' => $usersData['users'][$userId]['join_date'] ?? date('Y-m-d H:i:s')
        ];
        
        // Save updated data
        $this->writeUsersData($usersData);
        
        $this->log("User saved: $userId");
    }
    
    // Get user data
    public function getUser($userId) {
        $usersData = $this->readUsersData();
        return $usersData['users'][$userId] ?? null;
    }
    
    // Get all users
    public function getAllUsers() {
        $usersData = $this->readUsersData();
        return $usersData['users'] ?? [];
    }
    
    // Update user statistics
    public function updateUserStats($userId, $stat, $value = 1) {
        $usersData = $this->readUsersData();
        
        if (isset($usersData['users'][$userId])) {
            if (!isset($usersData['users'][$userId]['stats'])) {
                $usersData['users'][$userId]['stats'] = [];
            }
            
            if (!isset($usersData['users'][$userId]['stats'][$stat])) {
                $usersData['users'][$userId]['stats'][$stat] = 0;
            }
            
            $usersData['users'][$userId]['stats'][$stat] += $value;
            $this->writeUsersData($usersData);
        }
    }
    
    // Read users data from file
    private function readUsersData() {
        if (file_exists($this->usersFile)) {
            $usersJson = file_get_contents($this->usersFile);
            return json_decode($usersJson, true) ?: ['users' => []];
        }
        return ['users' => []];
    }
    
    // Write users data to file
    private function writeUsersData($data) {
        file_put_contents($this->usersFile, json_encode($data, JSON_PRETTY_PRINT));
    }
    
    // Make HTTP request to Telegram API
    private function makeRequest($url, $data) {
        $options = [
            'http' => [
                'header' => "Content-type: application/json\r\n",
                'method' => 'POST',
                'content' => json_encode($data)
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        $this->log("API request result: " . $result);
        return json_decode($result, true);
    }
    
    // Log messages
    public function log($message) {
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($this->logsFile, "[$timestamp] $message\n", FILE_APPEND);
    }
    
    // Check if user is admin
    public function isAdmin($userId) {
        $adminIds = ADMIN_IDS;
        return in_array((string)$userId, $adminIds);
    }
    
    // Send message to all users
    public function broadcastMessage($text, $excludeUsers = []) {
        $users = $this->getAllUsers();
        $sentCount = 0;
        
        foreach ($users as $userId => $userData) {
            // Skip excluded users
            if (in_array($userId, $excludeUsers)) {
                continue;
            }
            
            // Send message
            $result = $this->sendMessage($userId, $text);
            if ($result && $result['ok']) {
                $sentCount++;
            }
            
            // Small delay to avoid hitting rate limits
            usleep(100000); // 0.1 second
        }
        
        return $sentCount;
    }
}
?>