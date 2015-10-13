RENAME TABLE  `feed_items_feed_tags` TO  `feed_items_tags` ;

ALTER TABLE  `feed_items_tags` ADD  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;

ALTER TABLE  `feed_items_tags` ADD  `name` VARCHAR( 255 ) NOT NULL ,
ADD  `count` INT NOT NULL ,
ADD  `relevance` FLOAT NOT NULL ,
ADD  `raw_data` TEXT NOT NULL;

ALTER TABLE  `feed_items_tags` ADD  `normalized_value` VARCHAR( 255 ) NOT NULL AFTER  `name`;

ALTER TABLE  `feed_items_tags` ADD  `source` VARCHAR( 50 ) NOT NULL;

ALTER TABLE  `feed_items_tags` ADD INDEX (  `source` );

ALTER TABLE  `feed_items_tags` ADD INDEX (  `feed_item_id` );

ALTER TABLE  `feed_tags` CHANGE  `name`  `normalized_value` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE  `feed_items_tags` CHANGE  `normalized_value`  `normalized_value` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;

ALTER TABLE  `feed_items_tags` ENGINE = INNODB;

ALTER TABLE  `feed_items` ADD  `feed_items_tag_count` INT NOT NULL;

ALTER TABLE  `feed_tags` ADD  `feed_items_tag_count` INT NOT NULL;

ALTER TABLE  `feed_items` ADD  `analyzed` TINYINT( 1 ) NOT NULL;

