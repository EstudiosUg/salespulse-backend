# SalesPulse Email Integration Test Script (PowerShell)
# This script tests all API endpoints that trigger email sending

# Configuration
$BASE_URL = "http://localhost:8000/api"
$TEST_EMAIL = "test@example.com"
$TEST_PHONE = "+256700123456"
$TEST_PASSWORD = "SecurePass123"
$TEST_FIRST_NAME = "John"
$TEST_LAST_NAME = "Doe"

Write-Host "==================================================" -ForegroundColor Cyan
Write-Host "SalesPulse Email Integration Test" -ForegroundColor Cyan
Write-Host "==================================================" -ForegroundColor Cyan
Write-Host ""

# Test 1: User Registration
Write-Host "Test 1: User Registration" -ForegroundColor Yellow
Write-Host "Testing: POST /api/register"
Write-Host "Expected Emails: Welcome Email + Admin Notification"
Write-Host ""

$registerBody = @{
    first_name = $TEST_FIRST_NAME
    last_name = $TEST_LAST_NAME
    email = $TEST_EMAIL
    phone_number = $TEST_PHONE
    password = $TEST_PASSWORD
    password_confirmation = $TEST_PASSWORD
} | ConvertTo-Json

try {
    $registerResponse = Invoke-RestMethod -Uri "$BASE_URL/register" `
        -Method Post `
        -ContentType "application/json" `
        -Body $registerBody
    
    if ($registerResponse.success) {
        Write-Host "✓ Registration successful" -ForegroundColor Green
        $TOKEN = $registerResponse.data.token
        Write-Host "Token saved for authenticated requests"
        Write-Host ""
    }
} catch {
    Write-Host "✗ Registration failed" -ForegroundColor Red
    Write-Host $_.Exception.Message
    Write-Host ""
}

# Test 2: Forgot Password
Write-Host "Test 2: Forgot Password" -ForegroundColor Yellow
Write-Host "Testing: POST /api/forgot-password"
Write-Host "Expected Email: Password Reset Email with 6-digit code"
Write-Host ""

$forgotBody = @{
    email = $TEST_EMAIL
} | ConvertTo-Json

try {
    $forgotResponse = Invoke-RestMethod -Uri "$BASE_URL/forgot-password" `
        -Method Post `
        -ContentType "application/json" `
        -Body $forgotBody
    
    if ($forgotResponse.success) {
        Write-Host "✓ Reset code sent successfully" -ForegroundColor Green
        Write-Host "Check email for 6-digit reset code"
        Write-Host ""
        
        # Prompt for reset code
        $RESET_CODE = Read-Host "Enter the 6-digit reset code from email"
        
        # Test 3: Reset Password
        Write-Host ""
        Write-Host "Test 3: Reset Password" -ForegroundColor Yellow
        Write-Host "Testing: POST /api/reset-password"
        Write-Host "Expected Email: Password Reset Success Confirmation"
        Write-Host ""
        
        $NEW_PASSWORD = "NewPassword123"
        $resetBody = @{
            email = $TEST_EMAIL
            code = $RESET_CODE
            password = $NEW_PASSWORD
            password_confirmation = $NEW_PASSWORD
        } | ConvertTo-Json
        
        try {
            $resetResponse = Invoke-RestMethod -Uri "$BASE_URL/reset-password" `
                -Method Post `
                -ContentType "application/json" `
                -Body $resetBody
            
            if ($resetResponse.success) {
                Write-Host "✓ Password reset successful" -ForegroundColor Green
                $TEST_PASSWORD = $NEW_PASSWORD
                Write-Host ""
            }
        } catch {
            Write-Host "✗ Password reset failed" -ForegroundColor Red
            Write-Host $_.Exception.Message
            Write-Host ""
        }
    }
} catch {
    Write-Host "✗ Forgot password request failed" -ForegroundColor Red
    Write-Host $_.Exception.Message
    Write-Host ""
}

# Test 4: Login
Write-Host "Test 4: Login" -ForegroundColor Yellow
Write-Host "Testing: POST /api/login"
Write-Host ""

$loginBody = @{
    login = $TEST_EMAIL
    password = $TEST_PASSWORD
} | ConvertTo-Json

try {
    $loginResponse = Invoke-RestMethod -Uri "$BASE_URL/login" `
        -Method Post `
        -ContentType "application/json" `
        -Body $loginBody
    
    if ($loginResponse.success) {
        Write-Host "✓ Login successful" -ForegroundColor Green
        $TOKEN = $loginResponse.data.token
        Write-Host ""
    }
} catch {
    Write-Host "✗ Login failed" -ForegroundColor Red
    Write-Host $_.Exception.Message
    Write-Host ""
}

# Test 5: Change Password
if ($TOKEN) {
    Write-Host "Test 5: Change Password (Authenticated)" -ForegroundColor Yellow
    Write-Host "Testing: POST /api/change-password"
    Write-Host "Expected Email: Password Reset Success Confirmation"
    Write-Host ""
    
    $NEWER_PASSWORD = "EvenNewerPass456"
    $changeBody = @{
        current_password = $TEST_PASSWORD
        new_password = $NEWER_PASSWORD
        new_password_confirmation = $NEWER_PASSWORD
    } | ConvertTo-Json
    
    try {
        $changeResponse = Invoke-RestMethod -Uri "$BASE_URL/change-password" `
            -Method Post `
            -Headers @{Authorization = "Bearer $TOKEN"} `
            -ContentType "application/json" `
            -Body $changeBody
        
        if ($changeResponse.success) {
            Write-Host "✓ Password changed successfully" -ForegroundColor Green
            $TEST_PASSWORD = $NEWER_PASSWORD
            Write-Host ""
        }
    } catch {
        Write-Host "✗ Password change failed" -ForegroundColor Red
        Write-Host $_.Exception.Message
        Write-Host ""
    }

    # Test 6: Delete Account
    Write-Host "Test 6: Delete Account" -ForegroundColor Yellow
    Write-Host "Testing: DELETE /api/delete-account"
    Write-Host "Expected Email: Account Deleted Confirmation"
    Write-Host ""
    
    $deleteBody = @{
        password = $TEST_PASSWORD
    } | ConvertTo-Json
    
    try {
        $deleteResponse = Invoke-RestMethod -Uri "$BASE_URL/delete-account" `
            -Method Delete `
            -Headers @{Authorization = "Bearer $TOKEN"} `
            -ContentType "application/json" `
            -Body $deleteBody
        
        if ($deleteResponse.success) {
            Write-Host "✓ Account deleted successfully" -ForegroundColor Green
            Write-Host ""
        }
    } catch {
        Write-Host "✗ Account deletion failed" -ForegroundColor Red
        Write-Host $_.Exception.Message
        Write-Host ""
    }
}

Write-Host "==================================================" -ForegroundColor Cyan
Write-Host "Test Summary" -ForegroundColor Cyan
Write-Host "==================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Emails that should have been sent:"
Write-Host "1. ✉️  Welcome Email (user registration)"
Write-Host "2. ✉️  Admin Notification (user registration)"
Write-Host "3. ✉️  Password Reset Email (forgot password)"
Write-Host "4. ✉️  Password Reset Success (password reset)"
Write-Host "5. ✉️  Password Reset Success (password change)"
Write-Host "6. ✉️  Account Deleted (account deletion)"
Write-Host ""
Write-Host "Check your email inbox and spam folder for these emails."
Write-Host "If using Mailtrap or similar, check the web interface."
Write-Host ""
Write-Host "Note: Make sure your .env email configuration is correct!" -ForegroundColor Yellow
Write-Host "==================================================" -ForegroundColor Cyan

