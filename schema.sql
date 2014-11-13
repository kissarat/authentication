CREATE DATABASE `c9`;
CREATE USER `kissarat`@`localhost`;
GRANT ALL PRIVILEGES ON `c9`.* TO `kissarat`@`localhost`;
USE `c9`;

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`(
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(64) UNIQUE NOT NULL,
  `password` CHAR(32) NOT NULL,
  `salt` CHAR(32) UNIQUE NOT NULL,
  `verified` BOOLEAN NOT NULL DEFAULT FALSE,
  `created` DATE NOT NULL,
  `modified` TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP
);

DELIMITER $$

DROP PROCEDURE IF EXISTS `verify`$$
CREATE PROCEDURE `verify` (IN `email` VARCHAR(64), OUT `salt` CHAR(32))
  BEGIN
    IF (`email` REGEXP '^[^@]+@[^@]+\.[^@]{2,}$') = 0 THEN
      SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Invalid email format';
    ELSE
      SET `salt` = REPLACE(uuid(), '-', '');
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
