
watch:
		npm run watch

startsql:
		sudo systemctl start postgresql
symfony:
		symfony serve -d

startdocker:
		docker-compose -f compose.yaml up --build

stopdocker:
		docker-compose -f compose.yaml down
# Probleme cache nav (pas de changement fait) ctrl + shift + R
