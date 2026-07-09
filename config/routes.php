<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/*
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 * Cache: Routes are cached to improve performance, check the RoutingMiddleware
 * constructor in your `src/Application.php` file to change this behavior.
 *
 */
Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    // Register scoped middleware for in scopes.
    $routes->registerMiddleware('csrf', new CsrfProtectionMiddleware([
        'httpOnly' => true,
    ]));

    /*
     * Apply a middleware to the current route scope.
     * Requires middleware to be registered through `Application::routes()` with `registerMiddleware()`
     */
    $routes->applyMiddleware('csrf');

    /*
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

    /*
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    $routes->connect('/login', ['controller' => 'Usuarios', 'action' => 'login']);
    $routes->connect('/logout', ['controller' => 'Usuarios', 'action' => 'logout']);
    $routes->connect('/perfil', ['controller' => 'Usuarios', 'action' => 'perfil']);
    $routes->connect('/usuarios', ['controller' => 'Usuarios', 'action' => 'index']);
    $routes->connect('/cambiaclave', ['controller' => 'Usuarios', 'action' => 'cambiaclave']);
    $routes->connect('/nuevaclave', ['controller' => 'Usuarios', 'action' => 'nuevaclave']);
    $routes->connect('/registrodocente', ['controller' => 'Usuarios', 'action' => 'registrodocente']);
    $routes->connect('/registroestudiante', ['controller' => 'Usuarios', 'action' => 'registroestudiante']);

    $routes->connect('/sedes', ['controller' => 'Sedes', 'action' => 'index']);

    $routes->connect('/keepalive', ['controller' => 'Usuarios', 'action' => 'keepalive']);

    $routes->connect('/captcha-image/:id', ['controller' => 'Captcha', 'action' => 'image'], ['id' => '.+', 'pass' => ['id']]);
    $routes->connect('/captcha-reload', ['controller' => 'Captcha', 'action' => 'reload']);

    $routes->connect('/cursos/get-programas', ['controller' => 'Cursos', 'action' => 'getProgramas']);
    $routes->connect('/cursos/get-asignaturas', ['controller' => 'Cursos', 'action' => 'getAsignaturas']);
    $routes->connect('/cursos/get-horarios', ['controller' => 'Cursos', 'action' => 'getHorarios']);
    $routes->connect('/cursos/get-aulas', ['controller' => 'Cursos', 'action' => 'getAulas']);

    /*
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *
     * ```
     * $routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);
     * $routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);
     * ```
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks(DashedRoute::class);
});

/*
 * API routes (without CSRF middleware)
 */
Router::scope('/api', function (RouteBuilder $routes) {
    $routes->connect('/login', ['controller' => 'Api', 'action' => 'login']);
    $routes->connect('/logout', ['controller' => 'Api', 'action' => 'logout']);
    $routes->connect('/profile', ['controller' => 'Api', 'action' => 'profile']);
    $routes->connect('/dashboard', ['controller' => 'Api', 'action' => 'dashboard']);

    $routes->connect('/estudiantes', ['controller' => 'Api', 'action' => 'estudiantes']);
    $routes->connect('/estudiantes/{id}', ['controller' => 'Api', 'action' => 'estudianteView'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/estudiantes/guardar', ['controller' => 'Api', 'action' => 'estudianteGuardar']);
    $routes->connect('/estudiantes/eliminar/{id}', ['controller' => 'Api', 'action' => 'estudianteEliminar'], ['id' => '\d+', 'pass' => ['id']]);

    $routes->connect('/docentes', ['controller' => 'Api', 'action' => 'docentes']);
    $routes->connect('/docentes/{id}', ['controller' => 'Api', 'action' => 'docenteView'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/docentes/guardar', ['controller' => 'Api', 'action' => 'docenteGuardar']);
    $routes->connect('/docentes/eliminar/{id}', ['controller' => 'Api', 'action' => 'docenteEliminar'], ['id' => '\d+', 'pass' => ['id']]);

    $routes->connect('/cursos', ['controller' => 'Api', 'action' => 'cursos']);
    $routes->connect('/cursos/{id}', ['controller' => 'Api', 'action' => 'cursoView'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/cursos/guardar', ['controller' => 'Api', 'action' => 'cursoGuardar']);
    $routes->connect('/cursos/eliminar/{id}', ['controller' => 'Api', 'action' => 'cursoEliminar'], ['id' => '\d+', 'pass' => ['id']]);

    $routes->connect('/horarios', ['controller' => 'Api', 'action' => 'horarios']);
    $routes->connect('/horarios/{id}', ['controller' => 'Api', 'action' => 'horarioView'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/horarios/guardar', ['controller' => 'Api', 'action' => 'horarioGuardar']);
    $routes->connect('/horarios/eliminar/{id}', ['controller' => 'Api', 'action' => 'horarioEliminar'], ['id' => '\d+', 'pass' => ['id']]);

    $routes->connect('/periodos', ['controller' => 'Api', 'action' => 'periodos']);
    $routes->connect('/sedes', ['controller' => 'Api', 'action' => 'sedes']);
    $routes->connect('/programas', ['controller' => 'Api', 'action' => 'programas']);
    $routes->connect('/asignaturas', ['controller' => 'Api', 'action' => 'asignaturas']);
    $routes->connect('/aulas', ['controller' => 'Api', 'action' => 'aulas']);
    $routes->connect('/carreras', ['controller' => 'Api', 'action' => 'carreras']);
    $routes->connect('/trayectos', ['controller' => 'Api', 'action' => 'trayectos']);
});
