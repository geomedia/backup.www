ALTER TABLE  `feed_items_tags` CHANGE  `feed_tag_id`  `feed_tag_id` INT( 11 ) NULL DEFAULT NULL;

ALTER TABLE  `feed_items_tags` ADD  `country_id` INT NULL DEFAULT NULL AFTER  `feed_tag_id`;

ALTER TABLE  `countries` ADD  `feed_items_tag_count` INT NOT NULL;

