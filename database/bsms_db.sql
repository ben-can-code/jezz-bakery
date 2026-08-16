-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 16, 2026
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bsms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `category_list`
--

CREATE TABLE `category_list` (
  `category_id` int(30) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category_list`
--

INSERT INTO `category_list` (`category_id`, `name`, `description`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(1,  'Breads',        'Freshly baked loaves, rolls, and artisan breads made daily from quality flour.',                     1, 0, '2022-02-14 09:16:23', '2026-08-16 08:00:00'),
(2,  'Pastries',      'Buttery, flaky pastries and baked treats crafted with premium ingredients.',                        1, 0, '2022-02-14 09:19:04', '2026-08-16 08:00:00'),
(3,  'Cakes',         'Celebration and everyday cakes available in a variety of flavors and sizes.',                      1, 0, '2022-02-14 09:19:11', '2026-08-16 08:00:00'),
(4,  'Cookies',       'Handmade cookies baked fresh every morning in classic and specialty flavors.',                     1, 0, '2022-02-14 09:19:18', '2026-08-16 08:00:00'),
(5,  'Beverages',     'Hot and cold drinks to complement your bakery experience, including coffee and juices.',            1, 0, '2022-02-14 09:19:24', '2026-08-16 08:00:00'),
(6,  'Sandwiches',    'Freshly prepared sandwiches made with our own baked bread and quality fillings.',                  1, 0, '2022-02-14 09:19:30', '2026-08-16 08:00:00'),
(7,  'Muffins',       'Soft and moist muffins available in sweet and savory varieties, baked fresh daily.',               1, 0, '2022-02-14 09:19:37', '2026-08-16 08:00:00'),
(8,  'Donuts',        'Glazed, filled, and decorated donuts made from a light, airy dough.',                              1, 0, '2022-02-14 09:19:43', '2026-08-16 08:00:00'),
(9,  'Pies & Tarts',  'Sweet and savory pies and tarts with buttery crusts and rich fillings.',                           1, 0, '2022-02-14 09:19:49', '2026-08-16 08:00:00'),
(10, 'Specialty',     'Seasonal and limited-edition baked goods inspired by traditional and modern recipes.',              1, 0, '2022-02-14 09:19:55', '2026-08-16 08:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `product_list`
--

CREATE TABLE `product_list` (
  `product_id` int(30) NOT NULL,
  `product_code` text NOT NULL,
  `category_id` int(30) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `price` double NOT NULL DEFAULT 0,
  `alert_restock` double NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_list`
-- Categories: 1=Breads, 2=Pastries, 3=Cakes, 4=Cookies, 5=Beverages, 6=Sandwiches, 7=Muffins, 8=Donuts, 9=Pies & Tarts, 10=Specialty
--

INSERT INTO `product_list` (`product_id`, `product_code`, `category_id`, `name`, `description`, `price`, `alert_restock`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES

-- BREADS (category 1)
(1,  'BRD-001', 1, 'Classic White Loaf',        'A soft and fluffy white bread loaf made with enriched flour, perfect for sandwiches and toast. Baked fresh every morning.',                                           3.50,  20, 1, 0, '2026-08-16 08:00:00', NULL),
(2,  'BRD-002', 1, 'Whole Wheat Loaf',           'A hearty whole wheat bread packed with fiber and nutrients. Slightly nutty in flavor, great for a healthy start to the day.',                                        4.00,  20, 1, 0, '2026-08-16 08:00:00', NULL),
(3,  'BRD-003', 1, 'Sourdough Loaf',             'Traditionally fermented sourdough with a crisp golden crust and a moist, chewy interior. Made using a 24-hour cold fermentation process.',                          5.50,  15, 1, 0, '2026-08-16 08:00:00', NULL),
(4,  'BRD-004', 1, 'Dinner Rolls (6 pcs)',       'Soft, pillowy dinner rolls with a golden butter-brushed top. Perfect alongside soups, stews, or any meal.',                                                         2.50,  30, 1, 0, '2026-08-16 08:00:00', NULL),
(5,  'BRD-005', 1, 'Garlic Herb Baguette',       'A crispy French-style baguette infused with roasted garlic and mixed herbs. Ideal as a side or a base for bruschetta.',                                             3.00,  25, 1, 0, '2026-08-16 08:00:00', NULL),

-- PASTRIES (category 2)
(6,  'PST-001', 2, 'Butter Croissant',           'A classic French-style croissant with dozens of flaky, buttery layers. Light and airy inside with a beautifully golden, crisp exterior.',                           2.00,  30, 1, 0, '2026-08-16 08:00:00', NULL),
(7,  'PST-002', 2, 'Almond Croissant',           'A twice-baked croissant generously filled with almond cream and topped with toasted sliced almonds and powdered sugar.',                                             2.75,  25, 1, 0, '2026-08-16 08:00:00', NULL),
(8,  'PST-003', 2, 'Chocolate Danish',           'A flaky Danish pastry swirled with rich chocolate filling and drizzled with a sweet vanilla glaze.',                                                                2.50,  25, 1, 0, '2026-08-16 08:00:00', NULL),
(9,  'PST-004', 2, 'Cinnamon Roll',              'A soft and gooey cinnamon roll loaded with brown sugar and cinnamon filling, topped with a generous swirl of cream cheese frosting.',                               3.00,  20, 1, 0, '2026-08-16 08:00:00', NULL),
(10, 'PST-005', 2, 'Cheese Puff Pastry',         'Golden puff pastry squares filled with a savory blend of cheddar and cream cheese. Crispy on the outside and melty on the inside.',                                2.25,  20, 1, 0, '2026-08-16 08:00:00', NULL),

-- CAKES (category 3)
(11, 'CAK-001', 3, 'Classic Chocolate Cake',     'A rich and moist chocolate layer cake made with premium cocoa, filled and frosted with silky chocolate buttercream. Available by the slice.',                       4.50,  10, 1, 0, '2026-08-16 08:00:00', NULL),
(12, 'CAK-002', 3, 'Vanilla Birthday Cake',      'A light and fluffy vanilla sponge cake layered with fresh strawberry jam and whipped cream frosting. Decorated with colorful sprinkles.',                           4.00,  10, 1, 0, '2026-08-16 08:00:00', NULL),
(13, 'CAK-003', 3, 'Red Velvet Cake Slice',      'A vibrant red velvet cake with a subtle cocoa flavor, layered with tangy cream cheese frosting. Served as a generous single slice.',                                4.75,  10, 1, 0, '2026-08-16 08:00:00', NULL),
(14, 'CAK-004', 3, 'Caramel Cheesecake Slice',   'A dense and creamy New York-style cheesecake on a buttery graham cracker crust, drizzled with house-made salted caramel sauce.',                                    5.00,  10, 1, 0, '2026-08-16 08:00:00', NULL),

-- COOKIES (category 4)
(15, 'COK-001', 4, 'Chocolate Chip Cookies (6)', 'Six classic soft-baked cookies loaded with semi-sweet chocolate chips. Golden on the edges and perfectly chewy in the center.',                                     3.50,  20, 1, 0, '2026-08-16 08:00:00', NULL),
(16, 'COK-002', 4, 'Oatmeal Raisin Cookies (6)', 'Six wholesome cookies made with rolled oats, plump raisins, and a hint of cinnamon. Chewy, filling, and lightly spiced.',                                          3.00,  20, 1, 0, '2026-08-16 08:00:00', NULL),
(17, 'COK-003', 4, 'Peanut Butter Cookies (6)',  'Six rich and crumbly peanut butter cookies made with natural peanut butter. Pressed with a classic fork crosshatch pattern.',                                       3.00,  20, 1, 0, '2026-08-16 08:00:00', NULL),
(18, 'COK-004', 4, 'Sugar Cookies (6)',           'Six buttery sugar cookies with a soft center and slightly crisp edges. Topped with colorful royal icing decoration.',                                               2.75,  20, 1, 0, '2026-08-16 08:00:00', NULL),

-- BEVERAGES (category 5)
(19, 'BEV-001', 5, 'Brewed Coffee (Hot)',        'Freshly brewed house blend coffee served hot. Bold, smooth, and aromatic — the perfect companion to any bakery item.',                                              2.00,  50, 1, 0, '2026-08-16 08:00:00', NULL),
(20, 'BEV-002', 5, 'Café Latte',                 'A smooth espresso-based drink topped with steamed milk and a thin layer of velvety foam. Available in regular and large sizes.',                                    3.50,  30, 1, 0, '2026-08-16 08:00:00', NULL),
(21, 'BEV-003', 5, 'Hot Chocolate',              'A rich and creamy hot chocolate made with premium cocoa powder and whole milk, topped with mini marshmallows.',                                                     3.00,  30, 1, 0, '2026-08-16 08:00:00', NULL),
(22, 'BEV-004', 5, 'Fresh Orange Juice',         'Freshly squeezed orange juice served chilled. No added sugar or preservatives — just pure, natural citrus flavor.',                                                 2.50,  40, 1, 0, '2026-08-16 08:00:00', NULL),

-- SANDWICHES (category 6)
(23, 'SND-001', 6, 'Club Sandwich',              'A triple-decker sandwich with grilled chicken, crispy bacon, lettuce, tomato, and mayo served on toasted white bread. Served with a side of chips.',               5.50,  15, 1, 0, '2026-08-16 08:00:00', NULL),
(24, 'SND-002', 6, 'Tuna Melt Sandwich',         'Open-faced sourdough topped with seasoned tuna salad, sliced tomatoes, and melted cheddar cheese. Toasted until golden and bubbly.',                               5.00,  15, 1, 0, '2026-08-16 08:00:00', NULL),
(25, 'SND-003', 6, 'Veggie Sandwich',            'A hearty sandwich packed with fresh avocado, cucumber, roasted bell peppers, spinach, and hummus on whole wheat bread.',                                            4.50,  15, 1, 0, '2026-08-16 08:00:00', NULL),

-- MUFFINS (category 7)
(26, 'MUF-001', 7, 'Blueberry Muffin',           'A classic bakery-style muffin bursting with fresh blueberries. Soft and tender inside with a perfectly domed, lightly sugared top.',                               2.25,  20, 1, 0, '2026-08-16 08:00:00', NULL),
(27, 'MUF-002', 7, 'Double Chocolate Muffin',    'An intensely chocolatey muffin made with dark cocoa batter and loaded with chocolate chips throughout. Moist, rich, and satisfying.',                              2.50,  20, 1, 0, '2026-08-16 08:00:00', NULL),
(28, 'MUF-003', 7, 'Banana Walnut Muffin',       'A moist and flavorful muffin made with ripe bananas and crunchy walnuts. Naturally sweet with warm notes of cinnamon and vanilla.',                                2.25,  20, 1, 0, '2026-08-16 08:00:00', NULL),
(29, 'MUF-004', 7, 'Lemon Poppy Seed Muffin',   'A bright and zesty muffin with fresh lemon zest, poppy seeds, and a sweet lemon glaze drizzled on top. Light, fluffy, and refreshing.',                            2.25,  20, 1, 0, '2026-08-16 08:00:00', NULL),

-- DONUTS (category 8)
(30, 'DNT-001', 8, 'Glazed Donut',               'A light and airy yeast donut coated in a thin, sweet vanilla glaze. A timeless classic that never goes out of style.',                                              1.50,  30, 1, 0, '2026-08-16 08:00:00', NULL),
(31, 'DNT-002', 8, 'Chocolate Frosted Donut',    'A fluffy yeast donut topped with a thick layer of smooth chocolate frosting and colorful rainbow sprinkles.',                                                       1.75,  30, 1, 0, '2026-08-16 08:00:00', NULL),
(32, 'DNT-003', 8, 'Strawberry Jam Donut',       'A soft, pillowy donut filled with sweet strawberry jam and dusted generously with powdered sugar. A crowd favorite.',                                               2.00,  25, 1, 0, '2026-08-16 08:00:00', NULL),
(33, 'DNT-004', 8, 'Bavarian Cream Donut',       'A classic donut filled with rich, silky Bavarian cream and topped with dark chocolate fondant. Indulgent and irresistible.',                                        2.25,  25, 1, 0, '2026-08-16 08:00:00', NULL),

-- PIES & TARTS (category 9)
(34, 'PIE-001', 9, 'Classic Apple Pie (slice)',  'A warm slice of homestyle apple pie made with cinnamon-spiced apples in a golden, buttery double crust. Best served with a scoop of vanilla ice cream.',           3.75,  15, 1, 0, '2026-08-16 08:00:00', NULL),
(35, 'PIE-002', 9, 'Egg Tart',                   'A smooth, silky egg custard baked in a flaky short-crust pastry shell. Lightly sweet with a beautiful golden top.',                                                 1.75,  25, 1, 0, '2026-08-16 08:00:00', NULL),
(36, 'PIE-003', 9, 'Lemon Meringue Tart',        'A crisp pastry shell filled with tangy lemon curd and topped with toasted pillowy meringue. A perfect balance of sweet and sour.',                                  3.50,  15, 1, 0, '2026-08-16 08:00:00', NULL),
(37, 'PIE-004', 9, 'Chicken Pot Pie',            'A savory pie filled with tender chunks of chicken, carrots, peas, and potatoes in a creamy herb gravy, sealed under a flaky golden crust.',                        5.00,  10, 1, 0, '2026-08-16 08:00:00', NULL),

-- SPECIALTY (category 10)
(38, 'SPC-001', 10, 'Ensaymada',                 'A soft and pillowy Filipino-style brioche roll topped with creamy butter, sugar, and grated aged cheese. A beloved local classic.',                                 2.00,  20, 1, 0, '2026-08-16 08:00:00', NULL),
(39, 'SPC-002', 10, 'Pandesal (6 pcs)',           'Six freshly baked traditional Filipino bread rolls lightly coated in fine breadcrumbs. Soft, slightly sweet, and best enjoyed warm.',                               1.50,  50, 1, 0, '2026-08-16 08:00:00', NULL),
(40, 'SPC-003', 10, 'Ube Cheese Bread',           'A vibrant purple ube bread roll swirled with sweet ube filling and a generous layer of melted quick-melt cheese. A must-try Jezz specialty.',                      2.50,  20, 1, 0, '2026-08-16 08:00:00', NULL),
(41, 'SPC-004', 10, 'Spanish Bread',              'A soft bread roll filled with a buttery, sugary filling made with breadcrumbs, butter, and sugar. A nostalgic Filipino bakery staple.',                            1.25,  30, 1, 0, '2026-08-16 08:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stock_list`
--

CREATE TABLE `stock_list` (
  `stock_id` int(30) NOT NULL,
  `product_id` int(30) NOT NULL,
  `quantity` double NOT NULL DEFAULT 0,
  `expiry_date` datetime NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stock_list`
--

INSERT INTO `stock_list` (`stock_id`, `product_id`, `quantity`, `expiry_date`, `date_added`) VALUES
(1,  1,  80,  '2026-08-18 00:00:00', '2026-08-16 06:00:00'),
(2,  2,  60,  '2026-08-18 00:00:00', '2026-08-16 06:00:00'),
(3,  3,  40,  '2026-08-18 00:00:00', '2026-08-16 06:00:00'),
(4,  4,  120, '2026-08-18 00:00:00', '2026-08-16 06:00:00'),
(5,  5,  50,  '2026-08-18 00:00:00', '2026-08-16 06:00:00'),
(6,  6,  100, '2026-08-18 00:00:00', '2026-08-16 06:00:00'),
(7,  7,  80,  '2026-08-18 00:00:00', '2026-08-16 06:00:00'),
(8,  8,  90,  '2026-08-18 00:00:00', '2026-08-16 06:00:00'),
(9,  9,  70,  '2026-08-18 00:00:00', '2026-08-16 06:00:00'),
(10, 10, 60,  '2026-08-18 00:00:00', '2026-08-16 06:00:00'),
(11, 11, 30,  '2026-08-19 00:00:00', '2026-08-16 06:00:00'),
(12, 12, 30,  '2026-08-19 00:00:00', '2026-08-16 06:00:00'),
(13, 13, 25,  '2026-08-19 00:00:00', '2026-08-16 06:00:00'),
(14, 14, 20,  '2026-08-19 00:00:00', '2026-08-16 06:00:00'),
(15, 15, 60,  '2026-08-20 00:00:00', '2026-08-16 06:00:00'),
(16, 16, 60,  '2026-08-20 00:00:00', '2026-08-16 06:00:00'),
(17, 17, 60,  '2026-08-20 00:00:00', '2026-08-16 06:00:00'),
(18, 18, 50,  '2026-08-20 00:00:00', '2026-08-16 06:00:00'),
(19, 19, 150, '2026-08-17 00:00:00', '2026-08-16 06:00:00'),
(20, 20, 100, '2026-08-17 00:00:00', '2026-08-16 06:00:00'),
(21, 21, 100, '2026-08-17 00:00:00', '2026-08-16 06:00:00'),
(22, 22, 80,  '2026-08-17 00:00:00', '2026-08-16 06:00:00'),
(23, 23, 40,  '2026-08-17 00:00:00', '2026-08-16 06:00:00'),
(24, 24, 40,  '2026-08-17 00:00:00', '2026-08-16 06:00:00'),
(25, 25, 40,  '2026-08-17 00:00:00', '2026-08-16 06:00:00'),
(26, 26, 80,  '2026-08-18 00:00:00', '2026-08-16 06:00:00'),
(27, 27, 80,  '2026-08-18 00:00:00', '2026-08-16 06:00:00'),
(28, 28, 70,  '2026-08-18 00:00:00', '2026-08-16 06:00:00'),
(29, 29, 70,  '2026-08-18 00:00:00', '2026-08-16 06:00:00'),
(30, 30, 120, '2026-08-18 00:00:00', '2026-08-16 06:00:00'),
(31, 31, 100, '2026-08-18 00:00:00', '2026-08-16 06:00:00'),
(32, 32, 90,  '2026-08-18 00:00:00', '2026-08-16 06:00:00'),
(33, 33, 80,  '2026-08-18 00:00:00', '2026-08-16 06:00:00'),
(34, 34, 30,  '2026-08-20 00:00:00', '2026-08-16 06:00:00'),
(35, 35, 60,  '2026-08-19 00:00:00', '2026-08-16 06:00:00'),
(36, 36, 30,  '2026-08-19 00:00:00', '2026-08-16 06:00:00'),
(37, 37, 20,  '2026-08-18 00:00:00', '2026-08-16 06:00:00'),
(38, 38, 80,  '2026-08-18 00:00:00', '2026-08-16 06:00:00'),
(39, 39, 150, '2026-08-17 00:00:00', '2026-08-16 06:00:00'),
(40, 40, 70,  '2026-08-18 00:00:00', '2026-08-16 06:00:00'),
(41, 41, 100, '2026-08-17 00:00:00', '2026-08-16 06:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_items`
--

CREATE TABLE `transaction_items` (
  `transaction_id` int(30) NOT NULL,
  `product_id` int(30) NOT NULL,
  `quantity` double NOT NULL DEFAULT 0,
  `price` double NOT NULL DEFAULT 0,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaction_items`
--

INSERT INTO `transaction_items` (`transaction_id`, `product_id`, `quantity`, `price`, `date_added`) VALUES
(1, 6,  2, 2.00, '2026-08-16 08:30:00'),
(1, 19, 2, 2.00, '2026-08-16 08:30:00'),
(1, 9,  1, 3.00, '2026-08-16 08:30:00'),
(2, 1,  1, 3.50, '2026-08-16 09:15:00'),
(2, 20, 1, 3.50, '2026-08-16 09:15:00'),
(2, 15, 1, 3.50, '2026-08-16 09:15:00'),
(3, 11, 1, 4.50, '2026-08-16 10:00:00'),
(3, 21, 1, 3.00, '2026-08-16 10:00:00'),
(4, 23, 1, 5.50, '2026-08-16 11:45:00'),
(4, 22, 1, 2.50, '2026-08-16 11:45:00'),
(5, 30, 2, 1.50, '2026-08-16 12:30:00'),
(5, 31, 2, 1.75, '2026-08-16 12:30:00'),
(5, 19, 1, 2.00, '2026-08-16 12:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_list`
--

CREATE TABLE `transaction_list` (
  `transaction_id` int(30) NOT NULL,
  `receipt_no` text NOT NULL,
  `total` double NOT NULL DEFAULT 0,
  `tendered_amount` double NOT NULL DEFAULT 0,
  `change` double NOT NULL DEFAULT 0,
  `user_id` int(30) DEFAULT 1,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaction_list`
--

INSERT INTO `transaction_list` (`transaction_id`, `receipt_no`, `total`, `tendered_amount`, `change`, `user_id`, `date_added`) VALUES
(1, '1755337800', 11.00, 15.00, 4.00,  2, '2026-08-16 08:30:00'),
(2, '1755340500', 10.50, 20.00, 9.50,  2, '2026-08-16 09:15:00'),
(3, '1755343200', 7.50,  10.00, 2.50,  1, '2026-08-16 10:00:00'),
(4, '1755348300', 8.00,  10.00, 2.00,  2, '2026-08-16 11:45:00'),
(5, '1755351000', 9.00,  10.00, 1.00,  2, '2026-08-16 12:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_list`
--

CREATE TABLE `user_list` (
  `user_id` int(30) NOT NULL,
  `fullname` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `type` int(30) NOT NULL DEFAULT 1,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_list`
--

INSERT INTO `user_list` (`user_id`, `fullname`, `username`, `password`, `type`, `status`, `date_created`) VALUES
(1, 'Administrator', 'admin',   '0192023a7bbd73250516f069df18b500', 1, 1, '2022-02-14 00:44:30'),
(2, 'Claire Blake',  'cblake',  'cd74fae0a3adf459f73bbf187607ccea', 0, 1, '2022-02-14 02:29:23'),
(3, 'Mark Cooper',   'mcooper', '0c4635c5af0f173c26b0d85b6c9b398b', 1, 1, '2022-02-14 02:29:58');

--
-- Indexes for dumped tables
--

ALTER TABLE `category_list`
  ADD PRIMARY KEY (`category_id`);

ALTER TABLE `product_list`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

ALTER TABLE `stock_list`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `product_id` (`product_id`);

ALTER TABLE `transaction_items`
  ADD KEY `product_id` (`product_id`),
  ADD KEY `transaction_id` (`transaction_id`);

ALTER TABLE `transaction_list`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `user_list`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

ALTER TABLE `category_list`
  MODIFY `category_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

ALTER TABLE `product_list`
  MODIFY `product_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

ALTER TABLE `stock_list`
  MODIFY `stock_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

ALTER TABLE `transaction_list`
  MODIFY `transaction_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `user_list`
  MODIFY `user_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

ALTER TABLE `product_list`
  ADD CONSTRAINT `product_list_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category_list` (`category_id`) ON DELETE CASCADE;

ALTER TABLE `stock_list`
  ADD CONSTRAINT `stock_list_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product_list` (`product_id`) ON DELETE CASCADE;

ALTER TABLE `transaction_items`
  ADD CONSTRAINT `transaction_items_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product_list` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_items_ibfk_2` FOREIGN KEY (`transaction_id`) REFERENCES `transaction_list` (`transaction_id`) ON DELETE CASCADE;

ALTER TABLE `transaction_list`
  ADD CONSTRAINT `transaction_list_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_list` (`user_id`) ON DELETE SET NULL;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
