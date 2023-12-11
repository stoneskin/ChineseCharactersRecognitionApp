CREATE TABLE IF NOT EXISTS `ccrApp`.`student` (
    `StudentID` INT(32) NOT NULL AUTO_INCREMENT,
    `Email` TINYTEXT NOT NULL,
    `Password` VARCHAR(100) NOT NULL,
    `CreateDate` DATETIME NOT NULL default CURRENT_TIMESTAMP,
    `LastVisit` DATETIME on update CURRENT_TIMESTAMP NOT NULL default CURRENT_TIMESTAMP,
    `TeacherID` INT(32),
    `GradeID` TINYINT,
    PRIMARY KEY (`StudentID`), UNIQUE (`Email`)
    ); 