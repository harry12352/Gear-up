#!/bin/bash

echo "--> Setting the variable"
export CI_REGISTRY_IMAGE=$CI_REGISTRY_IMAGE

echo "--> Pulling latest code image."
docker pull $CI_REGISTRY_IMAGE:latest

echo "--> Changing directory to code directory."
cd ~/app/gear-up
$SHELL

echo "--> Stopping the app"
docker-compose down || true
docker-compose kill || true

echo "--> Removing app code volume"
sleep 5s && docker volume rm gear-up_app_code && sleep 5s

echo "--> Running app code."
docker-compose up -d

echo "--> Running app commands."
docker exec -u 0 gearwrx-app php artisan migrate --force -qn
docker exec -u 0 gearwrx-app php artisan storage:link -qn
docker exec -u 0 gearwrx-app php artisan db:seed --force -qn

exit 0