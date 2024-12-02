-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2024 at 10:30 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `techtrade`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brand_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `name`) VALUES
(1, 'Apple'),
(2, 'Samsung'),
(3, 'Sony'),
(4, 'LG'),
(5, 'Huawei'),
(6, 'Xiaomi'),
(7, 'HP'),
(8, 'Dell'),
(9, 'Asus'),
(10, 'Acer'),
(11, 'Microsoft'),
(12, 'Canon'),
(13, 'Nikon'),
(14, 'Bose'),
(15, 'JBL'),
(16, 'Google'),
(17, 'Valve'),
(18, 'Insta'),
(19, 'Microsoft');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `seller_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `trade_in_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`) VALUES
(1, 'Smartphones'),
(2, 'Laptops'),
(3, 'Tablets'),
(4, 'Cameras'),
(5, 'TVs'),
(6, 'Audio'),
(7, 'Gaming Consoles'),
(8, 'Printers'),
(9, 'Monitors'),
(10, 'Accessories');

-- --------------------------------------------------------

--
-- Table structure for table `deal_sections`
--

CREATE TABLE `deal_sections` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deal_sections`
--

INSERT INTO `deal_sections` (`id`, `name`) VALUES
(1, 'Limited Deals');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `trade_in_credit` decimal(10,2) DEFAULT 0.00,
  `shipping_address` text NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `payment_status` enum('Pending','Paid','Failed') DEFAULT 'Pending',
  `transaction_data` text DEFAULT NULL,
  `payment_reference` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `trade_in_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_reference` varchar(100) NOT NULL,
  `payment_status` varchar(50) NOT NULL,
  `transaction_data` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `brand_id`, `name`, `description`, `image`) VALUES
(1, 1, 2, 'Samsung Galaxy S24 Ultra, Titanium Black', '', NULL),
(2, 9, 1, 'Apple 32-inch Pro Display XDR with Retina 6K Display - Standard Glass', '<h2>Display Specifications</h2>\r\n\r\n<h3>Screen & Resolution</h3>\r\n<ul>\r\n    <li><strong>Display Size:</strong> 32-inch LCD</li>\r\n    <li><strong>Resolution:</strong> Retina 6K (6016 x 3384 pixels)</li>\r\n</ul>\r\n\r\n<h3>Visual Performance</h3>\r\n<ul>\r\n    <li><strong>Display Technology:</strong> Extreme Dynamic Range (XDR)</li>\r\n    <li><strong>Brightness:</strong>\r\n        <ul>\r\n            <li>Sustained: 1000 nits</li>\r\n            <li>Peak: 1600 nits</li>\r\n        </ul>\r\n    </li>\r\n    <li><strong>Contrast Ratio:</strong> 1,000,000:1</li>\r\n    <li><strong>Color:</strong>\r\n        <ul>\r\n            <li>P3 wide color gamut</li>\r\n            <li>10-bit color depth</li>\r\n        </ul>\r\n    </li>\r\n    <li><strong>Viewing Angle:</strong> Superwide</li>\r\n</ul>\r\n\r\n<h3>Connectivity</h3>\r\n<ul>\r\n    <li>1x Thunderbolt 3 port</li>\r\n    <li>3x USB-C ports</li>\r\n</ul>\r\n\r\n<h3>Additional Information</h3>\r\n<p><strong>Note:</strong> Pro Stand and VESA Mount Adapter sold separately</p>', NULL),
(3, 7, 11, 'Xbox Series X – 1TB Digital Edition', '<p>Experience the fastest, most powerful Xbox ever with Xbox Series X, now all-digital with a 1TB SSD in Robot White. Explore rich new worlds with 12 teraflops of raw graphic processing power, DirectX ray tracing, a custom SSD, and 4K gaming. Dive into legendary franchises like Call of Duty, Forza, Diablo, Halo, and more that come to life on Xbox Series X. Make the most of every gaming minute with Quick Resume, lightning-fast load times, and gameplay of up to 120 FPS—all powered by Xbox Velocity Architecture. Enjoy thousands of games from four generations of Xbox, with hundreds of optimized titles that look and play better than ever. Get the most out of your Xbox Series X with Xbox Game Pass Ultimate (membership sold separately). Play new games like Call of Duty: Black Ops 6, Avowed, and Indiana Jones and the Great Circle on day one, enjoy hundreds of high-quality games like Diablo IV, Forza Motorsport, Starfield, and more—with games added all the time, there’s always something new to play with Xbox Game Pass Ultimate.</p>', NULL),
(4, 1, 1, 'Apple iPhone 12, 128GB, Blue - Fully Unlocked ', '<h2>Product Details</h2>\n\n<ul>\n    <li><strong>Carrier Compatibility:</strong> Fully unlocked and compatible with any carrier of choice (e.g. AT&T, T-Mobile, Sprint, Verizon, US-Cellular, Cricket, Metro, etc.).</li>\n    <li><strong>Cosmetic Condition:</strong> Inspected and guaranteed to have minimal cosmetic damage, which is not noticeable when the device is held at arm\'s length.</li>\n    <li><strong>Functionality:</strong> Successfully passed a full diagnostic test which ensures like-new functionality and removal of any prior-user personal information.</li>\n    <li><strong>Included Accessories:</strong> The device does not come with headphones or a SIM card. It does include a charging cable that may be generic.</li>\n    <li><strong>Battery Health:</strong> Tested for battery health and guaranteed to have a minimum battery capacity of 80%.</li>\n</ul>', NULL),
(5, 4, 12, 'Canon EOS Rebel T7 DSLR Camera | 2 Lens Kit with EF18-55mm + EF 75-300mm Lens, Black', '<ul>\r\n        <li><strong>Improved Dual Pixel CMOS AF and eye detection AF</strong></li>\r\n        <li>24.1 Megapixel CMOS (APS-C) sensor with ISO 100–6400 (H: 12800)</li>\r\n        <li>Built-in Wi-Fi and NFC technology</li>\r\n        <li>Working temperature range: 32-104°F/0-40°C</li>\r\n        <li>9-Point AF system and AI Servo AF</li>\r\n        <li>Optical Viewfinder with approx 95% viewing coverage</li>\r\n        <li>Use the EOS Utility Webcam Beta Software (Mac and Windows) to turn your compatible Canon camera into a high-quality webcam</li>\r\n        <li>Video capture resolution: FHD 1080p</li>\r\n    </ul>', NULL),
(6, 4, 18, 'Insta360 X4 Standard Bundle - Waterproof 8K 360 Action Camera, 4K Wide-Angle Video', '<h3>IMMERSIVE 360 VIDEO</h3>     <p>The 8K era is here. Insta360 X4 delivers 360° video in stunning 8K or 5.7K60fps! Use Active HDR to keep shots color accurate too, even in action scenarios.</p>          <h3>SIMPLE, EASY REFRAMING</h3>     <p>With Insta360 X4, get out there and shoot, not having to worry about lining up a shot. Shoot first, then reframe easily after, all in the AI-powered Insta360 app.</p>          <h3>INVISIBLE SELFIE STICK EFFECT</h3>     <p>The signature third-person Insta360 shot, a specialty of 360 cameras. Immerse your viewers in obstruction-free 360° video, just like you\'re using a drone or as if you have your own film crew. (Note: The stick is not included in Standalone Version)</p>          <h3>4K & 170° MAXVIEW</h3>     <p>X4 can also be used as a wide-angle 4K action camera at a whopping 4K60fps, or get super wide views with the incredible 170° MaxView at 4K30fps.</p>          <h3>ULTRA STABLE FOOTAGE</h3>     <p>FlowState Stabilization and 360° Horizon Lock deliver smooth, level shots, no matter how intense the action is.</p>          <h3>SMARTER, RUGGED BUILD</h3>     <p>X4 withstands bumps and hardcore action like a pro. The lens guards are also upgraded, now easier to apply and remove on the go.</p>          <h3>COLD RESISTANT & WATERPROOF</h3>     <p>X4 can handle temperatures as low as -4ºF (-20ºC) with ease. You can also take X4 down to 33ft (10m) without a dive case or 164ft (60m) with the Invisible Dive Case.</p>          <h3>IMPROVED 2290MAH BATTERY, FAST CHARGING</h3>     <p>Compared to Insta360 X3, X4 offers 67% longer run time. Capture up to 135 minutes on one charge, with fast charging to get back out there faster than ever.</p>          <h3>2.5\" GORILLA GLASS TOUCHSCREEN</h3>     <p>X4 is equipped with an ultra bright 2.5\" touchscreen with strong Corning Gorilla Glass. Rock solid protection that\'s easy to use and control.</p>', NULL),
(7, 3, 1, 'Apple iPad Pro 11-Inch (M4): Built for Apple Intelligence, Ultra Retina XDR Display, 256GB, 12MP Front/Back Camera, LiDAR Scanner, Wi-Fi 6E, Face ID, All-Day Battery Life — Space Black', '<div class=\"product-description\">\r\n    <h3>WHY IPAD PRO</h3>\r\n    <p>iPad Pro is the ultimate iPad experience in an impossibly thin and light design. Featuring the breakthrough Ultra Retina XDR display, outrageous performance from the M4 chip, superfast wireless connectivity,* and compatibility with Apple Pencil Pro.* Plus powerful productivity features in iPadOS.</p>\r\n    \r\n    <h3>BUILT FOR APPLE INTELLIGENCE</h3>\r\n    <p>Apple Intelligence is the personal intelligence system that helps you write, express yourself, and get things done effortlessly. With groundbreaking privacy protections, it gives you peace of mind that no one else can access your data—not even Apple.*</p>\r\n    \r\n    <h3>11-INCH ULTRA RETINA XDR DISPLAY</h3>\r\n    <p>Ultra Retina XDR delivers extreme brightness and contrast and exceptional color accuracy and features advanced technologies like ProMotion, P3 wide color, and True Tone.* Plus a nano-texture display glass option is available in 1TB and 2TB configurations.</p>\r\n    \r\n    <h3>PERFORMANCE AND STORAGE</h3>\r\n    <p>Up to 10-core CPU in the M4 chip delivers powerful performance, while the 10‑core GPU provides blazing-fast graphics. And with all-day battery life, you can do anything you imagine on iPad Pro.* Up to 2TB of storage means you can store everything from apps to large files like 4K video.*</p>\r\n    \r\n    <h3>IPADOS + APPS</h3>\r\n    <p>iPadOS makes iPad more productive, intuitive, and versatile. With iPadOS, run multiple apps at once, use Apple Pencil to write in any text field with Scribble, and edit and share photos.* Stage Manager makes multitasking easy with resizable, overlapping apps and external display support. iPad Pro comes with essential apps like Safari, Messages, and Keynote, with over a million more apps available on the App Store.</p>\r\n    \r\n    <h3>APPLE PENCIL AND MAGIC KEYBOARD FOR IPAD PRO</h3>\r\n    <p>Apple Pencil Pro transforms iPad Pro into an immersive drawing canvas and the world’s best note‑taking device. Apple Pencil (USB-C) is also compatible with iPad Pro. Magic Keyboard for iPad Pro features a thin and light design, a great typing experience, and a built‑in glass trackpad with haptic feedback, while doubling as a protective cover for iPad.*</p>\r\n    \r\n    <h3>ADVANCED CAMERAS</h3>\r\n    <p>iPad Pro features a landscape 12MP Ultra Wide front camera that supports Center Stage for videoconferencing or epic Portrait mode selfies. The 12MP Wide back camera with adaptive True Tone flash is great for capturing photos or 4K video with ProRes support. Four studio-quality microphones and a four-speaker audio system provide rich audio. And AR experiences are enhanced with the LiDAR Scanner to capture a depth map of any space.</p>\r\n</div>', NULL),
(8, 6, 1, 'Apple AirPods Pro 2 Wireless Earbuds, Active Noise Cancellation, Hearing Aid Feature, Bluetooth Headphones, Transparency, Personalized Spatial Audio, High-Fidelity Sound, H2 Chip, USB-C Charging', '<ul>\r\n        <li><strong>PIONEERING HEARING</strong> — AirPods Pro 2 unlock the world’s first all-in-one hearing health experience: a scientifically validated Hearing Test,* clinical-grade and active Hearing Protection.*</li>\r\n        <li><strong>INTELLIGENT NOISE CONTROL</strong> — Active Noise Cancellation removes up to 2x more background noise.* Transparency mode lets you hear the world around you, and Adaptive Audio seamlessly blends Active Noise Cancellation and Transparency mode for the best listening experience in any environment.* And when you’re speaking with someone nearby, Conversation Awareness automatically lowers the volume of what’s playing.*</li>\r\n        <li><strong>IMPROVED SOUND AND CALL QUALITY</strong> — The Apple-designed H2 chip helps to create deeply immersive sound. The low-distortion, custom-built driver delivers crisp, clear high notes and full, rich bass in stunning definition. Voice Isolation improves the quality of phone calls in loud conditions.*</li>\r\n        <li><strong>CUSTOMIZABLE FIT</strong> — Includes four pairs of silicone tips (XS, S, M, L) to fit a wide range of ear shapes and provide all-day comfort. The tips create an acoustic seal to help keep out noise and secure AirPods Pro 2 in place.</li>\r\n        <li><strong>DUST, SWEAT, AND WATER RESISTANT</strong> — Both AirPods Pro and the MagSafe Charging Case are IP54 dust, sweat, and water resistant, so you can listen comfortably in more conditions.*</li>\r\n        <li><strong>PERSONALIZED SPATIAL AUDIO</strong> — With sound that suits your unique ear shape along with dynamic head tracking, AirPods Pro 2 deliver an immersive listening experience that places sound all around you.* You can also listen to select songs, shows, and movies in Dolby Atmos.</li>\r\n        <li><strong>A HIGHER LEVEL OF CONTROL</strong> — Simply swipe, press, and hold the stem to manage playback functions using Touch control. And with Siri Interactions, simply nod your head yes or shake your head no when Siri asks if you’d like to hear a message, answer a call, or manage a notification.*</li>\r\n    </ul>', NULL),
(9, 6, 16, 'Google Pixel Buds Pro 2 - Wireless Earbuds with Active Noise Cancellation – Bluetooth Headphones', '<ul>\r\n        <li><strong>Comfortable and Secure Fit</strong> — Google Pixel Buds Pro 2 are designed to be the most comfortable earbuds ever, with the most secure fit; and they’re built for Google AI, with the Tensor chip that powers premium, immersive sound.</li>\r\n        <li><strong>Lightweight and Adjustable</strong> — Pixel Buds Pro 2 are small, light, and made to stay put; use the twist-to-adjust stabilizer to lock your earbuds in during workouts, or adjust the other way for all-day comfort.</li>\r\n        <li><strong>Tensor A1 Chip</strong> — The first Google Tensor chip in an earbud, the Tensor A1 chip powers twice the Active Noise Cancellation and delivers premium sound.</li>\r\n        <li><strong>Conversation Detection</strong> — Pauses your music and switches your earbuds to Transparency mode when you start talking, so you don’t have to take them out.</li>\r\n        <li><strong>Powerful Sound</strong> — Pixel Buds Pro 2 have large 11mm drivers for powerful bass and a new high-frequency chamber for smooth treble.</li>\r\n    </ul>', NULL),
(10, 7, 17, 'Valve Steam Deck OLED 1TB Handheld Gaming Console', '<ul>\r\n        <li><strong>Storage:</strong> 1TB NVMe SSD</li>\r\n        \r\n        <li><strong>Display:</strong> \r\n            <ul>\r\n                <li>1280 x 800 HDR OLED display</li>\r\n                <li>Premium anti-glare etched glass</li>\r\n                <li>7.4\" Diagonal display size</li>\r\n                <li>Up to 90Hz refresh rate</li>\r\n            </ul>\r\n        </li>\r\n        \r\n        <li><strong>Connectivity:</strong> Wi-Fi 6E</li>\r\n        \r\n        <li><strong>Battery:</strong>\r\n            <ul>\r\n                <li>50Whr battery capacity</li>\r\n                <li>3-12 hours of gameplay (content-dependent)</li>\r\n            </ul>\r\n        </li>\r\n        \r\n        <li><strong>Included Accessories:</strong>\r\n            <ul>\r\n                <li>Carrying case with removable liner</li>\r\n            </ul>\r\n        </li>\r\n        \r\n        <li><strong>Exclusive Features:</strong>\r\n            <ul>\r\n                <li>Custom startup movie</li>\r\n                <li>Exclusive virtual keyboard theme</li>\r\n            </ul>\r\n        </li>\r\n    </ul>', NULL),
(11, 5, 2, 'SAMSUNG 55-Inch Class OLED 4K S90D Series HDR+ Smart TV w/Dolby Atmos, Object Tracking Sound Lite, Motion Xcelerator, Real Depth Enhancer, 4K AI Upscaling, Alexa Built-in ', '<ul>         <li><strong>OLED TECHNOLOGY:</strong> Discover pure blacks, bright whites and Pantone-validated color. Combined with detail and brightness, this pixel-packed screen gives you a dramatic view for everything you watch</li>                  <li><strong>OLED HDR+:</strong> Enjoy powerful brightness and rich contrast. Your TV analyzes each scene to boost brightness and improve image clarity¹</li>                  <li><strong>MOTION XCELERATOR 144Hz:</strong> Play games and content with ultra-smooth motion and virtually no lag or blur. Get uninterrupted action with crisp visuals rendered at top speeds²</li>                  <li><strong>REAL DEPTH ENHANCER:</strong> Experience depth and dimension on screen just like you do in real life. For all content, Real Depth Enhancer mirrors how the human eye processes depth by increasing foreground contrast</li>                  <li><strong>4K AI UPSCALING:</strong> Content instantly transformed to incredibly sharp up to 4K resolution. Whether you\'re streaming an HD movie, watching live sports, or looking back at home videos, experience it all transformed into sharp 4K resolution³</li>                  <li><strong>DOLBY ATMOS & OBJECT TRACKING SOUND LITE:</strong> Keep your ears on the action with built-in Dolby Atmos. You\'ll hear 3D surround sound that follows the movement on screen using our incredible virtual top channel audio</li>                  <li><strong>NQ4 AI GEN2 PROCESSOR:</strong> The power behind the 4K picture that actively improves the quality. Utilizing 20 specialized networks the AI-powered processor drives the intuitive Smart TV Hub, Dolby Atmos sound, and expertly upscaled 4K resolution</li>     </ul>      <div class=\"footnotes\">         <small>             ¹HDR performance may vary based on content and viewing conditions<br>             ²144Hz refresh rate available on supported content and devices<br>             ³Upscaling quality may vary depending on source content         </small>     </div>', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `image_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `section_products`
--

CREATE TABLE `section_products` (
  `deal_section_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sellers_products`
--

CREATE TABLE `sellers_products` (
  `seller_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) DEFAULT NULL,
  `discount` decimal(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trade_ins`
--

CREATE TABLE `trade_ins` (
  `trade_in_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `device_type` varchar(255) DEFAULT NULL,
  `device_condition` varchar(255) DEFAULT NULL,
  `usage_duration` varchar(255) DEFAULT NULL,
  `purchase_price` decimal(10,2) DEFAULT NULL,
  `trade_in_value` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `region_id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Customer','Seller','Administrator') DEFAULT 'Customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `average_rating` decimal(3,2) DEFAULT 0.00,
  `total_ratings` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `wishlist_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `carts_ibfk_1` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `trade_in_id` (`trade_in_id`),
  ADD KEY `cart_items_ibfk_1` (`cart_id`),
  ADD KEY `cart_items_ibfk_2` (`product_id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `deal_sections`
--
ALTER TABLE `deal_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_items_ibfk_1` (`order_id`),
  ADD KEY `order_items_ibfk_2` (`product_id`),
  ADD KEY `order_items_ibfk_3` (`seller_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `products_ibfk_1` (`category_id`),
  ADD KEY `products_ibfk_2` (`brand_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `product_images_ibfk_1` (`product_id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `reviews_ibfk_1` (`user_id`),
  ADD KEY `reviews_ibfk_2` (`product_id`);

--
-- Indexes for table `section_products`
--
ALTER TABLE `section_products`
  ADD PRIMARY KEY (`deal_section_id`,`product_id`),
  ADD KEY `section_products_ibfk_2` (`product_id`);

--
-- Indexes for table `sellers_products`
--
ALTER TABLE `sellers_products`
  ADD PRIMARY KEY (`seller_id`,`product_id`),
  ADD KEY `sellers_products_ibfk_2` (`product_id`);

--
-- Indexes for table `trade_ins`
--
ALTER TABLE `trade_ins`
  ADD PRIMARY KEY (`trade_in_id`),
  ADD KEY `trade_ins_ibfk_1` (`user_id`),
  ADD KEY `trade_ins_ibfk_2` (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`wishlist_id`),
  ADD UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  ADD KEY `wishlists_ibfk_2` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `deal_sections`
--
ALTER TABLE `deal_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trade_ins`
--
ALTER TABLE `trade_ins`
  MODIFY `trade_in_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`cart_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_3` FOREIGN KEY (`trade_in_id`) REFERENCES `trade_ins` (`trade_in_id`),
  ADD CONSTRAINT `cart_items_ibfk_4` FOREIGN KEY (`seller_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`seller_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`brand_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `section_products`
--
ALTER TABLE `section_products`
  ADD CONSTRAINT `section_products_ibfk_1` FOREIGN KEY (`deal_section_id`) REFERENCES `deal_sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `section_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `sellers_products`
--
ALTER TABLE `sellers_products`
  ADD CONSTRAINT `sellers_products_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sellers_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `trade_ins`
--
ALTER TABLE `trade_ins`
  ADD CONSTRAINT `trade_ins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trade_ins_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
