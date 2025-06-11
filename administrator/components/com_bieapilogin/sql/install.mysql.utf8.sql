CREATE TABLE IF NOT EXISTS `#__bieapilogin_logs` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`userid` INT NULL  DEFAULT 0,
`username` VARCHAR(255)  NULL  DEFAULT "",
`mail` VARCHAR(255)  NULL  DEFAULT "",
`date` DATETIME NULL  DEFAULT NULL ,
`token` VARCHAR(255)  NULL  DEFAULT "",
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE INDEX `#__bieapilogin_logs_userid` ON `#__bieapilogin_logs`(`userid`);

CREATE INDEX `#__bieapilogin_logs_date` ON `#__bieapilogin_logs`(`date`);

CREATE TABLE IF NOT EXISTS `#__bieapilogin_tokens` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`state` TINYINT(1)  NULL  DEFAULT 1,
`ordering` INT(11)  NULL  DEFAULT 0,
`checked_out` INT(11)  UNSIGNED,
`checked_out_time` DATETIME NULL  DEFAULT NULL ,
`created_by` INT(11)  NULL  DEFAULT 0,
`modified_by` INT(11)  NULL  DEFAULT 0,
`token` VARCHAR(255)  NOT NULL ,
`device` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
,KEY `idx_state` (`state`)
,KEY `idx_checked_out` (`checked_out`)
,KEY `idx_created_by` (`created_by`)
,KEY `idx_modified_by` (`modified_by`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

