<?php

    /**
        @backupGlobals disabled
        @backupStaticAttributes disabled
    */


    require_once 'src/Task.php';
    require_once 'setup.config';

    $DB = new PDO('pgsql:host=localhost;dbname=to_do_test', $DB_USER, $DB_PASS);

    class TaskTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Task::deleteAll();
        }

        function test_save()
        {
            //arrange
            $description = "Wash the dog";
            $test_task = new Task($description);

            //act
            $test_task->save();

            //assert
            $result = Task::getAll();
            $this->assertEquals($test_task, $result[0]);
        }

        function test_getAll()
        {
            //arrange
            $description = "Wash the dog";
            $description2 = "Water the lawn";
            $test_Task = new Task($description);
            $test_Task2 = new Task($description2);
            $test_Task->save();
            $test_Task2->save();

            //act
            $result = Task::getAll();

            //assert
            $this->assertEquals([$test_Task, $test_Task2], $result);
        }

        function test_deleteAll()
        {
            //arrange
            $description = "Wash the dog";
            $description2 = "Water the lawn";
            $test_Task = new Task($description);
            $test_Task2 = new Task($description2);
            $test_Task->save();
            $test_Task2->save();

            //act
            Task::deleteAll();

            $result = Task::getAll();

            //assert
            $this->assertEquals([], $result);
        }

        function test_getId()
        {
            //Arrange
            $description = "Wash the dog";
            $id = 1;
            $test_Task = new Task($description, $id);

            //Act
            $result = $test_Task->getId();

            //Assert
            $this->assertEquals(1, $result);
        }

        function test_setId()
        {
            //Arrange
            $description = "Wash the dog";
            $test_Task = new Task($description);

            //Act
            $test_Task->setId(2);

            //Assert
            $result = $test_Task->getId();
            $this->assertEquals(2, $result);
        }
    }
