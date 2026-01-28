# Inventory & Stock Tracking System

A modern, professional inventory management application designed for shops and warehouses. This system provides real-time stock monitoring, supplier management, and administrative control over user accounts.

## 🚀 Features

- **Inventory Dashboard**: Real-time overview of products with low-stock alerts.
- **Product Management**: Create, edit, and delete products with price and threshold tracking.
- **Supplier Management**: Maintain a central list of suppliers and link them to products.
- **Advanced Search**: Filter products by supplier, price range, and stock levels.
- **Super Admin Control**: Exclusive user management dashboard for creating and deleting employee accounts.
- **Responsive Design**: Fully optimized for Desktop, Tablet, and Mobile devices.
- **Modern Aesthetic**: Sharp 2px border-radius design language with a professional blue color palette.

## 🛠️ Tech Stack

- **Backend**: PHP 8.x
- **Database**: MySQL / MariaDB
- **Frontend**: HTML5, Vanilla CSS3, JavaScript (AJAX)
- **Design System**: Custom professional UI with 2px radius constraints.

## 📦 Local Setup

1. **Prerequisites**:
   - Install **XAMPP** (recommended) or any PHP/MySQL environment.
   - Ensure `Pdo_mysql` and `mysqli` extensions are enabled in PHP.

2. **Database Configuration**:
   - Create a database named `inventory_system`.
   - Import the provided SQL structure (if applicable) or ensure the `users`, `suppliers`, and `products` tables exist.
   - Update `includes/db_connect.php` with your database credentials.

3. **Deployment**:
   - Place the project folder inside your `htdocs` (XAMPP) or web root.
   - Access via `http://localhost/inventory-system`.

## 🔐 Default Superadmin Credentials

| Username | Password | Role |
| :--- | :--- | :--- |
| `admin` | `admin123` | Super Admin |

> [!NOTE]  
> New users cannot register themselves. They must be created by the Super Admin from the **Users** management page.

## 📄 License
This project is for college project of Herald College devloped by Aabid Hasan Batch 2026.
