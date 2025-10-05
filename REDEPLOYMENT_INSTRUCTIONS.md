# Redeployment Instructions

Since we've fixed the Dockerfile, you'll need to redeploy your bot to Render.com. Follow these steps:

## Step 1: Trigger a New Deployment

1. Go to your Render.com dashboard
2. Navigate to your Telegram bot service
3. Click on "Manual Deploy" or "Deploy latest commit"
4. Select the latest commit (which includes our Dockerfile fix)

## Step 2: Monitor the Build Process

1. Watch the build logs to ensure the Docker build completes successfully
2. The build should now pass the step where it was previously failing:
   ```
   RUN docker-php-ext-install curl
   ```

## Step 3: Verify the Deployment

1. Once deployed, check that your bot is running by visiting:
   ```
   https://your-service-name.onrender.com/healthz
   ```
   
2. You should see a JSON response indicating the bot is healthy

## Step 4: Test the Bot

1. Open Telegram
2. Find your bot
3. Send the `/start` command
4. The bot should respond with a welcome message

## Troubleshooting

If you still encounter issues:

1. **Check the build logs** - Look for any error messages during the Docker build process
2. **Verify environment variables** - Make sure your `TELEGRAM_BOT_TOKEN` and `ADMIN_TELEGRAM_ID` are set correctly
3. **Check webhook** - Ensure the webhook is properly set by visiting:
   ```
   https://api.telegram.org/botYOUR_BOT_TOKEN/getWebhookInfo
   ```

## What Was Fixed

The Dockerfile was updated to install system dependencies before attempting to install PHP extensions:

```dockerfile
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install curl
```

This ensures that the required libraries are available when installing the PHP curl extension.

Your bot should now deploy successfully! 🚀