CREATE TABLE IF NOT EXISTS `#__pagtesouro_uasgs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orgao` varchar(10) NOT NULL,
  `ug` varchar(10) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__pagtesouro_servicos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uasg_id` int(11) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_uasg_id` (`uasg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `#__pagtesouro_servicos`
  ADD CONSTRAINT `fk_servico_uasg` FOREIGN KEY (`uasg_id`) REFERENCES `#__pagtesouro_uasgs` (`id`) ON DELETE CASCADE;