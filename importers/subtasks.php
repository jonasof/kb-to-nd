<?php

function readSubtasks()
{
    global $kanboard_pdo;
    global $kanboard_mysql_escape;

    $query = $kanboard_pdo->query("
        SELECT title, status, task_id, ${kanboard_mysql_escape}position${kanboard_mysql_escape}
        FROM subtasks ORDER BY ${kanboard_mysql_escape}position${kanboard_mysql_escape} desc;
    ");

    return $query->fetchAll(PDO::FETCH_OBJ);
}
