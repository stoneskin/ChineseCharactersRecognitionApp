CREATE TABLE IF NOT EXISTS `ccrApp`.`event` (
    `ID` INT(32) NOT NULL AUTO_INCREMENT,
    `EventName` VARCHAR(100) NOT NULL,
    `AccessKey` VARCHAR(100) NOT NULL,
    `ActiveDate` DATETIME NOT NULL,
    `ExpiredDate` DATETIME NOT NULL,
    `CreatedDate` DATETIME on update CURRENT_TIMESTAMP NOT NULL,
    `ModifiedDate` DATETIME on update CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY (`ID`)
); 

INSERT INTO `ccrApp`. `event` (
    `ID`, 
    `EventName`, 
    `AccessKey`, 
    `ActiveDate`, 
    `ExpiredDate`, 
    `CreatedDate`, 
    `ModifiedDate`
) VALUES (
    '1', 
    'CRTest', 
    'testKey', 
    '2023-09-01', 
    '2023-10-10', 
    current_timestamp(), 
    current_timestamp()
) ;
