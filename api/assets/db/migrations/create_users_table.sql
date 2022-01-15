CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `first_name` VARCHAR(50) NOT NULL,
    `last_name` VARCHAR(50) NOT NULL DEFAULT '',
    `user_name` VARCHAR(50) NOT NULL,
    `gender` ENUM('Male','Female') NOT NULL,
    `email` VARCHAR(50) NOT NULL,
    `phone_number` VARCHAR(50) NOT NULL,
    `password` VARCHAR(200) DEFAULT NULL,
    `last_login` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;