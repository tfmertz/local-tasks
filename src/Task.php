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

        function setDescription($new_description)
        {
            $this->description = (string) $new_description;
        }

        function getDescription()
        {
            return $this->description;
        }

        function getId()
        {
            return $this->id;
        }

        function setId($new_id)
        {
            $this->id = (int) $new_id;
        }

        function save()
        {
            $statement = $GLOBALS['DB']->query("INSERT INTO tasks (task) VALUES ('{$this->getDescription()}') RETURNING id;");
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $this->setId($result['id']);
        }

        static function getAll()
        {
            $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks;");
            $tasks = array();
            foreach($returned_tasks as $task) {
                $description = $task['task'];
                $id = $task['id'];
                $new_task = new Task($description, $id);
                array_push($tasks, $new_task);
            }
            return $tasks;
        }

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM tasks *;");
        }

        static function find($search_id)
        {
            //grab row out of db
            $rows = $GLOBALS['DB']->query("SELECT * FROM tasks WHERE id = {$search_id};");
            $found_task = null;
            foreach($rows as $row) {
                $id = $row['id'];
                $desc = $row['task'];
                $new_task = new Task($desc, $id);
                $found_task = $new_task;
            }
            return $found_task;
        }
    }
?>
