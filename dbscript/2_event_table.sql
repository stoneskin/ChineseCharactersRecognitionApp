DROP TABLE IF EXISTS  `ccrApp`.`event`;
CREATE TABLE IF NOT EXISTS `ccrApp`.`event` (
    `ID` INT(32) NOT NULL AUTO_INCREMENT,
    `EventName` VARCHAR(100) NOT NULL,
    `AccessKey` VARCHAR(100) NOT NULL,
    `ActiveDate` DATETIME NOT NULL,
    `ExpiredDate` DATETIME NOT NULL,
    `CreatedDate` DATETIME  NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ModifiedDate` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`ID`)
); 

INSERT INTO `ccrApp`. `event` (
    `ID`, 
    `EventName`, 
    `AccessKey`, 
    `ActiveDate`, 
    `ExpiredDate`
) VALUES (
    '1', 
    'CRTest', 
    'testKey', 
    '2023-09-01', 
    '2024-10-10'
) ;
