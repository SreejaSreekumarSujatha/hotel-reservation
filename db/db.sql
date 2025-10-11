<!---users table-->

CREATE TABLE `hotel_db`.`users` (`id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(100) NOT NULL , `email` VARCHAR(150) NOT NULL , `password` VARCHAR(255) NOT NULL , `role` ENUM('admin','customer') NOT NULL DEFAULT 'customer' , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`), UNIQUE `email_unique` (`email`)) ENGINE = InnoDB;
<!--room table-->
CREATE TABLE `hotel_db`.`rooms` (`id` INT NOT NULL AUTO_INCREMENT , `room_number` VARCHAR(10) NOT NULL , `type` VARCHAR(50) NOT NULL , `price` DECIMAL(10,2) NOT NULL , `status` ENUM('available','booked') NOT NULL DEFAULT 'available' , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`), UNIQUE `roomno_unique` (`room_number`)) ENGINE = InnoDB;