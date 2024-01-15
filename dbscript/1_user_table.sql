DROP TABLE IF EXISTS  `user`;

CREATE TABLE IF NOT EXISTS `user` (
    `ID` INT(32) NOT NULL AUTO_INCREMENT,
    `Email` TINYTEXT NOT NULL,
    `Password` VARCHAR(100) NOT NULL,
    `CreateDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
    `LastVisit` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `IsAdmin` BOOLEAN NOT NULL DEFAULT FALSE,
    `UserType` TINYTEXT NOT NULL DEFAULT 'parent',
    `GradeID` INT(32) DEFAULT 0,
    PRIMARY KEY (`ID`), UNIQUE (`Email`)
    ); 

INSERT INTO `user` (`ID`, `Email`, `Password`, `CreateDate`, `LastVisit`, `IsAdmin`, `UserType`, `GradeID`)
    VALUES (NULL, 'test@test.com', 'test', CURRENT_TIME(), CURRENT_DATE(), 0, 'parent', 0);
INSERT INTO `user` (`ID`, `Email`, `Password`, `CreateDate`, `LastVisit`, `IsAdmin`, `UserType`, `GradeID`)
    VALUES (NULL, 'admin@test.com', 'test', CURRENT_TIME(), CURRENT_DATE(), 1, 'parent', 0);
INSERT INTO `user` (`ID`, `Email`, `Password`, `CreateDate`, `LastVisit`, `IsAdmin`, `UserType`, `GradeID`)
    VALUES (NULL, 'student@test.com', 'test', CURRENT_TIME(), CURRENT_DATE(), 1, 'student', 0);