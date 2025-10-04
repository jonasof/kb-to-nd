# KB-TO-DK

Import projects, columns and tasks from Kanboard to Nextcloud Deck.

## Limitations

All the boards from all users in Kanboard are copied to a single user in Nextcloud Deck.

The Kanboard app is much more complete and have many fields, while Nextcloud Deck is very simple. This script copies only the main data like title and description, but information like estimations, task score and time spent are not copied.

The encoding on database is different between Kanboard and Nextcloud Deck, so some special characters might be wrongly copied.

Tested with PHP 8, Nextcloud Deck 1.2.5 and Kanboard 1.2.18 (and also with Nextcloud Deck 1.15.3 and Kanboard 1.2.47)

Tested with both MariaDB and PostgreSQL for the Kanboard database (for PostgreSQL, use its DSN starting with "pgsql:"). Only tested with MariaDB for the Nextcloud database, but could probably be adapted for PostgreSQL as it as been done for Kanboard.

## How to

Execute import.php filling that environments variables.

```
KANBOARD_DATABASE_DSN=mysql:host=mariadb;dbname=kanboard \
KANBOARD_DATABASE_USER=root \
KANBOARD_DATABASE_PASSWORD=mariadb \
NEXTCLOUD_DATABASE_DSN=mysql:host=mariadb;dbname=owncloud \
NEXTCLOUD_DATABASE_USER=root \
NEXTCLOUD_DATABASE_PASSWORD=mariadb \
NEXTCLOUD_USERNAME=admin \
php import.php
```

Is possible to simulate the execution by adding DRY_RUN=true.

Is also possible to revert the execution by running result/revert_queries.sql in the Nextcloud database.

The docker-compose is optional and is meant to test the script in a sandbox environment, but it can be possible to use it to migrate the production instance.

It's also possible to run the converter inside docker, with something like:

```
docker build -t kb-to-nd:0.0.1 docker/
docker run --volume .:/app --network=host --env 'KANBOARD_DATABASE_DSN=mysql:host=mariadb;dbname=kanboard' --env KANBOARD_DATABASE_USER=root --env KANBOARD_DATABASE_PASSWORD=mariadb --env 'NEXTCLOUD_DATABASE_DSN=mysql:host=mariadb;dbname=owncloud' --env NEXTCLOUD_DATABASE_USER=root --env NEXTCLOUD_DATABASE_PASSWORD=mariadb --env NEXTCLOUD_USERNAME=admin kb-to-nd:0.0.1 sh -c "cd /app && php import.php"
```

## Migrated data

```
Kanboard -> Nextcloud Deck

projects -> oc_deck_boards
columns -> oc_deck_stacks
tasks -> oc_deck_cards
users -> oc_users
subtasks ->  description of oc_deck_cards
```

See "importers" folder for more details.
