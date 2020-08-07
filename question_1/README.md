# Question 1 Cleaner & Company App

The application allows company to book an appointment with cleaners

## Requirements

The list below stated are requirements for the project
 - php
 - sqlite
 - composer
 - phpunit

## Configure Application with Docker Image
### Requirement
 - docker


1- Generate new `.env` file with content as below
```
APP_NAME=Lumen
APP_ENV=production
APP_KEY=base64:MNefnaPvV6ur6VXsvjReME39JTjEFrBH1yWxAhP/QiM=
APP_DEBUG=false
APP_URL=http://localhost
APP_TIMEZONE=UTC

LOG_CHANNEL=stack
LOG_SLACK_WEBHOOK_URL=

DB_CONNECTION=sqlite
DB_PREFIX=justmop_

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

2- Generate image from `Dockerfile`
```sh
docker build . -t alihuseyn13/question_1
```

3- Create application container from image
```sh
docker run -d -p 8000:80 alihuseyn13/question_1
```

## Configure Application In Local

1- Install project package requirements
```sh
$ composer install
```

2- Generate new `.env` file with content as below
```
APP_NAME=Lumen
APP_ENV=production
APP_KEY=base64:MNefnaPvV6ur6VXsvjReME39JTjEFrBH1yWxAhP/QiM=
APP_DEBUG=false
APP_URL=http://localhost
APP_TIMEZONE=UTC

LOG_CHANNEL=stack
LOG_SLACK_WEBHOOK_URL=

DB_CONNECTION=sqlite
DB_PREFIX=justmop_

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

3- Generate database tables. 

Note: It is already filled with tables and data you can clean with `php artisan migrate:rollback` before continuing with any database-related operation. If you want to continue with available database then skip 4th and 5th explanation

```
$ php artisan migrate
```

4- (**Optional**) Fill the database with seed data. It will just create dummy cleaners and company

```
$ php artisan db:seed
```

5- Run the application

```
$ composer run server
```

## Testing

Note: It will delete sqlite in each test case. Use it as a database

```sh
$ composer run phpunit
```

## API Documentation

After configuration and successful run in browser type [http://localhost:8000/docs](http://localhost:8000/docs).
The swagger documentation for API will be seen inside of the tab and will allow you to make a request and observe a response.

## Database Structure

Simple 3 tables are used to generate a relational database structure.

```
|------------------|            |----------------------|           |----------------------|
| justmop_cleaners |            |   justmop_bookings   |           |   justmop_companies  |
|------------------| 1     1..* |----------------------|           |----------------------|            
| PK: id           | o--------> | PK: id               | 1..*    1 | PK: id               |
| name             |            | FK: cleaner_id       | <--------o| name                 |
|------------------|            | FK: company_id       |           |----------------------|
                                | start                |
                                | end                  |
                                | date                 |
                                |----------------------|
```
