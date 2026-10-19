BARANGAY HEALTH CENTER RECORDS SYSTEM - FOLDER GUIDE

This package preserves your current PHP pages and assets while organizing them
into clear folders. Empty files/folders are not automatically errors; you can
fill them as you build each feature.

ROOT PAGES
- index.php             Public homepage
- about.php              About page
- services.php           Services page
- contact.php            Contact page
- login.php              Login form
- register.php           Registration form
- forgot_password.php    Password recovery form
- logout.php             Logout entry page
- dashboard.php          Staff/admin dashboard page
- checkup.php             Checkup page

classes/
- Patient.php            Patient-related class (fill with class logic)
- MedicalRecord.php      Medical-record class
- Consultation.php       Consultation class
- Medicine.php           Medicine/inventory class
- person.php              Base person class (currently needs PHP syntax correction)

components/
- header.php              Shared head/meta/CSS link
- navbar.php              Public navigation
- navbar2.php             Logged-in navigation
- sidebar.php             Dashboard sidebar
- footer.php              Shared footer

config/
- database.php            Central database connection settings

design/
- style.css               Main stylesheet

logo/
- Image/logo files used by the pages

modules/
- patients.php            Patient management page/module
- medical_records.php     Medical-record page/module
- consultations.php       Consultation page/module
- medicine_inventory.php  Medicine inventory page/module

process/
- login_process.php             Handles login form submission
- logout_process.php            Handles logout/session cleanup
- forgot_password_process.php   Handles password recovery submission

IMPORTANT
1. This is a folder-organization package, not a fully working application.
2. Several files in the original project are empty or placeholders by design.
3. database.php, login_process.php, logout_process.php, and dashboard.php still
   need implementation/testing before login works.
4. Do not use real patient data until authentication, access controls, and data
   protection have been implemented and tested.
5. To run in XAMPP, place the healthsystem folder inside htdocs and open:
   http://localhost/healthsystem/
