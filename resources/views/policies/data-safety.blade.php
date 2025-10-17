<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Safety - SalesPulse</title>
    <meta name="description" content="Data Safety Information for SalesPulse mobile application - How we protect and handle your data">
    <meta name="robots" content="index, follow">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #27ae60;
            padding-bottom: 10px;
        }
        h2 {
            color: #34495e;
            margin-top: 30px;
        }
        h3 {
            color: #7f8c8d;
        }
        ul {
            margin: 10px 0;
        }
        li {
            margin: 5px 0;
        }
        .highlight {
            background-color: #e8f5e8;
            padding: 15px;
            border-left: 4px solid #27ae60;
            margin: 20px 0;
        }
        .warning {
            background-color: #fff3cd;
            padding: 15px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
        }
        .contact-info {
            background-color: #f0f8ff;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .last-updated {
            font-style: italic;
            color: #7f8c8d;
            text-align: right;
        }
        .app-info {
            background-color: #f0f8ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .data-category {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .security-badge {
            display: inline-block;
            background-color: #27ae60;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 0.9em;
            margin: 5px 5px 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="app-info">
            <strong>App:</strong> SalesPulse<br>
            <strong>Developer:</strong> Estudios UG<br>
            <strong>Package:</strong> com.estudios.ug.salespulse<br>
            <strong>Version:</strong> 1.0.7
        </div>

        <h1>Data Safety Information for SalesPulse</h1>
        
        <p class="last-updated">
            <strong>Effective Date:</strong> January 1, 2025<br>
            <strong>Last Updated:</strong> January 1, 2025
        </p>

        <div class="highlight">
            <h2>Our Commitment to Data Safety</h2>
            <p>At Estudios UG, we prioritize the security and privacy of your data. This document provides transparent information about how we collect, use, and protect your information in SalesPulse.</p>
        </div>

        <h2>Data Collection Overview</h2>
        <p>SalesPulse collects data necessary to provide business management services. We follow the principle of data minimization, collecting only what is essential for functionality.</p>

        <h2>Types of Data We Collect</h2>

        <div class="data-category">
            <h3>📊 Financial Data</h3>
            <ul>
                <li><strong>Sales Records:</strong> Transaction amounts, dates, customer information</li>
                <li><strong>Expense Data:</strong> Expense amounts, categories, receipts, descriptions</li>
                <li><strong>Commission Data:</strong> Commission calculations and payment records</li>
                <li><strong>Purpose:</strong> Core app functionality, business analytics</li>
                <li><strong>Retention:</strong> As long as account is active</li>
            </ul>
            <span class="security-badge">Encrypted</span>
            <span class="security-badge">Backed Up</span>
        </div>

        <div class="data-category">
            <h3>👤 Personal Information</h3>
            <ul>
                <li><strong>Account Details:</strong> Name, email address, phone number</li>
                <li><strong>Business Information:</strong> Company name, business type, location</li>
                <li><strong>Profile Data:</strong> Profile pictures, user preferences</li>
                <li><strong>Purpose:</strong> Account management, personalization</li>
                <li><strong>Retention:</strong> As long as account is active</li>
            </ul>
            <span class="security-badge">Encrypted</span>
            <span class="security-badge">Access Controlled</span>
        </div>

        <div class="data-category">
            <h3>📱 Device Information</h3>
            <ul>
                <li><strong>Device Identifiers:</strong> Device ID, operating system version</li>
                <li><strong>App Usage:</strong> Feature usage, session duration, crash reports</li>
                <li><strong>Location Data:</strong> General location (if permission granted)</li>
                <li><strong>Purpose:</strong> App optimization, support, analytics</li>
                <li><strong>Retention:</strong> 2 years maximum</li>
            </ul>
            <span class="security-badge">Anonymized</span>
            <span class="security-badge">Aggregated</span>
        </div>

        <div class="data-category">
            <h3>🔧 Technical Information</h3>
            <ul>
                <li><strong>Log Data:</strong> IP address, browser type, access times</li>
                <li><strong>Cookies:</strong> Session cookies for authentication</li>
                <li><strong>Analytics:</strong> App performance metrics, user behavior patterns</li>
                <li><strong>Purpose:</strong> Security, performance monitoring, improvement</li>
                <li><strong>Retention:</strong> 1 year maximum</li>
            </ul>
            <span class="security-badge">Anonymized</span>
            <span class="security-badge">Secure</span>
        </div>

        <h2>Data Security Measures</h2>

        <h3>🔐 Encryption</h3>
        <ul>
            <li><strong>In Transit:</strong> All data encrypted using TLS 1.3</li>
            <li><strong>At Rest:</strong> Database encryption with AES-256</li>
            <li><strong>Local Storage:</strong> Device data encrypted using platform security</li>
            <li><strong>Backups:</strong> Encrypted backups with separate encryption keys</li>
        </ul>

        <h3>🛡️ Access Controls</h3>
        <ul>
            <li><strong>Authentication:</strong> Multi-factor authentication for admin access</li>
            <li><strong>Authorization:</strong> Role-based access controls</li>
            <li><strong>Monitoring:</strong> Continuous access monitoring and logging</li>
            <li><strong>Principle:</strong> Least privilege access model</li>
        </ul>

        <h3>🔍 Monitoring and Detection</h3>
        <ul>
            <li><strong>Real-time Monitoring:</strong> 24/7 security monitoring</li>
            <li><strong>Threat Detection:</strong> Automated threat detection systems</li>
            <li><strong>Incident Response:</strong> Rapid response procedures</li>
            <li><strong>Audit Logs:</strong> Comprehensive audit trail</li>
        </ul>

        <h3>🔄 Data Backup and Recovery</h3>
        <ul>
            <li><strong>Frequency:</strong> Daily automated backups</li>
            <li><strong>Retention:</strong> 30-day backup retention</li>
            <li><strong>Geographic Distribution:</strong> Backups stored in multiple locations</li>
            <li><strong>Testing:</strong> Regular backup restoration testing</li>
        </ul>

        <h2>Data Sharing and Third Parties</h2>

        <div class="warning">
            <h3>⚠️ We Do NOT Sell Your Data</h3>
            <p>We do not sell, trade, or rent your personal information to third parties for marketing purposes.</p>
        </div>

        <h3>Limited Data Sharing</h3>
        <p>We may share data only in these specific circumstances:</p>
        <ul>
            <li><strong>Service Providers:</strong> Trusted partners who assist in app operation (under strict contracts)</li>
            <li><strong>Legal Requirements:</strong> When required by law or legal process</li>
            <li><strong>Business Transfers:</strong> In case of merger, acquisition, or asset sale</li>
            <li><strong>Consent:</strong> When you explicitly consent to sharing</li>
        </ul>

        <h3>Third-Party Services</h3>
        <ul>
            <li><strong>Analytics:</strong> Google Analytics (anonymized data only)</li>
            <li><strong>Cloud Storage:</strong> Secure cloud providers with privacy compliance</li>
            <li><strong>Payment Processing:</strong> Secure payment processors (if applicable)</li>
            <li><strong>Support:</strong> Customer support tools with data protection</li>
        </ul>

        <h2>Your Data Rights</h2>

        <h3>📋 Access and Control</h3>
        <ul>
            <li><strong>View Data:</strong> Access your personal information through the app</li>
            <li><strong>Update Information:</strong> Modify your profile and preferences</li>
            <li><strong>Data Export:</strong> Request a copy of your data in standard formats</li>
            <li><strong>Account Deletion:</strong> Request complete account and data deletion</li>
        </ul>

        <h3>🔒 Privacy Controls</h3>
        <ul>
            <li><strong>Notifications:</strong> Control push notification settings</li>
            <li><strong>Marketing:</strong> Opt-out of promotional communications</li>
            <li><strong>Analytics:</strong> Disable analytics data collection</li>
            <li><strong>Location:</strong> Control location data sharing</li>
        </ul>

        <h3>📤 Data Portability</h3>
        <ul>
            <li><strong>Export Formats:</strong> CSV, Excel, PDF formats available</li>
            <li><strong>Transfer:</strong> Move data to other services (where technically feasible)</li>
            <li><strong>API Access:</strong> Programmatic access to your data</li>
            <li><strong>Timeline:</strong> Data export requests fulfilled within 30 days</li>
        </ul>

        <h2>Data Retention</h2>

        <h3>Retention Schedule</h3>
        <ul>
            <li><strong>Active Accounts:</strong> Data retained while account is active</li>
            <li><strong>Inactive Accounts:</strong> Data retained for 2 years after last activity</li>
            <li><strong>Deleted Accounts:</strong> Data permanently deleted within 30 days</li>
            <li><strong>Legal Requirements:</strong> Some data may be retained longer for legal compliance</li>
        </ul>

        <h3>Automatic Deletion</h3>
        <ul>
            <li><strong>Temporary Data:</strong> Automatically deleted after 30 days</li>
            <li><strong>Log Files:</strong> Rotated and deleted after 1 year</li>
            <li><strong>Cache Data:</strong> Cleared regularly</li>
            <li><strong>Backup Data:</strong> Deleted according to retention schedule</li>
        </ul>

        <h2>International Data Transfers</h2>

        <h3>Transfer Safeguards</h3>
        <ul>
            <li><strong>Standard Contractual Clauses:</strong> EU-approved data transfer mechanisms</li>
            <li><strong>Adequacy Decisions:</strong> Transfers to countries with adequate protection</li>
            <li><strong>Certification:</strong> Privacy Shield certification (where applicable)</li>
            <li><strong>Consent:</strong> Explicit consent for transfers to other countries</li>
        </ul>

        <h3>Regional Compliance</h3>
        <ul>
            <li><strong>GDPR:</strong> Full compliance with European data protection laws</li>
            <li><strong>CCPA:</strong> California Consumer Privacy Act compliance</li>
            <li><strong>PIPEDA:</strong> Canadian privacy law compliance</li>
            <li><strong>Local Laws:</strong> Compliance with applicable local privacy laws</li>
        </ul>

        <h2>Children's Data Protection</h2>

        <div class="warning">
            <h3>👶 Children's Privacy</h3>
            <p>SalesPulse is not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13.</p>
        </div>

        <ul>
            <li><strong>Age Verification:</strong> We verify user age during registration</li>
            <li><strong>Parental Consent:</strong> Required for users under 18</li>
            <li><strong>Data Deletion:</strong> Immediate deletion if child data is discovered</li>
            <li><strong>Reporting:</strong> Parents can report concerns about child data</li>
        </ul>

        <h2>Security Incident Response</h2>

        <h3>Incident Management</h3>
        <ul>
            <li><strong>Detection:</strong> Automated monitoring and manual detection</li>
            <li><strong>Assessment:</strong> Rapid assessment of incident severity</li>
            <li><strong>Containment:</strong> Immediate containment of security threats</li>
            <li><strong>Recovery:</strong> Swift recovery and service restoration</li>
        </ul>

        <h3>User Notification</h3>
        <ul>
            <li><strong>Timeline:</strong> Users notified within 72 hours of confirmed breach</li>
            <li><strong>Method:</strong> Email and in-app notification</li>
            <li><strong>Information:</strong> Clear explanation of what happened and what we're doing</li>
            <li><strong>Support:</strong> Dedicated support for affected users</li>
        </ul>

        <h2>Compliance and Certifications</h2>

        <h3>Security Certifications</h3>
        <ul>
            <li><strong>SOC 2 Type II:</strong> Security and availability controls</li>
            <li><strong>ISO 27001:</strong> Information security management system</li>
            <li><strong>PCI DSS:</strong> Payment card industry compliance (if applicable)</li>
            <li><strong>Regular Audits:</strong> Third-party security assessments</li>
        </ul>

        <h3>Privacy Certifications</h3>
        <ul>
            <li><strong>GDPR Compliant:</strong> European data protection standards</li>
            <li><strong>Privacy by Design:</strong> Privacy considerations in all development</li>
            <li><strong>Data Protection Impact Assessments:</strong> Regular privacy assessments</li>
            <li><strong>Privacy Training:</strong> Staff training on privacy best practices</li>
        </ul>

        <div class="contact-info">
            <h2>Contact Information</h2>
            
            <h3>Data Protection Officer</h3>
            <ul>
                <li><strong>Email:</strong> dpo@estudios.ug</li>
                <li><strong>Response Time:</strong> Within 30 days of inquiry</li>
                <li><strong>Languages:</strong> English, Spanish, French</li>
            </ul>

            <h3>Security Team</h3>
            <ul>
                <li><strong>Email:</strong> security@estudios.ug</li>
                <li><strong>Emergency:</strong> Available 24/7 for security incidents</li>
                <li><strong>Reporting:</strong> Use this email for security concerns</li>
            </ul>

            <h3>General Privacy Questions</h3>
            <ul>
                <li><strong>Email:</strong> privacy@estudios.ug</li>
                <li><strong>Address:</strong> Estudios UG, Kampala, Central Region, Uganda</li>
                <li><strong>Phone:</strong> [Your Contact Number]</li>
            </ul>
        </div>

        <h2>Updates to Data Safety Information</h2>
        <p>We may update this Data Safety Information periodically to reflect changes in our practices or legal requirements. We will notify you of any material changes by:</p>
        <ul>
            <li><strong>In-App Notification:</strong> Prominent notice within the app</li>
            <li><strong>Email:</strong> Notification to your registered email address</li>
            <li><strong>Website:</strong> Updated information posted on our website</li>
        </ul>

        <hr>
        <p><strong>By using SalesPulse, you acknowledge that you have read and understood this Data Safety Information.</strong></p>
    </div>
</body>
</html>
