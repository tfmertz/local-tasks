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

        function addCategory($new_category)
        {
            $query = $GLOBALS['DB']->prepare("INSERT INTO categories_tasks (category_id, task_id) VALUES ({$new_category->getId()}, {$this->getId()});");
            $query->execute();
        }

        function categories()
        {
            $query = $GLOBALS['DB']->prepare("SELECT category_id FROM categories_tasks WHERE task_id = {$this->getId()};");
            $query->execute();
            $category_id_array = $query->fetchAll(PDO::FETCH_ASSOC);

            $categories = array();
            foreach($category_id_array as $row)
            {
                $category_id = $row['category_id'];
                $query = $GLOBALS['DB']->prepare("SELECT * FROM categories WHERE id = {$category_id};");
                $query->execute();

                $category_rows = $query->fetchAll(PDO::FETCH_ASSOC);

                foreach($category_rows as $cat)
                {
                    $id = $cat['id'];
                    $name = $cat['category'];
                    $new_category = new Category($name, $id);
                    array_push($categories, $new_category);
                }
            }
            return $categories;
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
