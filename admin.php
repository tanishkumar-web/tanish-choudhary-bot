<?php
// admin.php - Admin functions for the Telegram bot

class BotAdmin {
    private $bot;
    
    public function __construct($bot) {
        $this->bot = $bot;
    }
    
    // Show admin dashboard
    public function showDashboard($chatId) {
        if (!$this->bot->isAdmin($chatId)) {
            $this->bot->sendMessage($chatId, "❌ Access denied. Admin only.");
            return;
        }
        
        $text = "🔐 <b>Admin Dashboard</b>\n\n"
              . "Welcome to the admin panel.\n"
              . "Select an option below:";
        
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => '📊 Statistics', 'callback_data' => 'admin_stats'],
                    ['text' => '👥 User Management', 'callback_data' => 'admin_users']
                ],
                [
                    ['text' => '📢 Broadcast Message', 'callback_data' => 'admin_broadcast'],
                    ['text' => '⚙️ Settings', 'callback_data' => 'admin_settings']
                ],
                [
                    ['text' => '🔄 Restart Bot', 'callback_data' => 'admin_restart']
                ]
            ]
        ];
        
        return $this->bot->sendMessage($chatId, $text, $keyboard, 'HTML');
    }
    
    // Show statistics
    public function showStatistics($chatId) {
        if (!$this->bot->isAdmin($chatId)) {
            return;
        }
        
        $users = $this->bot->getAllUsers();
        $totalUsers = count($users);
        
        // Count active users (seen in last 24 hours)
        $activeUsers = 0;
        $oneDayAgo = strtotime('-1 day');
        
        foreach ($users as $user) {
            $lastSeen = strtotime($user['last_seen']);
            if ($lastSeen > $oneDayAgo) {
                $activeUsers++;
            }
        }
        
        $text = "📊 <b>Bot Statistics</b>\n\n"
              . "Total Users: $totalUsers\n"
              . "Active Users (24h): $activeUsers\n"
              . "Server Status: ✅ Online\n"
              . "Uptime: " . $this->getUptime();
        
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => '⬅️ Back', 'callback_data' => 'admin_dashboard']
                ]
            ]
        ];
        
        return $this->bot->sendMessage($chatId, $text, $keyboard, 'HTML');
    }
    
    // Show user management
    public function showUserManagement($chatId) {
        if (!$this->bot->isAdmin($chatId)) {
            return;
        }
        
        $text = "👥 <b>User Management</b>\n\n"
              . "Select an action:";
        
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => '📋 List All Users', 'callback_data' => 'admin_list_users'],
                    ['text' => '🔍 Search User', 'callback_data' => 'admin_search_user']
                ],
                [
                    ['text' => '🚫 Ban User', 'callback_data' => 'admin_ban_user'],
                    ['text' => '✅ Unban User', 'callback_data' => 'admin_unban_user']
                ],
                [
                    ['text' => '⬅️ Back', 'callback_data' => 'admin_dashboard']
                ]
            ]
        ];
        
        return $this->bot->sendMessage($chatId, $text, $keyboard, 'HTML');
    }
    
    // List all users
    public function listUsers($chatId) {
        if (!$this->bot->isAdmin($chatId)) {
            return;
        }
        
        $users = $this->bot->getAllUsers();
        $totalUsers = count($users);
        
        $text = "📋 <b>All Users</b> ($totalUsers)\n\n";
        
        // Show first 20 users
        $count = 0;
        foreach ($users as $userId => $userData) {
            if ($count >= 20) {
                $text .= "\n... and " . ($totalUsers - 20) . " more users";
                break;
            }
            
            $name = $userData['first_name'];
            if (!empty($userData['username'])) {
                $name .= " (@" . $userData['username'] . ")";
            }
            
            $text .= ($count + 1) . ". $name [$userId]\n";
            $count++;
        }
        
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => '⬅️ Back', 'callback_data' => 'admin_users']
                ]
            ]
        ];
        
        return $this->bot->sendMessage($chatId, $text, $keyboard, 'HTML');
    }
    
    // Broadcast message
    public function broadcastMessage($chatId, $message = null) {
        if (!$this->bot->isAdmin($chatId)) {
            return;
        }
        
        if (!$message) {
            $text = "📢 <b>Broadcast Message</b>\n\n"
                  . "Send me the message you want to broadcast to all users.";
            
            // In a real implementation, you would handle the next message as the broadcast content
            // For now, we'll just show an example
            $text .= "\n\n<i>Example usage would involve sending the actual message in the next message.</i>";
            
            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => '⬅️ Back', 'callback_data' => 'admin_dashboard']
                    ]
                ]
            ];
            
            return $this->bot->sendMessage($chatId, $text, $keyboard, 'HTML');
        }
        
        // Send broadcast
        $sentCount = $this->bot->broadcastMessage($message);
        
        $text = "✅ <b>Broadcast Sent</b>\n\n"
              . "Message delivered to $sentCount users.";
        
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => '⬅️ Back', 'callback_data' => 'admin_dashboard']
                ]
            ]
        ];
        
        return $this->bot->sendMessage($chatId, $text, $keyboard, 'HTML');
    }
    
    // Get server uptime
    private function getUptime() {
        // Simplified uptime calculation
        $startTime = $_SERVER['REQUEST_TIME'] ?? time();
        $uptimeSeconds = time() - $startTime;
        
        $hours = floor($uptimeSeconds / 3600);
        $minutes = floor(($uptimeSeconds % 3600) / 60);
        
        return "{$hours}h {$minutes}m";
    }
}
?>