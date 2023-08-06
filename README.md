# Checkout

## Project Overview

This project is a simple one-step checkout for an online store

## Technologies used

Project is created with:
* PHP v8.0.2
* vue v3.3.4
* webserver: nginx
* database: mariadb v10.4
* laravel v9.52.10

## Setup
To run this project: 


First, clone the repository.
```
$ git clone https://github.com/MartynaBorecka/smartbees-checkout.git
```
Navigate to the task directory.
```
$ cd smartbees-checkout
```
Run ddev.
```
$ ddev start
```
Import the database.
```
$ ddev import-db --src=$(pwd)/database.sql.gz
```
Enter the container.
```
$ ddev ssh
```
Navigate to the 'backend' directory.
```
$ cd backend
```
Install the required packages using Composer.
```
$ composer install
```
Copy the file `.env.example` to `.env`.
```
$cp .env.example .env
```
Next, navigate to the 'frontend' directory.
```
$ cd ../frontend
```
Install the required packages using npm.
```
$ npm install
```
Run server Vite for the Vue app.
```
$ npm run dev -- --host
```
Open browser:
* for http: `http://borecka-rekru.ddev.site:5172`
* for hhtps: `https://borecka-rekru.ddev.site:5173`

## reCAPTCHA

version 2
* site key: 6LdCImknAAAAAH-1SDmeK7Mg3KigMQfXUKccg1yq
* secret key: 6LdCImknAAAAANNzdTAYSrll6FUDutIea1oVVKOC

## Discount code

* Active discount code - RABAT25
* Unactive discount code - RABAT10