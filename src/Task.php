<?php

class Task
{
    private $description;
    private $id;

    function __construct($description, $id = null)
    {
        $this->description = $description;
        $this->id = $id;
    }

    function getDescription()
    {
        return $this->description;
    }

    function setDescription($new_desc)
    {
        $this->description = (string) $new_desc;
    }

    function getId()
    {
        return $this->id;
    }

    function setId($new_id)
    {
        $this->id = $new_id;
    }

    function save() {
        $GLOBALS['DB']->exec("INSERT INTO tasks (description) VALUES ('{$this->getDescription()}');");
    }

    static function getAll() {
        $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks;");
        $tasks = array();

        foreach ($returned_tasks as $task) {
            $description = $task['description'];
            $new_task = new Task($description);
            array_push($tasks, $new_task);
        }

        return $tasks;
    }

    static function deleteAll() {
        $GLOBALS['DB']->exec("DELETE FROM tasks *;");
    }
}
