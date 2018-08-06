<?php

namespace Core;

class Route
{
	private $routes;

	public function __construct(array $routes)
	{
		$this->setRoutes($routes);
		$this->run();
	}

	/**
	 * Quebrando a rota configurada em 3 indices
	 */
	private function setRoutes($routes)
	{
		//Verificando o controller e a action no indice 1 da rota configurada
		foreach ($routes as $route){
			$explode = explode('@', $route[1]);

			//Verifica se existe autenticação na rota
			if (isset($route[2])) {
				//Remontando rota configurada com autenticação dela
				$r = [$route[0], $explode[0], $explode[1], $route[2]];
			} else {
				//Remontando rota configurada
				$r = [$route[0], $explode[0], $explode[1]];				
			}
			$newRoutes[] = $r;
		}

		$this->routes = $newRoutes;
	}

	/**
	 * Obtendo POST e GEt da url
	 */
	private function getRequest()
	{	
		//Instanciando a classe vazia padrão do php
		$objA = new \stdClass;

	    //Quando for do tipo GET
	    $get = (object)$_GET;
	    //Quando for do tipo POST
	    $post = (object)$_POST;

	    $objA->get = $get;
	    $objA->post = $post;

	    return $objA;
	}

	/**
	 * Obtendo url informada pelo usuario
	 */
	private function getUrl()
	{
		return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	}

	private function run()
	{
		//Obtendo e Quebrando url
		$url = $this->getUrl();
		$urlArray = explode('/', $url);
		$found = false;

		//Verificando se existe parametro no indice 0 da rota configurada, se tiver, informa o que foi passado na url
		foreach ($this->routes as $route){
			$routeArray = explode('/', $route[0]);
			$param = [];

			for($i = 0; $i < count($routeArray); $i++){
				//Verificando se é parametro e se a quantidade de indices na url e na rota são iguais
				if((strpos($routeArray[$i], "{") !== false) && (count($urlArray) == count($routeArray))) {
					//Inserindo o valor passado na url no parametro da rota configurada  
					$routeArray[$i] = $urlArray[$i];
					//Armazenando parametro para enviar para o controller
					$param[] = $urlArray[$i];
				}
				$route[0] = implode($routeArray, '/');
			}

			if($url == $route[0]){
				$found = true;
				$controller = $route[1];
				$action = $route[2];
				//Instanciando Core/Auth
				$auth = new Auth;

				//Verificação se existe autenticação na rota e se o usuario esta logado
				if (isset($route[3]) && $route[3] == 'auth' && !$auth->check()) {
					$action = 'forbiden';
				}

				break;
			}
		}

		//Chamando controller
		if($found){
			$controller = Container::newController($controller);
			
			//Verificando se existe parametro para passar para o controller
			switch (count($param)){
				case 1:
					$controller->$action($param[0], $this->getRequest());
					break;
				case 2:
					$controller->$action($param[0], $param[1], $this->getRequest());
					break;
				case 3:
					$controller->$action($param[0], $param[1], $param[2], $this->getRequest());
					break;
				default:
					$controller->$action($this->getRequest());
					break;
			}
		} else {
			Container::pageNotFound(404);
		}
	}
}