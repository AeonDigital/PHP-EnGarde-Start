#
# Aeon Digital - 2021
# Rianna Cantarelli 
#
# Controle de versão https://semver.org/
MAJOR=0
MINOR=9
HASH=$(shell git log -1 --format=%h)
TAG=${MAJOR}.${MINOR}.${HASH}


#
# Compila a imagem
# Registra/atualiza a mesma dentro do docker
build:
	docker build -t engarde-apache-php-7.4 ./services/web-server/apache-php-7.4

#
# Efetua login no Docker Hub
login:
	docker login

#
# Define a tag da build atual conforme configurações
# da versão atual
tag: login
	docker tag engarde-apache-php-7.4 aeondigital/engarde-apache-php-7.4:$(TAG)

#
# Efetua o push da imagem para o Docker Hub
push: tag
	docker push aeondigital/engarde-apache-php-7.4:$(TAG)
