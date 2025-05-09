
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
