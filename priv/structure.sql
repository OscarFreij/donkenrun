
CREATE TABLE `donken_orders` (
  `id` int(11) NOT NULL,
  `customer` varchar(100) NOT NULL,
  `paid` tinyint(1) NOT NULL,
  `delivered` tinyint(1) NOT NULL,
  `comment` text NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `donken_order_products` (
  `donken_orders_id` int(11) NOT NULL,
  `donken_products_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `donken_products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `donken_orders`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `donken_order_products`
  ADD PRIMARY KEY (`donken_orders_id`,`donken_products_id`),
  ADD KEY `donken_products_id` (`donken_products_id`);

ALTER TABLE `donken_products`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `donken_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `donken_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `donken_order_products`
  ADD CONSTRAINT `donken_order_products_ibfk_1` FOREIGN KEY (`donken_orders_id`) REFERENCES `donken_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `donken_order_products_ibfk_2` FOREIGN KEY (`donken_products_id`) REFERENCES `donken_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
