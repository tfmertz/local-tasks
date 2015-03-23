<?php

    /**
     @backupGlobals disabled
     @backupStaticAttributes disabled
    */

    require_once "src/Category.php";

    class CategoryTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Category::deleteAll();
        }

        function test_getName()
        {
            //Arrange
            $test_Category = new Category("Home");

            //Act
            $result = $test_Category->getName();

            //Assert
            $this->assertEquals("Home", $result);
        }

        function test_setName()
        {
            //arrange
            $test_Category = new Category("H");

            //act
            $test_Category->setName("Home");
            $result = $test_Category->getName();

            //assert
            $this->assertEquals("Home", $result);
        }

        function test_getId()
        {
            //Arrange
            $test_Category = new Category("Other", 1);

            //Act
            $result = $test_Category->getId();

            //Assert
            $this->assertEquals(1, $result);
        }

        function test_setId()
        {
            //Arrange
            $test_Category = new Category("Work", 1);

            //Act
            $test_Category->setId(2);

            //Assert
            $result = $test_Category->getId();
            $this->assertEquals(2, $result);
        }

        function test_save()
        {
            //Arrange
            $test_Category = new Category("Garden");
            $test_Category->save();

            //Act
            $result = Category::getAll();

            //Assert
            $this->assertEquals($test_Category, $result[0]);
        }

        function test_saveGetsId()
        {
            //arrange
            $test_Category = new Category("Work");
            $test_Category->save();

            //act
            $result = $test_Category->getId();

            //assert
            $this->assertEquals(true, is_numeric($result));
        }

        function test_getAll()
        {
            //Arrange
            $test_Category = new Category("Home");
            $test_Category->save();
            $test_Category2 = new Category("Work");
            $test_Category2->save();

            //Act
            $result = Category::getAll();

            //Assert
            $this->assertEquals([$test_Category, $test_Category2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $test_Category = new Category("Home");
            $test_Category->save();
            $test_Category2 = new Category("Work");
            $test_Category2->save();

            //Act
            Category::deleteAll();
            $result = Category::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function test_find()
        {
            //Arrange
            $test_Category = new Category("Garden");
            $test_Category->save();
            $test_Category2 = new Category("Kitchen");
            $test_Category2->save();

            //Act
            $result = Category::find($test_Category->getId());

            //Assert
            $this->assertEquals($test_Category, $result);
        }

        function test_update()
        {
            //arrange
            $test_Category = new Category("Garden");
            $test_Category->save();

            //act
            $test_Category->update("Home");
            $result = $test_Category->getName();

            //assert
            $this->assertEquals("Home", $result);
        }

        function test_updateDatabase()
        {
            //arrange
            $test_Category = new Category("Garden");
            $test_Category->save();

            //act
            $test_Category->update("Home");
            $result = Category::getAll();

            //assert
            $this->assertEquals("Home", $result[0]->getName());
        }

        function test_delete()
        {
            //Arrange
            $test_Category = new Category("Garden");
            $test_Category->save();
            $test_Category2 = new Category("Kitchen");
            $test_Category2->save();

            //act
            $test_Category2->delete();
            $result = Category::getAll();

            //assert
            $this->assertEquals([$test_Category], $result);

        }

        function test_addTask()
        {
            //arrange
            $test_Category = new Category("Garden");
            $test_Category->save();

            $test_task = new Task("Weed the beds");
            $test_task->save();

            //act
            $test_Category->addTask($test_task);

            //assert
            $this->assertEquals([$test_task], $test_Category->tasks());
        }

        function test_tasks()
        {
            //arrange
            $test_Category = new Category("Garden");
            $test_Category->save();

            $test_task = new Task("Weed the beds");
            $test_task->save();

            $test_task2 = new Task("Dig out the tree");
            $test_task2->save();

            //act
            $test_Category->addTask($test_task);
            $test_Category->addTask($test_task2);
            $result = $test_Category->tasks();

            //assert
            $this->assertEquals([$test_task, $test_task2], $result);
        }
    }

?>
