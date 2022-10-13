# CCRP Soils Data Platform

This soils platform is the result of a collaboration between the Soils and the Research Methods Support teams, a pair of cross-cutting projects from the Collaborative Crop Research Program (CCRP).

https://soils.stats4sd.org/en/

1. If you haven't run this locally since April2020, do a fresh migration (`php artisan migrate:fresh`) as a lot of little things have changed in the database.

2. On localhost, also run the database seeds (`php artisan db:seed`) to have an admin user created automatically with the following details:
    Username: test@example.com
    Password: password

3. See below for setting up / testing the Jobs and local notifications.


## Setup Development Environment

1. Clone project
2. `composer install && npm install`
3. `cp .env.example .env`
4. Update .env file with required details
5. Include Stats4SD Development environment details:
    - MAILGUN info

6. `php artisan key:generate`
7. `php artisan migrate:fresh --seed`
8.  Publish telescope assets
    `php artisan telescope:publish`
9.  Install backpack

    `composer require backpack/crud:"4.1.*"`

    `composer require backpack/generators --dev`

    `php artisan backpack:install`

10. Install bootstrap

    `npm install vue bootstrap bootstrap-vue`

11. `npm run dev`


### Run Laravel Websockets & Queues
To run the local notifications, start up Laravel Websockets locally: `php artisan websockets:serve`. This runs the websockets server on localhost port 6001.

To test the job queue locally, run Horizon: `php artisan horizon`.


### Add new analysis result to data download in wide format
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

### Add new analysis result to data download in split format
Data download in split format extracts data in multiple sheets in excel file.<br/>
Individual analysis result table is extracted as individual sheet in excel file.

When there is a new analysis result, add new Export class for new analysis result table, then add the new Export class to SoilWorkbookExport.php.
