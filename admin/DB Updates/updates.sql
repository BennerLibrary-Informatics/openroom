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

--remove google exchange format and terse email message options, they didn't add anything of value
ALTER TABLE `settings` DROP COLUMN `email_can_gef`, DROP COLUMN `email_can_terse`, DROP COLUMN `email_cond_gef`, DROP COLUMN `email_cond_terse`, DROP COLUMN `email_res_gef`, DROP COLUMN `email_res_terse`;

--added email_message setting
INSERT INTO `settings` (`settingname`, `settingvalue`) VALUES ('email_message', 'Your room has been reserved! Keep this email as proof of your reservation as you may be asked to provide confirmation upon arrival. As a courtesy to other patrons, please cancel your reservation if this room is no longer needed.');
