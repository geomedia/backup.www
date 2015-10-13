ALTER TABLE  `feed_items_tags` ADD  `class` VARCHAR( 100 ) NULL AFTER  `normalized_value`;

ALTER TABLE  `feed_items_tags` CHANGE  `count`  `count` INT( 11 ) NULL DEFAULT NULL ,
CHANGE  `relevance`  `relevance` FLOAT NULL DEFAULT NULL;