-- Lecture seule. À lancer AVANT la migration 2026_10_23_100000_add_unique_matricule_e_travailleur.
-- Résultat attendu : 0 ligne. Sinon, corriger les doublons à la main avant de migrer.
SELECT matricule, COUNT(*) AS n, GROUP_CONCAT(id ORDER BY id) AS ids
FROM e_travailleur
GROUP BY matricule
HAVING COUNT(*) > 1;

-- Matricules vides ou NULL (un index unique accepte plusieurs NULL, pas plusieurs '') :
SELECT COUNT(*) AS matricules_vides FROM e_travailleur WHERE matricule = '' OR matricule IS NULL;
