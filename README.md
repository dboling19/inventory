# Inventory System - Developed by Daniel Boling @ 2023

Use to manage and organize whatever items better, easier, and efficiently.
Note:  Some changes may be necessary depending on items in particular.  Currently, this is intended for food items.

## Contribution
### First Time Setup
* Ensure Symfony Dev server is installed.  See https://symfony.com/download.
* Ensure Composer (PHP Package Manager) is installed.  See https://getcomposer.org/download/.
* Ensure NPM or Yarn is installed.  
  * See https://docs.npmjs.com/downloading-and-installing-node-js-and-npm for NPM
  * See https://classic.yarnpkg.com/lang/en/docs/install/#windows-stable for yarn.
  * *If Yarn is desired, it still requires NPM.  It simply has a different UX which some might be familiar with or prefer.*
* Clone the project with `git clone https://github.com/dboling19/bfc` and enter the directory with `cd <dir>`

### Needed after every pull, clone, or update.
* Run `composer update`.  This will install and update any required dependencies for the project. 
* If using NPM, run `npm install`, `npm upgrade`, `npm run dev`.
* If using Yarn, run `yarn install`, `yarn upgrade`, `yarn dev`.
* If database changes have been made during fetch, you'll need to update the database.
  * Run `php bin/console doctrine:migrations:migrate`.  Sometimes this does not work correctly, likely due to the database having data in it.  In which case follow below.
   * Run `php bin/console doctrine:database:drop --force`, `php bin/console doctrine:database:create`, and `php bin/console doctrine:schema:create`.
  * While database migrations would be preferred, I am having issues with Symfony generating them correctly, and most of the time they requre an empty database anyway, so I found little difference.
* Finally, run `symfony server:start`.  If you do not need/desire to see the logs, append `-d` to run the server as a daemon.  
  * If this is your first symfony project on the system, you may need to run `symfony server:ca:install` first to get CA certificates, as Symfony runs in HTTPS.
