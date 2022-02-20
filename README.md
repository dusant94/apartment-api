 
## Project first RUN

 1. - `git clone git@github.com:dusant94/apartment-api.git` 

 2. - `cd apartment-api/`

 3. - `composer install`

 4. - `cp .env.example .env`

 5. - `php artisan key:generate`

 6. - `php artisan migrate`

 7. - setup mail settings in .env (mailtrap.io)

 8. - `php artisan serve`

 9. - `php artisan queue:work`


### Commands for export and inport of apartments

 - php artisan export:apartments

 - php artisan import:apartments (filename)


### Packages used and useful documentation

- **[Laravel](https://laravel.com/docs/8.x/)**

- **[Swagger API documentation](http://127.0.0.1:8000/api/documentation)**
