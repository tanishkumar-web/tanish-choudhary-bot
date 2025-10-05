<?php
// verify_your_bot.php - Verify your specific bot configuration

echo "========================================\n";
echo "DARK's PHP Telegram Bot Verification\n";
echo "========================================\n\n";

// Check if required files exist
$requiredFiles = [
    'index.php' => 'Main entry point',
    'config.php' => 'Configuration file',
    'bot_functions.php' => 'Core bot functions',
    'games.php' => 'Games module',
    'admin.php' => 'Admin functions'
];

echo "Checking required files...\n";
$allFilesPresent = true;

foreach ($requiredFiles as $file => $description) {
    if (file_exists($file)) {
        echo "✅ $file - $description\n";
    } else {
        echo "❌ $file - MISSING!\n";
        $allFilesPresent = false;
    }
}

echo "\n";

// Check configuration
echo "Checking configuration...\n";
require_once 'config.php';

$botToken = BOT_TOKEN;
$adminIds = ADMIN_IDS;

echo "Bot Token: " . (strlen($botToken) > 20 ? substr($botToken, 0, 10) . "..." . substr($botToken, -5) : "NOT SET") . "\n";
echo "Admin ID: " . $adminIds[0] . "\n";

// Validate bot token format (basic check)
if (preg_match('/^\d+:[\w-]+$/', $botToken)) {
    echo "✅ Bot token format is valid\n";
} else {
    echo "❌ Bot token format appears invalid\n";
}

// Check if admin ID is set
if (!empty($adminIds[0]) && is_numeric($adminIds[0])) {
    echo "✅ Admin ID is set correctly\n";
} else {
    echo "❌ Admin ID is not set correctly\n";
}

echo "\n";

// Check data files
echo "Checking data files...\n";
if (file_exists('users.json')) {
    echo "✅ users.json - User data storage\n";
} else {
    echo "⚠️ users.json - Will be created on first run\n";
}

echo "\n";

// Check Docker configuration
echo "Checking Docker configuration...\n";
if (file_exists('Dockerfile')) {
    $dockerContent = file_get_contents('Dockerfile');
    if (strpos($dockerContent, 'php:') !== false) {
        echo "✅ Dockerfile - PHP base image found\n";
    } else {
        echo "❌ Dockerfile - No PHP base image found\n";
    }
    
    // Check if json extension installation is removed
    if (strpos($dockerContent, 'docker-php-ext-install json') !== false) {
        echo "❌ Dockerfile - Still trying to install json extension (should be removed)\n";
    } else {
        echo "✅ Dockerfile - Correctly excludes json extension (built into PHP)\n";
    }
    
    if (strpos($dockerContent, 'EXPOSE 8080') !== false) {
        echo "✅ Dockerfile - Port 8080 exposed\n";
    } else {
        echo "❌ Dockerfile - Port 8080 not exposed\n";
    }
} else {
    echo "❌ Dockerfile - MISSING!\n";
}

echo "\n";

// Check composer.json
echo "Checking composer.json...\n";
if (file_exists('composer.json')) {
    $composerContent = json_decode(file_get_contents('composer.json'), true);
    if ($composerContent && isset($composerContent['require'])) {
        echo "✅ composer.json - Dependencies defined\n";
        
        // Check if ext-json is still required
        if (isset($composerContent['require']['ext-json'])) {
            echo "❌ composer.json - Still requires ext-json (should be removed)\n";
        } else {
            echo "✅ composer.json - Correctly excludes ext-json requirement\n";
        }
        
        if (isset($composerContent['require']['php'])) {
            echo "✅ composer.json - PHP version specified\n";
        } else {
            echo "⚠️ composer.json - PHP version not specified\n";
        }
    } else {
        echo "❌ composer.json - Invalid format\n";
    }
} else {
    echo "❌ composer.json - MISSING!\n";
}

echo "\n";

// Final assessment
echo "========================================\n";
echo "VERIFICATION RESULTS\n";
echo "========================================\n";

if ($allFilesPresent) {
    echo "✅ All required files are present\n";
} else {
    echo "❌ Some required files are missing\n";
}

// Check if critical fixes are applied
$dockerFixed = (file_exists('Dockerfile') && strpos(file_get_contents('Dockerfile'), 'docker-php-ext-install json') === false);
$composerFixed = (file_exists('composer.json') && !isset(json_decode(file_get_contents('composer.json'), true)['require']['ext-json']));

if ($dockerFixed && $composerFixed) {
    echo "✅ Critical deployment fixes applied\n";
    echo "🎉 YOUR BOT IS READY FOR DEPLOYMENT!\n";
    echo "\nYou can now deploy to Render.com:\n";
    echo "1. Go to https://dashboard.render.com/\n";
    echo "2. Create a new Web Service\n";
    echo "3. Connect your GitHub repository\n";
    echo "4. Set environment variables:\n";
    echo "   TELEGRAM_BOT_TOKEN=$botToken\n";
    echo "   ADMIN_TELEGRAM_ID=" . $adminIds[0] . "\n";
    echo "5. Deploy and set webhook\n";
} else {
    echo "❌ Critical deployment fixes NOT applied\n";
    echo "Please check the Dockerfile and composer.json files\n";
}

echo "\nFor detailed deployment instructions, see YOUR_BOT_DEPLOYMENT.md\n";
?>