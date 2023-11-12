CREATE TABLE `ccrapp`.`records` (
    `RecordID` INT NOT NULL AUTO_INCREMENT,
    `ActivityID` INT NOT NULL,
    `WordID` INT NOT NULL,
    `Passed` BOOLEAN NOT NULL,
    `TimeElapsed` INT NOT NULL,
    PRIMARY KEY (`RecordID`),
    FOREIGN KEY (ActivityID) REFERENCES Activities(ActivityID),
    FOREIGN KEY (WordID) REFERENCES WordsLibrary(ID)
    ) ENGINE = InnoDB; 
