# Email Notifications Setup - SalesPulse

This document explains how to set up email notifications for new user registrations.

## Features

When a new user registers on SalesPulse, the system automatically sends:

1. **Welcome Email** to the new user with:
   - Greeting and thank you message
   - Overview of app features
   - Getting started guide
   - Login credentials reminder
   
2. **Manager Notification Email** with:
   - New user details (name, email, phone)
   - Registration date and time
   - Account status
   - User ID for reference

## Email Configuration

### Step 1: Configure .env File

Add these settings to your `.env` file:

```env
# Email Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@salespulse.com
MAIL_FROM_NAME="SalesPulse"

# Manager Email (receives new user notifications)
MANAGER_EMAIL=admin@salespulse.com
```

### Step 2: Gmail Setup (if using Gmail)

1. Go to [Google Account Settings](https://myaccount.google.com/)
2. Enable 2-Factor Authentication
3. Generate an App Password:
   - Go to Security → App Passwords
   - Select "Mail" and "Other (Custom name)"
   - Copy the 16-character password
   - Use this as `MAIL_PASSWORD` in .env

### Step 3: Using Other Email Services

#### Mailtrap (for testing)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
```

#### SendGrid
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

#### Mailgun
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your-mailgun-key
MAILGUN_ENDPOINT=api.mailgun.net
```

## Testing Email Notifications

### Method 1: Test via API

```bash
curl -X POST https://salespulse.estudios.ug/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "Test",
    "last_name": "User",
    "email": "test@example.com",
    "phone_number": "+256700000000",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Method 2: Test via Mobile App

1. Open SalesPulse app
2. Tap "Create Account"
3. Fill in registration form
4. Submit
5. Check both user email and manager email

## Email Templates

The email templates are located in:
- `resources/views/emails/welcome-user.blade.php` - User welcome email
- `resources/views/emails/new-user-notification.blade.php` - Manager notification

## Customizing Emails

### Change Manager Email

Update in `.env`:
```env
MANAGER_EMAIL=youremail@domain.com
```

### Modify Email Content

Edit the Blade templates in `resources/views/emails/`:

**For Welcome Email:**
```php
// File: resources/views/emails/welcome-user.blade.php
// Modify the HTML as needed
```

**For Manager Notification:**
```php
// File: resources/views/emails/new-user-notification.blade.php
// Modify the HTML as needed
```

### Change Email Subjects

Edit the Mailable classes:

```php
// File: app/Mail/WelcomeUserMail.php
public function envelope(): Envelope
{
    return new Envelope(
        subject: 'Your Custom Subject Here',
    );
}
```

## Troubleshooting

### Emails Not Sending

1. **Check logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Test mail configuration:**
   ```bash
   php artisan tinker
   Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); });
   ```

3. **Common Issues:**
   - Wrong credentials → Check MAIL_USERNAME and MAIL_PASSWORD
   - Port blocked → Try port 465 with SSL or 587 with TLS
   - Gmail blocking → Enable "Less secure app access" or use App Password
   - Queue not running → Make sure QUEUE_CONNECTION=sync in .env

### Email Going to Spam

1. Add SPF record to your domain DNS
2. Add DKIM record to your domain DNS
3. Use a verified domain for MAIL_FROM_ADDRESS
4. Use professional email service (SendGrid, Mailgun, etc.)

## Queue Configuration (Optional)

For better performance, use queue for emails:

1. **Update .env:**
   ```env
   QUEUE_CONNECTION=database
   ```

2. **Create jobs table:**
   ```bash
   php artisan queue:table
   php artisan migrate
   ```

3. **Run queue worker:**
   ```bash
   php artisan queue:work
   ```

4. **Update Mailable classes:**
   ```php
   class WelcomeUserMail extends Mailable implements ShouldQueue
   ```

## Production Checklist

- [ ] Configure proper SMTP service (SendGrid, Mailgun, etc.)
- [ ] Set correct MANAGER_EMAIL address
- [ ] Test emails in staging environment
- [ ] Verify emails don't go to spam
- [ ] Set up queue workers for production
- [ ] Monitor email logs
- [ ] Set up email bounce handling

## Support

For issues with email setup, check:
- Laravel Mail Documentation: https://laravel.com/docs/mail
- Email service provider documentation
- Application logs: `storage/logs/laravel.log`

