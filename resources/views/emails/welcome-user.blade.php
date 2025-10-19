<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to SalesPulse</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #00ACC1 0%, #00BCD4 50%, #26C6DA 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .welcome-box {
            background: white;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .feature {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-left: 4px solid #00ACC1;
            border-radius: 4px;
        }
        .feature-icon {
            font-size: 24px;
            margin-right: 10px;
        }
        .cta-button {
            display: inline-block;
            background: #00ACC1;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📈 Welcome to SalesPulse!</h1>
    </div>
    
    <div class="content">
        <div class="welcome-box">
            <h2>Hi {{ $user->first_name }}! 👋</h2>
            <p>Thank you for joining <strong>SalesPulse</strong> - Your ultimate sales and expense tracking companion!</p>
            <p>We're excited to have you on board and help you take control of your business finances.</p>
        </div>

        <h3>🚀 Here's what you can do with SalesPulse:</h3>
        
        <div class="feature">
            <span class="feature-icon">📊</span>
            <strong>Track Sales:</strong> Record and monitor all your sales transactions in real-time
        </div>
        
        <div class="feature">
            <span class="feature-icon">💰</span>
            <strong>Manage Expenses:</strong> Keep track of all your business expenses effortlessly
        </div>
        
        <div class="feature">
            <span class="feature-icon">🏢</span>
            <strong>Supplier Management:</strong> Manage your suppliers and commission tracking
        </div>
        
        <div class="feature">
            <span class="feature-icon">📈</span>
            <strong>Dashboard Analytics:</strong> Get insights into your business performance
        </div>
        
        <div class="feature">
            <span class="feature-icon">📄</span>
            <strong>Export Data:</strong> Download your transaction history anytime (Premium)
        </div>

        <div style="text-align: center;">
            <a href="https://salespulse.estudios.ug" class="cta-button">Open SalesPulse App</a>
        </div>

        <div class="welcome-box" style="margin-top: 30px;">
            <h3>🎯 Getting Started</h3>
            <ol>
                <li>Open the SalesPulse mobile app</li>
                <li>Login with your credentials:
                    <ul>
                        <li><strong>Email:</strong> {{ $user->email }}</li>
                        <li><strong>Phone:</strong> {{ $user->phone_number }}</li>
                    </ul>
                </li>
                <li>Start tracking your first sale or expense</li>
                <li>Explore the dashboard for insights</li>
            </ol>
        </div>

        <div class="welcome-box" style="background: #fff3cd; border-left: 4px solid #ffc107;">
            <h4 style="margin-top: 0;">💡 Pro Tip</h4>
            <p style="margin-bottom: 0;">Add your suppliers first to make commission tracking easier when recording sales!</p>
        </div>
    </div>

    <div class="footer">
        <p><strong>Need help?</strong> Contact our support team anytime.</p>
        <p>SalesPulse - Manage your business on the go</p>
        <p style="font-size: 12px; color: #999;">
            This email was sent to {{ $user->email }}
        </p>
    </div>
</body>
</html>

