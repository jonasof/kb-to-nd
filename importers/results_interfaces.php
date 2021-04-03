<?php 

// phpcs:ignoreFile PSR1.Classes.ClassDeclaration.MissingNamespace

class Results
{
    /** @var ProjectImportResults */
    public $project;
    /** @var ColumnImportResults */
    public $column;
    /** @var TaskImportResults */
    public $task;
     /** @var array */
    public $subtasks;
}

class ProjectImportResults
{
    public $id_mapping = [];
}

class ColumnImportResults
{
    public $id_mapping = [];
    public $failed_imports_missing_project = [];
}

class TaskImportResults
{
    public $id_mapping = [];
    public $failed_imports_missing_column = [];
}
