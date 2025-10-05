<?php
// syntax_check.php - Check PHP syntax of all bot files

echo "Checking PHP syntax for all bot files...\n\n";

$files = [
    'index.php',
    'config.php',
    'bot_functions.php',
    'games.php',
    'admin.php'
];

$allGood = true;

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "Checking $file... ";
        // Use PHP linter
        $output = shell_exec("php -l $file 2>&1");
        if (strpos($output, 'No syntax errors detected') !== false) {
            echo "✅ OK\n";
        } else {
            echo "❌ ERROR\n";
            echo $output . "\n";
            $allGood = false;
        }
    } else {
        echo "⚠️  $file not found\n";
    }
}

echo "\n";
if ($allGood) {
    echo "🎉 All PHP files have correct syntax!\n";
} else {
    echo "❌ Some files have syntax errors.\n";
}
?>