#!/bin/bash

docker-compose down

docker-compose up -d --force-recreate

>&2 echo "Waiting for container to run. Please wait....."

>&2 echo "Your application back end is now ready go to http://localhost"