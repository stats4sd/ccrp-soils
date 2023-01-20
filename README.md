# CCRP Soils Data Platform

This soils platform is the result of a collaboration between the Soils and the Research Methods Support teams, a pair of cross-cutting projects from the Collaborative Crop Research Program (CCRP).

https://soils.stats4sd.org/

# Development
This platform is built using Laravel/PHP. The front-end is written in VueJS and the admin panel uses Backpack for Laravel.

## Setup Local Environment
1.	Clone repo: `git@github.com:stats4sd/ccrp-soils.git`
2.	Copy `.env.example` as a new file and call it `.env`
3.	Update variables in `.env` file to match your local environment:
    1.	Check APP_URL is correct
    2.	Update DB_DATABASE (name of the local MySQL database to use), DB_USERNAME (local MySQL username) and DB_PASSWORD (local MySQL password)
    3.	If you need to test the Kobo link, make sure QUEUE_CONNECTION is set to `database` or `redis` (and that you have redis setup locally). Also add your test KOBO_USERNAME and KOBO_PASSWORD
    4.	If you need to test real email sending, update the MAIL_MAILER to mailgun, and copy over the Stats4SD Mailgun keys from 1 Password
4.	Create a local MySQL database with the same name used in the `.env` file
6.	Run the following setup commands in the root project folder:
```
composer install
php artisan key:generate
php artisan backpack:install
php artisan telescope:publish
npm install
npm run dev
```
7.	Migrate the database: `php aritsan migrate:fresh --seed` or copy from the staging site

## Run Laravel Websockets & Queues
To run the local notifications, start up Laravel Websockets locally: `php artisan websockets:serve`. This runs the websockets server on localhost port 6001.

To test the job queue locally, run Horizon: `php artisan horizon`.

## Add New Analysis Result to Data Download - Wide Format
Data download in wide format extracts data in one single sheet in excel file.<br/>
It is achieved by extracting data from database view.

There is a "base view" called "samples_merge" for most of the projects.<br/>
The CREATE VIEW SQL is stored in a manually created file database\views\samples_merged.sql.

For projects with specific data download requirement, the CREATE VIEW SQL is generated dynamically and executed when project is created or updated.

When there is a new analysis result, update the "base view" SQL in two files:
 - database\views\samples_merged.sql
 - src\App\Http\Controllers\SampleMergedController.php

Then run artisan command generatedbviews to re-generate database views.

`php artisan generatedbviews`

## Add New Analysis Result to Data Download - Split Format
Data download in split format extracts data in multiple sheets in excel file.<br/>
Individual analysis result table is extracted as individual sheet in excel file.

When there is a new analysis result, add new Export class for new analysis result table, then add the new Export class to SoilWorkbookExport.php.
