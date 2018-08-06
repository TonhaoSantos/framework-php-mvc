<?php

namespace Core;

class Redirect
{
    //Recebe a url que queremos ir e uma msg opcional
    public static function route($url, $with = [])
    {
        //Verifica se a variavel opcional possui alguam coisa dentro
        if (count($with) > 0) {
            //se possuir, entra e pega a chave e valor e cria uma sessão
            foreach ($with as $key => $value) {
                Session::set($key, $value);
            }
        }
        
        return header("location:$url");
    }
}