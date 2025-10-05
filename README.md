# PHP Telegram Bot

A complete PHP-based Telegram bot implementation ready for deployment on Render.com or local testing.

## Features

- ✅ Full webhook implementation
- ✅ User data storage in JSON format
- ✅ Interactive keyboards and callback queries
- 🎮 Built-in games module (Rock Paper Scissors, Dice Roll, Coin Toss)
- 👑 Admin dashboard with user management
- 📊 Statistics tracking
- 📡 Broadcasting capabilities
- 🛠 Health monitoring endpoint
- 📝 Comprehensive logging

## Files

- **[index.php](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/index.php)** - Main entry point and command handling
- **[config.php](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/config.php)** - Configuration settings
- **[bot_functions.php](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/bot_functions.php)** - Core bot functionality
- **[games.php](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/games.php)** - Games module
- **[admin.php](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/admin.php)** - Admin functions
- **[Dockerfile](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/Dockerfile)** - Docker configuration
- **[composer.json](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/composer.json)** - PHP dependencies
- **[docker-compose.yml](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/docker-compose.yml)** - Docker Compose configuration
- **[.htaccess](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/.htaccess)** - Apache configuration
- **[users.json](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/users.json)** - User data storage
- **[error.log](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/error.log)** - Error log file
- **[RUN_BOT_WINDOWS.bat](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/RUN_BOT_WINDOWS.bat)** - Setup instructions
- **[run_local.bat](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/run_local.bat)** - Local server script

## Prerequisites

Before running the bot, you need to:

1. **Get a Telegram Bot Token**:
   - Open Telegram and search for @BotFather
   - Send `/newbot` command and follow instructions
   - Copy the HTTP API token you receive

2. **Choose your deployment method**:
   - **Option A**: Install Docker Desktop (for local testing)
   - **Option B**: Deploy to Render.com (recommended for production)
   - **Option C**: Install PHP locally (for development)

## Running the Bot

### Option 1: Using Docker (Recommended)

1. Install Docker Desktop:
   - Download from: https://www.docker.com/products/docker-desktop
   - Install and start Docker Desktop

2. Configure your bot:
   - Edit [config.php](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/config.php):
     - Replace `YOUR_BOT_TOKEN_HERE` with your actual Telegram bot token
     - Replace `YOUR_TELEGRAM_ID` with your Telegram user ID (for admin access)

3. Run the bot:
   ```bash
   docker-compose up
   ```

4. Set the webhook:
   - Visit `http://localhost:8080/setwebhook` in your browser
   - Or manually set it using:
     ```
     https://api.telegram.org/bot[YOUR_BOT_TOKEN]/setWebhook?url=http://localhost:8080
     ```

### Option 2: Deploy to Render.com (Production)

1. Push this repository to GitHub

2. Create a new Web Service on Render:
   - Go to https://dashboard.render.com/
   - Click "New" → "Web Service"
   - Connect your GitHub repository
   - Set the following configuration:
     - **Name**: Your bot name
     - **Environment**: Docker
     - **Plan**: Free

3. Set environment variables in Render:
   ```
   TELEGRAM_BOT_TOKEN=your_actual_bot_token_here
   RENDER_EXTERNAL_URL=https://your-service-name.onrender.com
   ```

4. Set the webhook:
   - After deployment, visit: `https://your-service-name.onrender.com/setwebhook`
   - Or manually set it using:
     ```
     https://api.telegram.org/bot[YOUR_BOT_TOKEN]/setWebhook?url=https://your-service-name.onrender.com
     ```

### Option 3: Local PHP Server (Development)

1. Install PHP 7.4 or higher:
   - Download from: https://windows.php.net/download/
   - Add PHP to your system PATH

2. Configure your bot:
   - Edit [config.php](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/config.php):
     - Replace `YOUR_BOT_TOKEN_HERE` with your actual Telegram bot token
     - Replace `YOUR_TELEGRAM_ID` with your Telegram user ID

3. Run the local server script:
   ```bash
   run_local.bat
   ```

4. Use ngrok for webhook (since localhost isn't accessible from Telegram):
   - Download ngrok from: https://ngrok.com/
   - Run: `ngrok http 8080`
   - Copy the HTTPS URL and set webhook:
     ```
     https://api.telegram.org/bot[YOUR_BOT_TOKEN]/setWebhook?url=https://your-ngrok-url.ngrok.io
     ```

## Bot Commands

- `/start` - Start the bot and get welcome message
- `/help` - Show help information
- `/info` - Get user information
- `/games` - Play mini games
- `/settings` - Access bot settings
- `/admin` - Admin dashboard (admin only)

## Games

- Rock Paper Scissors 🪨📄✂️
- Dice Roll 🎲
- Coin Toss 🪙
- Number Guessing (partially implemented)

## Admin Features

- User statistics
- User management
- Broadcast messages
- Health monitoring

## Health Check

Visit `http://localhost:8080/healthz` (local) or `https://your-service-name.onrender.com/healthz` (Render) to check if the bot is running.

## File Permissions

The Dockerfile sets proper permissions for:
- Web server can read all application files
- Directories for logs and data storage are writable
- User data file ([users.json](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/users.json)) has appropriate permissions

## Data Persistence

- User data is stored in [users.json](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/users.json)
- Logs are written to [logs/](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/logs/) directory
- All data persists between container restarts

## Customization

You can extend this bot by:
1. Adding new commands in [index.php](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/index.php)
2. Creating new modules for additional functionality
3. Modifying the games in [games.php](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/games.php)
4. Adding more admin features in [admin.php](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/admin.php)

## Troubleshooting

1. **Webhook not working**:
   - Check that your bot token is correct
   - Ensure the URL is accessible from the internet (use ngrok for localhost)
   - Verify the webhook URL with:
     ```
     https://api.telegram.org/bot[YOUR_BOT_TOKEN]/getWebhookInfo
     ```

2. **Permission errors**:
   - Check file permissions in the Dockerfile
   - Ensure the web server can write to logs and data directories

3. **Bot not responding**:
   - Check the logs in [logs/](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/logs/) directory
   - Verify the bot token is correct
   - Make sure the webhook is properly set

4. **Docker issues**:
   - Ensure Docker Desktop is running
   - Check Docker logs with `docker-compose logs`
   - Restart Docker Desktop if needed

## Support

For issues with this bot, please check:
1. The logs in the [logs/](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/logs/) directory
2. The error messages in [error.log](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/error.log)
3. Ensure all configuration values are correctly set in [config.php](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/config.php)