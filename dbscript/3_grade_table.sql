DROP TABLE IF EXISTS  `grade`;

CREATE TABLE IF NOT EXISTS `grade` (
    `Grade` TINYINT NOT NULL,
    `NumberOfWords` INT(32) NOT NULL,
    `TimeLimit` INT(32) NOT NULL,
    `CreateDate` DATETIME NOT NULL default CURRENT_TIMESTAMP,
    `lastModified` DATETIME on update CURRENT_TIMESTAMP NOT NULL default CURRENT_TIMESTAMP,
    PRIMARY KEY (`Grade`)
    );

INSERT INTO `grade` (`Grade`,  `NumberOfWords`,`TimeLimit`, `CreateDate`, `lastModified`)
    VALUES
    (1, 10, 8,  current_timestamp(), current_timestamp()),
    (2, 10, 9, current_timestamp(), current_timestamp()),
    (3, 15, 10, current_timestamp(), current_timestamp()),
    (4, 15, 10, current_timestamp(), current_timestamp()),
    (5, 15, 10, current_timestamp(), current_timestamp()),
    (6, 15, 10, current_timestamp(), current_timestamp()),
    (7, 20, 10, current_timestamp(), current_timestamp()),
    (8, 20, 10, current_timestamp(), current_timestamp()),
    (9, 20, 10, current_timestamp(), current_timestamp());