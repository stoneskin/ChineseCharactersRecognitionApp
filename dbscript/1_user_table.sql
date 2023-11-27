DROP TABLE IF EXISTS  `ccrApp`.`user`;

CREATE TABLE IF NOT EXISTS `ccrApp`.`user` (
    `ID` INT(32) NOT NULL AUTO_INCREMENT,
    `Email` TINYTEXT NOT NULL,
    `Password` VARCHAR(100) NOT NULL,
    `CreateDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
    `LastVisit` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`ID`), UNIQUE (`Email`)
    ); 

INSERT INTO `user` (`ID`, `Email`, `Password`, `CreateDate`, `LastVisit`)
    VALUES (NULL, 'test@test.com', 'abc', CURRENT_TIME(), CURRENT_DATE());