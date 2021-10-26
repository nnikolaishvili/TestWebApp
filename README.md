<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## Project Setup Instructions

Test web application that fetches orders & products from third-party API.

---

### Setup guide
- Clone the project and move to the directory
```console
foo@bar:~$ git clone https://github.com/nnikolaishvili/TestWebApp.git TestWebApp
foo@bar:~$ cd TestWebApp
```
- Install composer
```console
foo@bar:~/TestWebApp$ composer install
```
- Copy .env.example file into .env
```console
foo@bar:~/TestWebApp$ cp .env.example .env
```
- Generate the application key
```console
foo@bar:~/TestWebApp$ php artisan key:generate
```
---
### Configure .env file & set the correct values for the keys mentioned below
- STOREDEN_KEY
- STOREDEN_EXCHANGE
---
- Install npm & compile the assets
```console
foo@bar:~/TestWebApp$ npm install
foo@bar:~/TestWebApp$ npm run dev
```
- Link the storage
```console
foo@bar:~/TestWebApp$ php artisan storage:link
```
### Note: .env file should to be configured before running the command below
- Run migrations & seed the tables
```console
foo@bar:~/TestWebApp$ php artisan migrate --seed
```

---

For running the server you can use the command
```console
foo@bar:~/TestWebApp$ php artisan serve
```
### Users credentials that you can use to authenticate
- admin@test.com TestAdmin123
- editor@test.com TestEditor123
- assistant@test.com TestAssistant123


### Third party API docs

- **[Orders](https://developers.storeden.com/docs/orders)**
- **[Products](https://developers.storeden.com/docs/products)**

#### Time spent: 3 days
