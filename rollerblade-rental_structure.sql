CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- INSERT INTO roles (name) VALUES 
-- ('admin'),
-- ('moderator'),
-- ('user');

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE user_profiles (
    id INT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL REFERENCES users(id),
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    email VARCHAR(100) NOT NULL UNIQUE,
    phone_number VARCHAR(15)
);

CREATE TABLE brands (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE models (
    id SERIAL PRIMARY KEY,
    brand_id INT NOT NULL REFERENCES brands(id),
    name VARCHAR(50) NOT NULL UNIQUE,
    release_date DATE,
    description TEXT
);

CREATE TABLE colors (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    hex VARCHAR(6) NOT NULL CHECK (LENGTH(hex) = 6), -- Enforce 6 characters
    hex_with_hash VARCHAR(7) GENERATED ALWAYS AS ('#' || hex) STORED -- Generated column with '#' prefix
);

CREATE TABLE wheel_sizes (
    id SERIAL PRIMARY KEY,
    size INT NOT NULL UNIQUE
);

CREATE TABLE model_versions (
    id SERIAL PRIMARY KEY,
    model_id INT NOT NULL REFERENCES models(id),
    color_id INT NOT NULL REFERENCES colors(id),
    wheel_size_id INT NOT NULL REFERENCES wheel_sizes(id),
    purchase_link VARCHAR(200)
);

CREATE TABLE photos (
    id SERIAL PRIMARY KEY,
    model_version_id INT NOT NULL REFERENCES model_versions(id),
    url TEXT NOT NULL
);

CREATE TABLE sizes (
    id SERIAL PRIMARY KEY,
    name VARCHAR(10) NOT NULL UNIQUE
);

CREATE TABLE rollerblades (
    id SERIAL PRIMARY KEY,
    model_version_id INT NOT NULL REFERENCES model_versions(id),
    size_id INT NOT NULL REFERENCES sizes(id),
    hourly_rate DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL
);

CREATE TABLE rental_statuses (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- INSERT INTO rental_statuses (name) VALUES 
-- ('submitted'),
-- ('confirmed'),
-- ('ready for pickup'),
-- ('in progress'),
-- ('to be returned'),
-- ('canceled'),
-- ('completed');

CREATE TABLE rentals (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES users(id),
    rollerblade_id INT NOT NULL REFERENCES rollerblades(id),
    start_date TIMESTAMP NOT NULL,
    end_date TIMESTAMP NOT NULL,
    status_id INT NOT NULL REFERENCES rental_statuses(id),
    notes TEXT
);

CREATE TABLE homepage_ratings (
    id SERIAL PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    icon TEXT,
    user_name VARCHAR(100) NOT NULL,
    website_name VARCHAR(50) NOT NULL
);
