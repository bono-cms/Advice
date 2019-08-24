
/* Categories */
DROP TABLE IF EXISTS `bono_module_advice_categories`;
CREATE TABLE `bono_module_advice_categories` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Category ID',
    `name` varchar(255) NOT NULL COMMENT 'Category name'   
) DEFAULT CHARSET = UTF8;

/* Advices */
DROP TABLE IF EXISTS `bono_module_advice`;
CREATE TABLE `bono_module_advice` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Advice ID',
    `category_id` INT DEFAULT NULL COMMENT 'Optional category ID',
    `published` varchar(1) NOT NULL COMMENT 'Whether advice is enabled',
    `icon` varchar(255) NOT NULL COMMENT 'Optional icon',

    FOREIGN KEY (category_id) REFERENCES bono_module_advice_categories(id) DELETE SET NULL ON UPDATE CASCADE
) DEFAULT CHARSET = UTF8;

DROP TABLE IF EXISTS `bono_module_advice_translations`;
CREATE TABLE `bono_module_advice_translations` (
    `id` INT NOT NULL COMMENT 'Advice ID',
    `lang_id` INT NOT NULL COMMENT 'Attached language ID',
    `title` varchar(255) NOT NULL COMMENT 'Advice title',
    `content` LONGTEXT NOT NULL COMMENT 'Advice description',

    FOREIGN KEY (id) REFERENCES bono_module_advice(id) ON DELETE CASCADE,
    FOREIGN KEY (lang_id) REFERENCES bono_module_cms_languages(id) ON DELETE CASCADE
) DEFAULT CHARSET = UTF8;
