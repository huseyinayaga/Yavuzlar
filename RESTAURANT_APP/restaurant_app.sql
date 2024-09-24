-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: db:3306
-- Üretim Zamanı: 24 Eyl 2024, 09:19:26
-- Sunucu sürümü: 9.0.1
-- PHP Sürümü: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `restaurant_app`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `basket`
--

CREATE TABLE `basket` (
  `id` int NOT NULL,
  `userID` int NOT NULL,
  `foodID` int NOT NULL,
  `note` text COLLATE utf8mb4_turkish_ci,
  `quantity` tinyint NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `basket`
--

INSERT INTO `basket` (`id`, `userID`, `foodID`, `note`, `quantity`, `created_at`) VALUES
(1, 10, 4, 'sicak gelsin', 4, '2024-09-18 10:57:38'),
(3, 9, 3, '', 1, '2024-09-19 08:17:47'),
(4, 9, 4, '', 1, '2024-09-19 08:17:47'),
(5, 10, 3, 'Hızlı gelsin', 5, '2024-09-19 08:37:04'),
(6, 12, 5, 'yavuz pizzalar iyi pişsin evladım', 4, '2024-09-20 12:46:58'),
(7, 14, 4, 'hızlı getir', 1, '2024-09-21 16:42:05'),
(8, 12, 10, '', 2, '2024-09-21 21:24:00'),
(9, 12, 9, '', 3, '2024-09-21 21:30:32'),
(10, 10, 3, '', 50, '2024-09-22 14:09:54'),
(11, 10, 5, '', 4, '2024-09-23 08:10:28');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `userID` int NOT NULL,
  `restaurantID` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `description` text COLLATE utf8mb4_turkish_ci NOT NULL,
  `score` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `comments`
--

INSERT INTO `comments` (`id`, `userID`, `restaurantID`, `username`, `title`, `description`, `score`, `created_at`, `updated_at`) VALUES
(1, 10, 1, 'yavuz', 'yavas teslimat', 'kurye cok yavasti ', 3, '2024-09-19 09:55:04', NULL),
(5, 9, 1, 'deneme', 'kjadskfsj', 'kjadksljf', 8, '2024-09-19 10:07:16', NULL),
(6, 12, 1, 'yusufNas', 'yavuz beni kızdırıyor', 'iyi pişsin yazdım çiğ hamur göndermiş o yüzden 1 puan ', 1, '2024-09-20 12:48:01', NULL),
(7, 12, 5, 'yusufNas', 'çok yavaş', 'Resul kebap almaz olaydım Adana istedim bana adana diye urfa göndermiş  kebap işini ehline bırakacaksınız Yusuf Has Kebap Yakında!!!', 1, '2024-09-21 21:29:21', NULL),
(8, 12, 3, 'yusufNas', 'Super', 'Yusuf Has doner bu isi yapiyor', 10, '2024-09-21 21:50:01', NULL),
(9, 14, 5, 'braderAksın', 'guzel', 'deneme', 5, '2024-09-22 14:06:30', NULL),
(10, 10, 2, 'yavuz', 'kotu pismis', 'lavuk pizaayi cig gondermis hamur bildigin', 1, '2024-09-23 08:13:16', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `company`
--

CREATE TABLE `company` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `description` text COLLATE utf8mb4_turkish_ci NOT NULL,
  `logo_path` text CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `company`
--

INSERT INTO `company` (`id`, `name`, `description`, `logo_path`, `deleted_at`) VALUES
(1, 'SiberVatan', 'siber vatan yemek sirketi ltd.', 'company_logo/siber_vatan.png', NULL),
(6, 'Yavuzlar', 'Yavuzlar yemek şirketi ltd.', 'company_logo/header-logo.png', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cupon`
--

CREATE TABLE `cupon` (
  `id` int NOT NULL,
  `restaurantID` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `discount` decimal(9,2) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `cupon`
--

INSERT INTO `cupon` (`id`, `restaurantID`, `name`, `discount`, `created_at`) VALUES
(1, 1, 'SADICAN', 50.00, '2024-09-18 11:00:00'),
(4, 3, 'YUSUF50', 50.00, '2024-09-20 15:09:40'),
(6, 2, 'YAVUZ100', 100.00, '2024-09-21 16:48:08');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `food`
--

CREATE TABLE `food` (
  `id` int NOT NULL,
  `restaurantID` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `description` text COLLATE utf8mb4_turkish_ci NOT NULL,
  `image_path` text COLLATE utf8mb4_turkish_ci NOT NULL,
  `price` decimal(7,2) NOT NULL,
  `discount` decimal(7,2) NOT NULL DEFAULT '0.00',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `food`
--

INSERT INTO `food` (`id`, `restaurantID`, `name`, `description`, `image_path`, `price`, `discount`, `created_at`, `deleted_at`) VALUES
(3, 1, 'Benim ikilim', '2 Adet Tavukburger® Sandviç + Patates Kızartması (Orta) + Kutu İçecek', 'food_photo/benim_ikilim.png', 200.00, 30.00, '2024-09-18 09:25:34', NULL),
(4, 1, 'Benim dörtlüm', '4 Adet Seçeceğiniz Sandviç + Patates Kızartması (Büyük) + 1 L. İçecek', 'food_photo/benim_dortlum.png', 375.00, 25.00, '2024-09-18 10:20:15', NULL),
(5, 2, 'Karışık Pizza (Orta Boy) (1 Kişilik)', 'Kaşar peyniri, sucuk, salam, sosis mantar, domates, biber', 'food_photo/orta_boy.png', 160.00, 0.00, '2024-09-18 13:28:05', NULL),
(6, 1, 'Whopper Menu', 'Whopper® + Patates Kızartması (Orta) + Kutu İçecek', 'food_photo/whopper.png', 240.00, 0.00, '2024-09-21 15:39:10', '2024-09-21 15:46:27'),
(7, 4, 'Sardalya Tava', '1 kg Sardalya Tava Ağzınıza Layık', 'food_photo/30066440.jpg', 280.00, 0.00, '2024-09-21 15:45:10', NULL),
(8, 4, 'Izgara Karadeniz Somon', '1 kg Izagra Somon Tava Balıkçı Mehmetten Ağzınıza Layık', 'food_photo/30066579.jpg', 380.00, 0.00, '2024-09-21 16:33:32', NULL),
(9, 3, 'Döner', 'Yarım Ekmek Arası Tavuk Yusuf Has Döner', 'food_photo/9576964.jpg', 67.00, 0.00, '2024-09-21 16:36:09', NULL),
(10, 5, 'Urfa Kebap', 'Mevsim salata, ezme, haydari, pirinç pilavı, közlenmiş domates, közlenmiş biber, soğan, lavaş ile', 'food_photo/8528756.jpg', 280.00, 30.00, '2024-09-21 21:19:23', NULL),


-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `userID` int NOT NULL,
  `order_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT 'Hazırlanıyor',
  `total_price` decimal(9,2) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `orders`
--

INSERT INTO `orders` (`id`, `userID`, `order_status`, `total_price`, `created_at`) VALUES
(1, 10, 'Teslim Edildi', 2255.00, '2024-09-18 13:48:05'),
(2, 10, 'Hazırlanıyor', 1905.00, '2024-09-18 14:07:51'),
(3, 10, 'Hazırlanıyor', 190.50, '2024-09-18 14:10:02'),
(4, 10, 'Hazırlanıyor', 1905.00, '2024-09-18 14:14:27'),
(5, 10, 'Hazırlanıyor', 381.00, '2024-09-18 19:17:23'),
(6, 9, 'Hazırlanıyor', 1600.00, '2024-09-18 19:26:05'),
(7, 9, 'Hazırlanıyor', 540.50, '2024-09-19 08:17:47'),
(8, 10, 'Hazırlanıyor', 702.50, '2024-09-19 08:37:04'),
(9, 12, 'Hazırlanıyor', 640.00, '2024-09-20 12:46:58'),
(10, 14, 'Teslim Edildi', 350.00, '2024-09-21 16:42:05'),
(11, 12, 'Teslim Edildi', 500.00, '2024-09-21 21:24:00'),
(12, 12, 'Teslim Edildi', 51.00, '2024-09-21 21:30:32'),
(13, 10, 'Hazırlanıyor', 8500.00, '2024-09-22 14:09:54'),
(14, 10, 'Teslim Edildi', 640.00, '2024-09-23 08:10:28');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `orders_items`
--

CREATE TABLE `orders_items` (
  `id` int NOT NULL,
  `orderID` int NOT NULL,
  `foodID` int NOT NULL,
  `quantity` tinyint NOT NULL,
  `price` decimal(9,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `orders_items`
--

INSERT INTO `orders_items` (`id`, `orderID`, `foodID`, `quantity`, `price`) VALUES
(1, 1, 3, 10, 1905.00),
(2, 1, 4, 1, 350.00),
(3, 2, 3, 10, 1905.00),
(4, 3, 3, 1, 190.50),
(5, 4, 3, 10, 1905.00),
(6, 5, 3, 2, 381.00),
(7, 6, 5, 10, 1600.00),
(8, 7, 3, 1, 190.50),
(9, 7, 4, 1, 350.00),
(10, 8, 3, 5, 702.50),
(11, 9, 5, 4, 640.00),
(12, 10, 4, 1, 350.00),
(13, 11, 10, 2, 500.00),
(14, 12, 9, 3, 51.00),
(15, 13, 3, 50, 8500.00),
(16, 14, 5, 4, 640.00);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `permissions`
--

CREATE TABLE `permissions` (
  `permissionID` tinyint NOT NULL,
  `permissionName` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `permissions`
--

INSERT INTO `permissions` (`permissionID`, `permissionName`) VALUES
(1, 'Müşteri Listeleme, Müşteri Arama, Müşteri Banlama, Müşteri Filtreleme, Firma Ekleme, Firma Silme, Firma Güncelleme, Firma Arama, Firma Listeleme, Kupon Ekleme, Kupon Silme'),
(2, 'Yemek Listeleme, Yemek Ekleme, Yemek Silme, Yemek Güncelleme, Yemek Arama, Sipariş Görüntüleme, Sipariş Durumu Güncelleme'),
(3, 'Para Görüntüleme, Profil Güncelleme, Sipariş Verme, Kupon Ekleme, Sipariş Listeleme, Para Yükleme, Yemek Arama');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `restaurant`
--

CREATE TABLE `restaurant` (
  `id` int NOT NULL,
  `companyID` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `description` text COLLATE utf8mb4_turkish_ci NOT NULL,
  `image_path` text COLLATE utf8mb4_turkish_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `score` double(5,1) DEFAULT '0.0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `restaurant`
--

INSERT INTO `restaurant` (`id`, `companyID`, `name`, `description`, `image_path`, `created_at`, `score`) VALUES
(1, 1, 'Sadican Burger', 'Klasik burger menüsü ile ünlü.', 'restaurant_logo/burger.png', '2024-09-18 09:10:45', 0.0),
(2, 1, 'Yavuz Pizza', 'Enfes pizza çeşitleri.', 'restaurant_logo/pizza.png', '2024-09-18 09:16:45', 0.0),
(3, 1, 'Yusuf Has Döner', 'Taze ve lezzetli döner.', 'restaurant_logo/doner.png', '2024-09-18 09:17:37', 0.0),
(4, 1, 'Balıkçı Mehmet', 'Taze ve lezzetli balık.', 'restaurant_logo/hamsi.png', '2024-09-18 09:18:15', 0.0),
(5, 6, 'Resul Kebap', 'Kebap işi bizden sorulur.', 'restaurant_logo/kebap.png', '2024-09-21 21:16:01', 0.0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `role`
--

CREATE TABLE `role` (
  `roleID` tinyint NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `role`
--

INSERT INTO `role` (`roleID`, `name`) VALUES
(1, 'Admin'),
(2, 'Firma'),
(3, 'Müşteri');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `roles_permissions`
--

CREATE TABLE `roles_permissions` (
  `roleID` tinyint NOT NULL,
  `permissionID` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `roles_permissions`
--

INSERT INTO `roles_permissions` (`roleID`, `permissionID`) VALUES
(1, 1),
(2, 2),
(3, 3);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `userID` int NOT NULL,
  `companyID` int DEFAULT NULL,
  `roleID` tinyint NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL,
  `surname` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `balance` decimal(9,2) NOT NULL DEFAULT '5000.00',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`userID`, `companyID`, `roleID`, `name`, `surname`, `username`, `password`, `balance`, `created_at`, `deleted_at`) VALUES
(9, NULL, 3, 'deneme', 'deneme', 'deneme', '$argon2id$v=19$m=65536,t=4,p=1$QzRSazN2QTZoV01RSDFybg$QWlSVvkYeSKZXLwkIuOExhE0gsBIkPrVgrAFXvxzrpM', 2859.50, '2024-09-17 15:54:40', '2024-09-20 11:22:22'),
(10, NULL, 3, 'yavuz', 'yilmaz', 'yavuz', '$argon2id$v=19$m=65536,t=4,p=1$VU5BNlBkRjRnRjAzWFRXcA$iOsAq9XMxm4yJH7/QhK6GtxziHxPRlfm8zrqg971AR8', 42996.00, '2024-09-17 16:03:38', NULL),
(11, NULL, 1, 'admin', 'admin', 'admin', '$argon2id$v=19$m=65536,t=4,p=1$S1ByODguT2hRNHNXUk5LNg$0BbiwYKfR2AIOdkHWbk8RdpyMcwGIknpvHdeBxhi/og', 5000.00, '2024-09-18 08:22:09', NULL),
(12, NULL, 3, 'yusuf', 'nas', 'yusufNas', '$argon2id$v=19$m=65536,t=4,p=1$dkZhdmNpY0d6SkV0MEYwaA$NUArlYZ+NkSfBybIGzTHjbmJAtP+VTZKcQSoG2S8bzI', 3809.00, '2024-09-20 12:46:02', NULL),
(13, 1, 2, 'Siber', 'vatan', 'siberVatan', '$argon2id$v=19$m=65536,t=4,p=1$bUJSQjZFVXZoSC5PS0RVaA$ivUysyl7AYMVgpdSaFf+VvaYrfZuqt0mC8hOZvVyaJQ', 5000.00, '2024-09-20 15:36:01', NULL),
(14, NULL, 3, 'brader', 'aksın', 'braderAksın', '$argon2id$v=19$m=65536,t=4,p=1$Tk03YmlQamlVMTV2eTFJcw$PAf8JERmED+jE8D3ADVHX1J/k81iilUelmPvojF2R0g', 4650.00, '2024-09-21 16:40:30', NULL),
(16, 6, 2, 'mehmet', 'özlerçelik', 'Yavuzlar', '$argon2id$v=19$m=65536,t=4,p=1$dXdJNE1wNkNtZmJ2S3RLMw$7c3YUO7Q8PnXecgDTdP1fA1BJQK3UgDhVnHT/Ulf6jA', 5000.00, '2024-09-21 21:07:36', NULL);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `basket`
--
ALTER TABLE `basket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `basket_users` (`userID`),
  ADD KEY `basket_food` (`foodID`);

--
-- Tablo için indeksler `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_users_id` (`userID`),
  ADD KEY `comments_users_username` (`username`),
  ADD KEY `comments_restaurant` (`restaurantID`);

--
-- Tablo için indeksler `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `cupon`
--
ALTER TABLE `cupon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_cupon` (`restaurantID`);

--
-- Tablo için indeksler `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`id`),
  ADD KEY `food_restaurant` (`restaurantID`);

--
-- Tablo için indeksler `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_users` (`userID`);

--
-- Tablo için indeksler `orders_items`
--
ALTER TABLE `orders_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_itemsANDorders` (`orderID`),
  ADD KEY `order_itemsANDfood` (`foodID`);

--
-- Tablo için indeksler `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`permissionID`);

--
-- Tablo için indeksler `restaurant`
--
ALTER TABLE `restaurant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_restaurant` (`companyID`);

--
-- Tablo için indeksler `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`roleID`);

--
-- Tablo için indeksler `roles_permissions`
--
ALTER TABLE `roles_permissions`
  ADD KEY `roleID` (`roleID`),
  ADD KEY `permissionID` (`permissionID`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `company_users` (`companyID`),
  ADD KEY `role_users` (`roleID`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `basket`
--
ALTER TABLE `basket`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Tablo için AUTO_INCREMENT değeri `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `company`
--
ALTER TABLE `company`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `cupon`
--
ALTER TABLE `cupon`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `food`
--
ALTER TABLE `food`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Tablo için AUTO_INCREMENT değeri `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Tablo için AUTO_INCREMENT değeri `orders_items`
--
ALTER TABLE `orders_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Tablo için AUTO_INCREMENT değeri `permissions`
--
ALTER TABLE `permissions`
  MODIFY `permissionID` tinyint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `restaurant`
--
ALTER TABLE `restaurant`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `role`
--
ALTER TABLE `role`
  MODIFY `roleID` tinyint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `userID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `basket`
--
ALTER TABLE `basket`
  ADD CONSTRAINT `basket_food` FOREIGN KEY (`foodID`) REFERENCES `food` (`id`),
  ADD CONSTRAINT `basket_users` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Tablo kısıtlamaları `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_restaurant` FOREIGN KEY (`restaurantID`) REFERENCES `restaurant` (`id`),
  ADD CONSTRAINT `comments_users_id` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `comments_users_username` FOREIGN KEY (`username`) REFERENCES `users` (`username`);

--
-- Tablo kısıtlamaları `cupon`
--
ALTER TABLE `cupon`
  ADD CONSTRAINT `restaurant_cupon` FOREIGN KEY (`restaurantID`) REFERENCES `restaurant` (`id`);

--
-- Tablo kısıtlamaları `food`
--
ALTER TABLE `food`
  ADD CONSTRAINT `food_restaurant` FOREIGN KEY (`restaurantID`) REFERENCES `restaurant` (`id`);

--
-- Tablo kısıtlamaları `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `order_users` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Tablo kısıtlamaları `orders_items`
--
ALTER TABLE `orders_items`
  ADD CONSTRAINT `order_itemsANDfood` FOREIGN KEY (`foodID`) REFERENCES `food` (`id`),
  ADD CONSTRAINT `orders_itemsANDorders` FOREIGN KEY (`orderID`) REFERENCES `orders` (`id`);

--
-- Tablo kısıtlamaları `restaurant`
--
ALTER TABLE `restaurant`
  ADD CONSTRAINT `company_restaurant` FOREIGN KEY (`companyID`) REFERENCES `company` (`id`);

--
-- Tablo kısıtlamaları `roles_permissions`
--
ALTER TABLE `roles_permissions`
  ADD CONSTRAINT `roles_permissions_ibfk_1` FOREIGN KEY (`roleID`) REFERENCES `role` (`roleID`),
  ADD CONSTRAINT `roles_permissions_ibfk_2` FOREIGN KEY (`permissionID`) REFERENCES `permissions` (`permissionID`);

--
-- Tablo kısıtlamaları `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `company_users` FOREIGN KEY (`companyID`) REFERENCES `company` (`id`),
  ADD CONSTRAINT `role_users` FOREIGN KEY (`roleID`) REFERENCES `role` (`roleID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
