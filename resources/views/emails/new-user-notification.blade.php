<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New User Registration</title>
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
            background: linear-gradient(135deg, #00897B 0%, #00ACC1 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 8px 0;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: -0.5px;
        }
        .header p {
            margin: 0;
            font-size: 16px;
            opacity: 0.95;
        }
        .content {
            padding: 35px 30px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a1a;
            margin: 0 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #00ACC1;
        }
        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #d4edda;
            color: #155724;
            margin-bottom: 15px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            background: #f8f9fa;
            border-radius: 6px;
            overflow: hidden;
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
        .status-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            border-left: 4px solid #00ACC1;
        }
        .status-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        .status-label {
            font-weight: 600;
            color: #666;
        }
        .status-value {
            font-weight: 500;
        }
        .status-active {
            color: #28a745;
        }
        .status-inactive {
            color: #dc3545;
        }
        .status-premium {
            color: #ffc107;
        }
        .status-standard {
            color: #6c757d;
        }
        .alert-box {
            background: #E0F7FA;
            border-left: 4px solid #00ACC1;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .alert-box h4 {
            margin: 0 0 12px 0;
            font-size: 16px;
            color: #00897B;
        }
        .alert-box ul {
            margin: 0;
            padding-left: 20px;
        }
        .alert-box li {
            margin: 6px 0;
            color: #333;
        }
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 6px;
        }
        .warning-box h4 {
            margin: 0 0 8px 0;
            font-size: 16px;
            color: #856404;
        }
        .warning-box p {
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New User Registration</h1>
            <p>SalesPulse Admin Notification</p>
        </div>
        
        <div class="content">
            <div class="section">
                <span class="badge">New User</span>
                <h2 class="section-title">Registration Details</h2>
                
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
                        <td>User ID</td>
                        <td>#{{ $user->id }}</td>
                    </tr>
                    <tr>
                        <td>Registered</td>
                        <td>{{ $user->created_at->format('F d, Y \a\t h:i A') }}</td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <h2 class="section-title">Account Status</h2>
                <div class="status-box">
                    <div class="status-item">
                        <span class="status-label">Account Status:</span>
                        <span class="status-value">
                            @if($user->is_active)
                                <span class="status-active">Active</span>
                            @else
                                <span class="status-inactive">Inactive</span>
                            @endif
                        </span>
                    </div>
                    <div class="status-item">
                        <span class="status-label">Premium Status:</span>
                        <span class="status-value">
                            @if($user->is_premium)
                                <span class="status-premium">Premium</span>
                            @else
                                <span class="status-standard">Standard</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="alert-box">
                <h4>Automated Actions Completed</h4>
                <ul>
                    <li>Welcome email sent to user</li>
                    <li>User account created successfully</li>
                    <li>User can now access the mobile app</li>
                </ul>
            </div>

            <div class="warning-box">
                <h4>Quick Actions</h4>
                <p>You can manage this user from the admin dashboard or monitor their activity through the system logs.</p>
            </div>
        </div>

        <div class="footer">
            <p><strong>SalesPulse Admin Notification</strong></p>
            <p>This is an automated notification from SalesPulse</p>
            <p class="small">{{ now()->format('F d, Y') }}</p>
        </div>
    </div>
</body>
</html>
