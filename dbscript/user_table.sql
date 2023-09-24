CREATE TABLE `user`.`user` (
    `ID` INT(32) NOT NULL AUTO_INCREMENT,
    `Email` TINYTEXT NOT NULL,
    `Password` VARCHAR(100) NOT NULL,
    `CreateDate` DATETIME on update CURRENT_TIMESTAMP NOT NULL,
    `LastVisit` DATETIME on update CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY (`ID`), UNIQUE (`Email`)
    ); 