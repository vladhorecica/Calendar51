CREATE DATABASE IF NOT EXISTS `calendar51`;

USE `calendar51`;

CREATE TABLE IF NOT EXISTS `event` (
  `id`          INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `description` TEXT,
  `date_format` VARCHAR(30)  NOT NULL,
  `from_date`   VARCHAR(200) NOT NULL,
  `to_date`     VARCHAR(200) NOT NULL,
  `location`    VARCHAR(50),
  `comment`     TEXT
);

INSERT INTO `event` (`description`, `date_format`, `from_date`, `to_date`, `location`, `comment`)
VALUES ('Retro Meeting', 'd-m-y H:i:s', '1469604600', '1469608200', '52/I Room', 'Bring cookies.');
