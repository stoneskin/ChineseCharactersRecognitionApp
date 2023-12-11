
--DROP TABLE IF EXISTS  `ccrApp`.`records`;
--DROP TABLE IF EXISTS  `ccrApp`.`activities`;


CREATE TABLE `ccrapp`.`activities` (
    `ActivityID` INT NOT NULL AUTO_INCREMENT,
    `EventID` INT NOT NULL,
    `StudentName` TEXT NOT NULL,
    `StudentID` INT NULL,
    `JudgeName` TEXT NOT NULL,
    `Level` INT NOT NULL,
    `FinalScore` INT NOT NULL,
    `StartTime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `CompletedTime` DATETIME  NULL,
    `TimeSpent` TIME,
    PRIMARY KEY (`ActivityID`)
    ) ENGINE = InnoDB; 


-- if we don't want drop the records or activities we could use below command to modify the table
ALTER TABLE `ccrapp`.`activities`
MODIFY COLUMN `CompletedTime` DATETIME  NULL;