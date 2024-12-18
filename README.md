# rollerblade-rental

project for studies of application to reserve rollerblades for rental

## Instalation

#### Docker

first make environment for app, you can do this simply by using docker, start your docker desktop and type in your command line

```
docker-compose up --build
```

> Used --build to install dependencies for connecting to database

after few seconds app will start

#### Database

now it is time to prepare database, you can do this simply by pgadmin4 available under **localhost:8888** in your browser

you can login using credentials from _docker-compose.yml_

```
      POSTGRES_USER: admin
      POSTGRES_PASSWORD: password

      PGADMIN_DEFAULT_EMAIL: admin@rr.com
      PGADMIN_DEFAULT_PASSWORD: password
```

![obraz](https://github.com/user-attachments/assets/5d9b1b92-89af-4398-93ad-99c21da7120e)

create database and name it like in _backend/credentials.php_

```
$db = 'rollerblade-rental';
```

now there are 2 ways to load data to your database:

1. copy content from _rollerblade-rental_structure.sql_ and run this as query to create structure

> ⚠️ Remember to run all sql statemenets not only one

if you want to also have some examples in your databaseal ready to work with, run queries from _rollerblade-rental_example_data.sql_

2. restore database content from rollerblade-rental_backup file

![obraz](https://github.com/user-attachments/assets/6e2d3fbf-62ea-471f-8f01-d3f72a5bc3fa)

#### Here you go!

Now everything is ready you can open app in your brower on **localhost:8080**!

> ℹ️ default password for user "admin" is "password"
