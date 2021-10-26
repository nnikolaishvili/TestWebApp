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
foo@bar:~$ composer install
```
- Copy .env.example file into .env
```console
foo@bar:~$ cp .env.example .env
```
- Generate the application key
```console
foo@bar:~$ php artisan key:generate
```
---
### Configure .env file & set the correct values for the keys mentioned below
- STOREDEN_KEY
- STOREDEN_EXCHANGE
---
- Install npm & compile the assets
```console
foo@bar:~$ npm install
foo@bar:~$ npm run dev
```
- Link the storage
```console
foo@bar:~$ php artisan storage:link
```
### Note: .env file should to be configured before running the command below
- Run migrations & seed the tables
```console
foo@bar:~$ php artisan migrate --seed
```

### Third party API docs

- **[Orders](https://developers.storeden.com/docs/orders)**
- **[Products](https://developers.storeden.com/docs/products)**
