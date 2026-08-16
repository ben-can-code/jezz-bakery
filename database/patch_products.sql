-- ============================================================
-- PATCH: Update categories & products to real bakery data
-- Jezz Bakery Management System
-- Generated: 2026-08-16
--
-- HOW TO USE:
--   Open phpMyAdmin → select bsms_db → click SQL tab → paste and run.
--   This patch is safe to run on a live database.
--   It does NOT drop any existing tables or transaction history.
-- ============================================================

USE `bsms_db`;

-- ============================================================
-- STEP 1: Update existing categories to real bakery names
-- ============================================================

UPDATE `category_list` SET `name`='Breads',       `description`='Freshly baked loaves, rolls, and artisan breads made daily from quality flour.',                     `delete_flag`=0, `status`=1 WHERE `category_id`=1;
UPDATE `category_list` SET `name`='Pastries',     `description`='Buttery, flaky pastries and baked treats crafted with premium ingredients.',                        `delete_flag`=0, `status`=1 WHERE `category_id`=2;
UPDATE `category_list` SET `name`='Cakes',        `description`='Celebration and everyday cakes available in a variety of flavors and sizes.',                      `delete_flag`=0, `status`=1 WHERE `category_id`=3;
UPDATE `category_list` SET `name`='Cookies',      `description`='Handmade cookies baked fresh every morning in classic and specialty flavors.',                     `delete_flag`=0, `status`=1 WHERE `category_id`=4;
UPDATE `category_list` SET `name`='Beverages',    `description`='Hot and cold drinks to complement your bakery experience, including coffee and juices.',            `delete_flag`=0, `status`=1 WHERE `category_id`=5;
UPDATE `category_list` SET `name`='Sandwiches',   `description`='Freshly prepared sandwiches made with our own baked bread and quality fillings.',                  `delete_flag`=0, `status`=1 WHERE `category_id`=6;
UPDATE `category_list` SET `name`='Muffins',      `description`='Soft and moist muffins available in sweet and savory varieties, baked fresh daily.',               `delete_flag`=0, `status`=1 WHERE `category_id`=7;
UPDATE `category_list` SET `name`='Donuts',       `description`='Glazed, filled, and decorated donuts made from a light, airy dough.',                              `delete_flag`=0, `status`=1 WHERE `category_id`=8;
UPDATE `category_list` SET `name`='Pies & Tarts', `description`='Sweet and savory pies and tarts with buttery crusts and rich fillings.',                           `delete_flag`=0, `status`=1 WHERE `category_id`=9;
UPDATE `category_list` SET `name`='Specialty',    `description`='Seasonal and limited-edition baked goods inspired by traditional and modern recipes.',              `delete_flag`=0, `status`=1 WHERE `category_id`=10;

-- Remove the old deleted sample category 11 (already flagged deleted, just ensure it stays hidden)
UPDATE `category_list` SET `delete_flag`=1, `status`=0 WHERE `category_id`=11;

-- ============================================================
-- STEP 2: Replace old placeholder products (IDs 1–6)
--         with real bakery products using the same IDs
-- ============================================================

-- Breads (category 1)
UPDATE `product_list` SET
  `product_code`='BRD-001', `category_id`=1,
  `name`='Classic White Loaf',
  `description`='A soft and fluffy white bread loaf made with enriched flour, perfect for sandwiches and toast. Baked fresh every morning.',
  `price`=3.50, `alert_restock`=20, `status`=1, `delete_flag`=0
WHERE `product_id`=1;

UPDATE `product_list` SET
  `product_code`='BRD-002', `category_id`=1,
  `name`='Whole Wheat Loaf',
  `description`='A hearty whole wheat bread packed with fiber and nutrients. Slightly nutty in flavor, great for a healthy start to the day.',
  `price`=4.00, `alert_restock`=20, `status`=1, `delete_flag`=0
WHERE `product_id`=2;

UPDATE `product_list` SET
  `product_code`='BRD-003', `category_id`=1,
  `name`='Sourdough Loaf',
  `description`='Traditionally fermented sourdough with a crisp golden crust and a moist, chewy interior. Made using a 24-hour cold fermentation process.',
  `price`=5.50, `alert_restock`=15, `status`=1, `delete_flag`=0
WHERE `product_id`=3;

UPDATE `product_list` SET
  `product_code`='BRD-004', `category_id`=1,
  `name`='Dinner Rolls (6 pcs)',
  `description`='Soft, pillowy dinner rolls with a golden butter-brushed top. Perfect alongside soups, stews, or any meal.',
  `price`=2.50, `alert_restock`=30, `status`=1, `delete_flag`=0
WHERE `product_id`=4;

UPDATE `product_list` SET
  `product_code`='BRD-005', `category_id`=1,
  `name`='Garlic Herb Baguette',
  `description`='A crispy French-style baguette infused with roasted garlic and mixed herbs. Ideal as a side or a base for bruschetta.',
  `price`=3.00, `alert_restock`=25, `status`=1, `delete_flag`=0
WHERE `product_id`=5;

-- Pastries (category 2) — product_id 6 was already delete_flag=1, restore it as a new product
UPDATE `product_list` SET
  `product_code`='PST-001', `category_id`=2,
  `name`='Butter Croissant',
  `description`='A classic French-style croissant with dozens of flaky, buttery layers. Light and airy inside with a beautifully golden, crisp exterior.',
  `price`=2.00, `alert_restock`=30, `status`=1, `delete_flag`=0
WHERE `product_id`=6;

-- ============================================================
-- STEP 3: Insert all new products (IDs 7–41)
-- ============================================================

INSERT INTO `product_list` (`product_id`,`product_code`,`category_id`,`name`,`description`,`price`,`alert_restock`,`status`,`delete_flag`,`date_created`,`date_updated`) VALUES
-- Pastries
(7,  'PST-002', 2, 'Almond Croissant',           'A twice-baked croissant generously filled with almond cream and topped with toasted sliced almonds and powdered sugar.',                                             2.75, 25, 1, 0, NOW(), NULL),
(8,  'PST-003', 2, 'Chocolate Danish',            'A flaky Danish pastry swirled with rich chocolate filling and drizzled with a sweet vanilla glaze.',                                                                2.50, 25, 1, 0, NOW(), NULL),
(9,  'PST-004', 2, 'Cinnamon Roll',               'A soft and gooey cinnamon roll loaded with brown sugar and cinnamon filling, topped with a generous swirl of cream cheese frosting.',                               3.00, 20, 1, 0, NOW(), NULL),
(10, 'PST-005', 2, 'Cheese Puff Pastry',          'Golden puff pastry squares filled with a savory blend of cheddar and cream cheese. Crispy on the outside and melty on the inside.',                                2.25, 20, 1, 0, NOW(), NULL),
-- Cakes
(11, 'CAK-001', 3, 'Classic Chocolate Cake',      'A rich and moist chocolate layer cake made with premium cocoa, filled and frosted with silky chocolate buttercream. Available by the slice.',                       4.50, 10, 1, 0, NOW(), NULL),
(12, 'CAK-002', 3, 'Vanilla Birthday Cake',       'A light and fluffy vanilla sponge cake layered with fresh strawberry jam and whipped cream frosting. Decorated with colorful sprinkles.',                           4.00, 10, 1, 0, NOW(), NULL),
(13, 'CAK-003', 3, 'Red Velvet Cake Slice',       'A vibrant red velvet cake with a subtle cocoa flavor, layered with tangy cream cheese frosting. Served as a generous single slice.',                                4.75, 10, 1, 0, NOW(), NULL),
(14, 'CAK-004', 3, 'Caramel Cheesecake Slice',    'A dense and creamy New York-style cheesecake on a buttery graham cracker crust, drizzled with house-made salted caramel sauce.',                                    5.00, 10, 1, 0, NOW(), NULL),
-- Cookies
(15, 'COK-001', 4, 'Chocolate Chip Cookies (6)',  'Six classic soft-baked cookies loaded with semi-sweet chocolate chips. Golden on the edges and perfectly chewy in the center.',                                     3.50, 20, 1, 0, NOW(), NULL),
(16, 'COK-002', 4, 'Oatmeal Raisin Cookies (6)',  'Six wholesome cookies made with rolled oats, plump raisins, and a hint of cinnamon. Chewy, filling, and lightly spiced.',                                          3.00, 20, 1, 0, NOW(), NULL),
(17, 'COK-003', 4, 'Peanut Butter Cookies (6)',   'Six rich and crumbly peanut butter cookies made with natural peanut butter. Pressed with a classic fork crosshatch pattern.',                                       3.00, 20, 1, 0, NOW(), NULL),
(18, 'COK-004', 4, 'Sugar Cookies (6)',            'Six buttery sugar cookies with a soft center and slightly crisp edges. Topped with colorful royal icing decoration.',                                               2.75, 20, 1, 0, NOW(), NULL),
-- Beverages
(19, 'BEV-001', 5, 'Brewed Coffee (Hot)',          'Freshly brewed house blend coffee served hot. Bold, smooth, and aromatic — the perfect companion to any bakery item.',                                              2.00, 50, 1, 0, NOW(), NULL),
(20, 'BEV-002', 5, 'Café Latte',                   'A smooth espresso-based drink topped with steamed milk and a thin layer of velvety foam. Available in regular and large sizes.',                                    3.50, 30, 1, 0, NOW(), NULL),
(21, 'BEV-003', 5, 'Hot Chocolate',                'A rich and creamy hot chocolate made with premium cocoa powder and whole milk, topped with mini marshmallows.',                                                     3.00, 30, 1, 0, NOW(), NULL),
(22, 'BEV-004', 5, 'Fresh Orange Juice',           'Freshly squeezed orange juice served chilled. No added sugar or preservatives — just pure, natural citrus flavor.',                                                 2.50, 40, 1, 0, NOW(), NULL),
-- Sandwiches
(23, 'SND-001', 6, 'Club Sandwich',                'A triple-decker sandwich with grilled chicken, crispy bacon, lettuce, tomato, and mayo served on toasted white bread. Served with a side of chips.',               5.50, 15, 1, 0, NOW(), NULL),
(24, 'SND-002', 6, 'Tuna Melt Sandwich',           'Open-faced sourdough topped with seasoned tuna salad, sliced tomatoes, and melted cheddar cheese. Toasted until golden and bubbly.',                               5.00, 15, 1, 0, NOW(), NULL),
(25, 'SND-003', 6, 'Veggie Sandwich',              'A hearty sandwich packed with fresh avocado, cucumber, roasted bell peppers, spinach, and hummus on whole wheat bread.',                                            4.50, 15, 1, 0, NOW(), NULL),
-- Muffins
(26, 'MUF-001', 7, 'Blueberry Muffin',             'A classic bakery-style muffin bursting with fresh blueberries. Soft and tender inside with a perfectly domed, lightly sugared top.',                               2.25, 20, 1, 0, NOW(), NULL),
(27, 'MUF-002', 7, 'Double Chocolate Muffin',      'An intensely chocolatey muffin made with dark cocoa batter and loaded with chocolate chips throughout. Moist, rich, and satisfying.',                              2.50, 20, 1, 0, NOW(), NULL),
(28, 'MUF-003', 7, 'Banana Walnut Muffin',         'A moist and flavorful muffin made with ripe bananas and crunchy walnuts. Naturally sweet with warm notes of cinnamon and vanilla.',                                2.25, 20, 1, 0, NOW(), NULL),
(29, 'MUF-004', 7, 'Lemon Poppy Seed Muffin',     'A bright and zesty muffin with fresh lemon zest, poppy seeds, and a sweet lemon glaze drizzled on top. Light, fluffy, and refreshing.',                            2.25, 20, 1, 0, NOW(), NULL),
-- Donuts
(30, 'DNT-001', 8, 'Glazed Donut',                 'A light and airy yeast donut coated in a thin, sweet vanilla glaze. A timeless classic that never goes out of style.',                                              1.50, 30, 1, 0, NOW(), NULL),
(31, 'DNT-002', 8, 'Chocolate Frosted Donut',      'A fluffy yeast donut topped with a thick layer of smooth chocolate frosting and colorful rainbow sprinkles.',                                                       1.75, 30, 1, 0, NOW(), NULL),
(32, 'DNT-003', 8, 'Strawberry Jam Donut',         'A soft, pillowy donut filled with sweet strawberry jam and dusted generously with powdered sugar. A crowd favorite.',                                               2.00, 25, 1, 0, NOW(), NULL),
(33, 'DNT-004', 8, 'Bavarian Cream Donut',         'A classic donut filled with rich, silky Bavarian cream and topped with dark chocolate fondant. Indulgent and irresistible.',                                        2.25, 25, 1, 0, NOW(), NULL),
-- Pies & Tarts
(34, 'PIE-001', 9, 'Classic Apple Pie (slice)',    'A warm slice of homestyle apple pie made with cinnamon-spiced apples in a golden, buttery double crust. Best served with a scoop of vanilla ice cream.',           3.75, 15, 1, 0, NOW(), NULL),
(35, 'PIE-002', 9, 'Egg Tart',                     'A smooth, silky egg custard baked in a flaky short-crust pastry shell. Lightly sweet with a beautiful golden top.',                                                 1.75, 25, 1, 0, NOW(), NULL),
(36, 'PIE-003', 9, 'Lemon Meringue Tart',          'A crisp pastry shell filled with tangy lemon curd and topped with toasted pillowy meringue. A perfect balance of sweet and sour.',                                  3.50, 15, 1, 0, NOW(), NULL),
(37, 'PIE-004', 9, 'Chicken Pot Pie',              'A savory pie filled with tender chunks of chicken, carrots, peas, and potatoes in a creamy herb gravy, sealed under a flaky golden crust.',                        5.00, 10, 1, 0, NOW(), NULL),
-- Specialty
(38, 'SPC-001', 10, 'Ensaymada',                   'A soft and pillowy Filipino-style brioche roll topped with creamy butter, sugar, and grated aged cheese. A beloved local classic.',                                 2.00, 20, 1, 0, NOW(), NULL),
(39, 'SPC-002', 10, 'Pandesal (6 pcs)',             'Six freshly baked traditional Filipino bread rolls lightly coated in fine breadcrumbs. Soft, slightly sweet, and best enjoyed warm.',                               1.50, 50, 1, 0, NOW(), NULL),
(40, 'SPC-003', 10, 'Ube Cheese Bread',             'A vibrant purple ube bread roll swirled with sweet ube filling and a generous layer of melted quick-melt cheese. A must-try Jezz specialty.',                      2.50, 20, 1, 0, NOW(), NULL),
(41, 'SPC-004', 10, 'Spanish Bread',                'A soft bread roll filled with a buttery, sugary filling made with breadcrumbs, butter, and sugar. A nostalgic Filipino bakery staple.',                            1.25, 30, 1, 0, NOW(), NULL);

-- ============================================================
-- STEP 4: Add fresh stock for all new products
-- ============================================================

-- Remove expired old stocks for products 1–6 first
DELETE FROM `stock_list` WHERE `product_id` IN (1,2,3,4,5,6);

-- Insert fresh stock for all 41 products
INSERT INTO `stock_list` (`product_id`, `quantity`, `expiry_date`) VALUES
(1,  80,  DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(2,  60,  DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(3,  40,  DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(4,  120, DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(5,  50,  DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(6,  100, DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(7,  80,  DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(8,  90,  DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(9,  70,  DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(10, 60,  DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(11, 30,  DATE_ADD(CURDATE(), INTERVAL 3 DAY)),
(12, 30,  DATE_ADD(CURDATE(), INTERVAL 3 DAY)),
(13, 25,  DATE_ADD(CURDATE(), INTERVAL 3 DAY)),
(14, 20,  DATE_ADD(CURDATE(), INTERVAL 3 DAY)),
(15, 60,  DATE_ADD(CURDATE(), INTERVAL 4 DAY)),
(16, 60,  DATE_ADD(CURDATE(), INTERVAL 4 DAY)),
(17, 60,  DATE_ADD(CURDATE(), INTERVAL 4 DAY)),
(18, 50,  DATE_ADD(CURDATE(), INTERVAL 4 DAY)),
(19, 150, DATE_ADD(CURDATE(), INTERVAL 1 DAY)),
(20, 100, DATE_ADD(CURDATE(), INTERVAL 1 DAY)),
(21, 100, DATE_ADD(CURDATE(), INTERVAL 1 DAY)),
(22, 80,  DATE_ADD(CURDATE(), INTERVAL 1 DAY)),
(23, 40,  DATE_ADD(CURDATE(), INTERVAL 1 DAY)),
(24, 40,  DATE_ADD(CURDATE(), INTERVAL 1 DAY)),
(25, 40,  DATE_ADD(CURDATE(), INTERVAL 1 DAY)),
(26, 80,  DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(27, 80,  DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(28, 70,  DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(29, 70,  DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(30, 120, DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(31, 100, DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(32, 90,  DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(33, 80,  DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(34, 30,  DATE_ADD(CURDATE(), INTERVAL 4 DAY)),
(35, 60,  DATE_ADD(CURDATE(), INTERVAL 3 DAY)),
(36, 30,  DATE_ADD(CURDATE(), INTERVAL 3 DAY)),
(37, 20,  DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(38, 80,  DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(39, 150, DATE_ADD(CURDATE(), INTERVAL 1 DAY)),
(40, 70,  DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(41, 100, DATE_ADD(CURDATE(), INTERVAL 1 DAY));

-- ============================================================
-- Done! 10 categories and 41 products are now active.
-- ============================================================
