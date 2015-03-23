<?php
    class Category
    {
        private $name;
        private $id;

        function __construct($name, $id = null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        function getName()
        {
            return $this->name;
        }

        function getId()
        {
            return $this->id;
        }

        function setId($new_id)
        {
            $this->id = (int) $new_id;
        }

        function getTasks()
        {
            $tasks = array();
            $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks WHERE category_id = {$this->getId()};");
            foreach($returned_tasks as $task) {
                $desc = $task['description'];
                $id = $task['id'];
                $category_id = $task['category_id'];
                $new_Task = new Task($desc, $id, $category_id);
                array_push($tasks, $new_Task);
            }
            return $tasks;
        }

        function save()
        {
            $statement = $GLOBALS['DB']->prepare("INSERT INTO categories (category) VALUES ('{$this->getName()}') RETURNING id;");
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $this->setId($result['id']);
        }

        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE categories SET category = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM categories * WHERE id = {$this->getId()};");
        }

        function addTask($new_task)
        {
            $GLOBALS['DB']->exec("INSERT INTO categories_tasks (category_id, task_id) VALUES ({$this->getId()}, {$new_task->getId()});");
        }

        function tasks()
        {
            $query = $GLOBALS['DB']->prepare("SELECT task_id FROM categories_tasks WHERE category_id = {$this->getId()};");
            $query->execute();
            $task_id_array = $query->fetchAll(PDO::FETCH_ASSOC);

            $tasks = array();
            foreach($task_id_array as $row)
            {
                $task_id = $row['task_id'];
                $task_query = $GLOBALS['DB']->prepare("SELECT * FROM tasks WHERE id = {$task_id};");
                $task_query->execute();
                $task_query = $task_query->fetchAll(PDO::FETCH_ASSOC);

                //only goes through once because we only grab one out
                foreach($task_query as $task)
                {
                    $id = $task['id'];
                    $name = $task['task'];
                    $new_task = new Task($name, $id);
                    array_push($tasks, $new_task);
                }
            }
            return $tasks;
        }

        static function getAll()
        {
            $returned_categories = $GLOBALS['DB']->query("SELECT * FROM categories;");
            $categories = array();
            foreach($returned_categories as $category) {
                $name = $category['category'];
                $id = $category['id'];
                $new_category = new Category($name, $id);
                array_push($categories, $new_category);
            }
            return $categories;
        }

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM categories *;");
        }

        static function find($search_id)
        {
            //query for the category
            $rows = $GLOBALS['DB']->query("SELECT * FROM categories WHERE id = {$search_id};");
            $found_category = null;
            foreach($rows as $row) {
                $id = $row['id'];
                $name = $row['category'];
                $new_category = new Category($name, $id);
                $found_category = $new_category;
            }
            return $found_category;
        }
    }
?>
