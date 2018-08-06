<?php

namespace App\Controllers;

use Core\BaseController;
use Core\Container;
use Core\Redirect;
use Core\Validator;

/*
//Outros itens disponiveis
use Core\Email;
use Core\Session;
use Core\Security;
*/


class MesmoNomeArquivoController extends BaseController
{
	//Variavel de instancia da model
	private $nameModelInstance;


	public function __construct()
	{
		//Mantendo construtor da classe pai
		parent::__construct();

		//Instanciando a model recebendo no nome do arquivo da model
		$this->$nameModelInstance = Container::getModel("NomeModel");
	}


	//Action Padrão
	public function	nomeAction()
	{
		//Passanto Titulo da pagina
		$this->setPageTitle('Tag title do html');

		//Passando Canonical
		$this->setCanonicalUrl('url desta pagina');

		//Passando tags Facebook, se algum parametro for passado como vazio '' sera utilizado o default da variavel no core > BaseController.php
		$this->setFacebookTags('Titulo', 'Descrição', 'URL', 'URL absoluta da img');

		//Passando Google Follow Tag, se algum parametro for passado como vazio '' sera utilizado o default da variavel no core > BaseController.php
		$this->setGoogleFollowTag('index, follow');

		//Definindo a view e o layout
		$this->renderView('about/index', 'layoutgeneral');
	}


	//Action Padrão passando dados para a view pela variavel dela
	public function	nomeAction()
	{
		//Aramazenando na variavel da view de duas formas
		$this->view->nomeVariavel = 80;
		$this->view->nomeVariavel = 'Texto';
		$this->view->nomeVariavel = {};
		$this->view->nomeVariavel = [];
		$this->view->nomeVariavel->outraVariavel = 80;
		$this->view->nomeVariavel->outraVariavel = 'Texto';
		$this->view->nomeVariavel->outraVariavel = {};
		$this->view->nomeVariavel->outraVariavel = [];

		//Passanto Titulo da pagina
		$this->setPageTitle('Tag title do html');

		//Passando Canonical
		$this->setCanonicalUrl('url desta pagina');

		//Passando tags Facebook, se algum parametro for passado como vazio '' sera utilizado o default da variavel no core > BaseController.php
		$this->setFacebookTags('Titulo', 'Descrição', 'URL', 'URL absoluta da img');

		//Passando Google Follow Tag, se algum parametro for passado como vazio '' sera utilizado o default da variavel no core > BaseController.php
		$this->setGoogleFollowTag('index, follow');

		//Definindo a view e o layout
		$this->renderView('about/index', 'layoutgeneral');
	}


	//Action Padrão passando dados para a view por uma variavel definida por você
	public function	nomeAction()
	{
		//Aramazenando na variavel da view de duas formas
		$this->nomeVariavel = 80;
		$this->nomeVariavel = 'Texto';
		$this->nomeVariavel = {};
		$this->nomeVariavel = [];

		//Passanto Titulo da pagina
		$this->setPageTitle('Tag title do html');

		//Passando Canonical
		$this->setCanonicalUrl('url desta pagina');

		//Passando tags Facebook, se algum parametro for passado como vazio '' sera utilizado o default da variavel no core > BaseController.php
		$this->setFacebookTags('Titulo', 'Descrição', 'URL', 'URL absoluta da img');

		//Passando Google Follow Tag, se algum parametro for passado como vazio '' sera utilizado o default da variavel no core > BaseController.php
		$this->setGoogleFollowTag('index, follow');

		//Definindo a view e o layout
		$this->renderView('about/index', 'layoutgeneral');
	}


	//Action com request
	public function nomeAction($request)
	{
		//Para obter o post
		$request->post->nomeCampoPassado

		//Para obter o get
		$request->get->nomeCampoPassado


		//Fazer oq quiser com os dados capiturados
	}


	//Action com validação
	public function nomeAction()
	{
		//Dados para serem validados
		$data = [
			'nomeAtributo' => 'Valor',
			'nomeAtributo' => $request->post->nomeCampoPassado, //Para usar precisa passar por parametro o $request igual o anterior
			'nomeAtributo' => $request->get->nomeCampoPassado //Para usar precisa passar por parametro o $request igual o anterior
		];

		if (Validator::make($data, $this->$nameModelInstance->nomeRulesDefinidaNaModelInstanciada())) {
			//Caso seja invalidado a validação informe a rota para onde retornar, se aprovado pula esta linha e segue o fluxo
			return Redirect::route("/rota");
		}
	}


	//Action com redirect
	public function nomeAction()
	{
		//Redirect passando mensagem de erro
		return Redirect::route('/rota', [
			'errors' => ['Mensagem']
		]);

		//Redirect passando mensagem de sucesso
		return Redirect::route('/rota', [
			'success' => ['Mensagem']
		]);
	}


	//Action para view que possui formulario
	public function nomeAction()
	{
		//Criando sessão
		Security::formToken('tokenform');

		$this->tokenForm = Session::get('tokenform');
	}


	//Action para a action do formulario
	public function nomeAction($request)
	{
		//Verificando se o token do request é o mesmo do geredo na sessão (na action anterior)
		if ($request->post->tokenForm == $this->tokenForm) {

		} else {
			if (isset($_POST)) {
				unset($_POST);
			}
			if (isset($request->get)) {
				unset($request->get);
			}

			return Redirect::route('/rota', [
				'errors' => ['Token não confere com o criado pelo sistema.']
			]);
		}
	}


	//Action que valida se esta sendo passado request, assim da para bloquear rota de get, post ou ambos
	public function nomeAction($request)
	{
		//Usar Security::hasRequestPost($request->post) ou Security::hasRequestGet($request->get) como quiser para validar
		if (Security::hasRequestPost($request->post)) {

		} else if (Security::hasRequestGet($request->get)) {

		} else {

		}
	}


	//Usuando core > Security.php
	public function nomeAction($request)
	{
		//Dentro deste arquivo existem algumas funções prontas
		//Para usar:
		$variavelQueRecebe = Security::nomeFuncao($seTiverParametroPassarViaVariavelOuDireta);

		//Depois fazer o que quiser com este dado
		//Desde passos anteriores ou oq vc imaginar
	}


	//Enviando email core > Email.php
	public function nomeAction($request)
	{
		//Tentando enviar email
		try{
			//Setando dados do e-mail
			$data = [
				'email' => $email,
				'assunto' => 'Assinatura Newsletter'
			];

			//Enviando mensagem
			$sendMail = Email::subscribeNewsletterSend($data);
		}catch(\Exception $e){
			$sendMail = '';
		}

		if ($sendMail == true || $sendMail == '') {
			return Redirect::route('/rota', [
				'success' => ['Mensagem']
			]);
		} else {
			return Redirect::route('/rota', [
				'errors' => ['Mensagem']
			]);
		}
	}


	//Usar os scripts das models
	public function nomeAction($request)
	{
		$varivavelDeRecebimentoDosDados = $this->$nameModelInstance->funcaoDaModel($parametro1m, $parametro2, $parametrosDefinidos);

		//True se deu certo ou se retornou algo e false se nao seu certo ou nao retornou nada
		if ($varivavelDeRecebimentoDosDados == true) {
			//Usar um laço de repeditção para manipular os dados tanto aqui quando na view
			foreach ($varivavelDeRecebimentoDosDados as $key => $value) {
			}


			//Para passar os dados para view se foi feito uma consulta
			$this->view->nomeQualquer = $varivavelDeRecebimentoDosDados;
		} else {
			//Case retorne falso para não achado resultado ou erro ao atualizar, gravar ou deletar
		}
	}
}
