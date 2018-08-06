<?php

namespace Core;

class Auth
{
    //Classe responsavel por manipular quem esta logado ou não
    private static $id = null;
    private static $name = null;
    private static $email = null;
    private static $logged = null;

    public function __construct()
    {
        //Verifica se existe sessão de usuario
        if (Session::get('user')) {
            //Pegando sessão
            $user = Session::get('user');
            //Definindo dados
            self::$id = $user['id'];
            self::$name = $user['name'];
            self::$email = $user['email'];

            //Definindo dados
            self::$logged = Session::get('logged');
        }
    }

    //Retorna id
    public static function id()
    {
        return self::$id;
    }

    //Retorna nome
    public static function getName()
    {
        return self::$name;
    }

    //Retorna email
    public static function email()
    {
        return self::$email;
    }

    //Retorna status
    public static function logged()
    {
        return self::$logged;
    }

    //Verifica se esta logado
    public static function check()
    {
        if (self::$logged == null) {
            return false;
        } else {
            return true;
        }
    }
}
