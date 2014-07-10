CREATE TABLE IF NOT EXISTS `checklists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;


INSERT INTO `checklists` (`id`, `nom`) VALUES
(1, 'Ma première Checklist');

CREATE TABLE IF NOT EXISTS `checklists_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_checklist` int(11) NOT NULL,
  `texte` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_checklist` (`id_checklist`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `checklists_items` (`id`, `id_checklist`, `texte`) VALUES
(1, 1, 'Mon premier élément'),
(2, 1, 'Mon second élément');

ALTER TABLE `checklists_items`
  ADD CONSTRAINT `checklist_item` FOREIGN KEY (`id_checklist`) REFERENCES `checklists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;