<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - SalesPulse</title>
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
            background: linear-gradient(135deg, #00ACC1 0%, #00BCD4 50%, #26C6DA 100%);
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
        .content {
            padding: 35px 30px;
        }
        .alert-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 25px;
        }
        .alert-box p {
            margin: 0;
            color: #856404;
            font-size: 14px;
        }
        .message-box {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 6px;
            margin-bottom: 30px;
            border-left: 4px solid #00ACC1;
        }
        .message-box h2 {
            margin: 0 0 15px 0;
            font-size: 22px;
            color: #1a1a1a;
        }
        .message-box p {
            margin: 10px 0;
            font-size: 16px;
            color: #555;
        }
        .reset-code-box {
            background: linear-gradient(135deg, #E0F7FA 0%, #B2EBF2 100%);
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            margin: 30px 0;
            border: 2px solid #00ACC1;
        }
        .reset-code-label {
            font-size: 14px;
            color: #00897B;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            display: block;
        }
        .reset-code {
            font-size: 36px;
            font-weight: 700;
            color: #00897B;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            margin: 15px 0;
            display: block;
            user-select: all;
        }
        .reset-code-note {
            font-size: 13px;
            color: #00897B;
            margin-top: 10px;
        }
        .info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin: 25px 0;
        }
        .info-box h3 {
            margin: 0 0 12px 0;
            font-size: 16px;
            color: #1a1a1a;
            font-weight: 600;
        }
        .info-box ul {
            margin: 0;
            padding-left: 25px;
        }
        .info-box li {
            margin: 8px 0;
            color: #555;
        }
        .security-notice {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 20px;
            border-radius: 6px;
            margin: 25px 0;
        }
        .security-notice h4 {
            margin: 0 0 8px 0;
            font-size: 16px;
            color: #721c24;
            font-weight: 600;
        }
        .security-notice p {
            margin: 0;
            color: #721c24;
            font-size: 14px;
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
        .expiry-info {
            text-align: center;
            padding: 15px;
            background: #e7f3ff;
            border-radius: 6px;
            margin-top: 20px;
        }
        .expiry-info strong {
            color: #004085;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reset Your Password</h1>
        </div>
        
        <div class="content">
            <div class="alert-box">
                <p><strong>Security Alert:</strong> A password reset was requested for your account.</p>
            </div>

            <div class="message-box">
                <h2>Hi {{ $user->first_name }}!</h2>
                <p>We received a request to reset the password for your SalesPulse account associated with <strong>{{ $user->email }}</strong>.</p>
                <p>Use the verification code below to reset your password:</p>
            </div>

            <div class="reset-code-box">
                <span class="reset-code-label">Your Reset Code</span>
                <span class="reset-code">{{ $resetCode }}</span>
                <div class="reset-code-note">Enter this code in the app to continue</div>
            </div>

            <div class="expiry-info">
                <strong>This code will expire in 60 minutes</strong>
            </div>

            <div class="info-box">
                <h3>How to Reset Your Password</h3>
                <ul>
                    <li>Open the SalesPulse mobile app</li>
                    <li>Go to the password reset screen</li>
                    <li>Enter the verification code above</li>
                    <li>Create a new secure password</li>
                    <li>Confirm your new password</li>
                </ul>
            </div>

            <div class="security-notice">
                <h4>Didn't Request This?</h4>
                <p>If you didn't request a password reset, please ignore this email. Your password will remain unchanged. If you're concerned about your account security, please contact our support team immediately.</p>
            </div>

            <div class="info-box" style="background: #e3f2fd; border-left: 4px solid #2196F3;">
                <h3 style="color: #1565C0;">Security Tips</h3>
                <ul>
                    <li>Never share your reset code with anyone</li>
                    <li>Use a strong, unique password</li>
                    <li>Don't reuse passwords from other accounts</li>
                    <li>Enable two-factor authentication if available</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p><strong>Need help?</strong> Contact our support team at salespulse@estudios.ug</p>
            <p>SalesPulse - Manage your business on the go</p>
            <p class="small">This email was sent to {{ $user->email }}</p>
            <p class="small">If you have any concerns, please contact us immediately</p>
        </div>
    </div>
</body>
</html>

