TAG=$(shell git log -1 --format=%h)


build:
	docker build -t engarde-php7.4-apache-mysql ./docker/

login:
	docker login

tag: login
	docker tag engarde-php7.4-apache-mysql aeondigital/engarde-php7.4-apache-mysql:$(TAG)
	
push: tag
	docker push aeondigital/engarde-php7.4-apache-mysql:$(TAG)
