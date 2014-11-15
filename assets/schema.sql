CREATE DATABASE `c9`;
CREATE USER `kissarat`@`localhost`;
GRANT ALL PRIVILEGES ON `c9`.* TO `kissarat`@`localhost`;
USE `c9`;

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`(
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(64) UNIQUE NOT NULL,
  `password` BINARY(64) NOT NULL,
  `salt` VARCHAR(64) UNIQUE NOT NULL,
  `verified` BOOLEAN NOT NULL DEFAULT FALSE,
#   `avatar` BLOB,
  `created` DATE NOT NULL,
  `modified` TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS `log`;
CREATE TABLE `log`(
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user` INT NOT NULL REFERENCES `user`(`id`),
  `status` BOOLEAN NOT NULL,
  `browser` TINYTEXT,
  `host` VARCHAR(32),
  `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

DROP VIEW IF EXISTS `auth`;
CREATE VIEW `auth` AS
  SELECT u.`email`, `browser`, `status`
  FROM `log` l JOIN `user` u ON l.`user` = u.`id`;

DROP VIEW IF EXISTS `last_auth`;
CREATE VIEW `last_auth` AS
  SELECT `email`, count(`time`) as `count`
  FROM `log` l JOIN `user` u ON l.`user` = u.`id`
  GROUP BY `email`
  ORDER BY `time` DESC
  LIMIT 1;

DELIMITER $$

DROP PROCEDURE IF EXISTS `verify`$$
CREATE PROCEDURE `verify` (IN `email` VARCHAR(64), OUT `salt` CHAR(32))
  BEGIN
    IF (`email` REGEXP '^[^@]+@[^@]+\.[^@]{2,}$') = 0 THEN
      SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Invalid email format';
    END IF ;
  END$$

DROP TRIGGER IF EXISTS `user_insert`$$
CREATE TRIGGER `user_insert`
  BEFORE INSERT ON `user`
FOR EACH ROW
  BEGIN
    SET NEW.created = current_timestamp();
    CALL `verify`(NEW.email, NEW.salt);
  END$$

DROP TRIGGER IF EXISTS `user_update`$$
CREATE TRIGGER `user_update`
  BEFORE UPDATE ON `user`
FOR EACH ROW
  BEGIN
    CALL `verify`(NEW.email, NEW.salt);
  END$$

DELIMITER ;
