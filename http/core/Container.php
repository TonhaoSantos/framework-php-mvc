<?php

namespace Core;

class Container
{
	//Automatiza o carregamento do controller
	public static function newController($controller)
	{
		$objController = "App\\Controllers\\" . $controller;
		return new $objController;
	}

	//Automatiza a instancia da model
	public static function getModel($model)
	{
		$objModel = "\\App\\Models\\" . $model;
		return new $objModel(DataBase::getDatabase());
	}

	//Automatiza o carregamento da pagina de erro
	public static function pageNotFound($error = 404)
	{
		if (file_exists(__DIR__ . "/../app/Views/errors/{$error}.phtml")) {
			return require_once __DIR__ . "/../app/Views/errors/{$error}.phtml";
		} else {
			echo "Erro 404 - Página não encontrada!";
		}
	}
}