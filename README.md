# rollerblade-rental

project for studies of application to reserve rollerblades for rental

## Instalation

first make environment for app, you can do this simply by using docker, start your docker desktop and type in your command line

```
docker-compose up
```

after few seconds app will start
now it is time to prepare database, simple way is to open **localhost:8888** in your browser, login by using credentials from _docker-compose.yml_

```
      POSTGRES_USER: admin
      POSTGRES_PASSWORD: password

      PGADMIN_DEFAULT_EMAIL: admin@rr.com
      PGADMIN_DEFAULT_PASSWORD: password
```

create database and name it like in _backend/credentials.php_

```
$db = 'rollerblade-rental';
```

now copy content from _rollerblade-rental_structure.sql_ and run this as query to create structure

> ⚠️ Remrember to run all sql statemenets not only one

if you want use also _rollerblade-rental_example_data.sql_ to have some examples in your databaseal ready to work with

Now everything is ready you can open app in your brower on **localhost:8080**!
