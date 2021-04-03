<?php

function importProjects(): ProjectImportResults
{
    $results = new ProjectImportResults();

    foreach (readProjects() as $project) {
        $project_id = insertProject($project);

        $results->id_mapping[$project->id] = $project_id;
    }

    return $results;
}

function readProjects()
{
    global $kanboard_pdo;

    $query = $kanboard_pdo->prepare("
        SELECT id, name, is_active, token, last_modified, is_public, is_private, description, identifier, 
        start_date, end_date, owner_id, priority_default, priority_start, priority_end, email, 
        predefined_email_subjects, per_swimlane_task_limits, task_limit, enable_global_tags 
        FROM projects");

    $query->execute();

    while ($project = $query->fetch(PDO::FETCH_OBJ)) {
        yield $project;
    }
}

function insertProject($project): int
{
    global $nextcloud_pdo;
    global $nextcloud_user;

    $insert_query = $nextcloud_pdo->prepare("
        INSERT INTO oc_deck_boards
        (title, owner, archived, last_modified)
        VALUES(:title, :owner, :archived, :last_modified);
    ");

    $insert_query->execute([
        ':title' => $project->name,
        ':owner' => $nextcloud_user,
        ':archived' => $project->is_active ? 0 : 1,
        ':last_modified' => $project->last_modified,
    ]);

    return $nextcloud_pdo->lastInsertId();
}
