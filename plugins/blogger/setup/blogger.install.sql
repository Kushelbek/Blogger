--
-- Bloger schema --
--

-- Main Blogger table
CREATE TABLE IF NOT EXISTS `cot_user_blogs` (
  `ub_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `ub_cat` varchar(255) NOT NULL,
  `ub_theme` varchar(255) DEFAULT NULL,
  `ub_scheme` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ub_comnotify` int(1) DEFAULT '0',
  `ub_config` text,
  `ub_created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ub_created_by` int(11) NOT NULL DEFAULT '0',
  `ub_updated_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ub_updated_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ub_id`),
  KEY (`user_id`, `ub_cat`),
  KEY (`user_id`),
  KEY (`ub_cat`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  COLLATE=utf8_unicode_ci COMMENT='Bloger';

