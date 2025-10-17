# SalesPulse Backend API

A comprehensive Laravel REST API backend for the SalesPulse mobile application, providing sales tracking, expense management, dashboard analytics, and user management features.

## Features

- **Authentication System**
  - Email/Phone number login
  - JWT token-based authentication using Laravel Sanctum
  - Password management
  - Account deletion

- **Sales Management**
  - Create, read, update, delete sales records
  - Track product name, price, quantity, commission
  - Supplier management and relationships
  - Commission tracking (paid/unpaid status)
  - Date-based filtering and reporting

- **Expense Management**
  - Create, read, update, delete expense records
  - Track expense title, amount, description, and date
  - Monthly and date range filtering

- **Dashboard Analytics**
  - Monthly overview (total sales, expenses, products, commission)
  - Unpaid commission tracking by supplier
  - Sales and expense history
  - Monthly statistics and trends

- **User Profile & Settings**
  - Profile management (name, email, phone, avatar)
  - Theme preferences (light/dark)
  - Custom user settings storage
  - Premium subscription support

- **Data Export**
  - Excel export functionality (Premium feature)
  - Date range selection
  - Sales and expenses data export

- **Supplier Management**
  - Create and manage supplier information
  - Track supplier contact details
  - Link suppliers to sales records

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL/MariaDB
- Node.js & NPM (for asset compilation)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd salespulse-backend
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   Update your `.env` file with database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=salespulse_backend
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Create storage link**
   ```bash
   php artisan storage:link
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

## API Documentation

### Base URL
```
http://localhost:8000/api
```

### Authentication

#### Register
```http
POST /register
Content-Type: application/json

{
    "first_name": "John",
    "last_name": "Doe", 
    "email": "john@example.com",
    "phone_number": "+1234567890",
    "password": "password123",
    "password_confirmation": "password123"
}
```

#### Login
```http
POST /login
Content-Type: application/json

{
    "login": "john@example.com", // or phone number
    "password": "password123"
}
```

#### Logout
```http
POST /logout
Authorization: Bearer {token}
```

### Sales Management

#### Get Sales
```http
GET /sales?month=10&year=2024&supplier_id=1&commission_paid=false
Authorization: Bearer {token}
```

#### Create Sale
```http
POST /sales
Authorization: Bearer {token}
Content-Type: application/json

{
    "product_name": "Product Name",
    "price": 100.00,
    "quantity": 2,
    "commission": 10.00,
    "supplier_id": 1,
    "feedback": "Great product",
    "commission_paid": false,
    "sale_date": "2024-10-15"
}
```

#### Update Sale
```http
PUT /sales/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "commission_paid": true
}
```

#### Mark Commission as Paid
```http
PATCH /sales/{id}/mark-commission-paid
Authorization: Bearer {token}
```

### Expenses Management

#### Get Expenses
```http
GET /expenses?month=10&year=2024
Authorization: Bearer {token}
```

#### Create Expense
```http
POST /expenses
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Office Supplies",
    "amount": 50.00,
    "description": "Pens and paper",
    "expense_date": "2024-10-15"
}
```

### Dashboard Analytics

#### Get Overview
```http
GET /dashboard/overview?month=10&year=2024
Authorization: Bearer {token}
```

#### Get Unpaid Commissions
```http
GET /dashboard/unpaid-commissions
Authorization: Bearer {token}
```

#### Get Sales/Expense History
```http
GET /dashboard/history?month=10&year=2024&type=both
Authorization: Bearer {token}
```

#### Get Monthly Statistics
```http
GET /dashboard/monthly-stats?year=2024
Authorization: Bearer {token}
```

### User Profile & Settings

#### Get Profile
```http
GET /profile
Authorization: Bearer {token}
```

#### Update Profile
```http
PUT /profile
Authorization: Bearer {token}
Content-Type: application/json

{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone_number": "+1234567890",
    "theme": "dark"
}
```

#### Upload Avatar
```http
POST /profile/avatar
Authorization: Bearer {token}
Content-Type: multipart/form-data

avatar: [image file]
```

#### Export Data (Premium Feature)
```http
POST /export-data
Authorization: Bearer {token}
Content-Type: application/json

{
    "start_date": "2024-01-01",
    "end_date": "2024-12-31",
    "include_sales": true,
    "include_expenses": true
}
```

### Suppliers Management

#### Get Suppliers
```http
GET /suppliers?active=true
Authorization: Bearer {token}
```

#### Create Supplier
```http
POST /suppliers
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Supplier Name",
    "email": "supplier@example.com",
    "phone": "+1234567890",
    "address": "123 Main St",
    "notes": "Reliable supplier",
    "is_active": true
}
```

## Database Schema

### Users Table
- `id` - Primary key
- `name` - Full name
- `first_name` - First name
- `last_name` - Last name
- `email` - Email address (unique)
- `phone_number` - Phone number (unique)
- `password` - Hashed password
- `avatar` - Avatar filename
- `two_factor_enabled` - 2FA status
- `is_premium` - Premium subscription status
- `premium_expires_at` - Premium expiration date
- `theme` - UI theme preference
- `is_active` - Account status

### Sales Table
- `id` - Primary key
- `user_id` - Foreign key to users
- `supplier_id` - Foreign key to suppliers
- `product_name` - Product name
- `price` - Unit price
- `quantity` - Quantity sold
- `commission` - Commission amount
- `feedback` - Customer feedback
- `commission_paid` - Payment status
- `sale_date` - Sale date

### Expenses Table
- `id` - Primary key
- `user_id` - Foreign key to users
- `title` - Expense title
- `amount` - Expense amount
- `description` - Expense description
- `expense_date` - Expense date

### Suppliers Table
- `id` - Primary key
- `user_id` - Foreign key to users
- `name` - Supplier name
- `email` - Supplier email
- `phone` - Supplier phone
- `address` - Supplier address
- `notes` - Additional notes
- `is_active` - Active status

## Response Format

All API responses follow this format:

### Success Response
```json
{
    "success": true,
    "message": "Operation completed successfully",
    "data": {
        // Response data
    }
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        // Validation errors (if applicable)
    }
}
```

## Security Features

- **Authentication**: Laravel Sanctum for API token authentication
- **Authorization**: User-based data access control
- **Validation**: Comprehensive input validation
- **Password Hashing**: Bcrypt password hashing
- **File Upload Security**: Image validation and secure storage
- **SQL Injection Protection**: Eloquent ORM protection

## Premium Features

- Data export to Excel
- Advanced analytics (future feature)
- Extended data retention (future feature)

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint
```

### Database Seeding
```bash
php artisan db:seed
```

## Deployment

1. Set up production environment variables
2. Run migrations: `php artisan migrate --force`
3. Optimize application: `php artisan optimize`
4. Set up proper file permissions
5. Configure web server (Apache/Nginx)

## Support

For support and questions, please contact the development team or create an issue in the repository.

## License

This project is proprietary software. All rights reserved.
