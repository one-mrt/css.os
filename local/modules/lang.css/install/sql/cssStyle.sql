CREATE TABLE `cssStyle` (
    `id` int NOT NULL AUTO_INCREMENT,
    `site` varchar(255) NOT NULL, 
    `style` text NOT NULL, 
    `md5` varchar(255) NOT NULL, 
    `page` varchar(255) NOT NULL,
    `create` datetime NOT NULL, 
    `update` datetime NOT NULL,
    PRIMARY KEY(`id`)
);