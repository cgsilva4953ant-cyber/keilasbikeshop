-- Keila's Bike Shop - Complete Database Schema
-- This will drop and recreate the entire database

-- Drop and recreate database
DROP DATABASE IF EXISTS keilas_db;
CREATE DATABASE keilas_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE keilas_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(50),
  address TEXT,
  is_admin TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products/Bikes table
CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  category ENUM('mountain', 'road', 'hybrid', 'electric', 'kids', 'bmx') NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  description TEXT,
  specs TEXT,
  image VARCHAR(255),
  image_gallery TEXT, -- JSON array of additional images
  stock INT DEFAULT 0,
  featured TINYINT(1) DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_category (category),
  INDEX idx_featured (featured),
  INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  order_number VARCHAR(50) NOT NULL UNIQUE,
  total_amount DECIMAL(10, 2) NOT NULL,
  status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
  shipping_name VARCHAR(255) NOT NULL,
  shipping_email VARCHAR(255) NOT NULL,
  shipping_phone VARCHAR(50),
  shipping_address TEXT NOT NULL,
  payment_method VARCHAR(50) DEFAULT 'cod',
  payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user (user_id),
  INDEX idx_status (status),
  INDEX idx_order_number (order_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Order items table
CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  product_name VARCHAR(255) NOT NULL,
  product_price DECIMAL(10, 2) NOT NULL,
  quantity INT NOT NULL,
  subtotal DECIMAL(10, 2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT,
  INDEX idx_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contact inquiries table
CREATE TABLE IF NOT EXISTS inquiries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  subject VARCHAR(255),
  message TEXT NOT NULL,
  status ENUM('new', 'read', 'replied') DEFAULT 'new',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Product reviews table (optional - for future)
CREATE TABLE IF NOT EXISTS reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  user_id INT NOT NULL,
  rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
  review_text TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample products

INSERT INTO products (name, slug, category, price, description, specs, image, stock, featured) VALUES
('Trek Mountain X Pro', 'trek-mountain-x-pro', 'mountain', 68000.00, 'Professional mountain bike built for extreme terrain and durability.', 'Frame: Aluminum Alloy 6061 T6\nBrakes: Hydraulic disc brakes\nWheels: 29" double-wall rims\nGears: 21-speed Shimano', 'bike2.jpg', 15, 1),
('Speedster Road Elite', 'speedster-road-elite', 'road', 45000.00, 'Lightweight road bike designed for speed and long-distance comfort.', 'Frame: Carbon fiber composite\nBrakes: Alloy C-brakes\nWheels: 700c racing wheels\nGears: 18-speed Shimano', 'bike3.jpg', 10, 1),
('Urban Hybrid Commuter', 'urban-hybrid-commuter', 'hybrid', 32000.00, 'Perfect city bike combining comfort and performance for daily commuting.', 'Frame: Steel frame with comfort geometry\nBrakes: V-brakes front and rear\nWheels: 700c hybrid tires\nGears: 7-speed', 'bike4.jpg', 20, 0),
('Alpine Peak 3000', 'alpine-peak-3000', 'mountain', 85000.00, 'Premium mountain bike with advanced suspension and components.', 'Frame: Full carbon fiber\nSuspension: 120mm travel front and rear\nBrakes: Shimano hydraulic disc\nWheels: 27.5" tubeless ready', 'bike2.jpg', 8, 1),
('Lightning Bolt Road', 'lightning-bolt-road', 'road', 55000.00, 'Aerodynamic road bike for competitive cyclists and enthusiasts.', 'Frame: Aero carbon layup\nBrakes: Dual-pivot caliper\nWheels: Deep-section 50mm\nGears: 22-speed Shimano 105', 'bike3.jpg', 12, 0),
('E-Rider Electric', 'e-rider-electric', 'electric', 95000.00, 'Electric-assist bike with powerful motor and long-range battery.', 'Motor: 250W mid-drive\nBattery: 48V 14Ah (60km range)\nBrakes: Hydraulic disc\nDisplay: LCD with speed/battery', 'bike1.jpg', 6, 1),
('Kids Explorer', 'kids-explorer', 'kids', 12000.00, 'Safe and fun bike designed specifically for young riders.', 'Frame: Lightweight aluminum\nWheels: 20" with training wheels\nBrakes: Coaster brake + hand brake\nColors: Multiple options', 'bike4.jpg', 25, 0),
('BMX Street Pro', 'bmx-street-pro', 'bmx', 18000.00, 'Durable BMX bike built for tricks, jumps, and street riding.', 'Frame: CrMo steel\nWheels: 20" with pegs\nBrakes: U-brake rear\nHandlebars: 4-piece bars', 'bike2.jpg', 15, 0);

-- Insert admin user (password: admin123)
INSERT INTO users (name, email, password, is_admin) VALUES
('Admin', 'admin@keilasbikes.com', 'admin123', 1);

-- Insert sample regular user (password: user123)
INSERT INTO users (name, email, password, is_admin) VALUES
('Juan Dela Cruz', 'juan@example.com', 'user123', 0);
