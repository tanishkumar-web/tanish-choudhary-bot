<?php
// verify_deployment_readiness.php - Check if all files are ready for deployment

echo "========================================\n";
echo "PHP Telegram Bot Deployment Readiness Check\n";
echo "========================================\n\n";

// Check if required files exist
$requiredFiles = [
    'index.php' => 'Main entry point',
    'config.php' => 'Configuration file',
    'bot_functions.php' => 'Core bot functions',
    'games.php' => 'Games module',
    'admin.php' => 'Admin functions',
    'Dockerfile' => 'Docker configuration',
    'composer.json' => 'Dependencies',
    'users.json' => 'User data storage'
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

// Check PHP syntax
echo "Checking PHP syntax...\n";
$phpFiles = ['index.php', 'config.php', 'bot_functions.php', 'games.php', 'admin.php'];

$allSyntaxCorrect = true;
foreach ($phpFiles as $file) {
    if (file_exists($file)) {
        $output = [];
        $returnCode = 0;
        exec("php -l $file", $output, $returnCode);
        if ($returnCode === 0) {
            echo "✅ $file - Syntax OK\n";
        } else {
            echo "❌ $file - Syntax Error!\n";
            $allSyntaxCorrect = false;
        }
    }
}

echo "\n";

// Check Dockerfile
echo "Checking Docker configuration...\n";
if (file_exists('Dockerfile')) {
    $dockerContent = file_get_contents('Dockerfile');
    if (strpos($dockerContent, 'php:') !== false) {
        echo "✅ Dockerfile - PHP base image found\n";
    } else {
        echo "❌ Dockerfile - No PHP base image found\n";
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
echo "DEPLOYMENT READINESS ASSESSMENT\n";
echo "========================================\n";

if ($allFilesPresent && $allSyntaxCorrect) {
    echo "🎉 READY FOR DEPLOYMENT!\n";
    echo "All required files are present and PHP syntax is correct.\n";
    echo "You can now deploy to Render.com\n\n";
    echo "NEXT STEPS:\n";
    echo "1. Push to GitHub: git push origin main\n";
    echo "2. Create Web Service on Render.com\n";
    echo "3. Set environment variables\n";
    echo "4. Deploy and set webhook\n";
} else {
    echo "⚠️ NOT READY FOR DEPLOYMENT\n";
    echo "Please fix the issues identified above before deploying.\n";
}

echo "\nFor detailed deployment instructions, see LIVE_DEPLOYMENT_INSTRUCTIONS.md\n";
?>