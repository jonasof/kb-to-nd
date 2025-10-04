<?php

function importColumns($project_id_mapping): ColumnImportResults
{
    $results = new ColumnImportResults();

    foreach (readColumns() as $column) {
        if (!isset($project_id_mapping[(int) $column->project_id])) {
            $results->failed_imports_missing_project[] = $column;
            continue;
        }

        $id = insertColumn($column, $project_id_mapping);

        $results->id_mapping[$column->id] = $id;
    }

    return $results;
}

function readColumns()
{
    global $kanboard_pdo;
    global $kanboard_mysql_escape;

    $query = $kanboard_pdo->query("
        SELECT id, title, ${kanboard_mysql_escape}position${kanboard_mysql_escape}, project_id, task_limit, description, hide_in_dashboard
        FROM columns;
    ");

    $query->execute();

    while ($column = $query->fetch(PDO::FETCH_OBJ)) {
        yield $column;
    }
}

function insertColumn($column, $project_id_mapping): int
{
    global $nextcloud_pdo;

    $insert_query = $nextcloud_pdo->prepare("
        INSERT INTO oc_deck_stacks
        (title, board_id, `order`)
        VALUES(:title, :board_id, :order);
    ");

    $insert_query->execute([
        ':title' => $column->title,
        ':board_id' => $project_id_mapping[(int) $column->project_id],
        ':order' => $column->position,
    ]);

    return $nextcloud_pdo->lastInsertId();
}
