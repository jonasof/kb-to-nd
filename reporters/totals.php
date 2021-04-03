<?php

function reportTotals()
{
    global $results;
    
    echo "## Totals ## \n";
    echo "Projects imported: " . count($results->project->id_mapping) . "\n";
    echo "Columns imported: " . count($results->column->id_mapping) . "\n";
    echo "Tasks imported: " . count($results->task->id_mapping) . "\n";
    echo "\n";
}
