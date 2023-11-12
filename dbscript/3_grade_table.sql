DROP TABLE IF EXISTS  `ccrApp`.`grade`;

CREATE TABLE IF NOT EXISTS `ccrApp`.`grade` (
    `Grade` TINYINT NOT NULL,
    `SizeOfTest` INT(32) NOT NULL,
    `NumberOfWords` INT(32) NOT NULL,
    `TimeLimit` INT(32) NOT NULL,
    `CreateDate` DATETIME on update CURRENT_TIMESTAMP NOT NULL default CURRENT_TIMESTAMP,
    `lastModified` DATETIME on update CURRENT_TIMESTAMP NOT NULL default CURRENT_TIMESTAMP,
    PRIMARY KEY (`Grade`)
    );

INSERT INTO `ccrApp`.`grade` (`Grade`, `SizeOfTest`,  `NumberOfWords`,`TimeLimit`, `CreateDate`, `lastModified`)
    VALUES
    (1, 50, 100, 300,  current_timestamp(), current_timestamp()),
    (2, 50, 150, 400, current_timestamp(), current_timestamp()),
    (3, 50, 200, 500, current_timestamp(), current_timestamp());