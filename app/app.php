<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Task.php";
    require_once __DIR__."/../src/Category.php";
    require_once __DIR__."/../setup.config";

    $app = new Silex\Application();

    $DB = new PDO('pgsql:host=localhost;dbname=to_do', $DB_USER, $DB_PASS);

    $app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../views'));

    $app->get("/", function() use ($app) {
        $allTasks = Task::getAll();

        return $app['twig']->render('homepage.twig');
    });

    return $app;
?>
