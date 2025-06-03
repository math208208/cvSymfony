
watch:
		npm run watch

startsql:
		sudo systemctl start postgresql
symfony:
		symfony serve -d

startdocker:
		docker-compose -f compose.yaml up --build -d

stopdocker:
		docker-compose -f compose.yaml down

restartdocker:
		docker-compose -f compose.yaml down && docker-compose -f compose.yaml up --build -d

migrate:
		php bin/console doctrine:migrations:migrate


migration:
		symfony console make:migration

contphp:
		docker compose exec php-fpm bash

openCypress:
		npx cypress open


startCypress:
		npx cypress run

# verifsrc:
#  		phpcs --standard=PSR12 src

# Probleme cache nav (pas de changement fait) ctrl + shift + R


# SELECT *
# FROM "user" u
# LEFT JOIN formation f ON f.user_id = u.id
# LEFT JOIN experience_pro ep ON ep.user_id = u.id
# LEFT JOIN experience_uni eu ON eu.user_id = u.id
# LEFT JOIN langage l ON l.user_id = u.id
# LEFT JOIN outil o ON o.user_id = u.id
# LEFT JOIN loisir lo ON lo.user_id = u.id
# LEFT JOIN competence c ON c.user_id = u.id
# WHERE u.nom LIKE 'Matheo'
#    OR f.intitule LIKE '%searchTerm%'
#    OR ep.description LIKE '%searchTerm%'
#    OR eu.description LIKE '%searchTerm%'
#    OR l.nom_langue LIKE '%searchTerm%'
#    OR o.nom LIKE '%searchTerm%'
#    OR lo.nom LIKE '%searchTerm%'
#    OR c.nom LIKE '%searchTerm%'
# ;