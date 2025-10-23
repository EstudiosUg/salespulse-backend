<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to SalesPulse</title>
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
            font-size: 36px;
            font-weight: 600;
            letter-spacing: -0.5px;
        }
        .content {
            padding: 35px 30px;
        }
        .welcome-message {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 6px;
            margin-bottom: 30px;
            border-left: 4px solid #00ACC1;
        }
        .welcome-message h2 {
            margin: 0 0 15px 0;
            font-size: 24px;
            color: #1a1a1a;
        }
        .welcome-message p {
            margin: 10px 0;
            font-size: 16px;
            color: #555;
        }
        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #1a1a1a;
            margin: 30px 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #00ACC1;
        }
        .feature-grid {
            display: grid;
            gap: 15px;
            margin: 20px 0;
        }
        .feature {
            background: #f8f9fa;
            padding: 18px;
            border-left: 4px solid #00ACC1;
            border-radius: 6px;
            transition: transform 0.2s;
        }
        .feature strong {
            display: block;
            font-size: 16px;
            color: #00897B;
            margin-bottom: 5px;
        }
        .feature p {
            margin: 0;
            color: #555;
            font-size: 14px;
        }
        .cta-container {
            text-align: center;
            margin: 35px 0;
            padding: 20px 0;
        }
        .cta-button {
            display: inline-block;
            background: #00ACC1;
            color: white;
            padding: 16px 40px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            transition: background 0.3s;
        }
        .cta-button:hover {
            background: #00897B;
        }
        .getting-started {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 6px;
            margin-top: 30px;
        }
        .getting-started h3 {
            margin: 0 0 15px 0;
            font-size: 18px;
            color: #1a1a1a;
        }
        .getting-started ol {
            margin: 0;
            padding-left: 25px;
        }
        .getting-started li {
            margin: 12px 0;
            color: #555;
        }
        .getting-started ul {
            margin: 8px 0;
            padding-left: 25px;
        }
        .getting-started strong {
            color: #333;
        }
        .tip-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 6px;
            margin-top: 20px;
        }
        .tip-box h4 {
            margin: 0 0 8px 0;
            font-size: 16px;
            color: #856404;
            font-weight: 600;
        }
        .tip-box p {
            margin: 0;
            color: #333;
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
        .credentials {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to SalesPulse</h1>
        </div>
        
        <div class="content">
            <div class="welcome-message">
                <h2>Hi {{ $user->first_name }}!</h2>
                <p>Thank you for joining <strong>SalesPulse</strong> - Your ultimate sales and expense tracking companion!</p>
                <p>We're excited to have you on board and help you take control of your business finances.</p>
            </div>

            <h3 class="section-title">What You Can Do with SalesPulse</h3>
            
            <div class="feature-grid">
                <div class="feature">
                    <strong>Track Sales</strong>
                    <p>Record and monitor all your sales transactions in real-time</p>
                </div>
                
                <div class="feature">
                    <strong>Manage Expenses</strong>
                    <p>Keep track of all your business expenses effortlessly</p>
                </div>
                
                <div class="feature">
                    <strong>Supplier Management</strong>
                    <p>Manage your suppliers and commission tracking</p>
                </div>
                
                <div class="feature">
                    <strong>Dashboard Analytics</strong>
                    <p>Get insights into your business performance</p>
                </div>
                
                <div class="feature">
                    <strong>Export Data</strong>
                    <p>Download your transaction history anytime (Premium)</p>
                </div>
            </div>

            <div class="cta-container">
                <a href="https://play.google.com/store/apps/details?id=com.estudios.ug.salespulse" class="cta-button">Open SalesPulse App</a>
            </div>

            <div class="getting-started">
                <h3>Getting Started</h3>
                <ol>
                    <li>Open the SalesPulse mobile app</li>
                    <li>Login with your credentials:
                        <div class="credentials">
                            <strong>Email:</strong> {{ $user->email }}<br>
                            <strong>Phone:</strong> {{ $user->phone_number }}
                        </div>
                    </li>
                    <li>Start tracking your first sale or expense</li>
                    <li>Explore the dashboard for insights</li>
                </ol>
            </div>

            <div class="tip-box">
                <h4>Pro Tip</h4>
                <p>Add your suppliers first to make commission tracking easier when recording sales!</p>
            </div>
        </div>

        <div class="footer">
            <p><strong>Need help?</strong> Contact our support team at salespulse@estudios.ug</p>
            <p>SalesPulse - Manage your business on the go</p>
            <p class="small">This email was sent to {{ $user->email }}</p>
        </div>
    </div>
</body>
</html>
