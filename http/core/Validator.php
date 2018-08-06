<?php

namespace Core;

class Validator
{    
    //Regras de validação
    //$data = dados submetidos no formulario
    //$rules =  regras de validação
    public static function make(array $data, array $rules)
    {
        //Limpando variavel
        $errors = null;

        //Pegando a regra
        foreach ($rules as $rulekey => $ruleValue) {
            
            //Pegando o dado foram passados
            foreach ($data as $datakey => $dataValue) {                    
                if ($rulekey == $datakey) {

                    //Zerando variavel
                    $itemsPipe = [];

                    //Verifica se existe pipi (|) no $ruleValue
                    if (strpos($ruleValue, "|")) {
                        //Quebrando $ruleValue
                        $itemsPipe = explode("|", $ruleValue);

                        foreach ($itemsPipe as $itemsPipe) {
                            //Zerando variavel
                            $subItemsPipe = [];

                            //Verifica se existe dois pontos (:)
                            if(strpos($itemsPipe, ":")) {                                
                                //Quebrando
                                $subItemsPipe = explode(":", $itemsPipe);

                                //Regras
                                switch ($subItemsPipe[0]) {
                                    case 'min':
                                        //Verifica se o tamanho da string passada é menor que a quantidade passada na regra
                                        if (strlen($dataValue) < $subItemsPipe[1]) {
                                            $errors["$rulekey"] = "O campo {$rulekey} deve conter um minimo de {$subItemsPipe[1]} caracteres.";
                                        }
                                        break;
                                    case 'max':
                                        //Verifica se o tamanho da string passada é maior que a quantidade passada na regra
                                        if (strlen($dataValue) > $subItemsPipe[1]) {
                                            $errors["$rulekey"] = "O campo {$rulekey} deve conter um maximo de {$subItemsPipe[1]} caracteres.";
                                        }
                                        break;
                                    case 'unique':
                                        //Obtendo a model 
                                        $objModel = "\\App\\Models\\" . $subItemsPipe[1];
                                        //Instanciando model
                                        $model = new $objModel;
                                        //Verificando se ja existe
                                        $find = $model->where($subItemsPipe[2], $dataValue)->first();
                                        if ($find > 0) {
                                            //Verificando se esta sendo passado o proximo parametro e se existir verifica se é o mesmo usuario pelo id
                                            if(isset($subItemsPipe[3]) && $find->id == $subItemsPipe[3]){
                                                break;                                                
                                            } else {
                                                $errors["$rulekey"] = "O {$rulekey} já esta cadastrado em outro usuario.";
                                                break;
                                            }
                                        }
                                        break;
                                }
                            } else {
                                switch ($itemsPipe) {
                                    case 'required':
                                        if ($dataValue == "" || empty($dataValue)) {
                                            $errors["$rulekey"] = "O campo {$rulekey} deve ser preenchido.";
                                        }
                                        break;
                                    case 'email':
                                        if (!filter_var($dataValue, FILTER_VALIDATE_EMAIL)) {
                                            $errors["$rulekey"] = "O campo {$rulekey} não é valido. Insira um email valido.";
                                        }
                                        break;
                                    case 'float':
                                        if (!filter_var($dataValue, FILTER_VALIDATE_FLOAT)) {
                                        $errors["$rulekey"] = "O campo {$rulekey} deve conter um número decimal.";
                                        }
                                        break;
                                    case 'int':
                                        if (!filter_var($dataValue, FILTER_VALIDATE_INT)) {
                                            $errors["$rulekey"] = "O campo {$rulekey} deve conter um número inteiro.";
                                        }
                                        break;                        
                                    default:
                                        break;
                                }
                            }
                        }
                    }
                    //Verifica se existe dois pontos (:) no $ruleValue
                    elseif (strpos($ruleValue, ":")) {
                        //Quebrando $ruleValue
                        $itemsTwoPoints = explode(":", $ruleValue);

                        //Regras
                        switch ($itemsTwoPoints[0]) {
                            case 'min':
                                //Verifica se o tamanho da string passada é menor que a quantidade passada na regra
                                if (strlen($dataValue) < $itemsTwoPoints[1]) {
                                    $errors["$rulekey"] = "O campo {$rulekey} deve conter um minimo de {$itemsTwoPoints[1]} caracteres.";
                                }
                                break;
                            case 'max':
                                //Verifica se o tamanho da string passada é maior que a quantidade passada na regra
                                if (strlen($dataValue) > $itemsTwoPoints[1]) {
                                    $errors["$rulekey"] = "O campo {$rulekey} deve conter um maximo de {$itemsTwoPoints[1]} caracteres.";
                                }
                                break;
                                /*case 'unique':
                                    //Obtendo a model 
                                    $objModel = "\\App\\Models\\" . $subItemsPipe[1];
                                    //Instanciando model
                                    $model = new $objModel;
                                    //Verificando se ja existe
                                    $find = $model->where($subItemsPipe[2], $dataValue)->first();
                                    if ($find->$subItemsPipe[2]) {
                                        //Verificando se esta sendo passado o proximo parametro e se existir verifica se é o mesmo usuario pelo id
                                        if(isset($subItemsPipe[3]) && $find->id == $subItemsPipe[3]){
                                            break;                                                
                                        } else {
                                            $errors["$rulekey"] = "O {$rulekey} já esta cadastrado em outro usuario.";
                                            break;
                                        }
                                    }
                                    break;*/
                        }

                    }
                } else {
                    //Pegando o dado passado
                    foreach ($data as $datakey => $dataValue) {
                        
                        if ($rulekey == $datakey) {

                            switch ($ruleValue) {
                                case 'required':
                                    if ($dataValue == "" || empty($dataValue)) {
                                        $errors["$rulekey"] = "O campo {$rulekey} deve ser preenchido.";
                                    }
                                    break;
                                case 'email':
                                    if (!filter_var($dataValue, FILTER_VALIDATE_EMAIL)) {
                                        $errors["$rulekey"] = "O campo {$rulekey} não é valido.";
                                    }
                                    break;
                                case 'float':
                                    if (!filter_var($dataValue, FILTER_VALIDATE_FLOAT)) {
                                        $errors["$rulekey"] = "O campo {$rulekey} deve conter um número decimal.";
                                    }
                                    break;
                                case 'int':
                                    if (!filter_var($dataValue, FILTER_VALIDATE_INT)) {
                                        $errors["$rulekey"] = "O campo {$rulekey} deve conter um número inteiro.";
                                    }
                                    break;                        
                                default:
                                    break;
                            }
                        }

                    } 
                }
            }           
        }

        //Verifianco se houve erros
        if ($errors) {
            //Passando erros por sessão
            Session::set('errors', $errors);
            //Mantendo valores dos inputs na sessão
            Session::set('inputs', $data);
            return true;
        } else {        
            //Destroindo a sessão    
            Session::destroy('errors', 'inputs');
            return false;
        }
    }
}