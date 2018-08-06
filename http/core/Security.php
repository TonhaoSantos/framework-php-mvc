<?php

namespace Core;

class Security
{
	//XSS
    //Converte caracteres aplicaveis em entidades html
    public static function htmlEntitiesEncoding ($value)
    {
		$newValue = htmlentities($value, ENT_QUOTES, 'UTF-8');

		return $newValue;
	}

	//Desconverte entidades html em caracteres aplicaveis
    public static function htmlEntitiesDecoding ($value)
    {
		$newValue = html_entity_decode($value, ENT_QUOTES, 'UTF-8');

		return $newValue;
	}

	//SQL Injection
    public static function sqlInjection ($value)
    {
		$parameters = array('*', '#', '%', '\'', '"', '>', '<', '-', ';', '=', '\/', '!', '!=');
		$string = $value;

		foreach ($parameters as $valueParameters) {
			$newParameter = self::htmlEntitiesEncoding($valueParameters);

			$string = str_replace($valueParameters, $newParameter, $string);
		}

		return $string;
	}
		
    //PHP Injection
    public static function phpInjection ($value)
    {
		$parameters = "/(http|https|www|ftp|.dat|.txt|.gif|wget|php|js|sql)/";
		$string = $value;
		
		if (preg_match($parameters, $string)) {
			return true;
		} else {
			return false;
		}        
	}
	
	//Url Referencia
	//Antes de chamar a mesma verificar se existe a url de referencia usando isset($_SERVER['HTTP_REFERER'])
	//depois verificar se é igual a url que tem direito de chamar o mesmo
	public static function getUrlReferer($urlFile, $urlReferer){
		if ($urlReferer != '' && $urlReferer != null) {
			if($urlFile == $urlReferer){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
		
    //Obter ip do usuario
    public static function getClientIp ()
    {
		$ipAddress = 0;
		
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ipAddress = $_SERVER['HTTP_CLIENT_IP'];
		} else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
			$ipAddress = $_SERVER['HTTP_X_FORWARDED'];
		} else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
			$ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
		} else if (isset($_SERVER['HTTP_FORWARDED'])) {
			$ipAddress = $_SERVER['HTTP_FORWARDED'];
		} else if (isset($_SERVER['REMOTE_ADDR'])) {
			$ipAddress = $_SERVER['REMOTE_ADDR'];
		} else {
			$ipAddress = 0;
		}
		
		return $ipAddress;
	}
		
    //Obter user agent do usuario
    public static function getClientUserAgent ()
    {
		$userAgent = 'Nulo';

		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$userAgent = $_SERVER['HTTP_USER_AGENT'];
		}   
		
		return $userAgent;
	}
		
    //Obter data e hora
    public static function getCurrentDateTime ()
    {
		$currentDateTime = date('Y-m-d H:i:s'); 
		
		return $currentDateTime;
	}
		
    //Criptografando senha
    public static function encryptPassword ($value)
    {
		//Removendo espaço da senha
		$newValue = str_replace(" ", "", $value);
		$valueCharactersQuantity = strlen($newValue);
		$minCharactersQuantity = 8;
		$minUpperCaseLetter = 1;
		$minLowerCaseLetter = 1;
		$minNumberQuantity = 1;
		$minSymbolQuantity = 1;
		$referenceSymbols = array('!', '&', '*', '#', '%', '@');
		$encryptPasswordText = "";
		
		if($valueCharactersQuantity >= $minCharactersQuantity) {
			//Explodindo/Transformando variavel em um array
			$splitValue = str_split($newValue);

			//Procurando se esta repetindo o caracter anterior
			$counterEncryptPassword = 0;
			$previousCharacter;
			
			foreach ($splitValue as $currentCharacter) {
				if ($counterEncryptPassword == 0) {
					$previousCharacter = $currentCharacter;
					$counterEncryptPassword = ++$counterEncryptPassword;
				} else {
					if ($currentCharacter == $previousCharacter) {
						$encryptPasswordText = 'repetidos';
						$previousCharacter = "";
						$counterEncryptPassword = 0;
						
						break;
					} else {
						$previousCharacter = $currentCharacter;
						$counterEncryptPassword = ++$counterEncryptPassword;
						$encryptPasswordText = 'tudo ok';
					}
				}
			}
			
			//Verificando se esta sendo setado algum caracter solicitado			
			if($encryptPasswordText === 'tudo ok'){
				foreach ($referenceSymbols as $currentReferenceSymbols) {
					if (in_array($currentReferenceSymbols, $splitValue)) { 
						$encryptPasswordText = 'tudo ok';
						
						break;
					} else {
						$encryptPasswordText = 'sem especiais';
					}
				}
				
				//Verificando se esta sendo setado algum caracter maiusculo
				if($encryptPasswordText === 'tudo ok'){
					foreach ($splitValue as $currentCharacter) {
						if (ctype_upper($currentCharacter)) {
							$encryptPasswordText = 'tudo ok';
							break;
						} else {
							$encryptPasswordText = 'sem maiuscula';
						}
					}
					
					//Verificando se esta sendo setado algum caracter minusculo
					if($encryptPasswordText === 'tudo ok'){
						foreach ($splitValue as $currentCharacter) {
							if (ctype_lower($currentCharacter)) {
								$encryptPasswordText = 'tudo ok';
								break;
							} else {
								$encryptPasswordText = 'sem minuscula';
							}
						}
							
						if($encryptPasswordText === 'tudo ok'){
							$parameters = "/[0-9]+/";
							$string = $newValue;
							
							if (preg_match($parameters, $string)) {
								//Converte caracteres aplicaveis em entidades html
								$newValue = self::htmlEntitiesEncoding($newValue);
								
								$passwordOptions = ['cost' => 13];
								$encryptPasswordText = password_hash($newValue, PASSWORD_BCRYPT, $passwordOptions);
								
								return $encryptPasswordText;
							} else {
								$encryptPasswordText = 'sem numero';
								return $encryptPasswordText;
							}
						
						} else {
							return $encryptPasswordText;
						}

					} else {
					    return $encryptPasswordText;
					}

				} else {
					return $encryptPasswordText;
				}
				
			} else {			
				return $encryptPasswordText;				
			}
			
		} else {
			$encryptPasswordText = 'minima';
			return $encryptPasswordText;
		}
	}
		
    //Criptografando
    public static function encryptData ($value)
    {
		$passwordOptions = ['cost' => 13];
		$encrypt = password_hash($value, PASSWORD_BCRYPT, $passwordOptions);
							
        return $encrypt;
	}
		
    //Comparando senha
    public static function comparePassword ($userValue, $dbValue)
    {
		//Converte caracteres aplicaveis em entidades html
		$newUserValue = self::htmlEntitiesEncoding($userValue);

		//Compara
		$isPassword = password_verify($newUserValue, $dbValue);
		
		return $isPassword;
	}
		
    //Injeção de CRLF
    public static function checkCrlfInjection ($responseParameter)
    {
		$cr = '/\%0d/';
		$lf = '/\%0a/';	
		$response;

		$cr_check = preg_match($cr , $responseParameter);
		$lf_check = preg_match($lf , $responseParameter);
	
		if (($cr_check > 0) || ($lf_check > 0)){
			$response = true;
		} else {
			$response = false;
		}
		
		return $response;
	}
		
    //CSRF – Cross-site request forgery
    public static function formToken ($formName)
    {
		//Setando token do formulario
		$tokenOptions = ['cost' => 13];		
		$tokenHash = password_hash(rand(100, 10000), PASSWORD_BCRYPT, $tokenOptions);

		//Criando Sessão
		Session::set($formName, $tokenHash);
	}
		
    //Sem Request Post
	public static function hasRequestPost($requestPost)
	{
		$array = get_object_vars($requestPost);

		//Teste 1 - if(count(get_object_vars($requestPost)) == 0){echo 'Vazio';};
		//Teste 2 - var_dump(empty($array));

		if (!empty($array)) {
			return true;
		} else {
			return false;
		}
	}
		
    //Sem Request Get
	public static function hasRequestGet($requestGet)
	{
		$array = get_object_vars($requestGet);

		//Teste 1 - if(count(get_object_vars($requestGet)) == 0){echo 'Vazio';};
		//Teste 2 - var_dump(empty($array));

		if (!empty($array)) {
			return true;
		} else {
			return false;
		}
	}

	//Gera senha aleatoria
	public static function randomPassword ($size, $uppercase, $lowercase , $numbers, $symbols)
    {
		$ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ";
		$mi = "abcdefghijklmnopqrstuvyxwz";
		$nu = "0123456789";
		$si = "!&*#%@";
		$password = '';
	
		if ($uppercase) {
			$password .= str_shuffle($ma);
		}
	
		if ($lowercase) {
			$password .= str_shuffle($mi);
		}
	
		if ($numbers) {
			$password .= str_shuffle($nu);
		}
	
		if ($symbols) {
			$password .= str_shuffle($si);
		}
	
		return substr(str_shuffle($password),0,$size);
	}
}