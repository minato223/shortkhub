#Variables

IMAGE_NAME=laminecoder/shortkhub:latest
IMAGE_VERSION=latest

#Build

build:
	docker build -t $(IMAGE_NAME):$(IMAGE_VERSION) . --no-cache

#Push

push:
	docker push $(IMAGE_NAME):$(IMAGE_VERSION)

#Run

run:
	docker run -p 8080:8080 -d $(IMAGE_NAME):$(IMAGE_VERSION)

#Clean

clean:
	docker rmi $(IMAGE_NAME):$(IMAGE_VERSION)

#Production

prod:
	docker-compose -f compose.yaml -f compose.prod.yaml up --build --remove-orphans --force-recreate

dev:
	docker-compose -f compose.yaml -f compose.dev.yaml up --build --remove-orphans

local:
	docker-compose -f compose.yaml -f compose.dev.yaml up -d && symfony serve