# LIVE Deployment Instructions for PHP Telegram Bot

Follow these steps to deploy your PHP Telegram bot live on Render.com:

## Step 1: Get Your Telegram Bot Token

1. Open Telegram and search for @BotFather
2. Start a chat with BotFather
3. Send `/newbot` command
4. Follow the instructions to create your bot
5. Copy the HTTP API token that BotFather provides (it looks like `123456789:ABCdefGhIJKlmNoPQRsTUVwxyZ`)

## Step 2: Deploy to Render.com

1. Go to [Render Dashboard](https://dashboard.render.com/)
2. Sign up or log in to your account
3. Click "New" → "Web Service"
4. Connect your GitHub account if prompted
5. Select your repository (`tanishkumar-web/tanish-choudhary-bot`)
6. Set the following configuration:
   - **Name**: `telegram-bot` (or any name you prefer)
   - **Region**: Choose the region closest to your users
   - **Branch**: `main`
   - **Root Directory**: Leave empty
   - **Environment**: `Docker`
   - **Dockerfile Path**: Leave as default
   - **Plan**: `Free`

## Step 3: Configure Environment Variables

In the Render dashboard, add these environment variables:

1. Click on "Advanced" settings
2. Add the following environment variables:
   ```
   TELEGRAM_BOT_TOKEN=your_actual_bot_token_from_step_1
   RENDER_EXTERNAL_URL=https://your-service-name.onrender.com
   ```

Note: Replace `your_actual_bot_token_from_step_1` with the token you got from BotFather.
The `RENDER_EXTERNAL_URL` will be automatically set by Render, but you can also specify it.

## Step 4: Deploy the Service

1. Click "Create Web Service"
2. Wait for Render to build and deploy your bot (this may take a few minutes)
3. Once deployed, you'll see a URL like `https://telegram-bot-xyz123.onrender.com`

## Step 5: Set the Telegram Webhook

You have two options to set the webhook:

### Option A: Automatic Webhook Setup (Recommended)
1. Visit your deployed URL and add `/setwebhook` at the end:
   ```
   https://your-service-name.onrender.com/setwebhook
   ```
2. You should see a JSON response indicating success

### Option B: Manual Webhook Setup
1. Open your browser and visit this URL (replace YOUR_BOT_TOKEN with your actual token):
   ```
   https://api.telegram.org/botYOUR_BOT_TOKEN/setWebhook?url=https://your-service-name.onrender.com
   ```
2. You should see a JSON response with `"ok":true`

## Step 6: Test Your Bot

1. Open Telegram
2. Search for your bot by its username (provided by BotFather)
3. Start a chat with your bot
4. Send `/start` command
5. The bot should respond with a welcome message

## Step 7: Configure Admin Access (Optional)

1. Find your Telegram user ID:
   - Search for @userinfobot in Telegram
   - Start a chat and it will show your user ID
2. Edit [config.php](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/config.php) in your repository:
   - Replace `YOUR_TELEGRAM_ID` with your actual Telegram user ID
3. Commit and push the change:
   ```bash
   git add config.php
   git commit -m "Update admin ID"
   git push origin main
   ```
4. Render will automatically redeploy your bot

## Monitoring Your Bot

1. **Logs**: View logs in the Render dashboard under your service's "Logs" tab
2. **Health Check**: Visit `https://your-service-name.onrender.com/healthz` to check if your bot is running
3. **User Data**: User information is stored in [users.json](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/users.json)

## Bot Commands

Your bot supports these commands:
- `/start` - Welcome message and main menu
- `/help` - Help information
- `/info` - User information
- `/games` - Mini-games menu
- `/settings` - Bot settings
- `/admin` - Admin dashboard (only for configured admin users)

## Games Included

- Rock Paper Scissors 🪨📄✂️
- Dice Roll 🎲
- Coin Toss 🪙

## Troubleshooting

### If your bot isn't responding:
1. Check the webhook is set correctly:
   ```
   https://api.telegram.org/botYOUR_BOT_TOKEN/getWebhookInfo
   ```
2. Check Render logs for errors
3. Verify your bot token is correct in environment variables

### If you get permission errors:
1. Check that the Dockerfile sets proper file permissions
2. Ensure the web server can write to logs and data directories

### If deployment fails:
1. Check the build logs in Render
2. Verify all required files are in the repository
3. Make sure the Dockerfile is correct

## Keeping Your Bot Alive

Render's free tier has some limitations:
- Service may go to sleep after 15 minutes of inactivity
- To keep it awake, you can:
  1. Set up a cron job to ping your bot regularly
  2. Upgrade to a paid Render plan for 24/7 uptime

Your PHP Telegram bot is now LIVE and ready to serve users 24/7!