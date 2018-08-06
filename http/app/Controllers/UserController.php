<?php

namespace App\Controllers;

use Core\Container;
use Core\BaseController;
use Core\Redirect;
use Core\Validator;
use Core\Authenticate;
use Core\Email;
use Core\Session;
use Core\Security;

class UserController extends BaseController
{
	//Extendendo classe, como tem uma sendo extendida nao tem outra forma a nao ser por trait
	use Authenticate;

	//Variavel de instancia da model
	private $userInstance;

	public function __construct()
	{
		//Mantendo construtor da classe pai
		parent::__construct();
		
		//Instanciando a model
		$this->userInstance = Container::getModel("User");
	}

	//Criar usuario
	public function create()
	{	
		$this->setPageTitle("New User");
		return $this->renderView('user/create', 'layoutgeneral');
	}

	//Inserir usuario
	public function store($request)
	{	
		//Pegando dados passados
		$data = [
			'name' => $request->post->name,
			'email' => $request->post->email,
			'password' => $request->post->password
		];

		//Verificando retorno validação (rules vindo do models/User.php)
		if (Validator::make($data, $this->userInstance->rulesCreate())) {
			//Retorna para a rota de edição
			return Redirect::route("/user/create");
		}

        //Criptografando senha passada
        //$passwordOptions = ['cost' => 13];
	    //$data['password'] = password_hash($request->post->password, PASSWORD_BCRYPT, $passwordOptions);
	    $data['password'] = password_hash($request->post->password, PASSWORD_BCRYPT);
		
		try{
			//Busca de qual post quero atualizar
			$this->userInstance->create($data);
			return Redirect::route('/', [
				'success' => ['Usuario criado com sucesso!']
			]);
		}catch(\Exception $e){
			return Redirect::route('/', [
				'errors' => [$e->getMessage()]
			]);
		}
	}

	//Editar post
	public function edit($id)
	{	
		$this->view->user = $this->userInstance->where('id', $id)->first();		
		$this->setPageTitle('Edit user - '. $this->view->user->name);
		return $this->renderView('user/edit', 'layoutgeneral');
	}

	//Atualizar usuario
	public function update($id, $request)
	{	
		//Pegando dados passados
		$data = [
			'name' => $request->post->name,
			'email' => $request->post->email,
			'password' => $request->post->password
		];

		//Verificando retorno validação (rules vindo do models/User.php)
		if (Validator::make($data, $this->userInstance->rulesUpdate($id))) {
			//Retorna para a rota de edição
			return Redirect::route("/user/{$id}/edit");
		}

        //Criptografando senha passada
        //$passwordOptions = ['cost' => 13];
	    //$data['password'] = password_hash($request->post->password, PASSWORD_BCRYPT, $passwordOptions);
	    $data['password'] = password_hash($request->post->password, PASSWORD_BCRYPT);
		
		try{
			//Busca de qual post quero atualizar
			$user = $this->userInstance->find($id);
			$user->update($data);
			return Redirect::route('/', [
				'success' => ['Usuario atualizado com sucesso!']
			]);
		}catch(\Exception $e){
			return Redirect::route('/user', [
				'errors' => [$e->getMessage()]
			]);
		}
	}
}