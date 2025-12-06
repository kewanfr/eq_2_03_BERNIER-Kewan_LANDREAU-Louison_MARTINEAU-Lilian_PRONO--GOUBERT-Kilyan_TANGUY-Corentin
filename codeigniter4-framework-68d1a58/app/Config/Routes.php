<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/login', 'Login::loginView');
$routes->get('/register', 'Register::registerView');
$routes->get('/cookies/accept', 'Cookies::accept');
$routes->get('/cookies/decline', 'Cookies::decline');
$routes->get('/auth/logout', 'Login::logoutAction');

$routes->post('/auth/login', 'Login::loginAction');
$routes->post('/auth/register', 'Register::registerAction');

service('auth')->routes($routes);
