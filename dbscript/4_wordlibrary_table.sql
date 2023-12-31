DROP TABLE IF EXISTS  `wordslibrary`;

CREATE TABLE `wordslibrary` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `Words` TEXT NOT NULL,
    `Level` INT NOT NULL,
    PRIMARY KEY (`ID`)) 