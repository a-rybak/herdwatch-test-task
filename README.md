How to use...

At the root run docker-compose up -d --build
Enter the container php_sf_docker to install composer packages and create database which set up in env file ( I know it's not a good step to push to repo such env data but it's just local test task)
Run migrations
API in this backend project was tested in Postman (https://prnt.sc/JLvL3hbAn3tk)
There is Adminer service for checking data in database

Work with client folder is almost the same.
Enter container client_sf_docker, install all dependencies
In src/command folder all available console commands are stored
Each command has annotaions which shows how to run API request (example of command: php bin/console get-request)
All command are built using Builder desing patten
Client and its commands were tested here https://jsonplaceholder.typicode.com/ (set in config/services.yml)


Short docs about API method you may find here http://localhost:8080/api/doc
Validation in app is very simple because there was no special requirements and DB structure is pretty short and simple as well
Accessing record only by ID (e.g. PUT /api/groups/1 etc.)
No filters in list requests (get users by name etc.), only CRUD has been implemented as task said
