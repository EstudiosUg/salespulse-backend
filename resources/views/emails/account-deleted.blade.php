<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Deleted - SalesPulse</title>
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
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
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
        .message-box {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 6px;
            margin-bottom: 30px;
            border-left: 4px solid #6c757d;
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
        .info-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 6px;
            margin: 25px 0;
        }
        .info-box h3 {
            margin: 0 0 12px 0;
            font-size: 16px;
            color: #856404;
            font-weight: 600;
        }
        .info-box p {
            margin: 8px 0;
            color: #856404;
            font-size: 14px;
        }
        .info-box ul {
            margin: 10px 0;
            padding-left: 25px;
        }
        .info-box li {
            margin: 6px 0;
            color: #856404;
        }
        .restore-box {
            background: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 25px;
            border-radius: 6px;
            margin: 25px 0;
            text-align: center;
        }
        .restore-box h3 {
            margin: 0 0 15px 0;
            font-size: 18px;
            color: #0c5460;
            font-weight: 600;
        }
        .restore-box p {
            margin: 10px 0;
            color: #0c5460;
            font-size: 15px;
        }
        .restore-box .deadline {
            font-size: 20px;
            font-weight: 700;
            color: #0c5460;
            margin: 15px 0;
        }
        .feedback-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 25px;
            border-radius: 6px;
            margin: 25px 0;
        }
        .feedback-box h3 {
            margin: 0 0 12px 0;
            font-size: 16px;
            color: #004085;
            font-weight: 600;
        }
        .feedback-box p {
            margin: 8px 0;
            color: #004085;
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
        .deleted-data-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin: 25px 0;
        }
        .deleted-data-box h3 {
            margin: 0 0 15px 0;
            font-size: 16px;
            color: #1a1a1a;
            font-weight: 600;
        }
        .deleted-data-box ul {
            margin: 0;
            padding-left: 25px;
        }
        .deleted-data-box li {
            margin: 8px 0;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Account Deleted</h1>
        </div>
        
        <div class="content">
            <div class="message-box">
                <h2>Goodbye, {{ $user->first_name }}</h2>
                <p>Your SalesPulse account has been successfully deleted as requested. We're sorry to see you go!</p>
                <p>This email confirms the deletion of your account and provides important information about your data.</p>
            </div>

            <h3 style="color: #1a1a1a; margin: 25px 0 15px 0; font-size: 18px; font-weight: 600;">Account Details</h3>
            <table class="info-table">
                <tr>
                    <td>Full Name</td>
                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <td>Phone Number</td>
                    <td>{{ $user->phone_number }}</td>
                </tr>
                <tr>
                    <td>Deleted On</td>
                    <td>{{ now()->format('F d, Y \a\t h:i A') }}</td>
                </tr>
            </table>

            @if(isset($restorePeriod) && $restorePeriod > 0)
            <div class="restore-box">
                <h3>Changed Your Mind?</h3>
                <p>You have a limited time to restore your account if you change your mind.</p>
                <div class="deadline">You can restore your account within {{ $restorePeriod }} days</div>
                <p>To restore your account, please contact our support team at <strong>support@estudios.ug</strong> before the deadline.</p>
            </div>
            @endif

            <div class="deleted-data-box">
                <h3>What Happens to Your Data</h3>
                <ul>
                    <li>All personal information will be permanently deleted</li>
                    <li>Sales records and transaction history will be removed</li>
                    <li>Expense records and receipts will be deleted</li>
                    <li>Commission calculations and reports will be erased</li>
                    <li>Supplier information will be permanently removed</li>
                    <li>All uploaded documents and images will be deleted</li>
                </ul>
            </div>

            <div class="info-box">
                <h3>Data Retention Policy</h3>
                <p><strong>Personal Data:</strong> Your personal information will be deleted immediately from our active systems.</p>
                <p><strong>Backups:</strong> Data in backup systems will be permanently erased within 30 days.</p>
                <p><strong>Legal Requirements:</strong> Some anonymized data may be retained for legal compliance purposes only.</p>
            </div>

            <div class="feedback-box">
                <h3>We Value Your Feedback</h3>
                <p>We're always looking to improve SalesPulse. If you have a moment, we'd love to hear about your experience and why you decided to leave.</p>
                <p>Please feel free to share your feedback at <strong>feedback@estudios.ug</strong></p>
            </div>

            <div class="message-box" style="background: #d4edda; border-left: 4px solid #28a745;">
                <h2 style="color: #155724; font-size: 18px;">Thank You</h2>
                <p style="color: #155724;">Thank you for using SalesPulse. We appreciate the time you spent with us and wish you all the best in your business endeavors.</p>
                <p style="color: #155724; margin-bottom: 0;">If you ever need a sales and expense tracking solution again, we'll be here to help!</p>
            </div>
        </div>

        <div class="footer">
            <p><strong>Questions?</strong> Contact us at salespulse@estudios.ug</p>
            <p>SalesPulse - Estudios UG</p>
            <p class="small">This is a confirmation email for account deletion</p>
            <p class="small">{{ now()->format('F d, Y') }}</p>
        </div>
    </div>
</body>
</html>

