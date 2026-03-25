# BOOKHUB - Student eBook Store

![eBookStore Logo](assets/logo.png) <!-- Replace with actual logo path if exists -->

**BOOKHUB** is a comprehensive web-based platform designed for students to browse, purchase, and download academic eBooks. The system features a secure user authentication system, an administrative dashboard for book and user management, and a robust checkout process with automated PDF invoicing.

---

## 🚀 Features

### **For Students**
- **User Authentication**: Secure login and registration with email verification.
- **Book Discovery**: Browse books by category with a clean, responsive UI.
- **Shopping Cart**: Add books to a persistent cart and manage selections.
- **Secure Checkout**: Purchase books and receive automated PDF receipts/invoices via email.
- **Purchase History**: View and download previously purchased eBooks from your account.

### **For Administrators**
- **Dashboard Overview**: Statistical summary of users and book sales.
- **Book Management**: Upload new eBooks (PDF), edit details, or delete listings.
- **User Management**: Monitor registered users and manage account statuses.
- **Automated Reporting**: Generate and download comprehensive system reports in PDF format.

---

## 🛠️ Technology Stack

- **Backend**: PHP 8.x
- **Database**: MySQL (PDO for secure queries)
- **Frontend**: HTML5, Vanilla CSS3, JavaScript
- **Libraries**:
  - `vlucas/phpdotenv`: Environment variable management.
  - `PHPMailer/PHPMailer`: Secure SMTP email handling.
  - `tecnickcom/tcpdf`: PDF generation for reports and invoices.
- **Composer**: PHP dependency management.

---

## 🔒 Security Features

This project utilizes modern security best practices:
- **Environment Variables**: Sensitive data (DB credentials, SMTP passwords) are stored in a `.env` file, excluded from version control.
- **Prepared Statements**: Protection against SQL Injection.
- **Email Verification**: Ensures users register with valid email addresses.
- **Password Hashing**: Secure storage of user credentials using `password_hash()`.

---

## ⚙️ Installation & Setup

### **Prerequisites**
- XAMPP / WAMP / LAMP or any PHP/MySQL environment.
- [Composer](https://getcomposer.org/) installed.

### **Setup Steps**
## Requirements

- PHP >= 7.4
- MySQL / MariaDB
- Composer
- XAMPP or similar local server

## Setup Instructions

1. **Clone the repository:**
   ```bash
   git clone https://github.com/MuliKituku/BOOKHUB.git
   cd BOOKHUB
   ```

2. **Install Dependencies**:
   ```bash
   composer install
   ```

3. **Database Configuration**:
   - Create a database named `ebookstore` in your MySQL server.
   - Import the provided SQL schema (if available, e.g., `ebookstore.sql`).

## Importing the Database

1. Open phpMyAdmin.
2. Create a new database named `ebookstore`.
3. Go to the Import tab.
4. Upload the `ebookstore.sql` file from the repository.
5. Click Go to import.

4. **Environment Setup**:
   - Copy `.env.example` to a new file named `.env`:
     ```bash
     cp .env.example .env
     ```
   - Update the `.env` file with your local database and SMTP (Gmail) credentials.

5. **Run the application**:
   - Move the project to your `htdocs` (XAMPP) or `www` folder.
   - Access the site at `http://localhost/BOOKHUB`.

---

##  Contributing
Contributions are welcome! Please open an issue or submit a pull request for any improvements.