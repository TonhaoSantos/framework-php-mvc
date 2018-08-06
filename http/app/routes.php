<?php

/**
 * Configurando as rotas
 * Parametros:
 * 1º - Rota digitada pelo usuario
 * 2º - Controller responsavel por esta rota + "@ação" responsavel
 * 3º - Proteção da rota, verifica se esta logado para poder visualizar
 * 4º - Autenticação
 *
 * $route[] = ['1º', '2º@3º', '4º'];
 *
 * Exemplo:
 *         $route[] = ['/portal', 'SystemController@index', 'auth'];
 *
 * Obs.: Informar no max. 3 parametros nas rotas (1º)
 */
/**
 * User
 **/
// User Create
$route[] = ['/user/create', 'UserController@create'];
// User Store
$route[] = ['/user/store', 'UserController@store'];
// User Editor
$route[] = ['/user/{id}/edit', 'UserController@edit'];
// User Update
$route[] = ['/user/{id}/update', 'UserController@update'];
// User Login
$route[] = ['/login', 'UserController@login'];
// User Login Auth
$route[] = ['/login/auth', 'UserController@auth'];
// User Logout
$route[] = ['/logout', 'UserController@logout'];

/**
 * Home
 **/
// Home
$route[] = ['/', 'HomeController@index'];
$route[] = ['/index', 'HomeController@index'];
// Sitemap
$route[] = ['/sitemap', 'HomeController@sitemap'];






/**
 * System
 **/
// Home
$route[] = ['/portal', 'SystemController@index', 'auth'];


/**
 * Retornando as rotas configuradas
 */
 return $route;
