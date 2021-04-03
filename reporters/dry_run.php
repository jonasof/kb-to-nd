<?php

function reportDryRun()
{
    global $results;

    echo "## DRY Run results \n";
    echo "See file 'result/transactions_to_import.json' to see results\n\n";

    $json_contents = [
        "projects" => fetchTableResults("oc_deck_boards", array_values($results->project->id_mapping)),
        "columns" => fetchTableResults("oc_deck_stacks", array_values($results->column->id_mapping)),
        "cards" => fetchTableResults("oc_deck_cards", array_values($results->task->id_mapping)),
    ];

    file_put_contents("result/transactions_to_import.json", json_encode($json_contents));
}

function fetchTableResults($table, $ids)
{
    global $nextcloud_pdo;

    $ids_string = implode(",", $ids);

    return $nextcloud_pdo->query("SELECT * FROM $table where id in ($ids_string)")->fetchAll(PDO::FETCH_OBJ);
}
