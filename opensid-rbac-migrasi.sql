#
# DROP TABLE + FOREIGN KEY DAN ROLLBACK MENU
#

/* ========= DROP fk_users_groups_users1 ========= */

DROP PROCEDURE IF EXISTS tmp_drop_foreign_key;
DELIMITER $$
CREATE PROCEDURE tmp_drop_foreign_key(IN tableName VARCHAR(64), IN constraintName VARCHAR(64))
BEGIN
    IF EXISTS(
        SELECT * FROM information_schema.table_constraints
        WHERE 
            table_schema    = DATABASE()     AND
            table_name      = 'users_groups'   AND
            constraint_name = 'fk_users_groups_users1' AND
            constraint_type = 'FOREIGN KEY')
    THEN
        SET @query = CONCAT('ALTER TABLE ', tableName, ' DROP FOREIGN KEY ', constraintName, ';');
        PREPARE stmt FROM @query; 
        EXECUTE stmt; 
        DEALLOCATE PREPARE stmt; 
    END IF; 
END$$
DELIMITER ;
CALL tmp_drop_foreign_key('users_groups', 'fk_users_groups_users1');
DROP PROCEDURE tmp_drop_foreign_key;

/* ========= DROP fk_users_groups_groups1 ========= */
DROP PROCEDURE IF EXISTS tmp1_drop_foreign_key;
DELIMITER $$
CREATE PROCEDURE tmp1_drop_foreign_key(IN tableName VARCHAR(64), IN constraintName VARCHAR(64))
BEGIN
    IF EXISTS(
        SELECT * FROM information_schema.table_constraints
        WHERE 
            table_schema    = DATABASE()     AND
            table_name      = 'users_groups'   AND
            constraint_name = 'fk_users_groups_groups1' AND
            constraint_type = 'FOREIGN KEY')
    THEN
        SET @query = CONCAT('ALTER TABLE ', tableName, ' DROP FOREIGN KEY ', constraintName, ';');
        PREPARE stmt FROM @query; 
        EXECUTE stmt; 
        DEALLOCATE PREPARE stmt; 
    END IF; 
END$$
DELIMITER ;
CALL tmp1_drop_foreign_key('users_groups', 'fk_users_groups_groups1');
DROP PROCEDURE tmp1_drop_foreign_key;

/* ========= DROP TABLE ========= */
DROP TABLE IF EXISTS `groups`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `users_groups`;
DROP TABLE IF EXISTS `login_attempts`;
DROP TABLE IF EXISTS `group_perm`;

/* ========= ROLLBACK ========= */
UPDATE `setting_modul` SET `url` = 'man_user' WHERE `setting_modul`.`id` = 11;
UPDATE `setting_modul` SET `url` = 'man_user' WHERE `setting_modul`.`id` = 44;
UPDATE `setting_modul` SET `urut` = '4' WHERE `setting_modul`.`id` = 45;
UPDATE `setting_modul` SET `urut` = '5' WHERE `setting_modul`.`id` = 46;
SET @query1 = 'DELETE FROM `setting_modul` WHERE `id`=204';
PREPARE stmt FROM @query1;
EXECUTE stmt; 
SET @query2 = 'DELETE FROM `setting_modul` WHERE `id`=205';
PREPARE stmt FROM @query2;
EXECUTE stmt;
SET @query3 = 'DELETE FROM `setting_modul` WHERE `id`=206';
PREPARE stmt FROM @query3;
EXECUTE stmt;


#
# Table structure for table 'groups'
#

CREATE TABLE `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Dumping data for table 'groups'
#

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'operator', 'Operator'),
(3, 'redaksi', 'Redaksi'),
(4, 'kontributor', 'Kontributor');


#
# Table structure for table 'users'
#

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(254) NOT NULL,
  `activation_selector` varchar(255) DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `forgotten_password_selector` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_selector` varchar(255) DEFAULT NULL,
  `remember_code` varchar(255) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `user_img` text,
  `id_grup` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `uc_username` UNIQUE (`username`),
  CONSTRAINT `uc_email` UNIQUE (`email`),
  CONSTRAINT `uc_activation_selector` UNIQUE (`activation_selector`),
  CONSTRAINT `uc_forgotten_password_selector` UNIQUE (`forgotten_password_selector`),
  CONSTRAINT `uc_remember_selector` UNIQUE (`remember_selector`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


#
# Dumping data for table 'users'
#

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `email`, `activation_code`, `forgotten_password_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`, `user_img`, `id_grup`) VALUES
     (1, '127.0.0.1', 'adminrbac', '$2y$12$VCCzq5PRAqu35pCZvB9OTu2zFeWojWzzi8CDod4IJgPftkZKc4bDi', 'admin@opendesa.id', '', '','1268889823','1268889823','1', 'admin', 'rbac', 'opendesa', '08100000000', 'kuser.png', 1);


#
# Table structure for table 'users_groups'
#

CREATE TABLE `users_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_users_groups_users1_idx` (`user_id`),
  KEY `fk_users_groups_groups1_idx` (`group_id`),
  CONSTRAINT `uc_users_groups` UNIQUE (`user_id`, `group_id`),
  CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
     (1,1,1);


#
# Table structure for table 'login_attempts'
#

CREATE TABLE `login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


#
# Table structure for table 'group_perm'
#

CREATE TABLE IF NOT EXISTS `group_perm` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `group_id` int(10) UNSIGNED NOT NULL,
  `perm_id` int(10) UNSIGNED DEFAULT NULL,
  `create_id` int(10) UNSIGNED DEFAULT NULL,
  `update_id` int(10) UNSIGNED DEFAULT NULL,
  `delete_id` int(10) UNSIGNED DEFAULT NULL,
  `print_id` int(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `perm_id` (`perm_id`),
  KEY `group_id` (`group_id`),
  KEY `create_id` (`create_id`),
  KEY `update_id` (`update_id`),
  KEY `delete_id` (`delete_id`),
  KEY `print_id` (`print_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Dumping data for table 'group_perm'
#

INSERT INTO `group_perm` (`id`, `group_id`, `perm_id`, `create_id`, `update_id`, `delete_id`, `print_id`) VALUES
(1, 1, 1, 0, 0, 0, 0),
(2, 1, 200, 0, 0, 0, 0),
(3, 1, 2, 0, 0, 0, 0),
(4, 1, 3, 0, 0, 0, 0),
(5, 1, 4, 0, 0, 0, 0),
(6, 1, 15, 0, 0, 0, 0),
(7, 1, 5, 0, 0, 0, 0),
(8, 1, 201, 0, 0, 0, 0),
(9, 1, 6, 0, 0, 0, 0),
(10, 1, 7, 0, 0, 0, 0),
(11, 1, 9, 0, 0, 0, 0),
(12, 1, 10, 0, 0, 0, 0),
(13, 1, 11, 0, 0, 0, 0),
(14, 1, 13, 0, 0, 0, 0),
(15, 1, 14, 0, 0, 0, 0),
(16, 1, 17, 17, 17, 17, 17),
(17, 1, 20, 20, 20, 20, 20),
(18, 1, 18, 18, 18, 18, 18),
(19, 1, 21, 21, 21, 21, 21),
(20, 1, 22, 22, 22, 22, 22),
(21, 1, 23, 23, 23, 23, 23),
(22, 1, 24, 24, 24, 24, 24),
(23, 1, 25, 25, 25, 25, 25),
(24, 1, 26, 26, 26, 26, 26),
(25, 1, 27, 27, 27, 27, 27),
(26, 1, 28, 28, 28, 28, 28),
(27, 1, 29, 29, 29, 29, 29),
(28, 1, 30, 30, 30, 30, 30),
(29, 1, 31, 31, 31, 31, 31),
(30, 1, 32, 32, 32, 32, 32),
(31, 1, 33, 33, 33, 33, 33),
(32, 1, 57, 57, 57, 57, 57),
(33, 1, 58, 58, 58, 58, 58),
(34, 1, 59, 59, 59, 59, 59),
(35, 1, 60, 60, 60, 60, 60),
(36, 1, 61, 61, 61, 61, 61),
(37, 1, 63, 63, 63, 63, 63),
(38, 1, 67, 67, 67, 67, 67),
(39, 1, 68, 68, 68, 68, 68),
(40, 1, 69, 69, 69, 69, 69),
(41, 1, 70, 70, 70, 70, 70),
(42, 1, 71, 71, 71, 71, 71),
(43, 1, 72, 72, 72, 72, 72),
(44, 1, 73, 73, 73, 73, 73),
(45, 1, 202, 202, 202, 202, 202),
(46, 1, 203, 203, 203, 203, 203),
(47, 1, 205, 205, 205, 205, 205),
(48, 1, 206, 206, 206, 206, 206),
(49, 1, 62, 62, 62, 62, 62),
(50, 1, 8, 8, 8, 8, 8),
(51, 1, 39, 39, 39, 39, 39),
(52, 1, 40, 40, 40, 40, 40),
(53, 1, 41, 41, 41, 41, 41),
(54, 1, 42, 42, 42, 42, 42),
(55, 1, 43, 43, 43, 43, 43),
(56, 1, 44, 44, 44, 44, 44),
(57, 1, 204, 204, 204, 204, 204),
(58, 1, 45, 45, 45, 45, 45),
(59, 1, 46, 46, 46, 46, 46),
(60, 1, 47, 47, 47, 47, 47),
(61, 1, 48, 48, 48, 48, 48),
(62, 1, 49, 49, 49, 49, 49),
(63, 1, 50, 50, 50, 50, 50),
(64, 1, 51, 51, 51, 51, 51),
(65, 1, 52, 52, 52, 52, 52),
(66, 1, 53, 53, 53, 53, 53),
(67, 1, 54, 54, 54, 54, 54),
(68, 1, 64, 64, 64, 64, 64),
(69, 1, 55, 55, 55, 55, 55),
(70, 1, 56, 56, 56, 56, 56),
(71, 2, 1, 0, 0, 0, 0),
(72, 2, 200, 0, 0, 0, 0),
(73, 2, 2, 0, 0, 0, 0),
(74, 2, 3, 0, 0, 0, 0),
(75, 2, 4, 0, 0, 0, 0),
(76, 2, 15, 0, 0, 0, 0),
(77, 2, 5, 0, 0, 0, 0),
(78, 2, 201, 0, 0, 0, 0),
(79, 2, 6, 0, 0, 0, 0),
(80, 2, 7, 0, 0, 0, 0),
(81, 2, 9, 0, 0, 0, 0),
(82, 2, 10, 0, 0, 0, 0),
(83, 2, 11, 0, 0, 0, 0),
(84, 2, 13, 0, 0, 0, 0),
(85, 2, 14, 0, 0, 0, 0),
(86, 2, 17, 17, 17, 17, 17),
(87, 2, 20, 20, 20, 20, 20),
(88, 2, 18, 18, 18, 18, 18),
(89, 2, 21, 21, 21, 21, 21),
(90, 2, 22, 22, 22, 22, 22),
(91, 2, 23, 23, 23, 23, 23),
(92, 2, 24, 24, 24, 24, 24),
(93, 2, 25, 25, 25, 25, 25),
(94, 2, 26, 26, 26, 26, 26),
(95, 2, 27, 27, 27, 27, 27),
(96, 2, 28, 28, 28, 28, 28),
(97, 2, 29, 29, 29, 29, 29),
(98, 2, 30, 30, 30, 30, 30),
(99, 2, 31, 31, 31, 31, 31),
(100, 2, 32, 32, 32, 32, 32),
(101, 2, 33, 33, 33, 33, 33),
(102, 2, 57, 57, 57, 57, 57),
(103, 2, 58, 58, 58, 58, 58),
(104, 2, 59, 59, 59, 59, 59),
(105, 2, 60, 60, 60, 60, 60),
(106, 2, 61, 61, 61, 61, 61),
(107, 2, 63, 63, 63, 63, 63),
(108, 2, 67, 67, 67, 67, 67),
(109, 2, 68, 68, 68, 68, 68),
(110, 2, 69, 69, 69, 69, 69),
(111, 2, 70, 70, 70, 70, 70),
(112, 2, 71, 71, 71, 71, 71),
(113, 2, 72, 72, 72, 72, 72),
(114, 2, 73, 73, 73, 73, 73),
(115, 2, 202, 202, 202, 202, 202),
(116, 2, 203, 203, 203, 203, 203),
(117, 2, 205, 205, 205, 205, 205),
(118, 2, 206, 206, 206, 206, 206),
(119, 2, 62, 62, 62, 62, 62),
(120, 2, 8, 8, 8, 8, 8),
(121, 2, 39, 39, 39, 39, 39),
(122, 2, 40, 40, 40, 40, 40),
(123, 2, 41, 41, 41, 41, 41),
(124, 2, 42, 42, 42, 42, 42),
(125, 2, 47, 47, 47, 47, 47),
(126, 2, 48, 48, 48, 48, 48),
(127, 2, 49, 49, 49, 49, 49),
(128, 2, 50, 50, 50, 50, 50),
(129, 2, 51, 51, 51, 51, 51),
(130, 2, 52, 52, 52, 52, 52),
(131, 2, 53, 53, 53, 53, 53),
(132, 2, 54, 54, 54, 54, 54),
(133, 2, 64, 64, 64, 64, 64),
(134, 2, 55, 55, 55, 55, 55),
(135, 2, 56, 56, 56, 56, 56),
(136, 3, 13, 0, 0, 0, 0),
(137, 3, 47, 47, 47, 47, 47),
(138, 3, 48, 48, 48, 48, 48),
(139, 3, 49, 49, 49, 49, 49),
(140, 3, 50, 50, 50, 50, 50),
(141, 3, 51, 51, 51, 51, 51),
(142, 3, 52, 52, 52, 52, 52),
(143, 3, 53, 53, 53, 53, 53),
(144, 3, 54, 54, 54, 54, 54),
(145, 3, 64, 64, 64, 64, 64),
(146, 4, 13, 0, 0, 0, 0),
(147, 4, 47, 47, 47, 47, 47),
(148, 4, 48, 48, 48, 48, 48),
(149, 4, 49, 49, 49, 49, 49),
(150, 4, 50, 50, 50, 50, 50),
(151, 4, 51, 51, 51, 51, 51),
(152, 4, 52, 52, 52, 52, 52),
(153, 4, 53, 53, 53, 53, 53),
(154, 4, 54, 54, 54, 54, 54),
(155, 4, 64, 64, 64, 64, 64);

#
# Menambahkan menu 'Group / Hak Akses' ke table 'setting_modul'
# dan update no urut menu karena ada penambahan menu 'Group / Hak Akses' di table 'setting_modul'
#

UPDATE `setting_modul` SET `url` = '' WHERE `setting_modul`.`id` = 11;
UPDATE `setting_modul` SET `url` = 'users' WHERE `setting_modul`.`id` = 44;
UPDATE `setting_modul` SET `urut` = '5' WHERE `setting_modul`.`id` = 45;
UPDATE `setting_modul` SET `urut` = '6' WHERE `setting_modul`.`id` = 46;
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `parent`) VALUES (204, 'Group / Hak Akses', 'user_groups', '1', 'fa-users', '4', '1', '0', '11');

# Menambahkan sub menu 'Bantuan' ke table 'setting_modul'
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `parent`) VALUES (205, 'Bantuan', 'program_bantuan/clear', '1', 'fa-heart', '1', '1', '0', '6');

# Menambahkan sub menu 'Pertanahan' ke table 'setting_modul'
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `parent`) VALUES (206, 'Pertanahan', 'data_persil/clear', '1', 'fa-map-signs', '1', '1', '0', '7');


/* ========= Proses Migrasi di atas ini HARUS dilakukan SEBELUM masuk web admin ========= */

/* ========= Proses Migrasi di bawah ini BISA dilakukan SETELAH masuk web admin (tetapi akan 2x proses migrasi, maka ditambahkan disini) ========= */

#
# Menambahkan Dummy Email Address ke table 'user' dgn mengambil 'username' sbg nama email
#

UPDATE `user` SET `email` = CONCAT(user.username, '@opensid.id');

#
# Salin Data Pengguna yg sudah ada di table 'user' ke table 'users'
#

INSERT INTO `users` ( `username`, `password`, `email`, `last_login`, `active`, `first_name`, `company`, `phone`, `user_img`, `id_grup`) SELECT `username`, `password`, `email`, CAST(user.last_login AS DATE), `active`, `nama`, `company`, `phone`, `foto`, `id_grup` FROM `user`;

#
# Menambahkan Data Pengguna yg sudah ada ke dalam Group
#

INSERT INTO `users_groups` (`user_id`, `group_id`) SELECT `id`, `id_grup` FROM `users` WHERE id > 1;
