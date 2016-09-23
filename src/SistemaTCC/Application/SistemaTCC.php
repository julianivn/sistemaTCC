<?php

namespace SistemaTCC\Application;

use Silex\Application;
use Silex\Provider\TwigServiceProvider;

class SistemaTCC extends Application {

    public function __construct() {

        parent::__construct();

        $app = $this;

        $app['debug'] = true;

        // Provider
        $app->register(new TwigServiceProvider(), ['twig.path' => __DIR__ . '/../View/']);

        // Controller
        $app->get('/', "\\SistemaTCC\\Controller\\IndexController::indexAction");
        $app->get('/creditos/', "\\SistemaTCC\\Controller\\IndexController::creditosAction");

    }

}
