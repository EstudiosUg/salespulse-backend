# SalesPulse API - cURL Examples

## Base URL
```
http://localhost:8000/api
```

## Authentication APIs

### 1. Register New User
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone_number": "+1234567890",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### 2. Login with Email
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "login": "john@example.com",
    "password": "password123"
  }'
```

### 3. Login with Phone Number
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "login": "+1234567890",
    "password": "password123"
  }'
```

### 4. Get Current User Profile
```bash
curl -X GET http://localhost:8000/api/me \
  -H "Authorization: Bearer {{token}}"
```

### 5. Logout
```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer {{token}}"
```

### 6. Change Password
```bash
curl -X POST http://localhost:8000/api/change-password \
  -H "Authorization: Bearer {{token}}" \
  -H "Content-Type: application/json" \
  -d '{
    "current_password": "password123",
    "new_password": "newpassword123",
    "new_password_confirmation": "newpassword123"
  }'
```

### 7. Delete Account
```bash
curl -X DELETE http://localhost:8000/api/delete-account \
  -H "Authorization: Bearer {{token}}" \
  -H "Content-Type: application/json" \
  -d '{
    "password": "password123"
  }'
```

## User Profile & Settings APIs

### 8. Get User Profile
```bash
curl -X GET http://localhost:8000/api/profile \
  -H "Authorization: Bearer {{token}}"
```

### 9. Update User Profile
```bash
curl -X PUT http://localhost:8000/api/profile \
  -H "Authorization: Bearer {{token}}" \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "Jane",
    "last_name": "Smith",
    "email": "jane@example.com",
    "phone_number": "+1987654321",
    "theme": "dark"
  }'
```

### 10. Upload Avatar
```bash
curl -X POST http://localhost:8000/api/profile/avatar \
  -H "Authorization: Bearer {{token}}" \
  -F "avatar=@/path/to/your/image.jpg"
```

### 11. Get User Settings
```bash
curl -X GET http://localhost:8000/api/settings \
  -H "Authorization: Bearer {{token}}"
```

### 12. Update User Settings
```bash
curl -X PUT http://localhost:8000/api/settings \
  -H "Authorization: Bearer {{token}}" \
  -H "Content-Type: application/json" \
  -d '{
    "notifications_enabled": true,
    "currency": "USD",
    "language": "en"
  }'
```

### 13. Export Data (Premium Feature)
```bash
curl -X POST http://localhost:8000/api/export-data \
  -H "Authorization: Bearer {{token}}" \
  -H "Content-Type: application/json" \
  -d '{
    "start_date": "2024-01-01",
    "end_date": "2024-12-31",
    "include_sales": true,
    "include_expenses": true
  }'
```

## Sales Management APIs

### 14. Get All Sales
```bash
curl -X GET http://localhost:8000/api/sales \
  -H "Authorization: {{token}}"
```

### 15. Get Sales with Filters
```bash
curl -X GET "http://localhost:8000/api/sales?month=10&year=2024&supplier_id=1&commission_paid=false&per_page=20" \
  -H "Authorization: {{token}}"
```

### 16. Get Sales by Date Range
```bash
curl -X GET "http://localhost:8000/api/sales?start_date=2024-10-01&end_date=2024-10-31" \
  -H "Authorization: Bearer {{token}}"
```

### 17. Create New Sale
```bash
curl -X POST http://localhost:8000/api/sales \
  -H "Authorization: Bearer {{token}}" \
  -H "Content-Type: application/json" \
  -d '{
    "product_name": "Laptop Computer",
    "price": 1200.00,
    "quantity": 1,
    "commission": 120.00,
    "supplier_id": 1,
    "feedback": "Excellent product quality",
    "commission_paid": false,
    "sale_date": "2024-10-15"
  }'
```

### 18. Get Specific Sale
```bash
curl -X GET http://localhost:8000/api/sales/1 \
  -H "Authorization: Bearer {{token}}"
```

### 19. Update Sale
```bash
curl -X PUT http://localhost:8000/api/sales/1 \
  -H "Authorization: Bearer {{token}}" \
  -H "Content-Type: application/json" \
  -d '{
    "product_name": "Updated Laptop Computer",
    "price": 1300.00,
    "quantity": 1,
    "commission": 130.00,
    "feedback": "Updated feedback",
    "commission_paid": true
  }'
```

### 20. Delete Sale
```bash
curl -X DELETE http://localhost:8000/api/sales/1 \
  -H "Authorization: Bearer {{token}}"
```

### 21. Mark Commission as Paid
```bash
curl -X PATCH http://localhost:8000/api/sales/1/mark-commission-paid \
  -H "Authorization: Bearer {{token}}"
```

## Expenses Management APIs

### 22. Get All Expenses
```bash
curl -X GET http://localhost:8000/api/expenses \
  -H "Authorization: Bearer {{token}}"
```

### 23. Get Expenses with Filters
```bash
curl -X GET "http://localhost:8000/api/expenses?month=10&year=2024&per_page=15" \
  -H "Authorization: Bearer {{token}}"
```

### 24. Get Expenses by Date Range
```bash
curl -X GET "http://localhost:8000/api/expenses?start_date=2024-10-01&end_date=2024-10-31" \
  -H "Authorization: Bearer {{token}}"
```

### 25. Create New Expense
```bash
curl -X POST http://localhost:8000/api/expenses \
  -H "Authorization: Bearer {{token}}" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Office Supplies",
    "amount": 150.00,
    "description": "Pens, paper, and office materials",
    "expense_date": "2024-10-15"
  }'
```

### 26. Get Specific Expense
```bash
curl -X GET http://localhost:8000/api/expenses/1 \
  -H "Authorization: Bearer {{token}}"
```

### 27. Update Expense
```bash
curl -X PUT http://localhost:8000/api/expenses/1 \
  -H "Authorization: Bearer {{token}}" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Updated Office Supplies",
    "amount": 175.00,
    "description": "Updated description with more items",
    "expense_date": "2024-10-16"
  }'
```

### 28. Delete Expense
```bash
curl -X DELETE http://localhost:8000/api/expenses/1 \
  -H "Authorization: Bearer {{token}}"
```

## Suppliers Management APIs

### 29. Get All Suppliers
```bash
curl -X GET http://localhost:8000/api/suppliers \
  -H "Authorization: Bearer {{token}}"
```

### 30. Get Active Suppliers Only
```bash
curl -X GET "http://localhost:8000/api/suppliers?active=true" \
  -H "Authorization: Bearer {{token}}"
```

### 31. Create New Supplier
```bash
curl -X POST http://localhost:8000/api/suppliers \
  -H "Authorization: Bearer {{token}}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Tech Supplies Inc",
    "email": "contact@techsupplies.com",
    "phone": "+1234567891",
    "address": "123 Tech Street, Silicon Valley, CA",
    "notes": "Reliable supplier for tech products",
    "is_active": true
  }'
```

### 32. Get Specific Supplier
```bash
curl -X GET http://localhost:8000/api/suppliers/1 \
  -H "Authorization: Bearer {{token}}"
```

### 33. Update Supplier
```bash
curl -X PUT http://localhost:8000/api/suppliers/1 \
  -H "Authorization: Bearer {{token}}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Updated Tech Supplies Inc",
    "email": "newemail@techsupplies.com",
    "phone": "+1234567899",
    "address": "456 New Tech Street, Silicon Valley, CA",
    "notes": "Updated supplier information",
    "is_active": true
  }'
```

### 34. Delete Supplier
```bash
curl -X DELETE http://localhost:8000/api/suppliers/1 \
  -H "Authorization: Bearer {{token}}"
```

## Dashboard Analytics APIs

### 35. Get Complete Dashboard (All Data in One Call) ⭐ NEW & RECOMMENDED
```bash
curl -X GET http://localhost:8000/api/dashboard \
  -H "Authorization: Bearer {{token}}"
```

### 35a. Get Complete Dashboard for Specific Month
```bash
curl -X GET "http://localhost:8000/api/dashboard?month=10&year=2024" \
  -H "Authorization: Bearer {{token}}"
```

### 36. Get Dashboard Overview
```bash
curl -X GET http://localhost:8000/api/dashboard/overview \
  -H "Authorization: Bearer {{token}}"
```

### 37. Get Dashboard Overview for Specific Month
```bash
curl -X GET "http://localhost:8000/api/dashboard/overview?month=10&year=2024" \
  -H "Authorization: Bearer {{token}}"
```

### 38. Get Unpaid Commissions
```bash
curl -X GET http://localhost:8000/api/dashboard/unpaid-commissions \
  -H "Authorization: Bearer {{token}}"
```

### 39. Get Sales and Expense History
```bash
curl -X GET http://localhost:8000/api/dashboard/history \
  -H "Authorization: Bearer {{token}}"
```

### 40. Get History for Specific Month and Type
```bash
curl -X GET "http://localhost:8000/api/dashboard/history?month=10&year=2024&type=sales" \
  -H "Authorization: Bearer {{token}}"
```

### 41. Get Monthly Statistics
```bash
curl -X GET http://localhost:8000/api/dashboard/monthly-stats \
  -H "Authorization: Bearer {{token}}"
```

### 42. Get Monthly Statistics for Specific Year
```bash
curl -X GET "http://localhost:8000/api/dashboard/monthly-stats?year=2024" \
  -H "Authorization: Bearer {{token}}"
```

## Response Examples

### Successful Response Format
```json
{
  "success": true,
  "message": "Operation completed successfully",
  "data": {
    // Response data here
  }
}
```

### Error Response Format
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field_name": ["Validation error message"]
  }
}
```

### Login Response Example
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "first_name": "John",
      "last_name": "Doe",
      "email": "john@example.com",
      "phone_number": "+1234567890",
      "avatar": null,
      "theme": "light",
      "is_premium": true,
      "premium_expires_at": "2025-10-15T19:45:32.000000Z"
    },
    "token": "1|abc123def456...",
    "token_type": "Bearer"
  }
}
```

### Complete Dashboard Response Example (NEW)
```json
{
  "success": true,
  "data": {
    "overview": {
      "total_sales": 2650.00,
      "total_expenses": 1785.00,
      "total_products": 8,
      "commission_paid": 120.00,
      "unpaid_commission": 67.50,
      "net_profit": 865.00,
      "month": 10,
      "year": 2024
    },
    "unpaid_commissions": {
      "total_unpaid": 67.50,
      "list": [
        {
          "supplier_id": 1,
          "supplier": {
            "id": 1,
            "name": "Tech Supplies Inc",
            "email": "contact@techsupplies.com"
          },
          "supplier_name": "Tech Supplies Inc",
          "total_commission": 67.50,
          "sales_count": 2
        }
      ]
    },
    "recent_activity": {
      "sales": [...],
      "expenses": [...]
    }
  }
}
```

### Dashboard Overview Response Example
```json
{
  "success": true,
  "data": {
    "total_sales": 2650.00,
    "total_expenses": 1785.00,
    "total_products": 8,
    "commission_paid": 120.00,
    "unpaid_commission": 67.50,
    "net_profit": 865.00,
    "month": 10,
    "year": 2024
  }
}
```

## Notes

1. **Replace `YOUR_TOKEN_HERE`** with the actual token received from login
2. **Base URL** may change in production (replace `localhost:8000` with your domain)
3. **File uploads** require `multipart/form-data` content type
4. **Date formats** should be in `YYYY-MM-DD` format
5. **Pagination** is available on list endpoints with `per_page` parameter
6. **Filtering** is available on sales and expenses with various parameters
7. **Premium features** require active premium subscription

## Quick Test Sequence

1. Register a new user
2. Login to get token
3. Create a supplier
4. Create a sale with the supplier
5. Create an expense
6. Check dashboard overview
7. Update the sale
8. Mark commission as paid
9. Export data (if premium)

This covers all the CRUD operations and analytics features of your SalesPulse API!
