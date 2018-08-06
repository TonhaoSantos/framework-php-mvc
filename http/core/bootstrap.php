<?php
/**
 * Iniciando sessão
 * Se não existir nenhum id de sesseão, starta ela
 */
 //session_cache_expire(10);
if(!session_id()){
   session_start();
}

 /**
  * Setando fuso horario da aplicação
  */
 date_default_timezone_set('America/Sao_Paulo');

/**
 * Iniciando rotas
 * Importandos array das rotas
 */
$routes = require_once __DIR__ . "/../app/routes.php";

/**
 * Instacia a classe Route passando as rotas
 */
$route = new \Core\Route($routes);