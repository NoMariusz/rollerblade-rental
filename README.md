# rollerblade-rental

Rollerblade reservation system project for studies 

<img src="https://github.com/user-attachments/assets/caf76ea7-f800-4836-bb66-fa94c38f7768" alt="Rollerblades page" height="550" />
<img src="https://github.com/user-attachments/assets/0103e77a-0f3c-49ef-a749-79183b018b7e" alt="Rollerblade mobile vertical page" height="610" /> 
<img src="https://github.com/user-attachments/assets/baa7b223-1d32-4200-83ce-29eeb389fafa" alt="Users mobile horizontal page" height="610" /> 


## Instalation

#### Docker

First make environment for app, you can do this simply by using docker, start your docker desktop and type in your command line

```
docker-compose up --build
```

> Used --build to install dependencies for connecting to database

After few seconds app will start

#### Database

Now it is time to prepare database, you can do this simply by pgadmin4 available under **localhost:8888** in your browser

You can login using credentials from _docker-compose.yml_

```
      POSTGRES_USER: admin
      POSTGRES_PASSWORD: password

      PGADMIN_DEFAULT_EMAIL: admin@rr.com
      PGADMIN_DEFAULT_PASSWORD: password
```

![obraz](https://github.com/user-attachments/assets/5d9b1b92-89af-4398-93ad-99c21da7120e)

Create database and name it like in _backend/credentials.php_

```
$db = 'rollerblade-rental';
```

Now there are 2 ways to load data to your database:

1. Copy content from _rollerblade-rental_structure.sql_ and run this as query to create structure

> ⚠️ Remember to run all sql statemenets not only one

if you want to also have some examples in your databaseal ready to work with, run queries from _rollerblade-rental_example_data.sql_

2. Restore database content from rollerblade-rental_backup file

![obraz](https://github.com/user-attachments/assets/6e2d3fbf-62ea-471f-8f01-d3f72a5bc3fa)

#### Here you go!

Now everything is ready you can open app in your brower on **localhost:8080**!

> ℹ️ default password for user "admin" is "password"


## Usage - short description

Every user, even unlogged have access and can show base pages
- home page */home* or */* beeing simple "landing page" of project
- */contact* page with some fake contant information
- can show our rollerblades at */rollerblades* and check if they are available

When user creates account by registering self on */register*
- he can rent specific rollerblade on */rollerblade?id=x*
- all rentals list with information about their statuses are under */rentals*
- of course user can logout on */logout* and login again on */login* page

Moderator users also
- have access to lost of all rentals for all users on */rentals/all*
- can change status of every rental

Admin users also
- have access to */users* view with list of all users
- can delete user
- can change user password
- can change role of any user


## Technologies
- PHP
- JS
- CSS
- HTML
- DOCKER
- POSTGRES
- PGADMIN


## Development/Contributions

For further development let's write some of main project structure and development assumptions
- we use **MVC pattern**, where
  - **Models**
        - are entries stored in database
  - **Views**
        - are files providing some read operations for our Models
        - should be stored in __/views__ folder
        - could be helpfull to use __/src/shared/ViewUtils.php__ inside
        - from the assumptions can return anything, html page, or json, or whatever
  - **Controllers**
        - are scripts modyfying Models
        - should be stored in __/controllers__ folder
        - should be a file with class implementing __/src/shared/BaseController.php__ and calling own **process** function at end of the script
        - should return valid JSON script
- all network access is handled by __index.php__ file that uses **__/src/Router.php__** file to **handle routing**
- all 'standard' files like views or controllers should **include __/src/includes.php__** file to have access for all utility classes
- here is list of main utility classes:
  - __/src/shared/DbManager.php__ - manages database interaction, provide comfortable interface for our database
  - __/src/auth/AuthUtils.php__ - handling session and user authentication
  - __/src/shared/CommunicationUtils.php__ - mainly for debug logging
