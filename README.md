# 💰 Finance Management System

A modern **Laravel 9** web application for managing **Sales Invoices, Purchase Invoices, Budgets, and Expenses** with role-based authentication, financial reports, and an intuitive Bootstrap 5 interface.

---

## 📌 Features

### 🔐 Authentication & Authorization

* Admin and User Login
* Role-Based Access Control
* Secure Authentication

### 📄 Invoice Management

* Create Sales Invoices
* Create Purchase Invoices
* Edit & Delete Invoices
* Track Invoice Payments
* Automatic Status Updates

  * Pending
  * Paid
  * Overdue

### 💸 Budget Management

* Create Department Budgets
* Expense Tracking
* Budget Progress Bar
* Remaining Budget Calculation

### 📊 Dashboard

* Total Sales
* Total Purchases
* Total Receivables
* Total Payables
* Budget Overview
* Recent Transactions

### 📈 Reports & Analytics

* Interactive Charts (Chart.js)
* Monthly Sales Report
* Monthly Purchase Report
* Budget Analysis

### 🌐 Currency Exchange

* Live Currency Exchange Rates
* Financial Overview

### 🎨 User Interface

* Responsive Bootstrap 5 Design
* Mobile Friendly
* Modern Dashboard
* Clean Navigation

---

# 🛠 Technology Stack

| Technology | Version    |
| ---------- | ---------- |
| PHP        | 8.0+       |
| Laravel    | 9.x        |
| MySQL      | 8+         |
| Bootstrap  | 5          |
| JavaScript | Vanilla JS |
| Chart.js   | Latest     |

---

# 📂 Project Structure

```
Finance-Management/
│
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── artisan
├── composer.json
└── README.md
```

---

# ⚙ Requirements

* PHP 8.0 or Higher
* Composer
* MySQL
* XAMPP / WAMP / Laragon
* Git

---

# 🚀 Installation

## 1. Clone Repository

```bash
git clone https://github.com/parthasarathy27/Finance-Management.git
```

---

## 2. Go to Project

```bash
cd Finance-Management
```

---

## 3. Install Dependencies

```bash
composer install
```

---

## 4. Copy Environment File

```bash
copy .env.example .env
```

or

```bash
cp .env.example .env
```

---

## 5. Generate Application Key

```bash
php artisan key:generate
```

---

## 6. Create Database

Open **phpMyAdmin**

Create a database named

```
invoice_budget_system
```

---

## 7. Configure `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=invoice_budget_system
DB_USERNAME=root
DB_PASSWORD=
```

---

## 8. Run Migration & Seed

```bash
php artisan migrate:fresh --seed
```

---

## 9. Start Server

```bash
php artisan serve
```

or access through XAMPP

```
http://localhost/Invoic_Budget_Manage_Sys/public
```

---

# 🔑 Default Login Accounts

## 👨‍💼 Admin

**Email**

```
admin@example.com
```

**Password**

```
password
```

---

## 👤 User

**Email**

```
user@example.com
```

**Password**

```
password
```

---

# 📷 Application Modules

* Dashboard
* Sales Invoice
* Purchase Invoice
* Budget Management
* Expense Tracking
* Reports
* Currency Exchange
* User Management
* Profile Management

---

# 📊 Dashboard Highlights

* Total Sales
* Total Purchases
* Pending Payments
* Overdue Invoices
* Budget Utilization
* Monthly Analytics
* Charts & Reports

---

# 🔒 User Roles

### Admin

* Full Dashboard Access
* Manage Users
* Create/Edit/Delete Invoices
* Manage Budgets
* View Reports
* System Administration

### User

* View Dashboard
* View Invoices
* View Budgets
* View Reports

---

# 📱 Responsive Design

* Desktop
* Laptop
* Tablet
* Mobile

---

# 📦 Future Improvements

* PDF Invoice Export
* Email Notifications
* REST API
* Multi-Currency Support
* Dark Mode
* Excel Import & Export
* Backup & Restore

---

# 🤝 Contributing

1. Fork the repository
2. Create a feature branch

```bash
git checkout -b feature-name
```

3. Commit your changes

```bash
git commit -m "Add new feature"
```

4. Push the branch

```bash
git push origin feature-name
```

5. Open a Pull Request

---

⭐ If you like this project, don't forget to **Star** the repository!
