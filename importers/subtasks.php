<?php

function readSubtasks()
{
    global $kanboard_pdo;

    $query = $kanboard_pdo->query("
        SELECT title, status, task_id, `position`
        FROM subtasks ORDER BY `position` desc;
    ");

    return $query->fetchAll(PDO::FETCH_OBJ);
}
