-- Migration pour ajouter le champ photo à la table e_travailleur
-- Date de création : 2026-01-21
-- Description : Ajoute un champ pour stocker le chemin de la photo du travailleur

ALTER TABLE `e_travailleur`
ADD COLUMN `photo` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Chemin de la photo du travailleur'
AFTER `description`;

-- Créer un index pour optimiser les recherches (optionnel)
-- CREATE INDEX idx_photo ON e_travailleur(photo);

-- Commande pour mettre à jour les photos par défaut si nécessaire
-- UPDATE e_travailleur SET photo = 'default.png' WHERE photo IS NULL;
