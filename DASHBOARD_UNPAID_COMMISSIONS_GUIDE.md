# Dashboard Unpaid Commissions - Complete Guide

## Overview
This guide explains how the unpaid commission section works on the dashboard, including how it displays, how to mark commissions as paid, and how the data updates dynamically.

---

## Dashboard Structure

### 1. Overview Cards (Monthly Data)
The dashboard displays four key metrics for the current/selected month:
- **Total Sales**: Sum of all sales (price × quantity)
- **Total Expenses**: Sum of all expenses
- **Total Products**: Total quantity of products sold
- **Commission Paid**: Total commission that has been marked as paid

### 2. Unpaid Commission Section
Below the overview cards, there's a dedicated section for unpaid commissions that:
- ✅ **Only appears when there are unpaid commissions** (`has_unpaid: true`)
- ✅ Shows **total unpaid commission amount**
- ✅ Lists commissions **grouped by supplier**
- ✅ Shows **individual products** with their commissions under each supplier
- ✅ **Automatically disappears** when all commissions are marked as paid

---

## API Response Structure

### Complete Dashboard Endpoint
**GET** `/api/dashboard`

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
      "year": 2025
    },
    "unpaid_commissions": {
      "has_unpaid": true,              // ← Use this to show/hide section
      "total_unpaid": 67.50,
      "list": [
        {
          "supplier_id": 1,
          "supplier": {
            "id": 1,
            "name": "Tech Supplies Inc",
            "email": "contact@techsupplies.com",
            "phone": "+1234567890"
          },
          "supplier_name": "Tech Supplies Inc",
          "total_commission": 67.50,
          "sales_count": 2,
          "products": [                  // ← Product-level details
            {
              "id": 1,
              "product_name": "Laptop Computer",
              "commission": 50.00,
              "sale_date": "2025-10-15",
              "quantity": 1,
              "price": 1200.00,
              "total_amount": 1200.00
            },
            {
              "id": 2,
              "product_name": "Wireless Mouse",
              "commission": 17.50,
              "sale_date": "2025-10-14",
              "quantity": 5,
              "price": 35.00,
              "total_amount": 175.00
            }
          ]
        },
        {
          "supplier_id": null,
          "supplier": null,
          "supplier_name": "No Supplier",   // ← Handles sales without supplier
          "total_commission": 25.00,
          "sales_count": 1,
          "products": [...]
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

---

## Frontend Implementation Guide

### Conditional Display Logic

```dart
// In your dashboard screen widget
Widget build(BuildContext context) {
  return Column(
    children: [
      // Overview Cards
      _buildOverviewCards(dashboardData['overview']),
      
      // Unpaid Commission Section (conditional)
      if (dashboardData['unpaid_commissions']['has_unpaid'] == true)
        _buildUnpaidCommissionsSection(
          dashboardData['unpaid_commissions']
        ),
      
      // Recent Activity
      _buildRecentActivity(dashboardData['recent_activity']),
    ],
  );
}
```

### Building the Unpaid Commission Section

```dart
Widget _buildUnpaidCommissionsSection(Map<String, dynamic> unpaidData) {
  final totalUnpaid = unpaidData['total_unpaid'];
  final List suppliers = unpaidData['list'];
  
  return Card(
    child: Column(
      children: [
        // Header with total
        ListTile(
          title: Text('Unpaid Commissions'),
          trailing: Text(
            '\$${totalUnpaid.toStringAsFixed(2)}',
            style: TextStyle(
              fontSize: 20,
              fontWeight: FontWeight.bold,
              color: Colors.orange,
            ),
          ),
        ),
        
        Divider(),
        
        // List by supplier
        ...suppliers.map((supplier) => 
          _buildSupplierCommissionGroup(supplier)
        ).toList(),
      ],
    ),
  );
}

Widget _buildSupplierCommissionGroup(Map<String, dynamic> supplier) {
  return ExpansionTile(
    title: Text(supplier['supplier_name']),
    subtitle: Text(
      '${supplier['sales_count']} product(s) - '
      '\$${supplier['total_commission'].toStringAsFixed(2)}'
    ),
    trailing: ElevatedButton(
      onPressed: () => _markSupplierCommissionsPaid(supplier['supplier_id']),
      child: Text('Mark All Paid'),
    ),
    children: [
      // Product-level list
      ...supplier['products'].map((product) => 
        _buildProductCommissionItem(product)
      ).toList(),
    ],
  );
}

Widget _buildProductCommissionItem(Map<String, dynamic> product) {
  return ListTile(
    title: Text(product['product_name']),
    subtitle: Text(
      'Qty: ${product['quantity']} × \$${product['price']} = '
      '\$${product['total_amount']} | ${product['sale_date']}'
    ),
    trailing: Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Text(
          '\$${product['commission'].toStringAsFixed(2)}',
          style: TextStyle(fontWeight: FontWeight.bold),
        ),
        SizedBox(width: 8),
        IconButton(
          icon: Icon(Icons.check_circle_outline),
          onPressed: () => _markSingleCommissionPaid(product['id']),
          tooltip: 'Mark Paid',
        ),
      ],
    ),
  );
}
```

---

## Marking Commissions as Paid

### Option 1: Mark Single Product Commission
**PATCH** `/api/sales/{saleId}/mark-commission-paid`

```dart
Future<void> _markSingleCommissionPaid(int saleId) async {
  final response = await http.patch(
    Uri.parse('$baseUrl/sales/$saleId/mark-commission-paid'),
    headers: {'Authorization': 'Bearer $token'},
  );
  
  if (response.statusCode == 200) {
    // Refresh dashboard data
    _loadDashboard();
  }
}
```

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

### Option 2: Mark All Commissions for a Supplier (NEW)
**PATCH** `/api/sales/supplier/{supplierId}/mark-commissions-paid`

```dart
Future<void> _markSupplierCommissionsPaid(int? supplierId) async {
  // Handle null supplier (sales without supplier)
  final supplierParam = supplierId?.toString() ?? 'null';
  
  final response = await http.patch(
    Uri.parse('$baseUrl/sales/supplier/$supplierParam/mark-commissions-paid'),
    headers: {'Authorization': 'Bearer $token'},
  );
  
  if (response.statusCode == 200) {
    final data = json.decode(response.body);
    print('Marked ${data['updated_count']} commissions as paid');
    
    // Refresh dashboard data
    _loadDashboard();
  }
}
```

**Response:**
```json
{
  "success": true,
  "message": "Marked 3 commission(s) as paid for this supplier",
  "updated_count": 3
}
```

### Option 3: Mark Multiple Selected Commissions (NEW)
**POST** `/api/sales/mark-multiple-commissions-paid`

```dart
Future<void> _markMultipleCommissionsPaid(List<int> saleIds) async {
  final response = await http.post(
    Uri.parse('$baseUrl/sales/mark-multiple-commissions-paid'),
    headers: {
      'Authorization': 'Bearer $token',
      'Content-Type': 'application/json',
    },
    body: json.encode({
      'sale_ids': saleIds,
    }),
  );
  
  if (response.statusCode == 200) {
    final data = json.decode(response.body);
    print('Marked ${data['updated_count']} commissions as paid');
    
    // Refresh dashboard data
    _loadDashboard();
  }
}
```

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

---

## Complete User Flow Examples

### Scenario 1: User marks individual product commission as paid

1. User views dashboard
2. Sees "Unpaid Commissions" section (because `has_unpaid: true`)
3. Expands "Tech Supplies Inc" supplier
4. Clicks "Mark Paid" button next to "Laptop Computer"
5. Backend updates that sale's `commission_paid` to `true`
6. Frontend refreshes dashboard
7. "Laptop Computer" disappears from the list
8. If it was the last product for that supplier, the supplier disappears
9. If it was the last unpaid commission overall:
   - `has_unpaid` becomes `false`
   - Entire "Unpaid Commissions" section disappears ✅

### Scenario 2: User marks all commissions for a supplier as paid

1. User views dashboard with multiple suppliers
2. Clicks "Mark All Paid" button for "Tech Supplies Inc"
3. Backend marks ALL unpaid sales for that supplier as paid
4. Frontend refreshes dashboard
5. "Tech Supplies Inc" completely disappears from unpaid list
6. Other suppliers with unpaid commissions remain visible
7. If it was the last supplier, entire section disappears

### Scenario 3: No unpaid commissions

1. All commissions have been marked as paid
2. Dashboard API returns `has_unpaid: false`
3. Frontend doesn't render the unpaid commission section at all
4. Dashboard only shows overview cards and recent activity ✅

---

## Testing the Unpaid Commission Flow

### Step 1: Create Sales with Commissions

```bash
# Create Sale 1
curl -X POST http://localhost:8000/api/sales \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "product_name": "Laptop",
    "price": 1000,
    "quantity": 1,
    "commission": 100,
    "supplier_id": 1,
    "commission_paid": false,
    "sale_date": "2025-10-17"
  }'

# Create Sale 2
curl -X POST http://localhost:8000/api/sales \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "product_name": "Mouse",
    "price": 50,
    "quantity": 2,
    "commission": 10,
    "supplier_id": 1,
    "commission_paid": false,
    "sale_date": "2025-10-17"
  }'
```

### Step 2: View Dashboard with Unpaid Commissions

```bash
curl -X GET http://localhost:8000/api/dashboard \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Expected Response:**
- `has_unpaid: true`
- `total_unpaid: 110`
- List shows supplier with 2 products

### Step 3: Mark One Commission as Paid

```bash
curl -X PATCH http://localhost:8000/api/sales/1/mark-commission-paid \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Step 4: View Updated Dashboard

```bash
curl -X GET http://localhost:8000/api/dashboard \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Expected Response:**
- `has_unpaid: true` (still have one unpaid)
- `total_unpaid: 10` (only Mouse remaining)
- List shows supplier with 1 product (Laptop gone)

### Step 5: Mark Remaining Commission as Paid

```bash
curl -X PATCH http://localhost:8000/api/sales/2/mark-commission-paid \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Step 6: View Final Dashboard

```bash
curl -X GET http://localhost:8000/api/dashboard \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Expected Response:**
- `has_unpaid: false` ✅
- `total_unpaid: 0`
- `list: []` (empty array)
- **Frontend should hide the entire section!**

---

## Key Features Summary

✅ **Conditional Display**: Section only appears when `has_unpaid: true`

✅ **Grouped by Supplier**: Easy to see which supplier owes what

✅ **Product-Level Details**: Shows each individual product with commission

✅ **Multiple Ways to Mark Paid**:
  - Single product at a time
  - All products for a supplier
  - Multiple selected products

✅ **Real-time Updates**: Data refreshes after marking as paid

✅ **Handles Edge Cases**:
  - Sales without suppliers (shows as "No Supplier")
  - Empty unpaid list (hides section)
  - Month filtering (overview data is monthly, unpaid is all-time)

✅ **User-Friendly**: Clear totals, organized by supplier, easy actions

---

## Best Practices

### 1. Auto-refresh After Actions
```dart
Future<void> _markCommissionPaid(int saleId) async {
  try {
    await apiService.markCommissionPaid(saleId);
    
    // Show success message
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text('Commission marked as paid')),
    );
    
    // Refresh dashboard
    await _loadDashboard();
  } catch (e) {
    // Show error message
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text('Error: $e')),
    );
  }
}
```

### 2. Confirmation for Bulk Actions
```dart
Future<void> _markSupplierCommissionsPaid(int supplierId, int count) async {
  final confirmed = await showDialog<bool>(
    context: context,
    builder: (context) => AlertDialog(
      title: Text('Confirm Payment'),
      content: Text('Mark $count commission(s) as paid?'),
      actions: [
        TextButton(
          onPressed: () => Navigator.pop(context, false),
          child: Text('Cancel'),
        ),
        ElevatedButton(
          onPressed: () => Navigator.pop(context, true),
          child: Text('Confirm'),
        ),
      ],
    ),
  );
  
  if (confirmed == true) {
    await apiService.markSupplierCommissionsPaid(supplierId);
    await _loadDashboard();
  }
}
```

### 3. Loading States
```dart
bool _isMarkingPaid = false;

Future<void> _markCommissionPaid(int saleId) async {
  setState(() => _isMarkingPaid = true);
  
  try {
    await apiService.markCommissionPaid(saleId);
    await _loadDashboard();
  } finally {
    setState(() => _isMarkingPaid = false);
  }
}
```

---

## Conclusion

The unpaid commission section on the dashboard is now fully functional with:

1. ✅ Conditional display based on unpaid status
2. ✅ Grouping by supplier
3. ✅ Product-level details
4. ✅ Multiple marking options (single, bulk, by supplier)
5. ✅ Automatic updates when commissions are marked as paid
6. ✅ Proper handling of edge cases

Users can now easily track and manage their unpaid commissions, and the section will automatically disappear when all commissions have been paid!

