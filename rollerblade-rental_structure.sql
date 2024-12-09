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
    role_id INT NOT NULL REFERENCES roles(id),
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE user_profiles (
    id SERIAL PRIMARY KEY,
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

-- view for all rollerblade for list

CREATE OR REPLACE VIEW rollerblades_detailed AS
SELECT
	distinct on (p.model_version_id, s.id)
    rb.id AS rollerblade_id,
    CONCAT_WS(' ', b.name, m.name, ws.size::TEXT, c.name) AS rollerblade_name,
    rb.quantity,
    rb.hourly_rate,
    p.url AS photo_url,
	s.name AS size
FROM
    rollerblades rb
JOIN
    model_versions mv ON rb.model_version_id = mv.id
JOIN
    models m ON mv.model_id = m.id
JOIN
    brands b ON m.brand_id = b.id
JOIN
    colors c ON mv.color_id = c.id
JOIN
    wheel_sizes ws ON mv.wheel_size_id = ws.id
JOIN
    sizes s ON rb.size_id = s.id
join
	photos p on mv.id = p.model_version_id;


-- function like parametrized view for finding free rollerblades in specific time range

CREATE OR REPLACE FUNCTION all_available_rollerblades_between_dates(start_date TIMESTAMP, end_date TIMESTAMP)
  returns table (rolerblade_id INT, rollerblade_name TEXT, quantity INT, hourly_rate DECIMAL(10, 2), photo_url TEXT, size TEXT)
  language sql as
$func$
  select * from rollerblades_detailed r where quantity > (
select count(*) from rentals where 
rollerblade_id = r.rollerblade_id and 
end_date >= $1 AND 
start_date <= $2 and 
status_id not IN (select id from rental_statuses where name = 'canceled'))
$func$;

-- view for all rentals for user

CREATE OR REPLACE VIEW rental_details AS
SELECT
    r.id AS rental_id,
    u.username AS user_name,
    rs.name AS rental_status,
    r.start_date,
    r.end_date,
    rb_d.rollerblade_name,
    s.name AS rollerblade_size,
    r.notes
FROM
    rentals r
JOIN
    users u ON r.user_id = u.id
JOIN
    rental_statuses rs ON r.status_id = rs.id
JOIN
    rollerblades rb ON r.rollerblade_id = rb.id
JOIN
    sizes s ON rb.size_id = s.id
JOIN
    rollerblades_detailed rb_d ON rb.id = rb_d.rollerblade_id;

-- trigger for validating rental dates
CREATE OR REPLACE FUNCTION validate_rental_dates()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.end_date <= NEW.start_date THEN
        RAISE EXCEPTION 'end_date must be after start_date';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER validate_rental_dates_trigger
BEFORE INSERT OR UPDATE ON rentals
FOR EACH ROW
EXECUTE FUNCTION validate_rental_dates();

-- trigger that delete also user_profiles on user delete
CREATE OR REPLACE FUNCTION delete_user_profile()
RETURNS TRIGGER AS $$
BEGIN
    DELETE FROM user_profiles
    WHERE user_id = OLD.id;

    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER delete_user_profile_trigger
AFTER DELETE ON users
FOR EACH ROW
EXECUTE FUNCTION delete_user_profile();