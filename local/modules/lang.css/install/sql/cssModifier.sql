CREATE TABLE `cssModifier` (
    `id` int NOT NULL AUTO_INCREMENT,
    `point` varchar(255) NOT NULL,
    `event` varchar(255) NOT NULL,
    `pseudo` varchar(255) NOT NULL,
    `property` varchar(255) NOT NULL,
    `value` varchar(255) NOT NULL,
    `modifier` varchar(255) NOT NULL,
    `style` varchar(255) NOT NULL,
    PRIMARY KEY(`id`)
)