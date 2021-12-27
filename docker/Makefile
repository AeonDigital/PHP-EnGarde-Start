#
# Aeon Digital - 2021
# Rianna Cantarelli <rianna@aeondigital.com.br>
#
VENDOR="aeondigital"
IMAGE="apache-php-7.4"

MAJOR=0
MINOR=9

HASH=$(shell git log -1 --format=%h)
TAG=${MAJOR}.${MINOR}.${HASH}

PROJECT=$(VENDOR)/$(IMAGE):$(TAG)





#
# DOCKER - CONTAINERS

#
# Encerra o processamento de todos os containers
docker-container-stop-all:
	@echo " - Encerrando o processamento de todos os containers ..."
	docker stop $(shell docker container ls -q)

#
# Remove todos os containers inativos
docker-container-remove-inactives:
	@echo " - Removendo todos os containers inativos ..."
	docker container prune

#
# Remove todos os containers 
docker-container-remove-all:
	@echo " - Removendo todos os containers ..."
	docker rm -f $(shell docker container ls -a -q)



#
# DOCKER - VOLUMES

#
# Remove todos os volumes inativos
docker-volume-remove-inactives:
	@echo " - Removendo todos os volumes inativos ..."
	docker volume prune

#
# Remove todos os volumes
docker-volume-remove-all:
	@echo " - Removendo todos os volumes ..."
	docker volume rm $(shell docker volume ls -a -q)



#
# DOCKER - NETWORK

#
# Remove todas as redes inativas
docker-network-remove-inactives:
	@echo " - Removendo todas as redes inativas ..."
	docker network prune

#
# Remove todas as redes
docker-network-remove-all:
	@echo " - Removendo todas as redes ..."
	docker network rm $(shell docker network ls -a -q)



#
# DOCKER - IMAGE

#
# Remove todas as redes inativas
docker-image-remove-inactives:
	@echo " - Removendo todas as imagens inativas ..."
	docker image prune

#
# Remove todas as redes
docker-image-remove-all:
	@echo " - Removendo todas as imagens ..."
	docker image rm $(shell docker image ls -a -q)





#
# Restaura o Docker da máquina atual para um estado inicial
# Esta ação excluirá TODOS os seguintes itens:
# - containers
# - volumes
# - redes
# - imagens
# 
# Use esta opção APENAS SE TIVER CERTEZA de que não precisa de
# nenhuma informação de nenhum dos itens listados acima.
#
# Para ter segurança que todos os comandos serão rodados use a 
# flag '-i' (--ignore-errors) junto ao comando ``make``.
#
# > make -i docker-reset
#
docker-reset: 
	@echo "\n\n :: Iniciando Docker Reset"
	docker stop $(shell docker container ls -q)
	docker rm -f $(shell docker container ls -a -q)
	docker volume rm $(shell docker volume ls -q)
	docker network rm $(shell docker network ls -q)
	docker image rm $(shell docker image ls -q)
	@echo "\n\n :: Tarefa finalizada"





#
# Compila a imagem conforme instruções descritas no ``Dockerfile`` 
# do local apontado.
build-web-server:
	docker build -t $(IMAGE) ./services/web-server/apache-php-7.4

#
# Executa a imagem do servidor web criando um container nomeado
# para o mesmo.
run-web-server:
	docker run -it -p 8080:80 --name $(IMAGE) $(IMAGE)

#
# Inicia o container do servidor web
start-web-server:
	docker start $(IMAGE)

#
# Inicia o container do servidor web em modo iterativo
start-web-server-i:
	docker start -i $(IMAGE)

#
# Entra no container do servidor web
open-web-server:
	docker exec -i $(IMAGE) bash


#
# Efetua login no Docker Hub
login:
	docker login

#
# Define a tag da build atual conforme configurações
# da versão atual
tag: login
	docker tag $(IMAGE) $(PROJECT)

#
# Efetua o push da imagem para o Docker Hub
push: tag
	docker push $(PROJECT)
