#!/bin/bash

# SalesPulse Email Integration Test Script
# This script tests all API endpoints that trigger email sending

# Configuration
BASE_URL="http://localhost:8000/api"
TEST_EMAIL="test@example.com"
TEST_PHONE="+256700123456"
TEST_PASSWORD="SecurePass123"
TEST_FIRST_NAME="John"
TEST_LAST_NAME="Doe"

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "=================================================="
echo "SalesPulse Email Integration Test"
echo "=================================================="
echo ""

# Test 1: User Registration (sends welcome email + admin notification)
echo -e "${YELLOW}Test 1: User Registration${NC}"
echo "Testing: POST /api/register"
echo "Expected Emails: Welcome Email + Admin Notification"
echo ""

REGISTER_RESPONSE=$(curl -s -X POST "$BASE_URL/register" \
  -H "Content-Type: application/json" \
  -d "{
    \"first_name\": \"$TEST_FIRST_NAME\",
    \"last_name\": \"$TEST_LAST_NAME\",
    \"email\": \"$TEST_EMAIL\",
    \"phone_number\": \"$TEST_PHONE\",
    \"password\": \"$TEST_PASSWORD\",
    \"password_confirmation\": \"$TEST_PASSWORD\"
  }")

if echo "$REGISTER_RESPONSE" | grep -q "\"success\":true"; then
    echo -e "${GREEN}✓ Registration successful${NC}"
    TOKEN=$(echo "$REGISTER_RESPONSE" | grep -o '"token":"[^"]*' | cut -d'"' -f4)
    echo "Token saved for authenticated requests"
    echo ""
else
    echo -e "${RED}✗ Registration failed${NC}"
    echo "$REGISTER_RESPONSE"
    echo ""
fi

# Test 2: Forgot Password (sends reset code email)
echo -e "${YELLOW}Test 2: Forgot Password${NC}"
echo "Testing: POST /api/forgot-password"
echo "Expected Email: Password Reset Email with 6-digit code"
echo ""

FORGOT_RESPONSE=$(curl -s -X POST "$BASE_URL/forgot-password" \
  -H "Content-Type: application/json" \
  -d "{\"email\": \"$TEST_EMAIL\"}")

if echo "$FORGOT_RESPONSE" | grep -q "\"success\":true"; then
    echo -e "${GREEN}✓ Reset code sent successfully${NC}"
    echo "Check email for 6-digit reset code"
    echo ""
    
    # Prompt for reset code
    echo -n "Enter the 6-digit reset code from email: "
    read RESET_CODE
    
    # Test 3: Reset Password (sends success email)
    echo ""
    echo -e "${YELLOW}Test 3: Reset Password${NC}"
    echo "Testing: POST /api/reset-password"
    echo "Expected Email: Password Reset Success Confirmation"
    echo ""
    
    NEW_PASSWORD="NewPassword123"
    RESET_RESPONSE=$(curl -s -X POST "$BASE_URL/reset-password" \
      -H "Content-Type: application/json" \
      -d "{
        \"email\": \"$TEST_EMAIL\",
        \"code\": \"$RESET_CODE\",
        \"password\": \"$NEW_PASSWORD\",
        \"password_confirmation\": \"$NEW_PASSWORD\"
      }")
    
    if echo "$RESET_RESPONSE" | grep -q "\"success\":true"; then
        echo -e "${GREEN}✓ Password reset successful${NC}"
        TEST_PASSWORD="$NEW_PASSWORD"
        echo ""
    else
        echo -e "${RED}✗ Password reset failed${NC}"
        echo "$RESET_RESPONSE"
        echo ""
    fi
else
    echo -e "${RED}✗ Forgot password request failed${NC}"
    echo "$FORGOT_RESPONSE"
    echo ""
fi

# Test 4: Login to get fresh token
echo -e "${YELLOW}Test 4: Login${NC}"
echo "Testing: POST /api/login"
echo ""

LOGIN_RESPONSE=$(curl -s -X POST "$BASE_URL/login" \
  -H "Content-Type: application/json" \
  -d "{
    \"login\": \"$TEST_EMAIL\",
    \"password\": \"$TEST_PASSWORD\"
  }")

if echo "$LOGIN_RESPONSE" | grep -q "\"success\":true"; then
    echo -e "${GREEN}✓ Login successful${NC}"
    TOKEN=$(echo "$LOGIN_RESPONSE" | grep -o '"token":"[^"]*' | cut -d'"' -f4)
    echo ""
else
    echo -e "${RED}✗ Login failed${NC}"
    echo "$LOGIN_RESPONSE"
    echo ""
fi

# Test 5: Change Password (sends success email)
if [ ! -z "$TOKEN" ]; then
    echo -e "${YELLOW}Test 5: Change Password (Authenticated)${NC}"
    echo "Testing: POST /api/change-password"
    echo "Expected Email: Password Reset Success Confirmation"
    echo ""
    
    NEWER_PASSWORD="EvenNewerPass456"
    CHANGE_RESPONSE=$(curl -s -X POST "$BASE_URL/change-password" \
      -H "Content-Type: application/json" \
      -H "Authorization: Bearer $TOKEN" \
      -d "{
        \"current_password\": \"$TEST_PASSWORD\",
        \"new_password\": \"$NEWER_PASSWORD\",
        \"new_password_confirmation\": \"$NEWER_PASSWORD\"
      }")
    
    if echo "$CHANGE_RESPONSE" | grep -q "\"success\":true"; then
        echo -e "${GREEN}✓ Password changed successfully${NC}"
        TEST_PASSWORD="$NEWER_PASSWORD"
        echo ""
    else
        echo -e "${RED}✗ Password change failed${NC}"
        echo "$CHANGE_RESPONSE"
        echo ""
    fi

    # Test 6: Delete Account (sends deletion email)
    echo -e "${YELLOW}Test 6: Delete Account${NC}"
    echo "Testing: DELETE /api/delete-account"
    echo "Expected Email: Account Deleted Confirmation"
    echo ""
    
    DELETE_RESPONSE=$(curl -s -X DELETE "$BASE_URL/delete-account" \
      -H "Content-Type: application/json" \
      -H "Authorization: Bearer $TOKEN" \
      -d "{\"password\": \"$TEST_PASSWORD\"}")
    
    if echo "$DELETE_RESPONSE" | grep -q "\"success\":true"; then
        echo -e "${GREEN}✓ Account deleted successfully${NC}"
        echo ""
    else
        echo -e "${RED}✗ Account deletion failed${NC}"
        echo "$DELETE_RESPONSE"
        echo ""
    fi
fi

echo "=================================================="
echo "Test Summary"
echo "=================================================="
echo ""
echo "Emails that should have been sent:"
echo "1. ✉️  Welcome Email (user registration)"
echo "2. ✉️  Admin Notification (user registration)"
echo "3. ✉️  Password Reset Email (forgot password)"
echo "4. ✉️  Password Reset Success (password reset)"
echo "5. ✉️  Password Reset Success (password change)"
echo "6. ✉️  Account Deleted (account deletion)"
echo ""
echo "Check your email inbox and spam folder for these emails."
echo "If using Mailtrap or similar, check the web interface."
echo ""
echo -e "${YELLOW}Note: Make sure your .env email configuration is correct!${NC}"
echo "=================================================="

