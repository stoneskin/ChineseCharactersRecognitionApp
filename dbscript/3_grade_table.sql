CREATE TABLE IF NOT EXISTS `ccrApp`.`grade` (
    `Grade` TINYINT NOT NULL,
    `SizeOfTest` INT(32) NOT NULL,
    `CreateDate` DATETIME on update CURRENT_TIMESTAMP NOT NULL default CURRENT_TIMESTAMP,
    `lastModified` DATETIME on update CURRENT_TIMESTAMP NOT NULL default CURRENT_TIMESTAMP,
    PRIMARY KEY (`Grade`)
    );

INSERT INTO `ccrApp`.`grade` (`Grade`, `SizeOfTest`, `CreateDate`, `lastModified`)
    VALUES
    (1, 50, current_timestamp(), current_timestamp()),
    (2, 50, current_timestamp(), current_timestamp()),
    (3, 50, current_timestamp(), current_timestamp());