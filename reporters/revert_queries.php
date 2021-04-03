<?php

function revertQueries()
{
    global $results;

    echo "## Revert Queries ## \n";
    echo "See the file 'result/revert_queries.sql' to delete the entries on nextcloud created by this script\n";

    file_put_contents("result/revert_queries.sql", "-- Revert Queries \n
DELETE FROM oc_deck_cards WHERE id IN (" . implode(",", array_values($results->task->id_mapping)) . ");
DELETE FROM oc_deck_stacks WHERE id IN (" . implode(",", array_values($results->column->id_mapping)) . ");
DELETE FROM oc_deck_boards WHERE id IN (" . implode(",", array_values($results->project->id_mapping)) . ");
    ");
}
