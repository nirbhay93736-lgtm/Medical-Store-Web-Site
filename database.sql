CREATE DATABASE IF NOT EXISTS medistore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE medistore;

CREATE TABLE IF NOT EXISTS suppliers (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(160) NOT NULL,
 contact_person VARCHAR(120) NULL,
 phone VARCHAR(40) NULL,
 email VARCHAR(160) NULL,
 address VARCHAR(255) NULL,
 gstin VARCHAR(30) NULL,
 active TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS medicines (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(160) NOT NULL,
 generic_name VARCHAR(160) NULL,
 category VARCHAR(100) NULL,
 strength VARCHAR(60) NULL,
 unit VARCHAR(30) NOT NULL DEFAULT 'strip',
 price DECIMAL(10,2) NOT NULL DEFAULT 0,
 cost_price DECIMAL(10,2) NOT NULL DEFAULT 0,
 stock_qty INT NOT NULL DEFAULT 0,
 reorder_level INT NOT NULL DEFAULT 10,
 expiry_date DATE NULL,
 supplier_id INT UNSIGNED NULL,
 active TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_medicine_supplier FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL,
 INDEX idx_medicine_name (name), INDEX idx_medicine_stock (stock_qty, reorder_level)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sales (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 invoice_no VARCHAR(32) NOT NULL UNIQUE,
 customer_name VARCHAR(160) NULL,
 customer_phone VARCHAR(40) NULL,
 subtotal DECIMAL(12,2) NOT NULL,
 discount DECIMAL(12,2) NOT NULL DEFAULT 0,
 tax_rate DECIMAL(5,2) NOT NULL DEFAULT 0,
 tax DECIMAL(12,2) NOT NULL DEFAULT 0,
 total DECIMAL(12,2) NOT NULL,
 payment_method VARCHAR(30) NOT NULL DEFAULT 'Cash',
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_sale_created (created_at)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sale_items (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 sale_id BIGINT UNSIGNED NOT NULL,
 medicine_id INT UNSIGNED NULL,
 medicine_name VARCHAR(160) NOT NULL,
 quantity INT NOT NULL,
 unit_price DECIMAL(10,2) NOT NULL,
 line_total DECIMAL(12,2) NOT NULL,
 CONSTRAINT fk_item_sale FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
 CONSTRAINT fk_item_medicine FOREIGN KEY (medicine_id) REFERENCES medicines(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO suppliers (name, contact_person, phone, email, address) VALUES
('WellSpring Distributors','Anita Rao','+91 98765 43210','orders@wellspring.example','Bengaluru'),
('CarePlus Pharma','Rahul Mehta','+91 91234 56780','sales@careplus.example','Mysuru');
INSERT INTO medicines (name,generic_name,category,strength,unit,price,cost_price,stock_qty,reorder_level,expiry_date,supplier_id) VALUES
('Paracetamol','Paracetamol','Pain relief','500 mg','strip',24.00,16.00,84,20,'2027-08-31',1),
('Vitamin C','Ascorbic acid','Vitamins','500 mg','bottle',115.00,78.00,32,8,'2027-11-30',2),
('Cetirizine','Cetirizine hydrochloride','Allergy','10 mg','strip',32.00,20.00,6,12,'2027-03-31',1),
('Antacid syrup','Aluminium hydroxide','Digestive care','200 ml','bottle',86.00,58.00,18,8,'2027-05-31',2),
('ORS sachet','Oral rehydration salts','Hydration','21 g','sachet',22.00,13.00,3,15,'2027-01-31',1);
