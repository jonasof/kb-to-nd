<?php

function importTasks($column_id_mapping, $all_subtasks): TaskImportResults
{
    $results = new TaskImportResults();

    foreach (readTasks() as $task) {
        if (!isset($column_id_mapping[(int) $task->column_id])) {
            $results->failed_imports_missing_column[] = $task;
            continue;
        }

        $subtasks = filterSubtasks($task, $all_subtasks);

        $id = insertTask($task, $column_id_mapping, $subtasks);

        $results->id_mapping[$task->id] = $id;
    }

    return $results;
}

function readTasks()
{
    global $kanboard_pdo;

    $query = $kanboard_pdo->query("
        SELECT id, title, description, date_creation, date_completed, date_due, 
        color_id, project_id, column_id, owner_id, `position`, score, is_active, 
        category_id, creator_id, date_modification, reference, date_started, 
        time_spent, time_estimated, swimlane_id, date_moved, recurrence_status, 
        recurrence_trigger, recurrence_factor, recurrence_timeframe, recurrence_basedate, 
        recurrence_parent, recurrence_child, priority, external_provider, external_uri
        FROM tasks;
    ");

    $query->execute();

    while ($column = $query->fetch(PDO::FETCH_OBJ)) {
        yield $column;
    }
}

function insertTask($task, $column_id_mapping, $subtasks): int
{
    global $nextcloud_pdo;
    global $nextcloud_user;

    $description = buildDescription($task->description, $subtasks);

    $insert_query = $nextcloud_pdo->prepare("
        INSERT INTO oc_deck_cards
        (title, description, stack_id, `type`, last_modified, last_editor, 
        created_at, owner, `order`, archived, duedate, notified)
        VALUES(:title, :description, :stack_id, :type, :last_modified, :last_editor, 
        :created_at, :owner, :order, :archived, :duedate, :notified);
    ");

    $insert_query->execute([
        ':title' => $task->title,
        ':description' => $description,
        ':stack_id' =>  $column_id_mapping[(int) $task->column_id],
        ':type' =>  "plain",
        ':last_modified' => $task->date_modification,
        ':last_editor' => $nextcloud_user,
        ':created_at' => $task->date_creation,
        ':owner' => $nextcloud_user,
        ':order' => $task->position,
        ':archived' => $task->is_active ? 0 : 1,
        ':duedate' => $task->date_due ? date("Y-m-d H:i:s", $task->date_due): null,
        ':notified' => $task->date_due !== "0" && ((int) $task->date_due < time()) ? 1 : 0,
    ]);

    return $nextcloud_pdo->lastInsertId();
}

function filterSubtasks($task, $all_subtasks)
{
    $subtasks = array_filter($all_subtasks, function ($subtask) use ($task) {
        return $subtask->task_id === $task->id;
    });

    usort($subtasks, function ($a, $b) {
        return $a->position <=> $b->position;
    });

    return $subtasks;
}

function buildDescription($description, $subtasks)
{
    return $description . "\n\n" . implode("\n", array_map(function ($subtask) {
        return "Subtask: $subtask->title " . ($subtask->status > 0 ? "(complete)" : "") . "\n";
    }, $subtasks));
}
