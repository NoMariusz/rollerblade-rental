INSERT INTO roles (name) VALUES 
('admin'),
('moderator'),
('user');

INSERT INTO users (username, password, role_id) VALUES
('john_doe', 'password123', (SELECT id FROM roles WHERE name = 'user'));

INSERT INTO user_profiles (id, user_id, first_name, last_name, email, phone_number) VALUES
(1, (SELECT id FROM users WHERE username = 'john_doe'), 'John', 'Doe', 'john.doe@example.com', '123-456-789');

INSERT INTO brands (name) VALUES 
('Powerslide'),
('Rollerblade');

INSERT INTO wheel_sizes (size) VALUES 
(110),
(80);

INSERT INTO colors (name, hex) VALUES 
('red', 'FF0000'),
('pink', 'FFC0CB'),
('green', '008000'),
('white', 'FFFFFF');

INSERT INTO models (brand_id, name, release_date, description) VALUES 
(1, 'Next', '2023-05-01', 'Feel the NEXT level of blading'),
(1, 'Imperial', '2023-06-15', 'Pink Freeskate legend'),
(2, 'Lightning', '2023-07-01', 'All in one Rollerblade for all levels of skaters');

INSERT INTO model_versions (model_id, color_id, wheel_size_id, purchase_link) VALUES 
(1, 1, 1, 'https://bladeville.pl/powerslide-next-110-czarno-czerwone.html'), -- Red Powerslide Next 110
(2, 2, 2, 'https://bladeville.pl/powerslide-imperial-80-lollipop.html'), -- Pink Powerslide Imperial 80
(3, 3, 2, 'https://bladeville.pl/rolki-rollerblade-lightning/rollerblade-lightning-czarno-zolte.html'), -- Green Rollerblade Lightning 80
(3, 4, 1, 'https://bladeville.pl/rolki-rollerblade-lightning/rollerblade-lightning-110-bialo-czarne.html'); -- White Rollerblade Lightning 110

INSERT INTO photos (model_version_id, url) VALUES 
(1, 'https://cdn.bladeville.pl/media/catalog/product/cache/1/image/480x480/9df78eab33525d08d6e5fb8d27136e95/9/0/908374_next_black_red_110_2021_view01.jpg'),
(2, 'https://cdn.bladeville.pl/media/catalog/product/cache/1/image/480x480/9df78eab33525d08d6e5fb8d27136e95/9/0/908426-38967_ps_imperial_lollipop_80_2023_view01.jpg'),
(2, 'https://cdn.bladeville.pl/media/catalog/product/cache/1/image/480x480/9df78eab33525d08d6e5fb8d27136e95/9/0/908426-38967_ps_imperial_lollipop_80_2023_view00.jpg'),
(3, 'https://cdn.bladeville.pl/media/catalog/product/cache/1/image/480x480/9df78eab33525d08d6e5fb8d27136e95/0/7/073726001a1_lightning_photo-outside_side_view.jpg'),
(4, 'https://cdn.bladeville.pl/media/catalog/product/cache/1/image/480x480/9df78eab33525d08d6e5fb8d27136e95/0/7/073720002v4_lightning_110_photo-outside_side_view.jpg'),
(4, 'https://cdn.bladeville.pl/media/catalog/product/cache/1/image/480x480/9df78eab33525d08d6e5fb8d27136e95/0/7/073720002v4_lightning_110_photo-primary_angled_view-_1_.jpg');


INSERT INTO sizes (name) VALUES 
('42'),
('39'),
('44');

INSERT INTO rollerblades (model_version_id, size_id, hourly_rate, quantity) VALUES 
(1, 1, 50.00, 5), -- Red Powerslide Next 110 (Size 42)
(2, 2, 20.00, 1), -- Pink Powerslide Imperial 80 (Size 39)
(3, 3, 30.00, 1), -- Green Rollerblade Lightning 80 (Size 44)
(4, 1, 60.00, 2), -- White Rollerblade Lightning 110 (Size 42)
(1, 3, 40.00, 2); -- Red Powerslide Next 110 (Size 44)

INSERT INTO rental_statuses (name) VALUES 
('submitted'),
('confirmed'),
('ready for pickup'),
('in progress'),
('to be returned'),
('canceled'),
('completed');

INSERT INTO rentals (user_id, rollerblade_id, start_date, end_date, status_id, notes) VALUES
(1, 1, '2024-11-10 00:00:00', '2024-11-30 23:59:59', (SELECT id FROM rental_statuses WHERE name = 'completed'), 'No issues, returned on time'),
(1, 3, '2024-12-01 00:00:00', '2025-02-01 23:59:59', (SELECT id FROM rental_statuses WHERE name = 'in progress'), 'Currently in use'),
(1, 2, '2024-01-01 00:00:00', '2026-01-01 23:59:59', (SELECT id FROM rental_statuses WHERE name = 'canceled'), 'Canceled due to personal reasons');

INSERT INTO homepage_ratings (title, icon, user_name, website_name) VALUES
('Great', 'https://similarpng.com/illustration-of-google-icon-on-transparent-background-png/', 'Jimmy', 'google.com'),
('Awesome', 'https://info.ceneo.pl/ceneo-pl-odswieza-logo-oraz-layout-komputerowej-wersji-serwisu', 'Andrzej', 'ceneo.pl'),
('So special', 'https://similarpng.com/illustration-of-google-icon-on-transparent-background-png/', 'Dan', 'google.com');
