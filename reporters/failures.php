<?php

function reportFailures()
{
    global $results;

    $columns_not_imported = array_column($results->column->failed_imports_missing_project, 'id');
    $missing_projects = array_unique(array_column($results->column->failed_imports_missing_project, 'project_id'));

    $tasks_not_imported = array_column($results->task->failed_imports_missing_column, 'id');
    $missing_columns = array_unique(array_column($results->task->failed_imports_missing_column, 'column_id'));
    
    echo "## Failures ## \n";
    echo "Columns not imported because of missing project: " . count($columns_not_imported) . "\n";
    echo "Tasks not imported because of missing columns: " . count($tasks_not_imported) . "\n";
    echo "See details on 'result/failures.txt'\n";
    echo "\n";
    
    file_put_contents("result/failures.txt", "
Columns ids not imported because of missing project: " . implode(",", $columns_not_imported) . "\n
(missing projects ids): " . implode(",", $missing_projects) . "\n
Tasks ids not imported because of missing columns: " . implode(",", $tasks_not_imported) . "\n
(missing columns ids): " . implode(",", $missing_columns) . "\n
    ");
}
