CREATE TABLE IF NOT EXISTS perishable_products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    batch_number VARCHAR(100),
    storage_temp VARCHAR(100),
    shelf_life_days INT,
    status VARCHAR(100),
    added_date DATE
);

CREATE TABLE IF NOT EXISTS post_harvest_monitor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    crop_name VARCHAR(255) NOT NULL,
    moisture_level VARCHAR(100),
    temperature VARCHAR(100),
    visual_inspection VARCHAR(255),
    inspection_date DATE,
    inspector VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS storage_conditions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    facility_name VARCHAR(255) NOT NULL,
    current_temp VARCHAR(100),
    humidity VARCHAR(100),
    ventilation_status VARCHAR(100),
    last_checked DATE
);

CREATE TABLE IF NOT EXISTS tracking_of_products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255) NOT NULL,
    current_location VARCHAR(255),
    destination VARCHAR(255),
    transit_status VARCHAR(100),
    dispatch_date DATE
);
