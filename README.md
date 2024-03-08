
### Deployment
- docker-compose up -d
- docker exec -ti "php-fpm container" bash
### IN php-fpm container
- composer install
- php artisan migrate
- chmod -R  777 ./

### Front-end
- visit http://localhost:3000/ - here interface
### Backend
- http://localhost:8080/ 
