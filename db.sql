CREATE TABLE IF NOT EXISTS `posts` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`create_date` TIMESTAMP,
	`title` VARCHAR(50),
	`content` VARCHAR(300),
	`hotness` INT,
	PRIMARY KEY (`id`),
	INDEX `hotness` (`hotness`)
);

CREATE TABLE IF NOT EXISTS `users_views` (
	`user_id` INT,
	`post_id` INT,
	INDEX `user_id_post_id` (`user_id`, `post_id`)
);