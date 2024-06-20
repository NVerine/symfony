DROP VIEW IF EXISTS VW_Permissions;

CREATE VIEW VW_Permissions AS

SELECT UG.id AS id,
       UG.name AS name,
       count(PERM.id) AS permissions
FROM users_group UG
         LEFT JOIN permissions PERM ON UG.id = PERM.group_id
GROUP BY UG.id, UG.name
ORDER BY UG.id DESC

;