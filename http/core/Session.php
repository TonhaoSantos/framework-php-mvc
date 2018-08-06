<?php

namespace Core;

class Session
{
    //Criando uma sessão nova
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    //Pegando valor da sessão
    public static function get($key)
    {
        if(isset($_SESSION[$key])){
            return $_SESSION[$key];
        }
        
        return false;
    }

    //Destruindo sessão
    public static function destroy($keys)
    {
        //Verifica se é um array de sessão e detroi todos se nao só destroi o array em questão
        if(is_array($keys)){
            foreach ($keys as $key) {
                unset($_SESSION[$key]);
            }
        }
        
        unset($_SESSION[$keys]);
    }
}