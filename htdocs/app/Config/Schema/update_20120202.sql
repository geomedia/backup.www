ALTER TABLE  `feed_updates` ADD  `cron_activity_id` INT NOT NULL AFTER  `feed_id`;

ALTER TABLE  `cron_activities` ENGINE = INNODB;

ALTER TABLE  `feeds` ENGINE = INNODB;

ALTER TABLE  `feed_tags` ENGINE = INNODB;

ALTER TABLE  `feed_updates` ENGINE = INNODB;

