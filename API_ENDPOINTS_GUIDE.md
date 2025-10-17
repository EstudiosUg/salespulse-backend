# API Endpoints Guide for SalesPulse

This document provides detailed information about all API endpoints, their expected responses, and how they match the app requirements.

## Base URL
```
http://your-domain.com/api
```

## Authentication
All protected endpoints require a Bearer token in the Authorization header:
```
Authorization: Bearer {your_token}
```

---

## Dashboard Endpoints

### 1. Complete Dashboard (Recommended)
**GET** `/dashboard`

Returns all dashboard data in a single API call.

**Query Parameters:**
- `month` (optional): Month number (1-12), defaults to current month
- `year` (optional): Year (e.g., 2024), defaults to current year

**Response:**
```json
{
  "success": true,
  "data": {
    "overview": {
      "total_sales": 15000.00,
      "total_expenses": 5000.00,
      "total_products": 45,
      "commission_paid": 1200.00,
      "unpaid_commission": 800.00,
      "net_profit": 10000.00,
      "month": 10,
      "year": 2025
    },
    "unpaid_commissions": {
      "has_unpaid": true,
      "total_unpaid": 800.00,
      "list": [
        {
          "supplier_id": 1,
          "supplier": {
            "id": 1,
            "name": "ABC Supplier",
            "email": "abc@example.com",
            "phone": "1234567890"
          },
          "supplier_name": "ABC Supplier",
          "total_commission": 500.00,
          "sales_count": 3,
          "products": [
            {
              "id": 1,
              "product_name": "Product A",
              "commission": 200.00,
              "sale_date": "2025-10-15",
              "quantity": 5,
              "price": 100.00,
              "total_amount": 500.00
            }
          ]
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

### 2. Dashboard Overview
**GET** `/dashboard/overview`

**Query Parameters:**
- `month` (optional): Month number (1-12)
- `year` (optional): Year

**Response:**
```json
{
  "success": true,
  "data": {
    "total_sales": 15000.00,
    "total_expenses": 5000.00,
    "total_products": 45,
    "commission_paid": 1200.00,
    "unpaid_commission": 800.00,
    "net_profit": 10000.00,
    "month": 10,
    "year": 2025
  }
}
```

### 3. Unpaid Commissions
**GET** `/dashboard/unpaid-commissions`

Returns unpaid commissions grouped by supplier with product details.

**Response:**
```json
{
  "success": true,
  "data": {
    "has_unpaid": true,
    "total_unpaid": 800.00,
    "unpaid_commissions": [
      {
        "supplier_id": 1,
        "supplier": {
          "id": 1,
          "name": "ABC Supplier",
          "email": "abc@example.com"
        },
        "supplier_name": "ABC Supplier",
        "total_commission": 500.00,
        "sales_count": 3,
        "products": [
          {
            "id": 1,
            "product_name": "Product A",
            "commission": 200.00,
            "sale_date": "2025-10-15",
            "quantity": 5,
            "price": 100.00,
            "total_amount": 500.00
          }
        ]
      }
    ]
  }
}
```

### 4. Sales & Expense History
**GET** `/dashboard/history`

**Query Parameters:**
- `month` (optional): Month number (1-12), defaults to current month
- `year` (optional): Year, defaults to current year
- `type` (optional): 'sales', 'expenses', or 'both' (default)
- `limit` (optional): Limit number of results

**Response:**
```json
{
  "success": true,
  "data": {
    "sales": [...],
    "expenses": [...]
  }
}
```

### 5. Monthly Statistics
**GET** `/dashboard/monthly-stats`

Returns sales and expenses for all 12 months.

**Query Parameters:**
- `year` (optional): Year, defaults to current year

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "month": 1,
      "month_name": "January",
      "sales": 12000.00,
      "expenses": 4000.00,
      "profit": 8000.00
    },
    ...
  ]
}
```

---

## Sales Endpoints

### 1. Get All Sales
**GET** `/sales`

Returns all sales for the authenticated user as a simple array.

**Query Parameters:**
- `month` (optional): Filter by month (1-12)
- `year` (optional): Filter by year
- `start_date` (optional): Filter by start date (YYYY-MM-DD)
- `end_date` (optional): Filter by end date (YYYY-MM-DD)
- `supplier_id` (optional): Filter by supplier ID
- `commission_paid` (optional): Filter by commission paid status (true/false)
- `per_page` (optional): Enable pagination (returns paginated response)

**Response (without pagination):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "supplier_id": 1,
      "product_name": "Product A",
      "price": 100.00,
      "quantity": 5,
      "commission": 50.00,
      "feedback": "Great product",
      "commission_paid": false,
      "sale_date": "2025-10-15",
      "total_amount": 500.00,
      "created_at": "2025-10-15T10:00:00.000000Z",
      "updated_at": "2025-10-15T10:00:00.000000Z",
      "supplier": {
        "id": 1,
        "name": "ABC Supplier",
        "email": "abc@example.com",
        "phone": "1234567890"
      }
    }
  ]
}
```

**Response (with per_page parameter):**
```json
{
  "success": true,
  "data": [...],
  "pagination": {
    "current_page": 1,
    "per_page": 15,
    "total": 45,
    "last_page": 3
  }
}
```

### 2. Create Sale
**POST** `/sales`

**Request Body:**
```json
{
  "product_name": "Product A",
  "price": 100.00,
  "quantity": 5,
  "commission": 50.00,
  "supplier_id": 1,
  "feedback": "Great product",
  "commission_paid": false,
  "sale_date": "2025-10-15"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Sale created successfully",
  "data": {
    "id": 1,
    "product_name": "Product A",
    ...
  }
}
```

### 3. Get Single Sale
**GET** `/sales/{id}`

### 4. Update Sale
**PUT** `/sales/{id}`

**Request Body:** Same as Create Sale (all fields optional)

### 5. Delete Sale
**DELETE** `/sales/{id}`

### 6. Mark Commission as Paid (Single)
**PATCH** `/sales/{id}/mark-commission-paid`

**Response:**
```json
{
  "success": true,
  "message": "Commission marked as paid",
  "data": {
    "id": 1,
    "commission_paid": true,
    ...
  }
}
```

### 7. Mark Multiple Commissions as Paid (Bulk) ⭐ NEW
**POST** `/sales/mark-multiple-commissions-paid`

**Request Body:**
```json
{
  "sale_ids": [1, 2, 3, 5, 7]
}
```

**Response:**
```json
{
  "success": true,
  "message": "Marked 5 commission(s) as paid",
  "updated_count": 5
}
```

### 8. Mark All Commissions for a Supplier as Paid ⭐ NEW
**PATCH** `/sales/supplier/{supplierId}/mark-commissions-paid`

Marks all unpaid commissions for a specific supplier as paid.

**Parameters:**
- `supplierId`: Supplier ID, or use `null` or `0` for sales without supplier

**Response:**
```json
{
  "success": true,
  "message": "Marked 3 commission(s) as paid for this supplier",
  "updated_count": 3
}
```

---

## Expenses Endpoints

### 1. Get All Expenses
**GET** `/expenses`

Returns all expenses for the authenticated user as a simple array.

**Query Parameters:**
- `month` (optional): Filter by month (1-12)
- `year` (optional): Filter by year
- `start_date` (optional): Filter by start date (YYYY-MM-DD)
- `end_date` (optional): Filter by end date (YYYY-MM-DD)
- `per_page` (optional): Enable pagination (returns paginated response)

**Response (without pagination):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "title": "Office Rent",
      "amount": 1000.00,
      "description": "Monthly office rent payment",
      "expense_date": "2025-10-01",
      "created_at": "2025-10-01T10:00:00.000000Z",
      "updated_at": "2025-10-01T10:00:00.000000Z"
    }
  ]
}
```

**Response (with per_page parameter):**
```json
{
  "success": true,
  "data": [...],
  "pagination": {
    "current_page": 1,
    "per_page": 15,
    "total": 30,
    "last_page": 2
  }
}
```

### 2. Create Expense
**POST** `/expenses`

**Request Body:**
```json
{
  "title": "Office Rent",
  "amount": 1000.00,
  "description": "Monthly office rent payment",
  "expense_date": "2025-10-01"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Expense created successfully",
  "data": {
    "id": 1,
    "title": "Office Rent",
    ...
  }
}
```

### 3. Get Single Expense
**GET** `/expenses/{id}`

### 4. Update Expense
**PUT** `/expenses/{id}`

**Request Body:** Same as Create Expense (all fields optional)

### 5. Delete Expense
**DELETE** `/expenses/{id}`

---

## Suppliers Endpoints

### 1. Get All Suppliers
**GET** `/suppliers`

### 2. Create Supplier
**POST** `/suppliers`

### 3. Get Single Supplier
**GET** `/suppliers/{id}`

### 4. Update Supplier
**PUT** `/suppliers/{id}`

### 5. Delete Supplier
**DELETE** `/suppliers/{id}`

---

## Key Changes Made to Fix Data Rendering

### Problem 1: Pagination Structure
**Issue:** Controllers were using `paginate()` which returns:
```json
{
  "data": {
    "data": [...],
    "current_page": 1,
    ...
  }
}
```

But frontend expected:
```json
{
  "data": [...]
}
```

**Solution:** Changed controllers to return simple arrays by default using `get()` instead of `paginate()`. Pagination is now optional via `per_page` parameter.

### Problem 2: Unpaid Commissions Query
**Issue:** The `groupBy` query with supplier relationship wasn't working properly and didn't handle null suppliers.

**Solution:** 
- Changed to get all unpaid sales first, then group using collection methods
- Added proper handling for null suppliers
- Added product details to unpaid commissions

### Problem 3: Limited History Data
**Issue:** History endpoint was limiting results to 10 items.

**Solution:** Removed fixed limit, made it optional via `limit` parameter so users can get all transactions for a month.

### Problem 4: Missing Total Amount in Sales
**Issue:** Sales didn't include computed total_amount field.

**Solution:** Added `total_amount` to the appended attributes in Sale model.

---

## Testing the Endpoints

### Using cURL

1. **Login to get token:**
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'
```

2. **Get Dashboard Data:**
```bash
curl -X GET "http://localhost:8000/api/dashboard" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

3. **Get Sales:**
```bash
curl -X GET "http://localhost:8000/api/sales" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

4. **Get Expenses:**
```bash
curl -X GET "http://localhost:8000/api/expenses" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

5. **Create a Sale:**
```bash
curl -X POST http://localhost:8000/api/sales \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "product_name": "Test Product",
    "price": 100,
    "quantity": 5,
    "commission": 50,
    "commission_paid": false,
    "sale_date": "2025-10-17"
  }'
```

6. **Create an Expense:**
```bash
curl -X POST http://localhost:8000/api/expenses \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Office Supplies",
    "amount": 250.50,
    "description": "Printer paper and ink",
    "expense_date": "2025-10-17"
  }'
```

---

## Frontend Integration Notes

1. **Sales Screen:** Use `GET /sales` to fetch all sales. Data will be returned as a simple array in `response.data.data`.

2. **Expenses Screen:** Use `GET /expenses` to fetch all expenses. Data will be returned as a simple array in `response.data.data`.

3. **Dashboard Screen:** 
   - **Option 1 (Recommended):** Use `GET /dashboard` to get all dashboard data in one call
   - **Option 2:** Make separate calls to:
     - `GET /dashboard/overview` - for overview cards
     - `GET /dashboard/unpaid-commissions` - for unpaid commissions section
     - `GET /dashboard/history` - for sales/expense history

4. **Filtering by Month:** Add `?month=10&year=2025` query parameters to any endpoint that supports it.

5. **Mark Commission as Paid:** 
   - Single: Use `PATCH /sales/{id}/mark-commission-paid`
   - Multiple: Use `POST /sales/mark-multiple-commissions-paid`
   - By Supplier: Use `PATCH /sales/supplier/{supplierId}/mark-commissions-paid`

---

## Data Structure Alignment with App Requirements

✅ **Dashboard Overview Cards:**
- Total Sales for the month ✓
- Total Expenses for the month ✓
- Total Products sold ✓
- Commission Paid ✓

✅ **Unpaid Commission Card:**
- Total unpaid commission ✓
- List by supplier ✓
- List of products with commission ✓
- Only appears if there's unpaid commission ✓

✅ **Sales & Expense History:**
- Filterable by month ✓
- Shows both sales and expenses ✓
- Can edit individual items ✓

✅ **Sales Form Fields:**
- Product name ✓
- Price ✓
- Quantity ✓
- Commission ✓
- Supplier (relationship) ✓
- Feedback ✓
- Commission paid toggle ✓
- Date picker ✓

✅ **Expense Form Fields:**
- Title ✓
- Amount ✓
- Description ✓
- Date picker ✓

