# Deployment Troubleshooting Guide

This guide helps you resolve common issues when deploying your PHP Telegram bot.

## Common Docker Build Issues

### 1. "docker-php-ext-install json curl" Error
**Problem**: 
```
cp: cannot stat 'modules/*': No such file or directory
make: *** [Makefile:88: install-modules] Error 1
```

**Solution**: 
- Fixed in our Dockerfile by removing `json` (it's built into PHP)
- Only install `curl` extension:
```dockerfile
RUN docker-php-ext-install curl
```

### 2. "No such file or directory" for modules
**Problem**: Compilation fails during extension installation

**Solution**:
- Ensure you're using the correct PHP base image
- Check that extensions exist for your PHP version
- Use only extensions that are available for PHP 8.1

## Common Render.com Deployment Issues

### 1. Build Failures
**Problem**: Deployment fails during build phase

**Solutions**:
- Check the build logs in Render dashboard
- Verify all required files are in the repository
- Ensure Dockerfile syntax is correct
- Confirm composer.json is valid JSON

### 2. Application Crashes After Deployment
**Problem**: Build succeeds but app crashes when accessed

**Solutions**:
- Check runtime logs in Render dashboard
- Verify environment variables are set correctly
- Ensure port is set to 8080 in Dockerfile
- Check file permissions in container

### 3. Webhook Not Working
**Problem**: Bot doesn't receive messages from Telegram

**Solutions**:
- Verify webhook URL is set correctly:
  ```
  https://api.telegram.org/botYOUR_BOT_TOKEN/setWebhook?url=https://your-app.onrender.com
  ```
- Check that your app is accessible from the internet
- Ensure your app responds correctly to webhook requests
- Check that the app is running on the correct port

## Common PHP Runtime Issues

### 1. "Class not found" Errors
**Problem**: PHP cannot find required classes or files

**Solutions**:
- Check that all required files are included with `require_once`
- Verify file paths are correct
- Ensure proper file permissions

### 2. "Function not found" Errors
**Problem**: Required PHP extensions are missing

**Solutions**:
- Install required extensions in Dockerfile:
  ```dockerfile
  RUN docker-php-ext-install curl
  ```
- Check that extensions are listed in composer.json:
  ```json
  "require": {
      "ext-curl": "*"
  }
  ```

### 3. Permission Errors
**Problem**: Cannot write to files or directories

**Solutions**:
- Set proper permissions in Dockerfile:
  ```dockerfile
  RUN chown -R www-data:www-data /var/www/html
  RUN chmod -R 755 /var/www/html
  ```
- Ensure log and data directories exist:
  ```dockerfile
  RUN mkdir -p /var/www/html/logs
  RUN mkdir -p /var/www/html/data
  ```

## Environment Variable Issues

### 1. Variables Not Being Read
**Problem**: Environment variables are not accessible in PHP

**Solutions**:
- Use `getenv()` function in PHP:
  ```php
  $botToken = getenv('TELEGRAM_BOT_TOKEN');
  ```
- Set variables in Render dashboard:
  ```
  TELEGRAM_BOT_TOKEN=your_actual_token
  ADMIN_TELEGRAM_ID=your_telegram_user_id
  ```

### 2. Fallback Values Not Working
**Problem**: Default values are used instead of environment variables

**Solutions**:
- Check variable names match exactly
- Ensure variables are set before PHP starts
- Use null coalescing operator:
  ```php
  define('BOT_TOKEN', getenv('TELEGRAM_BOT_TOKEN') ?: 'YOUR_BOT_TOKEN_HERE');
  ```

## Network and Connectivity Issues

### 1. Cannot Connect to Telegram API
**Problem**: Bot cannot send messages or receive updates

**Solutions**:
- Check that curl extension is installed
- Verify bot token is correct
- Ensure outbound HTTPS connections are allowed
- Check for firewall restrictions

### 2. Webhook URL Not Accessible
**Problem**: Telegram cannot reach your webhook URL

**Solutions**:
- Ensure your app is deployed and running
- Verify the URL is publicly accessible
- Check that HTTPS is used (required by Telegram)
- Confirm port 8080 is exposed and bound

## File System Issues

### 1. Cannot Write to users.json
**Problem**: User data is not being saved

**Solutions**:
- Check file permissions in Dockerfile
- Ensure the directory exists:
  ```php
  if (!file_exists(DATA_DIR)) {
      mkdir(DATA_DIR, 0755, true);
  }
  ```
- Verify the web server user has write permissions

### 2. Log Files Not Created
**Problem**: No logs are being written

**Solutions**:
- Check that log directory exists and has write permissions
- Ensure the log file path is correct
- Verify sufficient disk space

## Testing Your Fixes

### 1. Test Locally with Docker
```bash
docker build -t telegram-bot .
docker run -p 8080:8080 telegram-bot
```

### 2. Test PHP Syntax
```bash
php -l index.php
php -l config.php
```

### 3. Test with Composer
```bash
composer install --dry-run
```

## Getting Help

If you're still experiencing issues:

1. Check the detailed logs in Render dashboard
2. Verify all environment variables are set correctly
3. Ensure your GitHub repository is up to date
4. Check that your bot token is correct and active
5. Contact Render support for platform-specific issues

Your PHP Telegram bot should now deploy successfully! 🚀