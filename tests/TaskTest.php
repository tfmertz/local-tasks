<?php

    /**
        @backupGlobals disabled
        @backupStaticAttributes disabled
    */

    require_once "src/Task.php";
    require "setup.config";

    $DB = new PDO('pgsql:host=localhost;dbname=to_do_test', $DB_USER, $DB_PASS);


    class TaskTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Task::deleteAll();
        }

        function test_getId()
        {
            //Arrange
            $test_task = new Task("Wash the dog", 1);
            $test_task->save();

            //Act
            $result = $test_task->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function test_setId()
        {
            //arrange
            $test_task = new Task("Water the lawn", 1);
            $test_task->save();

            //Act
            $test_task->setId(2);

            //Assert
            $result = $test_task->getId();
            $this->assertEquals(2, $result);
        }

        function test_save()
        {
            //Arrange
            $test_task = new Task("Wash the dog", 1);

            //Act
            $test_task->save();

            //Assert
            $result = Task::getAll();
            $this->assertEquals($test_task, $result[0]);
        }

        function test_delete()
        {
            //arrange
            $test_task = new Task("Wash the dog");
            $test_task->save();

            //act
            $test_task->delete();
            $result = Task::getAll();

            //assert
            $this->assertEquals([], $result);
        }

        function test_saveSetsId()
        {
            //arrange
            $test_task = new Task("Do the Dishes", null);

            //act
            $test_task->save();

            //assert
            $result = Task::getAll();
            $this->assertEquals(true, is_numeric($test_task->getId()));
        }

        function test_getAll()
        {
            //Arrange
            $test_task = new Task("Wash the dog");
            $test_task->save();
            $test_task2 = new Task("Water the lawn");
            $test_task2->save();

            //Act
            $result = Task::getAll();

            //Assert
            $this->assertEquals([$test_task, $test_task2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $test_task = new Task("Water the lawn");
            $test_task->save();
            $test_task2 = new Task("Feed the dog");
            $test_task2->save();

            //Act
            Task::deleteAll();

            //Assert
            $result = Task::getAll();
            $this->assertEquals([], $result);
        }

        function test_find()
        {
            //Arrange
            $test_task = new Task("Feed the kids");
            $test_task->save();
            $test_task2 = new Task("Water the lawn");
            $test_task2->save();

            //Act
            $result = Task::find($test_task->getId());

            //Assert
            $this->assertEquals($test_task, $result);
        }

        function test_update()
        {
            //arrange
            $test_task = new Task("Feed the hens");
            $test_task->save();

            //act
            $test_task->update("Shovel the driveway");

            //assert
            $this->assertEquals("Shovel the driveway", $test_task->getDescription());
        }

        function test_updateDatabase()
        {
            //arrange
            $test_task = new Task("Feed the hens");
            $test_task->save();

            //act
            $test_task->update("Shovel the driveway");
            $result = Task::find($test_task->getId());

            //assert
            $this->assertEquals("Shovel the driveway", $result->getDescription());
        }

        function test_addCategories()
        {
            //arrange
            $test_task = new Task("Mow the lawn");
            $test_task->save();

            $test_category = new Category("Garden");
            $test_category->save();

            //act
            $test_task->addCategory($test_category);
            $result = $test_task->categories();

            //assert
            $this->assertEquals([$test_category], $result);
        }

        function test_categories()
        {
            //arrange
            $test_task = new Task("Mow the lawn");
            $test_task->save();

            $test_category = new Category("Garden");
            $test_category->save();
            $test_category2 = new Category("Home");
            $test_category2->save();

            //act
            $test_task->addCategory($test_category);
            $test_task->addCategory($test_category2);
            $result = $test_task->categories();

            //assert
            $this->assertEquals([$test_category, $test_category2], $result);
        }
    }
?>
