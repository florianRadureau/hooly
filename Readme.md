## Requirements ##
PHP 8 (+ extensions, including postgresql extensions)
Symfony 5
docker, docker-compose

## Install ##
copy .env to .env.local and change the DATABASE_URL by :
DATABASE_URL="postgresql://user:password@127.0.0.1:49153/hooly?serverVersion=13&charset=utf8"

At the project root :
docker-compose up -d (check if the db is up with 'docker ps')
bin/console d:d:c
bin/console d:m:m
bin/console d:f:l
symfony serve

## Usage ##
You now have 7 trucks and 7 locations in database, including 1 not available on every Friday.
To create a reservation you can make a POST http call on 127.0.0.1:8000/reservations with a json body. Mandatory fields : location (location id you want to reserve), truck (your truck id) and date.
Example :
{
    "location": 1,
    "truck": 2,
    "date": "16/05/2022"
}