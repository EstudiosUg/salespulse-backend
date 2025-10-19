<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New User Registration</title>
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
            background: linear-gradient(135deg, #00897B 0%, #00ACC1 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .info-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .user-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .user-details p {
            margin: 8px 0;
        }
        .label {
            font-weight: bold;
            color: #666;
            display: inline-block;
            width: 120px;
        }
        .value {
            color: #333;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
        }
        .stat-box {
            background: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            flex: 1;
            margin: 0 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #00ACC1;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-new {
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 New User Registration!</h1>
        <p style="margin: 10px 0 0 0;">SalesPulse</p>
    </div>
    
    <div class="content">
        <div class="info-box">
            <span class="badge badge-new">NEW USER</span>
            <h2 style="margin-top: 10px;">Registration Details</h2>
            
            <div class="user-details">
                <p>
                    <span class="label">Full Name:</span>
                    <span class="value">{{ $user->first_name }} {{ $user->last_name }}</span>
                </p>
                <p>
                    <span class="label">Email:</span>
                    <span class="value">{{ $user->email }}</span>
                </p>
                <p>
                    <span class="label">Phone Number:</span>
                    <span class="value">{{ $user->phone_number }}</span>
                </p>
                <p>
                    <span class="label">User ID:</span>
                    <span class="value">#{{ $user->id }}</span>
                </p>
                <p>
                    <span class="label">Registered:</span>
                    <span class="value">{{ $user->created_at->format('F d, Y \a\t h:i A') }}</span>
                </p>
            </div>
        </div>

        <div class="info-box">
            <h3>📊 Account Status</h3>
            <p>
                <span class="label">Account Status:</span>
                <span class="value">
                    @if($user->is_active)
                        <span style="color: #28a745;">✓ Active</span>
                    @else
                        <span style="color: #dc3545;">✗ Inactive</span>
                    @endif
                </span>
            </p>
            <p>
                <span class="label">Premium Status:</span>
                <span class="value">
                    @if($user->is_premium)
                        <span style="color: #ffc107;">⭐ Premium</span>
                    @else
                        <span style="color: #6c757d;">Standard</span>
                    @endif
                </span>
            </p>
        </div>

        <div class="info-box" style="background: #E0F7FA; border-left: 4px solid #00ACC1;">
            <h4 style="margin-top: 0;">📧 Automated Actions</h4>
            <ul style="margin-bottom: 0;">
                <li>✓ Welcome email sent to user</li>
                <li>✓ User account created successfully</li>
                <li>✓ User can now access the mobile app</li>
            </ul>
        </div>

        <div class="info-box" style="background: #fff3cd; border-left: 4px solid #ffc107;">
            <h4 style="margin-top: 0;">⚡ Quick Actions</h4>
            <p style="margin-bottom: 0;">
                You can manage this user from the admin dashboard or monitor their activity through the system logs.
            </p>
        </div>
    </div>

    <div class="footer">
        <p><strong>SalesPulse Admin Notification</strong></p>
        <p>This is an automated notification from SalesPulse</p>
        <p style="font-size: 12px; color: #999;">
            {{ now()->format('F d, Y') }}
        </p>
    </div>
</body>
</html>

