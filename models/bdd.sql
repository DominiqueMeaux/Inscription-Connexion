DROP DATABASE IF EXISTS `session`;

CREATE DATABASE `session`;

USE `session`;


CREATE TABLE `password_resets`(
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `email` VARCHAR(255),
    `token` VARCHAR(255),
    `token` VARCHAR(255),
    `created_at`  DATETIME NOT NULL
)ENGINE=InnoDB;

CREATE TABLE `users`(
    `id`       INT PRIMARY KEY AUTO_INCREMENT,    
    `name`     VARCHAR(255) NOT NULL,
    `email`    VARCHAR(255) NOT NULL,
    `password`      VARCHAR(255) NOT NULL,
    `photo`      VARCHAR(255),
    `created_at`  DATETIME NOT NULL
    
)ENGINE=InnoDB;