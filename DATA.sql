-- 1. สร้างฐานข้อมูลใหม่
CREATE DATABASE IF NOT EXISTS `luxury_watch` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `luxury_watch`;

-- 2. ล้างตารางเก่า
DROP TABLE IF EXISTS `order_details`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `product`;
DROP TABLE IF EXISTS `users`;

-- 3. ตาราง Users
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `role` enum('member','admin') NOT NULL DEFAULT 'member',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 4. ตาราง Product
CREATE TABLE `product` (
  `id_menu` int(11) NOT NULL AUTO_INCREMENT,
  `name_menu` varchar(100) NOT NULL,
  `des_menu` text DEFAULT NULL,
  `how` varchar(100) DEFAULT NULL, -- แบรนด์: Rolex, Patek, Omega
  `img` varchar(255) DEFAULT NULL,
  `price` decimal(12,2) DEFAULT 0.00, -- เพิ่มขนาดตัวเลขรองรับราคานาฬิกาแพงๆ
  PRIMARY KEY (`id_menu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 5. ตาราง Orders
CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `receiver_name` varchar(100) NOT NULL,
  `receiver_address` text NOT NULL,
  `receiver_phone` varchar(20) NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `status` enum('pending','paid','shipped','cancelled') NOT NULL DEFAULT 'pending',
  `payment_method` enum('cod','transfer') NOT NULL DEFAULT 'cod',
  `slip_image` varchar(255) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 6. ตาราง Order Details
CREATE TABLE `order_details` (
  `detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  PRIMARY KEY (`detail_id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id_menu`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 7. ข้อมูลตัวอย่าง Admin (Tel: 0888888888, Pass: 1234)
INSERT INTO `users` (`fullname`, `phone`, `password`, `address`, `role`) VALUES
('Admin Timekeeper', '0888888888', '$2y$10$U.T.N.F.N.F.N.F.N.F.N.Ou.ExampleHashFor1234', 'Luxury Watch HQ', 'admin');

-- 8. ข้อมูลตัวอย่างสินค้า (นาฬิกา)
INSERT INTO `product` (`name_menu`, `des_menu`, `how`, `img`, `price`) VALUES
('Rolex Submariner Date', 'นาฬิกาดำน้ำระดับตำนาน ตัวเรือน Oystersteel หน้าปัดสีดำ ขอบเซรามิก Cerachrom ทนทานและสง่างาม', 'Rolex', 'rolex_sub.jpg', 450000.00),
('Patek Philippe Nautilus', 'สปอร์ตหรูยอดนิยมตลอดกาล หน้าปัดสีน้ำเงินไล่เฉด ตัวเรือน Stainless Steel ดีไซน์แปดเหลี่ยมโค้งมน', 'Patek Philippe', 'patek_nautilus.jpg', 1200000.00),
('Omega Speedmaster Moonwatch', 'นาฬิกาเรือนแรกที่ไปดวงจันทร์ โครโนกราฟระดับตำนาน เครื่อง Co-Axial Master Chronometer', 'Omega', 'omega_speedmaster.jpg', 285000.00),
('Audemars Piguet Royal Oak', 'ไอคอนแห่งความหรูหรา หน้าปัด Grande Tapisserie ตัวเรือนและสาย Stainless Steel ขัดด้านสลับเงา', 'Audemars Piguet', 'ap_royaloak.jpg', 950000.00);