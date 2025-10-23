<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Successful - SalesPulse</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 50px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 600;
            letter-spacing: -0.5px;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.95;
        }
        .content {
            padding: 35px 30px;
        }
        .success-box {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-left: 5px solid #28a745;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
        }
        .success-icon {
            width: 80px;
            height: 80px;
            background: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .success-icon::after {
            content: "✓";
            font-size: 48px;
            color: white;
            font-weight: bold;
        }
        .success-box h2 {
            margin: 0 0 10px 0;
            font-size: 24px;
            color: #155724;
        }
        .success-box p {
            margin: 0;
            color: #155724;
            font-size: 16px;
        }
        .message-box {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 6px;
            margin-bottom: 25px;
        }
        .message-box h3 {
            margin: 0 0 15px 0;
            font-size: 18px;
            color: #1a1a1a;
        }
        .message-box p {
            margin: 10px 0;
            font-size: 15px;
            color: #555;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            background: #f8f9fa;
            border-radius: 6px;
            overflow: hidden;
            margin: 25px 0;
        }
        .info-table tr {
            border-bottom: 1px solid #e9ecef;
        }
        .info-table tr:last-child {
            border-bottom: none;
        }
        .info-table td {
            padding: 12px 15px;
        }
        .info-table td:first-child {
            font-weight: 600;
            color: #666;
            width: 140px;
        }
        .info-table td:last-child {
            color: #333;
        }
        .security-notice {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 6px;
            margin: 25px 0;
        }
        .security-notice h4 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #856404;
            font-weight: 600;
        }
        .security-notice p {
            margin: 8px 0;
            color: #856404;
            font-size: 14px;
        }
        .tips-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 25px;
            border-radius: 6px;
            margin: 25px 0;
        }
        .tips-box h4 {
            margin: 0 0 15px 0;
            font-size: 16px;
            color: #004085;
            font-weight: 600;
        }
        .tips-box ul {
            margin: 0;
            padding-left: 25px;
        }
        .tips-box li {
            margin: 8px 0;
            color: #004085;
        }
        .cta-box {
            background: linear-gradient(135deg, #00ACC1 0%, #00BCD4 100%);
            padding: 25px;
            border-radius: 8px;
            text-align: center;
            margin: 25px 0;
        }
        .cta-box h3 {
            margin: 0 0 10px 0;
            color: white;
            font-size: 18px;
        }
        .cta-box p {
            margin: 0 0 20px 0;
            color: white;
            font-size: 14px;
        }
        .cta-button {
            display: inline-block;
            background: white;
            color: #00ACC1;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
        }
        .footer {
            background: #f8f9fa;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 6px 0;
            font-size: 14px;
            color: #666;
        }
        .footer .small {
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Password Reset Successful</h1>
            <p>Your password has been changed</p>
        </div>
        
        <div class="content">
            <div class="success-box">
                <div class="success-icon"></div>
                <h2>Success!</h2>
                <p>Your password has been successfully reset.</p>
            </div>

            <div class="message-box">
                <h3>Hi {{ $user->first_name }}!</h3>
                <p>This email confirms that your SalesPulse account password was successfully changed.</p>
                <p>You can now use your new password to log in to the SalesPulse mobile app.</p>
            </div>

            <h3 style="color: #1a1a1a; margin: 25px 0 15px 0; font-size: 18px; font-weight: 600;">Password Change Details</h3>
            <table class="info-table">
                <tr>
                    <td>Account</td>
                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <td>Changed On</td>
                    <td>{{ now()->format('F d, Y \a\t h:i A') }}</td>
                </tr>
                @if(isset($ipAddress))
                <tr>
                    <td>IP Address</td>
                    <td>{{ $ipAddress }}</td>
                </tr>
                @endif
                @if(isset($device))
                <tr>
                    <td>Device</td>
                    <td>{{ $device }}</td>
                </tr>
                @endif
            </table>

            <div class="security-notice">
                <h4>Didn't Make This Change?</h4>
                <p>If you did not reset your password, your account may have been compromised. Please take immediate action:</p>
                <p><strong>Contact our support team immediately at support@estudios.ug</strong></p>
                <p>We will help you secure your account and investigate this issue.</p>
            </div>

            <div class="tips-box">
                <h4>Keep Your Account Secure</h4>
                <ul>
                    <li>Never share your password with anyone</li>
                    <li>Use a unique password for SalesPulse</li>
                    <li>Consider using a password manager</li>
                    <li>Log out from shared or public devices</li>
                    <li>Keep your email account secure</li>
                    <li>Be cautious of phishing emails</li>
                </ul>
            </div>

            <div class="cta-box">
                <h3>Ready to Get Started?</h3>
                <p>Log in to SalesPulse with your new password</p>
                <a href="https://play.google.com/store/apps/details?id=com.estudios.ug.salespulse" class="cta-button">Open SalesPulse</a>
            </div>

            <div class="message-box" style="background: #d1ecf1; border-left: 4px solid #17a2b8;">
                <h3 style="color: #0c5460;">Need Help?</h3>
                <p style="color: #0c5460; margin-bottom: 0;">If you're having trouble logging in or have any questions, our support team is here to help. Contact us at salespulse@estudios.ug</p>
            </div>
        </div>

        <div class="footer">
            <p><strong>SalesPulse Security</strong></p>
            <p>This is an automated security notification</p>
            <p class="small">This email was sent to {{ $user->email }}</p>
            <p class="small">{{ now()->format('F d, Y \a\t h:i A') }}</p>
        </div>
    </div>
</body>
</html>

