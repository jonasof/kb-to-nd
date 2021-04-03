<?php

require("importers/results_interfaces.php");
require("importers/columns.php");
require("importers/projects.php");
require("importers/tasks.php");
require("importers/user.php");
require("importers/subtasks.php");

require("reporters/dry_run.php");
require("reporters/totals.php");
require("reporters/failures.php");
require("reporters/revert_queries.php");

# Variables

$kanboard_pdo = new PDO(
    getenv("KANBOARD_DATABASE_DSN"),
    getenv("KANBOARD_DATABASE_USER"),
    getenv("KANBOARD_DATABASE_PASSWORD")
);

$nextcloud_pdo = new PDO(
    getenv("NEXTCLOUD_DATABASE_DSN"),
    getenv("NEXTCLOUD_DATABASE_USER"),
    getenv("NEXTCLOUD_DATABASE_PASSWORD")
);

$nextcloud_user = getenv("NEXTCLOUD_USERNAME");

$dry_run = boolval(getenv("DRY_RUN") ?? false);

if (!userExists($nextcloud_user)) {
    die("Nextcloud user '$nextcloud_user' does not exists\n");
}

# Execution

$nextcloud_pdo->beginTransaction();

$results = new Results();

$results->project = importProjects();
$results->column = importColumns($results->project->id_mapping);
$results->subtaks = readSubtasks();
$results->task = importTasks($results->column->id_mapping, $results->subtaks);

if ($dry_run) {
    reportDryRun();
    $nextcloud_pdo->rollBack();
} else {
    $nextcloud_pdo->commit();
}

reportTotals();
reportFailures();
revertQueries();
