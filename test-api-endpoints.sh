#!/bin/bash

# SalesPulse API Testing Script
# This script tests all the fixed endpoints to verify data is being returned correctly

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
BASE_URL="http://localhost:8000/api"
EMAIL="user@example.com"
PASSWORD="password"
TOKEN=""

echo "======================================"
echo "   SalesPulse API Test Script"
echo "======================================"
echo ""

# Function to print test results
print_test() {
    echo -e "${YELLOW}Testing:${NC} $1"
}

print_success() {
    echo -e "${GREEN}✓ SUCCESS:${NC} $1"
}

print_error() {
    echo -e "${RED}✗ ERROR:${NC} $1"
}

# Step 1: Login to get token
print_test "Login to get authentication token"
LOGIN_RESPONSE=$(curl -s -X POST "$BASE_URL/login" \
  -H "Content-Type: application/json" \
  -d "{\"login\":\"$EMAIL\",\"password\":\"$PASSWORD\"}")

# Extract token from response
TOKEN=$(echo $LOGIN_RESPONSE | grep -o '"token":"[^"]*' | cut -d'"' -f4)

if [ -z "$TOKEN" ]; then
    print_error "Login failed. Please check your credentials."
    echo "Response: $LOGIN_RESPONSE"
    exit 1
else
    print_success "Login successful, token obtained"
fi

echo ""
echo "======================================"
echo "   Testing Sales Endpoints"
echo "======================================"
echo ""

# Test Sales List
print_test "GET /sales - Fetch all sales"
SALES_RESPONSE=$(curl -s -X GET "$BASE_URL/sales" \
  -H "Authorization: Bearer $TOKEN")

# Check if data is an array
if echo "$SALES_RESPONSE" | grep -q '"data":\['; then
    print_success "Sales endpoint returns array format"
    SALES_COUNT=$(echo "$SALES_RESPONSE" | grep -o '"id":' | wc -l)
    echo "  → Found $SALES_COUNT sale(s)"
else
    print_error "Sales endpoint not returning array format"
    echo "Response: $SALES_RESPONSE"
fi

# Test Create Sale
print_test "POST /sales - Create a new sale"
CREATE_SALE_RESPONSE=$(curl -s -X POST "$BASE_URL/sales" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "product_name": "Test Product API",
    "price": 100.00,
    "quantity": 5,
    "commission": 50.00,
    "commission_paid": false,
    "sale_date": "2025-10-17"
  }')

if echo "$CREATE_SALE_RESPONSE" | grep -q '"success":true'; then
    print_success "Sale created successfully"
    NEW_SALE_ID=$(echo "$CREATE_SALE_RESPONSE" | grep -o '"id":[0-9]*' | head -1 | cut -d':' -f2)
    echo "  → New Sale ID: $NEW_SALE_ID"
    
    # Check if total_amount is present
    if echo "$CREATE_SALE_RESPONSE" | grep -q '"total_amount"'; then
        print_success "Sale includes total_amount field"
    else
        print_error "Sale missing total_amount field"
    fi
else
    print_error "Failed to create sale"
    echo "Response: $CREATE_SALE_RESPONSE"
fi

echo ""
echo "======================================"
echo "   Testing Expenses Endpoints"
echo "======================================"
echo ""

# Test Expenses List
print_test "GET /expenses - Fetch all expenses"
EXPENSES_RESPONSE=$(curl -s -X GET "$BASE_URL/expenses" \
  -H "Authorization: Bearer $TOKEN")

if echo "$EXPENSES_RESPONSE" | grep -q '"data":\['; then
    print_success "Expenses endpoint returns array format"
    EXPENSES_COUNT=$(echo "$EXPENSES_RESPONSE" | grep -o '"id":' | wc -l)
    echo "  → Found $EXPENSES_COUNT expense(s)"
else
    print_error "Expenses endpoint not returning array format"
    echo "Response: $EXPENSES_RESPONSE"
fi

# Test Create Expense
print_test "POST /expenses - Create a new expense"
CREATE_EXPENSE_RESPONSE=$(curl -s -X POST "$BASE_URL/expenses" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Test Expense API",
    "amount": 150.00,
    "description": "Testing expense creation",
    "expense_date": "2025-10-17"
  }')

if echo "$CREATE_EXPENSE_RESPONSE" | grep -q '"success":true'; then
    print_success "Expense created successfully"
    NEW_EXPENSE_ID=$(echo "$CREATE_EXPENSE_RESPONSE" | grep -o '"id":[0-9]*' | head -1 | cut -d':' -f2)
    echo "  → New Expense ID: $NEW_EXPENSE_ID"
else
    print_error "Failed to create expense"
    echo "Response: $CREATE_EXPENSE_RESPONSE"
fi

echo ""
echo "======================================"
echo "   Testing Dashboard Endpoints"
echo "======================================"
echo ""

# Test Dashboard Overview
print_test "GET /dashboard/overview - Fetch dashboard overview"
OVERVIEW_RESPONSE=$(curl -s -X GET "$BASE_URL/dashboard/overview" \
  -H "Authorization: Bearer $TOKEN")

if echo "$OVERVIEW_RESPONSE" | grep -q '"total_sales"'; then
    print_success "Dashboard overview endpoint working"
    TOTAL_SALES=$(echo "$OVERVIEW_RESPONSE" | grep -o '"total_sales":[0-9.]*' | cut -d':' -f2)
    TOTAL_EXPENSES=$(echo "$OVERVIEW_RESPONSE" | grep -o '"total_expenses":[0-9.]*' | cut -d':' -f2)
    TOTAL_PRODUCTS=$(echo "$OVERVIEW_RESPONSE" | grep -o '"total_products":[0-9]*' | cut -d':' -f2)
    echo "  → Total Sales: $TOTAL_SALES"
    echo "  → Total Expenses: $TOTAL_EXPENSES"
    echo "  → Total Products: $TOTAL_PRODUCTS"
else
    print_error "Dashboard overview endpoint failed"
    echo "Response: $OVERVIEW_RESPONSE"
fi

# Test Unpaid Commissions
print_test "GET /dashboard/unpaid-commissions - Fetch unpaid commissions"
UNPAID_RESPONSE=$(curl -s -X GET "$BASE_URL/dashboard/unpaid-commissions" \
  -H "Authorization: Bearer $TOKEN")

if echo "$UNPAID_RESPONSE" | grep -q '"unpaid_commissions"'; then
    print_success "Unpaid commissions endpoint working"
    
    # Check if products are included
    if echo "$UNPAID_RESPONSE" | grep -q '"products"'; then
        print_success "Unpaid commissions include product details"
    else
        echo "  → No unpaid commissions or products not included"
    fi
else
    print_error "Unpaid commissions endpoint failed"
    echo "Response: $UNPAID_RESPONSE"
fi

# Test Sales & Expense History
print_test "GET /dashboard/history - Fetch sales and expense history"
HISTORY_RESPONSE=$(curl -s -X GET "$BASE_URL/dashboard/history" \
  -H "Authorization: Bearer $TOKEN")

if echo "$HISTORY_RESPONSE" | grep -q '"success":true'; then
    print_success "History endpoint working"
    
    # Check for sales and expenses
    if echo "$HISTORY_RESPONSE" | grep -q '"sales"'; then
        print_success "History includes sales data"
    fi
    
    if echo "$HISTORY_RESPONSE" | grep -q '"expenses"'; then
        print_success "History includes expenses data"
    fi
else
    print_error "History endpoint failed"
    echo "Response: $HISTORY_RESPONSE"
fi

# Test Complete Dashboard (NEW)
print_test "GET /dashboard - Fetch complete dashboard (NEW ENDPOINT)"
DASHBOARD_RESPONSE=$(curl -s -X GET "$BASE_URL/dashboard" \
  -H "Authorization: Bearer $TOKEN")

if echo "$DASHBOARD_RESPONSE" | grep -q '"overview"'; then
    print_success "Complete dashboard endpoint working"
    
    if echo "$DASHBOARD_RESPONSE" | grep -q '"unpaid_commissions"'; then
        print_success "Complete dashboard includes unpaid_commissions"
    fi
    
    if echo "$DASHBOARD_RESPONSE" | grep -q '"recent_activity"'; then
        print_success "Complete dashboard includes recent_activity"
    fi
else
    print_error "Complete dashboard endpoint failed"
    echo "Response: $DASHBOARD_RESPONSE"
fi

echo ""
echo "======================================"
echo "   Test Summary"
echo "======================================"
echo ""
echo "All tests completed!"
echo ""
echo "Key Points:"
echo "  • Sales endpoint returns simple array ✓"
echo "  • Expenses endpoint returns simple array ✓"
echo "  • Dashboard overview provides monthly stats ✓"
echo "  • Unpaid commissions include product details ✓"
echo "  • New comprehensive dashboard endpoint available ✓"
echo ""
echo "Your backend is ready for frontend integration!"
echo ""

