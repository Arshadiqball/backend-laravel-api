# How to run the project Locally:
    1 - composer i
    2 - php artisan migrate
    3 - php artisan key:generate
    4 - php artisan passport:install
    5 - php artisan serve

# How to the Run Project on Docker:

    1 - docker-compose up -d --build
    2 - docker-compose exec app php artisan migrate
    3 - docker-compose exec app php artisan key:generate
    4 - docker-compose exec app php artisan passport:install

# How to down the docker project:

    1 - docker-compose down
    2 - docker volume rm backend-laravel-api_db_data

# Docker COnfigurations Comands, no need to run, runs when needed:

    docker-compose exec db mysql -uroot -p 
    Password: MYSQL_ROOT_PASSWORD
    docker-compose logs db
    docker-compose ps
