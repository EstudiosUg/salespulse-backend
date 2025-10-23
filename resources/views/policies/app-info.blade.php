<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Information - SalesPulse</title>
    <meta name="description" content="App Information for SalesPulse mobile application - Developer details and app specifications">
    <meta name="robots" content="index, follow">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            background: white;
            max-width: 1000px;
            margin: 0 auto;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #00897B 0%, #00ACC1 100%);
            color: white;
            padding: 50px 40px;
            text-align: center;
        }
        .header h1 {
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 15px;
            letter-spacing: -0.5px;
        }
        .header p {
            font-size: 18px;
            opacity: 0.95;
        }
        .app-info-banner {
            background: #f8f9fa;
            padding: 25px 40px;
            border-bottom: 1px solid #e9ecef;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .info-item {
            text-align: center;
        }
        .info-item strong {
            display: block;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .info-item span {
            display: block;
            color: #1a1a1a;
            font-size: 16px;
            font-weight: 600;
        }
        .content {
            padding: 40px;
        }
        .section {
            margin-bottom: 50px;
        }
        .section:last-child {
            margin-bottom: 0;
        }
        h2 {
            color: #1a1a1a;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #00ACC1;
        }
        h3 {
            color: #34495e;
            font-size: 20px;
            font-weight: 600;
            margin: 25px 0 15px 0;
        }
        .highlight {
            background: linear-gradient(135deg, #E0F7FA 0%, #B2EBF2 100%);
            padding: 30px;
            border-radius: 8px;
            border-left: 5px solid #00ACC1;
            margin-bottom: 30px;
        }
        .highlight p {
            font-size: 16px;
            line-height: 1.8;
            color: #1a1a1a;
        }
        ul {
            list-style: none;
            padding-left: 0;
        }
        ul li {
            padding: 8px 0 8px 25px;
            position: relative;
            color: #555;
        }
        ul li:before {
            content: "•";
            color: #00ACC1;
            font-weight: bold;
            font-size: 20px;
            position: absolute;
            left: 0;
        }
        ul li strong {
            color: #333;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin: 30px 0;
        }
        .feature-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 30px;
            border-radius: 10px;
            border-left: 5px solid #00ACC1;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .feature-card h3 {
            color: #00897B;
            font-size: 18px;
            margin: 0 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #00ACC1;
        }
        .feature-card ul {
            margin-top: 15px;
        }
        .feature-card ul li {
            font-size: 14px;
            padding: 6px 0 6px 20px;
        }
        .feature-card ul li:before {
            font-size: 16px;
        }
        .spec-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .spec-table th, .spec-table td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        .spec-table th {
            background: linear-gradient(135deg, #00897B 0%, #00ACC1 100%);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.5px;
        }
        .spec-table tr:last-child td {
            border-bottom: none;
        }
        .spec-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .spec-table td:first-child {
            font-weight: 600;
            color: #555;
        }
        .contact-info {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            padding: 35px;
            border-radius: 10px;
            border-left: 5px solid #28a745;
            margin: 30px 0;
        }
        .contact-info h2 {
            border-bottom: 3px solid #28a745;
            color: #155724;
        }
        .contact-info h3 {
            color: #155724;
        }
        .version-box {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            border-left: 5px solid #6c757d;
            margin: 20px 0;
        }
        .version-box h3 {
            color: #495057;
            margin-top: 0;
        }
        .roadmap-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin: 30px 0;
        }
        .roadmap-card {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            padding: 25px;
            border-radius: 8px;
            border-left: 5px solid #ffc107;
        }
        .roadmap-card h3 {
            color: #856404;
            margin-top: 0;
        }
        hr {
            border: none;
            border-top: 2px solid #e9ecef;
            margin: 40px 0;
        }
        .footer-note {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            font-size: 16px;
            font-weight: 500;
        }
        .footer-note strong {
            display: block;
            font-size: 18px;
            margin-bottom: 10px;
        }
        @media (max-width: 768px) {
            .header h1 {
                font-size: 28px;
            }
            .content {
                padding: 25px;
            }
            .app-info-banner {
                grid-template-columns: 1fr;
                padding: 20px;
            }
            .feature-grid, .roadmap-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SalesPulse</h1>
            <p>App Information & Developer Details</p>
        </div>

        <div class="app-info-banner">
            <div class="info-item">
                <strong>Application</strong>
                <span>SalesPulse</span>
            </div>
            <div class="info-item">
                <strong>Developer</strong>
                <span>Estudios UG</span>
            </div>
            <div class="info-item">
                <strong>Package</strong>
                <span>com.estudios.ug.salespulse</span>
            </div>
            <div class="info-item">
                <strong>Version</strong>
                <span>1.0.7</span>
            </div>
            <div class="info-item">
                <strong>Last Updated</strong>
                <span>January 1, 2025</span>
            </div>
        </div>

        <div class="content">
            <div class="highlight">
                <h2 style="border-bottom: none; margin-bottom: 15px; padding-bottom: 0;">About SalesPulse</h2>
                <p>SalesPulse is a comprehensive business management application designed to help entrepreneurs and small businesses track their sales, manage expenses, and calculate commissions efficiently. Built with modern technology and user-friendly design, SalesPulse empowers businesses to make data-driven decisions.</p>
            </div>

            <div class="section">
                <h2>Developer Information</h2>

                <h3>Company Details</h3>
                <ul>
                    <li><strong>Company Name:</strong> Estudios UG</li>
                    <li><strong>Legal Entity:</strong> Uganda Limited Company</li>
                    <li><strong>Registration:</strong> Registered in Uganda</li>
                    <li><strong>Founded:</strong> 2024</li>
                    <li><strong>Industry:</strong> Software Development & Technology</li>
                </ul>

                <h3>Contact Information</h3>
                <ul>
                    <li><strong>Email:</strong> support@estudios.ug</li>
                    <li><strong>Website:</strong> https://estudios.ug</li>
                    <li><strong>Address:</strong> Kampala, Central Region, Uganda</li>
                    <li><strong>Phone:</strong> [Your Contact Number]</li>
                </ul>

                <h3>Team</h3>
                <ul>
                    <li><strong>Founder:</strong> Ivan Elly</li>
                    <li><strong>Development Team:</strong> Experienced mobile and web developers</li>
                    <li><strong>Design Team:</strong> UI/UX specialists</li>
                    <li><strong>Support Team:</strong> Customer service professionals</li>
                </ul>
            </div>

            <div class="section">
                <h2>App Specifications</h2>

                <table class="spec-table">
                    <thead>
                        <tr>
                            <th>Specification</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>App Name</td>
                            <td>SalesPulse</td>
                        </tr>
                        <tr>
                            <td>Package Name</td>
                            <td>com.estudios.ug.salespulse</td>
                        </tr>
                        <tr>
                            <td>Current Version</td>
                            <td>1.0.7</td>
                        </tr>
                        <tr>
                            <td>Version Code</td>
                            <td>7</td>
                        </tr>
                        <tr>
                            <td>Minimum SDK</td>
                            <td>API Level 21 (Android 5.0)</td>
                        </tr>
                        <tr>
                            <td>Target SDK</td>
                            <td>API Level 35 (Android 15)</td>
                        </tr>
                        <tr>
                            <td>Compile SDK</td>
                            <td>API Level 36</td>
                        </tr>
                        <tr>
                            <td>Platform</td>
                            <td>Android</td>
                        </tr>
                        <tr>
                            <td>Framework</td>
                            <td>Flutter</td>
                        </tr>
                        <tr>
                            <td>Backend</td>
                            <td>Laravel (PHP)</td>
                        </tr>
                        <tr>
                            <td>Database</td>
                            <td>MySQL</td>
                        </tr>
                        <tr>
                            <td>Cloud Storage</td>
                            <td>Firebase</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="section">
                <h2>App Features</h2>

                <div class="feature-grid">
                    <div class="feature-card">
                        <h3>Sales Tracking</h3>
                        <ul>
                            <li>Record sales transactions</li>
                            <li>Track customer information</li>
                            <li>Monitor sales performance</li>
                            <li>Generate sales reports</li>
                        </ul>
                    </div>

                    <div class="feature-card">
                        <h3>Expense Management</h3>
                        <ul>
                            <li>Log business expenses</li>
                            <li>Categorize expenses</li>
                            <li>Upload receipt images</li>
                            <li>Track spending patterns</li>
                        </ul>
                    </div>

                    <div class="feature-card">
                        <h3>Commission Calculation</h3>
                        <ul>
                            <li>Automatic commission calculation</li>
                            <li>Customizable commission rates</li>
                            <li>Track commission payments</li>
                            <li>Commission history</li>
                        </ul>
                    </div>

                    <div class="feature-card">
                        <h3>Reporting & Analytics</h3>
                        <ul>
                            <li>Financial reports</li>
                            <li>Performance analytics</li>
                            <li>Data visualization</li>
                            <li>Export capabilities</li>
                        </ul>
                    </div>

                    <div class="feature-card">
                        <h3>Cloud Sync</h3>
                        <ul>
                            <li>Automatic data backup</li>
                            <li>Multi-device sync</li>
                            <li>Offline functionality</li>
                            <li>Data recovery</li>
                        </ul>
                    </div>

                    <div class="feature-card">
                        <h3>Security & Privacy</h3>
                        <ul>
                            <li>Data encryption</li>
                            <li>Secure authentication</li>
                            <li>Privacy protection</li>
                            <li>GDPR compliance</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2>Technical Architecture</h2>

                <h3>Frontend (Mobile App)</h3>
                <ul>
                    <li><strong>Framework:</strong> Flutter (Dart)</li>
                    <li><strong>State Management:</strong> Provider pattern</li>
                    <li><strong>UI Components:</strong> Material Design</li>
                    <li><strong>Local Storage:</strong> SQLite, SharedPreferences</li>
                    <li><strong>Image Handling:</strong> Image picker, caching</li>
                </ul>

                <h3>Backend (API)</h3>
                <ul>
                    <li><strong>Framework:</strong> Laravel (PHP)</li>
                    <li><strong>API:</strong> RESTful API</li>
                    <li><strong>Authentication:</strong> Laravel Sanctum</li>
                    <li><strong>Database:</strong> MySQL</li>
                    <li><strong>File Storage:</strong> Local/Cloud storage</li>
                </ul>

                <h3>Cloud Services</h3>
                <ul>
                    <li><strong>Analytics:</strong> Firebase Analytics</li>
                    <li><strong>Crash Reporting:</strong> Firebase Crashlytics</li>
                    <li><strong>Push Notifications:</strong> Firebase Cloud Messaging</li>
                    <li><strong>Authentication:</strong> Firebase Auth</li>
                    <li><strong>Storage:</strong> Firebase Storage</li>
                </ul>
            </div>

            <div class="section">
                <h2>Permissions and Data Access</h2>

                <h3>Required Permissions</h3>
                <ul>
                    <li><strong>Internet Access:</strong> For API communication and cloud sync</li>
                    <li><strong>Storage:</strong> For saving receipts and documents</li>
                    <li><strong>Camera:</strong> For capturing receipt images</li>
                    <li><strong>Notifications:</strong> For important app updates</li>
                </ul>

                <h3>Optional Permissions</h3>
                <ul>
                    <li><strong>Location:</strong> For location-based features (if enabled)</li>
                    <li><strong>Contacts:</strong> For customer management (if enabled)</li>
                    <li><strong>Calendar:</strong> For appointment scheduling (if enabled)</li>
                </ul>
            </div>

            <div class="section">
                <h2>Data Handling</h2>

                <h3>Data Collection</h3>
                <ul>
                    <li><strong>User Data:</strong> Account information, preferences</li>
                    <li><strong>Business Data:</strong> Sales, expenses, commissions</li>
                    <li><strong>Technical Data:</strong> App usage, performance metrics</li>
                    <li><strong>Device Data:</strong> Device information, crash reports</li>
                </ul>

                <h3>Data Processing</h3>
                <ul>
                    <li><strong>Local Processing:</strong> Data processed on device</li>
                    <li><strong>Cloud Processing:</strong> Backup and sync operations</li>
                    <li><strong>Analytics:</strong> Anonymized usage statistics</li>
                    <li><strong>Security:</strong> Encrypted data transmission</li>
                </ul>
            </div>

            <div class="section">
                <h2>Compliance and Certifications</h2>

                <h3>Privacy Compliance</h3>
                <ul>
                    <li><strong>GDPR:</strong> European General Data Protection Regulation</li>
                    <li><strong>CCPA:</strong> California Consumer Privacy Act</li>
                    <li><strong>PIPEDA:</strong> Personal Information Protection and Electronic Documents Act</li>
                    <li><strong>Local Laws:</strong> Applicable privacy laws in Uganda</li>
                </ul>

                <h3>Security Standards</h3>
                <ul>
                    <li><strong>Data Encryption:</strong> AES-256 encryption</li>
                    <li><strong>Secure Communication:</strong> TLS 1.3</li>
                    <li><strong>Access Controls:</strong> Role-based permissions</li>
                    <li><strong>Audit Logging:</strong> Comprehensive activity logs</li>
                </ul>
            </div>

            <div class="section">
                <h2>Support and Maintenance</h2>

                <h3>Support Channels</h3>
                <ul>
                    <li><strong>Email Support:</strong> support@estudios.ug</li>
                    <li><strong>In-App Support:</strong> Help section within the app</li>
                    <li><strong>Documentation:</strong> Comprehensive user guides</li>
                    <li><strong>FAQ:</strong> Frequently asked questions</li>
                </ul>

                <h3>Update Policy</h3>
                <ul>
                    <li><strong>Regular Updates:</strong> Monthly feature updates</li>
                    <li><strong>Security Patches:</strong> Immediate security updates</li>
                    <li><strong>Bug Fixes:</strong> Weekly bug fix releases</li>
                    <li><strong>Compatibility:</strong> Support for latest Android versions</li>
                </ul>
            </div>

            <div class="contact-info">
                <h2>Contact Information</h2>
                
                <h3>General Support</h3>
                <ul>
                    <li><strong>Email:</strong> support@estudios.ug</li>
                    <li><strong>Response Time:</strong> Within 24 hours</li>
                    <li><strong>Business Hours:</strong> Monday-Friday, 9 AM - 6 PM (EAT)</li>
                </ul>

                <h3>Technical Support</h3>
                <ul>
                    <li><strong>Email:</strong> tech@estudios.ug</li>
                    <li><strong>Emergency:</strong> Critical issues addressed immediately</li>
                    <li><strong>Documentation:</strong> Available 24/7</li>
                </ul>

                <h3>Business Inquiries</h3>
                <ul>
                    <li><strong>Email:</strong> business@estudios.ug</li>
                    <li><strong>Partnerships:</strong> partnership@estudios.ug</li>
                    <li><strong>Media:</strong> media@estudios.ug</li>
                </ul>
            </div>

            <div class="section">
                <h2>Version History</h2>

                <div class="version-box">
                    <h3>Current Version: 1.0.7</h3>
                    <ul>
                        <li>Enhanced security features</li>
                        <li>Improved data export functionality</li>
                        <li>Bug fixes and performance improvements</li>
                        <li>Updated UI components</li>
                    </ul>
                </div>

                <h3>Previous Versions</h3>
                <ul>
                    <li><strong>1.0.6:</strong> Commission calculation improvements</li>
                    <li><strong>1.0.5:</strong> Expense categorization features</li>
                    <li><strong>1.0.4:</strong> Report generation enhancements</li>
                    <li><strong>1.0.3:</strong> Cloud sync improvements</li>
                    <li><strong>1.0.2:</strong> UI/UX updates</li>
                    <li><strong>1.0.1:</strong> Initial bug fixes</li>
                    <li><strong>1.0.0:</strong> Initial release</li>
                </ul>
            </div>

            <div class="section">
                <h2>Future Roadmap</h2>

                <div class="roadmap-grid">
                    <div class="roadmap-card">
                        <h3>Planned Features</h3>
                        <ul>
                            <li><strong>Multi-currency Support:</strong> Support for different currencies</li>
                            <li><strong>Advanced Analytics:</strong> More detailed business insights</li>
                            <li><strong>Team Collaboration:</strong> Multi-user business accounts</li>
                            <li><strong>Integration:</strong> Third-party service integrations</li>
                            <li><strong>Offline Mode:</strong> Enhanced offline functionality</li>
                        </ul>
                    </div>

                    <div class="roadmap-card">
                        <h3>Platform Expansion</h3>
                        <ul>
                            <li><strong>iOS Version:</strong> iPhone and iPad support</li>
                            <li><strong>Web Application:</strong> Browser-based version</li>
                            <li><strong>Desktop App:</strong> Windows and macOS versions</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-note">
            <strong>For the most up-to-date information about SalesPulse</strong>
            Visit our website at https://estudios.ug
        </div>
    </div>
</body>
</html>
