<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Task.php";
    require_once __DIR__."/../src/Category.php";
    require_once __DIR__."/../setup.config";

    $app = new Silex\Application();
    $app['debug'] = true;

    $DB = new PDO('pgsql:host=localhost;dbname=to_do', $DB_USER, $DB_PASS);

    $app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../views'));

    $app->get("/", function() use ($app) {

        return $app['twig']->render('homepage.twig');
    });

    $app->get("/categories", function() use ($app) {

        return $app['twig']->render('categories.twig', array('category_array' => Category::getAll()));
    });

    $app->get("/tasks", function() use ($app) {

        return $app['twig']->render('tasks.twig', array('task_array' => Task::getAll()));
    });

    $app->post("/tasks", function() use ($app) {

        $new_task = new Task($_POST['description']);
        $new_task->save();

        return $app['twig']->render('tasks.twig', array('task_array' => Task::getAll()));
    });

    return $app;
?>
