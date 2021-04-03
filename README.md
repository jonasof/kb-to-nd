# KB-TO-DK

Import projects, columns and tasks from Kanboard to Nextcloud Deck.

## Limitations

All the boards from all users in Kanboard are copied to a single user in Nextcloud Deck.

The Kanboard app is much more complete and have many fields, while Nextcloud Deck is very simple. This script copies only the main data like title and description, but information like estimations, task score and time spent are not copied.

The encoding on database is different between Kanboard and Nextcloud Deck, so some special characters might be wrongly copied.

Tested with PHP 8, Nextcloud Deck 1.2.5 and Kanboard 1.2.18 

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