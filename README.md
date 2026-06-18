# 7X Pharma Nexus

> **A Custom PHP MVC Framework & Application for Pharmacy Management.**  
> Developed and Maintained by **SOULAYMAN 7X**.

## 🚀 About The Project
**7X Pharma Nexus** is a web framework and application built with PHP using a custom Model-View-Controller (MVC) architecture from scratch. This project aims to provide a robust and flexible foundation for pharmacy management and medical systems.

## 📂 Folder Structure
The project is well-organized to ensure easy development and maintenance:
- `app/`: Contains the core source code of the project, including controllers (`controllers/`), models (`models/`), system configurations (`config/`), and core routing files (`core/`).
- `database/`: Dedicated to database files, dumps, and core schemas.
- `logs/`: Used for logging system errors and application events.
- `public/`: The document root open to the public, containing the entry point `index.php` and front-end assets (CSS, JS, Images) inside the `assets/` directory.
- `views/`: Contains the UI presentation templates (Pages & Components).

## ⚙️ Prerequisites
To run this project on your local environment, you will need:
- **PHP** (Version 8.0 or higher is recommended).
- **MySQL / MariaDB** for database management.
- A Web Server like **Apache** (XAMPP or Laragon is recommended).

## 🛠️ Installation
1. **Clone the repository** into your `htdocs` directory (if you are using XAMPP):
   ```bash
   git clone <repository_url> 7X_Pharma_Core
   ```
2. **Database Setup**: Create a new database and import the necessary tables (if available in the `database/` folder).
3. **Configuration**:
   - Open `app/config/Constants.php` and make sure the `BASE_URL` is correctly set to match your project folder path:
     ```php
     define('BASE_URL', 'http://localhost/7X_Pharma_Core/public');
     ```
   - Open `app/config/Database.php` and update the database connection credentials (username, password, database name) to match your environment.
4. **Run**: Open your browser and navigate to the following URL to start using the system:  
   [http://localhost/7X_Pharma_Core/public/](http://localhost/7X_Pharma_Core/public/)

## 📜 License
This project is licensed under the **MIT License**.  
Copyright (c) 2026 **SOULAYMAN 7X**. See the [LICENSE](LICENSE) file for more details.
