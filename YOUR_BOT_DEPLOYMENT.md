# Your PHP Telegram Bot Deployment Guide

This guide is specifically for your bot with the token `7762063253:AAEqP6LHGCuRx0ToS84ne_gLqpwoXX_J03k`.

## ✅ Your Bot Information

- **Bot Token**: `7762063253:AAEqP6LHGCuRx0ToS84ne_gLqpwoXX_J03k`
- **Admin ID**: `6080146784`
- **Bot Username**: You need to get this from @BotFather

## 🚀 Deploying Your Bot to Render.com

### Step 1: Set Up Render.com
1. Go to [Render Dashboard](https://dashboard.render.com/)
2. Sign in or create an account
3. Click "New" → "Web Service"

### Step 2: Connect Your Repository
1. Connect your GitHub account
2. Select repository: `tanishkumar-web/tanish-choudhary-bot`
3. Set these configurations:
   - **Name**: `my-telegram-bot` (or any name you prefer)
   - **Environment**: Docker
   - **Plan**: Free

### Step 3: Set Environment Variables
In the "Advanced" settings, add these environment variables:
```
TELEGRAM_BOT_TOKEN=7762063253:AAEqP6LHGCuRx0ToS84ne_gLqpwoXX_J03k
ADMIN_TELEGRAM_ID=6080146784
```

### Step 4: Deploy
1. Click "Create Web Service"
2. Wait for deployment to complete (may take 5-10 minutes)

### Step 5: Set Webhook
After deployment:
1. Visit your deployed URL: `https://your-app-name.onrender.com/setwebhook`
2. You should see a success message

Alternatively, manually set the webhook:
```
https://api.telegram.org/bot7762063253:AAEqP6LHGCuRx0ToS84ne_gLqpwoXX_J03k/setWebhook?url=https://your-app-name.onrender.com
```

## 🎮 Using Your Bot

### Bot Commands
- `/start` - Welcome message and main menu
- `/help` - Help information
- `/info` - Your user information
- `/games` - Mini-games menu
- `/settings` - Bot settings
- `/admin` - Admin dashboard (only for you!)

### Games Included
- Rock Paper Scissors 🪨📄✂️
- Dice Roll 🎲
- Coin Toss 🪙

### Admin Features (Only You Can Access)
- User statistics
- User management
- Broadcast messages to all users
- Health monitoring

## 📊 Monitoring Your Bot

### Health Check
Visit: `https://your-app-name.onrender.com/healthz`

### Logs
Check logs in the Render dashboard under your service's "Logs" tab

### User Data
User information is stored in [users.json](file:///c%3A/Users/Admin/OneDrive/Desktop/telegram%20bot/users.json)

## 🔧 Troubleshooting

### If Bot Isn't Responding
1. Check webhook is set:
   ```
   https://api.telegram.org/bot7762063253:AAEqP6LHGCuRx0ToS84ne_gLqpwoXX_J03k/getWebhookInfo
   ```
2. Check Render logs for errors
3. Verify environment variables are set correctly

### If Deployment Fails
1. Check build logs in Render
2. Verify Dockerfile is correct
3. Ensure all files are in the repository

## 🎉 Your Bot is Special!

Your bot includes personalized features that make it engaging and fun:
- Interactive games to keep users entertained
- Admin dashboard for complete control
- User data tracking for insights
- Professional command structure

Enjoy your new PHP Telegram bot! It's now ready to serve users 24/7.