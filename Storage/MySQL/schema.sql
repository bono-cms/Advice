
DROP TABLE IF EXISTS `bono_module_advice`;
CREATE TABLE `bono_module_advice` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Advice ID',
    `published` varchar(1) NOT NULL COMMENT 'Whether advice is enabled',
    `icon` varchar(255) NOT NULL COMMENT 'Optional icon'
) DEFAULT CHARSET = UTF8;

DROP TABLE IF EXISTS `bono_module_advice_translations`;
CREATE TABLE `bono_module_advice_translations` (
    `id` INT NOT NULL COMMENT 'Advice ID',
    `lang_id` INT NOT NULL COMMENT 'Attached language ID',
    `title` varchar(255) NOT NULL COMMENT 'Advice title',
    `content` LONGTEXT NOT NULL COMMENT 'Advice description'
) DEFAULT CHARSET = UTF8;

DROP TABLE IF EXISTS `bono_module_advice_categories`;
CREATE TABLE `bono_module_advice_categories` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Category ID',
    `name` varchar(255) NOT NULL COMMENT 'Category name'
) DEFAULT CHARSET = UTF8;
