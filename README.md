# Imperium Stellarum

A turn-based multiplayer browser game of galactic conquest. Check [https://imperiumstellarum.io/](https://imperiumstellarum.io/).

## Server Requirements

* MySQL ^5.7.31 / MariaDB (not tested, but should work)
* PHP 7.3+
* NodeJS ^14.15.3, npm ^6.14.9

## WIP

This is a rough and early work in progress, it is far from ready to be used in any implementation whatsoever. 
There are lots of areas that don't work yet - including elementary stuff like fleet movement, combat etc - lots of stuff will break again and again. 
I will create pre-releases whenever one or more areas are done.
 
Use at your own risk. 

(No, you can't play - yet)

## Usage: npm Scripts

* `npm run prod`: generate frontend files for **production** environment 
* `npm run dev`: generate frontend files for **development** environment and watches all applicable files for changes.
* `npm run icons`: generates a single icon sprite from all available svg-icons.
* `npm run cleanup`: prunes `public/assets` folder and deletes all generated files.
* `npm run db:testdata` creates fresh database tables and seeds with test data  
* `npm run ide:helper`: creates type hints and php docs for IDEs.

## Installation
- Clone this repository
- `composer install` to install php scripts into `vendor` folder.
- `npm install` to install all necessary node modules.
- `npm run prod && npm run icons` to generate frontend files.
- update `.env` file with database credentials
- **setup mailserver for development**: I use [https://mailtrap.io/](https://mailtrap.io/) - this can be used like a normal smtp server, but does not actually send anything. Instead, the mails are placed conveniently in an inbox for you to study. Create a free inbox, and copy the credentials to `.env`.
- `php artisan key:generate` to generate your own application key
- `php artisan storage:link` to create a symlink from `public` to `storage`.
- `npm run db:testdata` (alias for `php artisan migrate:fresh && php artisan db:seed`): create database tables and seed the database with test data. Creates two games (40x40 map and 100x100 map), 11 users and 22 players, seeds database with planets/stars, enlists the players, starts the games and seeds player relations, fleets and ships. All users have `password` as password. `ash@imperiumstellarum.io` has admin permissions.

## Attribution

A list of used third party images can be found in the [Attribution](./ATTRIBUTION.md) page. All images used are in the public domain, have creative commons or other permissive licenses. 

This project uses a lot of open source software - without the efforts of all these maintainers/contributors, none of this would be possible. For a detailed reference please check the [dependencies](./package.json).

## License

Imperium Stellarum is licensed under the [MIT license](https://opensource.org/licenses/MIT). Please see the [LICENSE](./LICENSE) file. 

### Can I use parts of Imperium Stellarum - or everything - for my own project?

Yes, absolutely. Use at your own risk though :)
