
--specify start time and end time for app
INSERT INTO `settings` (`settingname`, `settingvalue`) VALUES ('starttime', '');
INSERT INTO `settings` (`settingname`, `settingvalue`) VALUES ('endtime', '');

--adds phone number field
INSERT INTO `settings` (`settingname`, `settingvalue`) VALUES ('phone_number', '');

--room capacity max and min
ALTER TABLE `rooms` DROP COLUMN `roomcapacity`;
ALTER TABLE `rooms` ADD `roomcapacitymin` INT(11) NOT NULL AFTER `roomdescription`, ADD `roomcapacitymax` INT(11) NOT NULL AFTER `roomcapacitymin`;

--deletedrooms table max and min
ALTER TABLE `deletedrooms` DROP COLUMN `roomcapacity`;
ALTER TABLE `deletedrooms` ADD `roomcapacitymin` INT(11) NOT NULL AFTER `roomname`, ADD `roomcapacitymax` INT(11) NOT NULL AFTER `roomcapacitymin`;

--added supervisor role
CREATE TABLE IF NOT EXISTS `supervisors` (
  `username` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`username`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
