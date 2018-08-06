<?php

namespace Core;

abstract class BaseController
{
    //Variavel de incrementação para aparecer na view
    protected $view;
    //Erros
    protected $errors;
    //Inputs
    protected $inputs;
    //Success
    protected $success;
    //User
    protected $users;
    //Token Form
    protected $tokenForm;
    //Classe Core\Auth
    protected $auth;
    //Tem conteudo
    protected $hasContent;
    //Caminho onde esta a view
    private $viewPath;
    //Caminho onde esta a Layout
    private $layoutPath;
    //Endereço canonical
    private $canonicalUrl;
    //Tags Facebook
    private $facebookTags = ['ogTitle' => 'Titulo',
                             'ogDescription' => 'Descrição',
                             'ogUrl' => 'https://www.dominio.com.br',
                             'ogImage' => 'https://www.dominio.com.br/public/assets/img/facebook/nomeimagem-fbimg-principal.png'];
    // Google follow tag
    private $googleFollowTag = 'noindex, nofollow, nosnippet, noodp, noarchive, noimageindex';
    //Definindo Titulo das paginas
    private $pageTitle = null;

    public function __construct()
    {
        $this->view = new \stdClass;
        $this->auth = new Auth;
        $this->hasContent = 0;

        //Verificando se existe erro
        if (Session::get('errors')) {
            $this->errors = Session::get('errors');
            Session::destroy('errors');
        }
        //Verificando se existe inputs
        if (Session::get('inputs')) {
            $this->inputs = Session::get('inputs');
            Session::destroy('inputs');
        }
        //Vendo se sessão existe
        //Se existir pega o valor da sessão e passa para a view
        if(Session::get('success')){
            $this->success = Session::get('success');
            Session::destroy('success');
        }
        //Verificando se existe usuario
        if (Session::get('user')) {
            //Setando usuario
            $this->users = Session::get('user');
        }
    }

    //Responsavel por renderizar a view
    protected function renderView($viewPath, $layoutPath = null)
    {
        $this->viewPath = $viewPath;
        $this->layoutPath = $layoutPath;

        //Verifica se esta vindo por parametro algum layout
        if ($layoutPath) {
            return $this->layout();
        } else {
            return $this->content();
        }
    }

    //Responsavel por trazer o conteudo da view
    protected function content()
    {
        //Verifica se arquivo view existe
        if(file_exists(__DIR__ . "/../app/Views/{$this->viewPath}.phtml")){
            return require_once __DIR__ . "/../app/Views/{$this->viewPath}.phtml";
        } else {
            echo "Error: View path not found!";
        }
    }

    //Responsavel por trazer o layout
    protected function layout()
    {
        //Verifica se arquivo view existe
        if(file_exists(__DIR__ . "/../app/Views/layout/{$this->layoutPath}.phtml")){
            return require_once __DIR__ . "/../app/Views/layout/{$this->layoutPath}.phtml";
        } else {
            echo "Error: Layout path not found!";
        }
    }

    //Setando titulo das paginas
    protected function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;
    }

    //Obtendo titulo das paginas podendo setar separador (opcional)
    protected function getPageTitle($separator)
    {
        if (!empty($separator)) {
            return $this->pageTitle . " " . $separator . " ";
        } else {
            return $this->pageTitle;
        }
    }

    //Setando tags Facebook
    protected function setFacebookTags($title, $description, $url, $image)
    {
        //Setando titulo
        if(!empty($title)){
            $this->facebookTags['ogTitle'] = $title;
        }

        //Setando descrição
        if(!empty($description)){
            $this->facebookTags['ogDescription'] = $description;
        }

        //Setando url
        if(!empty($url)){
            $this->facebookTags['ogUrl'] = $url;
        }

        //Setando url
        if(!empty($image)){
            $this->facebookTags['ogImage'] = $image;
        }
    }

    //Obtendo tags Facebook
    protected function getFacebookTags($key)
    {
        return $this->facebookTags[$key];
    }

    //Setando Canonical URL
    protected function setCanonicalUrl($canonicalUrl)
    {
        //Setando Cananical
        if(!empty($canonicalUrl)){
            $this->canonicalUrl = $canonicalUrl;
        }
    }

    //Obtendo Canonical URL
    protected function getCanonicalUrl()
    {
        return $this->canonicalUrl;
    }

    //Setando Google Follow Tag
    protected function setGoogleFollowTag($googleFollowTag)
    {
        //Setando Google Follow Tag
        if(!empty($googleFollowTag)){
            $this->googleFollowTag = $googleFollowTag;
        }
    }

    //Obtendo Google Follow Tag
    protected function getGoogleFollowTag()
    {
        return $this->googleFollowTag;
    }

    //Redirecionando se não estiver logado
    public function forbiden()
    {
        return Redirect::route('/login');
    }
}
