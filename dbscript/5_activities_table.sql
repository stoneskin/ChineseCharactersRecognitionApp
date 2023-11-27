DROP TABLE IF EXISTS  `ccrApp`.`activities`;

CREATE TABLE `ccrapp`.`activities` (
    `ActivityID` INT NOT NULL AUTO_INCREMENT,
    `EventID` INT NOT NULL,
    `StudentName` TEXT NOT NULL,
    `StudentID` INT NULL,
    `JudgeName` TEXT NOT NULL,
    `Level` INT NOT NULL,
    `FinalScore` INT NOT NULL,
    `StartTime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `CompletedTime` DATETIME on update CURRENT_TIMESTAMP NULL,
    PRIMARY KEY (`ActivityID`)
    ) ENGINE = InnoDB; 