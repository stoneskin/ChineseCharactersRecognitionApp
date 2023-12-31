DROP TABLE IF EXISTS `records`;

CREATE TABLE `records` (
    `RecordID` INT NOT NULL AUTO_INCREMENT,
    `ActivityID` INT NOT NULL,
    `WordID` INT NOT NULL,
    `Passed` BOOLEAN NOT NULL,
    `TimeElapsed` INT NOT NULL,
    PRIMARY KEY (`RecordID`),
    FOREIGN KEY (`ActivityID`) REFERENCES activities(ActivityID),
    FOREIGN KEY (`WordID`) REFERENCES wordslibrary(ID)
    ) 
