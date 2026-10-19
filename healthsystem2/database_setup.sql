-- Database Setup for Barangay Health Center Records System
-- Run this in phpMyAdmin or MySQL to create the database and tables

CREATE DATABASE IF NOT EXISTS healthsystem;
USE healthsystem;

-- Users table for authentication
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('admin', 'staff', 'doctor', 'patient', 'visitor', 'guardian') DEFAULT 'staff',
    patient_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE SET NULL
);

-- Patients table
CREATE TABLE IF NOT EXISTS patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    birth_date DATE NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    address TEXT NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    emergency_contact VARCHAR(100) NOT NULL,
    emergency_contact_number VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Medical Records table
CREATE TABLE IF NOT EXISTS medical_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    record_date DATE NOT NULL,
    vital_signs TEXT,
    allergies TEXT,
    chronic_conditions TEXT,
    surgeries TEXT,
    family_history TEXT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
);

-- Consultations table
CREATE TABLE IF NOT EXISTS consultations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    consultation_date DATE NOT NULL,
    chief_complaint TEXT NOT NULL,
    diagnosis TEXT,
    treatment TEXT,
    notes TEXT,
    attending_physician VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
);

-- Medicine Inventory table
CREATE TABLE IF NOT EXISTS medicine_inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    medicine_name VARCHAR(100) NOT NULL,
    generic_name VARCHAR(100),
    category VARCHAR(50) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    unit VARCHAR(20) NOT NULL,
    expiry_date DATE NOT NULL,
    manufacturer VARCHAR(100),
    supplier VARCHAR(100),
    price DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Stock Movement table
CREATE TABLE IF NOT EXISTS stock_movements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    medicine_id INT NOT NULL,
    movement_type ENUM('in', 'out') NOT NULL,
    quantity INT NOT NULL,
    reason TEXT,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (medicine_id) REFERENCES medicine_inventory(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- NOTE: Register users through the registration page (register.php)
-- The system uses password_hash() for secure password storage

-- Insert default admin user (username: admin, password: admin123)
INSERT INTO users (username, password, email, role) VALUES
('admin', '$2y$10$YourActualHashHereForAdmin123', 'admin@healthcenter.com', 'admin');

-- Insert sample patient data
INSERT INTO patients (first_name, last_name, middle_name, birth_date, gender, address, contact_number, emergency_contact, emergency_contact_number) VALUES
('Juan', 'Dela Cruz', 'Santos', '1990-05-15', 'Male', '123 Barangay Street, Handumanan', '09123456789', 'Maria Dela Cruz', '09198765432'),
('Maria', 'Santos', 'Reyes', '1985-08-22', 'Female', '456 Purok 2, Handumanan', '09234567890', 'Pedro Santos', '09345678901'),
('Pedro', 'Reyes', 'Garcia', '1978-12-10', 'Male', '789 Zone 3, Handumanan', '09345678901', 'Ana Reyes', '09456789012');

-- Insert sample medicine data
INSERT INTO medicine_inventory (medicine_name, generic_name, category, quantity, unit, expiry_date, manufacturer, supplier, price) VALUES
('Paracetamol 500mg', 'Paracetamol', 'Pain Relief', 100, 'Tablets', '2025-12-31', 'Pharma Corp', 'MediSupply Inc', 5.50),
('Amoxicillin 500mg', 'Amoxicillin', 'Antibiotics', 50, 'Capsules', '2025-06-30', 'Antibio Labs', 'HealthDist Co', 25.00),
('Multivitamins', 'Multivitamins', 'Vitamins', 75, 'Tablets', '2026-01-15', 'VitaCo', 'NutriSupply', 15.75),
('Cough Syrup', 'Dextromethorphan', 'Respiratory', 30, 'ml', '2025-09-20', 'RespiCare', 'PharmaDist', 45.00);
