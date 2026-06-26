# FinTrack - Invoice & Budget Management System

A simple, standalone Laravel application for managing purchase invoices, sales invoices, budgets, and expenses. Built with a premium, responsive Bootstrap 5 UI and designed to run seamlessly on a local XAMPP environment without requiring Node.js.

## Core Features
- **Role-Based Access**: Separate Admin (full control) and Normal User (view-only) privileges.
- **Invoice Management**: Create, edit, and track payments for Sales and Purchase Invoices with automatic status updates (Pending, Paid, Overdue).
- **Budget Tracking**: Monitor expenses against defined budget limits with real-time visual progress bars.
- **Financial Dashboard**: View total receivables, payables, and live currency exchange rates.
- **Reports & Analytics**: Visual charts using Chart.js for business insights.

## Quick Setup Instructions (XAMPP)

1. **Place the Project**: Ensure the project folder is inside your XAMPP `htdocs` directory (e.g., `C:\xampp\htdocs\Invoic_Budget_Manage_Sys`).
2. **Start XAMPP**: Open the XAMPP Control Panel and Start both **Apache** and **MySQL**.
3. **Database**: 
   - Open your browser and go to `http://localhost/phpmyadmin`
   - Create a new empty database named `invoice_budget_system`.
4. **Run Migrations & Seed**:
   - Open a terminal/command prompt inside the project folder.
   - Run the following command to generate the tables and populate the dummy data:
     ```bash
     php artisan migrate:fresh --seed
     ```

## How to Access the App

Open your web browser and navigate to:
👉 **[http://localhost/Invoic_Budget_Manage_Sys/public/](http://localhost/Invoic_Budget_Manage_Sys/public/)**

### Default Test Accounts

**1. Admin Account (Full Access & User Management):**
- **Email:** `admin@example.com`
- **Password:** `password`

**2. Normal User Account (Restricted Views):**
- **Email:** `user@example.com`
- **Password:** `password`

## Technology Stack
- **Backend:** Laravel 9 (PHP 8.0+)
- **Frontend:** Blade Templates, Vanilla JavaScript, Bootstrap 5 (CDN)
- **Database:** MySQL
