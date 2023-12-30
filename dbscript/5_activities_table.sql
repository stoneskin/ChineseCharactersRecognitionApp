
--DROP TABLE IF EXISTS  `ccrApp`.`records`;
--DROP TABLE IF EXISTS  `ccrApp`.`activities`;


CREATE TABLE `activities` (
    `ActivityID` INT NOT NULL AUTO_INCREMENT,
    `EventID` INT NOT NULL,
    `StudentName` TEXT NOT NULL,
    `StudentID` INT NULL,
    `JudgeName` TEXT NOT NULL,
    `Level` INT NOT NULL,
    `FinalScore` INT NOT NULL,
    `StartTime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `CompletedTime` DATETIME  NULL,
    `TimeSpent` INT,
    PRIMARY KEY (`ActivityID`)
    ) 


