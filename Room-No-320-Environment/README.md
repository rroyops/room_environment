# Room No. 320 Environment Portal

A modern, highly secure, fully responsive, and robust web application designed as an ecological and environmental community research tracker. This codebase is fully engineered for local development inside **XAMPP (PHP 8 + MySQL)** with absolutely zero dependencies, npm commands, or composer installs required.

---

## 🚀 Quick XAMPP Setup Instructions

Follow these five steps to run the application on your local machine instantly:

1. **Extract the ZIP Folder:**
   Extract the generated `Room-No-320-Environment.zip` file.

2. **Move to htdocs:**
   Copy the extracted `Room-No-320-Environment` directory and paste it into your local XAMPP web root directory:
   ```text
   C:\xampp\htdocs\
   ```
   *(Ensure the folder name is exactly `Room-No-320-Environment` to preserve root-path URL aliases)*.

3. **Import the SQL Database:**
   * Open your XAMPP Control Panel and start both **Apache** and **MySQL**.
   * Open your web browser and navigate to **phpMyAdmin**: [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)
   * Click **New** in the sidebar to create a fresh database. Name it exactly: `room320_environment`
   * Select the newly created database, click on the **Import** tab at the top.
   * Click **Choose File** and locate the SQL file inside the codebase:
     `C:\xampp\htdocs\Room-No-320-Environment\database\room320_environment.sql`
   * Click **Import** (or **Go**) at the bottom.

4. **Verify Uploads Permissions:**
   Ensure that the `C:\xampp\htdocs\Room-No-320-Environment\uploads` directory exists and has write permissions. (The system will attempt to create it automatically if missing).

5. **Launch Portal:**
   Open your browser and navigate to:
   👉 [http://localhost/Room-No-320-Environment/](http://localhost/Room-No-320-Environment/)

---

## 🔑 Default Test Accounts

Use these pre-hashed, database-seeded accounts to explore the portals instantly:

### 🛡️ Administrator Account
* **Username:** `admin`
* **Email:** `admin@room320.com`
* **Password:** `admin123`
* **Capabilities:** Full access to Admin Console, edit/delete members, moderate student gallery uploads, broadcast bulletins, log community activities, and view inbox messages.

### 👤 Member Account
* **Username:** `john`
* **Email:** `john@room320.com`
* **Password:** `password123`
* **Capabilities:** View bulletins, manage personal profile bio, share project photographs with the community gallery, and view personal contribution charts.

---

## 🌟 Key Application Features

* **Beautiful Modern Homepage:** Features an elegant Bootstrap 5 carousel slider with custom dark filters, dynamic counts, active bulletins board, and a community search hub.
* **Global Search Core:** Search through members, activities, and announcements simultaneously.
* **Bulletins & Activities:** Full chronological log of research sprints, tree planting events, and gas sensor API announcements.
* **Photo Gallery:** Dynamic categorical gallery where users can upload and catalog research pictures. Includes standard image validations.
* **Dual Dashboards:** Custom bento-styled dashboards for admins and user profiles.
* **Responsive Layout:** 100% responsive across mobile, tablet, and desktop screens with fluid container wrappers.
* **Dark / Light Mode Toggle:** Smooth client-side theme switcher persisting choice through browser storage.
* **Secured Mailbox:** Submit questions and proposals. Admins can manage replies, delete logs, or mark read states.

---

## 🔒 Security Architectures Implemented

* **Prepared SQL Statements (PDO):** Employs strict parameterized statements to make the application 100% immune to SQL Injection (SQLi) attacks.
* **Cryptographic Password Hashing:** User registration employs the standard strong `PASSWORD_BCRYPT` (Bcrypt) hashes.
* **XSS Protection:** Integrates standard HTML entity sanitization filters to prevent Cross-Site Scripting (XSS) in user bio inputs, contact forms, or image titles.
* **CSRF Mitigation:** Employs cryptographically secure pseudo-random tokens (`random_bytes(32)`) in session stores to validate all state-changing POST forms (Login, Register, Upload, Contact forms).
* **Directory Index Hiding:** Integrates `.htaccess` overrides to block direct folder scanning and file listing.
