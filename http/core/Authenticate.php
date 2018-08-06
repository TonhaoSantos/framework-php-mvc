<?php

namespace Core;

trait Authenticate
{
    //Retornando view
    public function login()
    {
        //Verifica se ja esta logado para não abrir a tela de login novamente
        if (isset($_SESSION['logged']) && $_SESSION['logged'] == 'logado') {
            return Redirect::route('/');
        } else {
            //Criando sessão
            Security::formToken('tokenform');

            $this->tokenForm = Session::get('tokenform');

            //Passanto Titulo da pagina
            $this->setPageTitle('Login');
            //Passando Canonical
            $this->setCanonicalUrl('https://www.dominio.com.br/login');
            //Passando tags Facebook
            $this->setFacebookTags('Titulo', '', 'https://www.dominio.com.br/login', '');
            //Passando Google Follow Tag
            $this->setGoogleFollowTag('index, follow');
            //Carregando view e layout
            return $this->renderView('user/login', 'layoutgeneral');
        }

    }

    //Recebendo dados login
    public function auth($request)
    {
        //Codificando valor fornecido pelo usuario
        $encodedEmail = Security::htmlEntitiesEncoding($request->post->email);
        $encodedPassword = Security::htmlEntitiesEncoding($request->post->senha);
        //Obtendo informações do usuario
        $currentDateTime = Security::getCurrentDateTime();
        $clientIp = Security::getClientIp();
        $clientUserAgent = Security::getClientUserAgent();

        //Verifica se esta sendo passado post request
        if (Security::hasRequestPost($request->post)) {
            //Verifica se esta sendo passado get request
            if(Security::hasRequestGet($request->get)){
                return Redirect::route('/');
            } else {
                //Verificando se o token do request é o mesmo do geredo na sessão
                if($request->post->tokenForm == $this->tokenForm){
                    //Retorna se usuario esta bloqueado por tentativa
                    $resultNumberAttempts = $this->userInstance->findNumberAttempts($encodedEmail, 63);

                    //Retorna se usuario esta bloqueado pelos administradores
                    $userLock = $this->userInstance->findUserLock($encodedEmail, 6);

                    //Verifica se esta bloqueado por tentativa
                    if ($resultNumberAttempts == true) {
                        return Redirect::route('/login', [
                            'errors' => ['Este usuário está bloqueado!<br>Siga o passo a passo informado no e-mail para desbloqueio do mesmo!'],
                            'inputs' => ['email' => $request->post->email]
                        ]);
                    } elseif ($userLock == true) {
                        //Verifica se esta bloqueado pelo administrador do sistema
                        return Redirect::route('/login', [
                            'errors' => ['Este usuário está bloqueado por suspeitas de ações ilegais.<br>Entre em contato conosco para saber mais!'],
                            'inputs' => ['email' => $request->post->email]
                        ]);
                    } else {
                        //Procurando usuario pelo email fornecido
                        $resultUser = $this->userInstance->loginUser($encodedEmail);

                        //Verificando se foi encontrado um resultado
                        if ($resultUser == true) {
                            $isPassword = Security::comparePassword($request->post->senha, $resultUser->usu_senha);

                            //Verificand se a senha bate com a do banco
                            if ($isPassword) {
                                //Verifica se ja se encontra logado
                                $resultFindLog = $this->userInstance->findLogUser($resultUser->usu_id, 1);

                                if($resultFindLog == true){
                                    //Obtendo ip do usuário logado
                                    $resultLogIp = $resultFindLog->log_ipUsuario;

                                    $errorMsg = 'Usuário já se encontra logado no IP ';

                                    if ($resultLogIp != '' || $resultLogIp != null) {
                                        $errorMsg .= $resultLogIp . '!';
                                    }

                                    return Redirect::route('/login', [
                                        'errors' => [$errorMsg],
                                        'inputs' => ['email' => $request->post->email]
                                    ]);
                                } else {
                                    //Criando codigo de logout
                                    $logoutCodeTxt = $resultUser->usu_email . Security::getCurrentDateTime();
                                    $logoutCode = Security::encryptData($logoutCodeTxt);

                                    //Cadastra log do usuario
                                    $resultLogInsert = $this->userInstance->logUserInsert($resultUser->usu_id, $clientIp, $clientUserAgent, 1, $currentDateTime, $logoutCode, 1);

                                    //Verifica se tem tentativas de login
                                    $resultNumberAttempts = $this->userInstance->findNumberAttempts($encodedEmail, 64);
                                    if ($resultNumberAttempts == true) {
                                        //Deleta se houver
                                        $this->userInstance->deleteAttempt($resultNumberAttempts->ltl_id);
                                    }

                                    if($resultLogInsert == true){
                                        //Retornando dados do usuario
                                        $user = [
                                            'id' => $resultUser->usu_id,
                                            'name' => $resultUser->usu_usuario,
                                            'email' => $resultUser->usu_email,
                                            'type' => $resultUser->tiu_id_tipoUsuario,
                                            'logoutCode' => $logoutCode
                                        ];

                                        //Criando sessão
                                        Session::set('user', $user);
                                        Session::set('logged', 'logado');

                                        //Retornando rota
                                        return Redirect::route('/');
                                    } else {
                                        //Não implementado caso não seja inserido
                                    }
                                }
                            } else {
                                //Verifica se constam tentativas de acesso
                                $resultNumberAttempts = $this->userInstance->findNumberAttempts($encodedEmail, 64);

                                if ($resultNumberAttempts == true) {
                                    //Obtendo número de tentativas feitas
                                    $numberAttempts = $resultNumberAttempts->ltl_numeroTentativa;

                                    Switch ($numberAttempts) {
                                        case 1:
                                            //Incrementando número de tentativas
                                            $sumNumberAttempts = ++$numberAttempts;
                                            $lockStatus = 64;
                                            $unLockCode = null;

                                            //Atualiza o numero de tentativas para 2
                                            $resultUpdateNumberAttempts = $this->userInstance->updateNumberAttempts($encodedEmail, $encodedPassword, $clientIp, $clientUserAgent, $sumNumberAttempts, $lockStatus, $currentDateTime, $unLockCode);

                                            if ($resultUpdateNumberAttempts == true) {
                                                return Redirect::route('/login', [
                                                    'errors' => ['Usuário/Senha incorretos!<br>Você possui mais 1 tentativa antes de ser bloqueado!'],
                                                    'inputs' => ['email' => $request->post->email]
                                                ]);
                                            } else {
                                                //Não implementado caso não seja atualizado
                                            }
                                            break;
                                        case 2:
                                            //Incrementando número de tentativas
                                            $sumNumberAttempts = ++$numberAttempts;
                                            $lockStatus = 63;

                                            //Codigo para desbloqueio
                                            $dataUnLockCode = $encodedEmail . $resultUser->usu_usuario;
                                            $unLockCode = Security::encryptData($dataUnLockCode);

                                            //Atualiza o numero de tentativas para 3
                                            $resultUpdateNumberAttempts = $this->userInstance->updateNumberAttempts($encodedEmail, $encodedPassword, $clientIp, $clientUserAgent, $sumNumberAttempts, $lockStatus, $currentDateTime, $unLockCode);

                                            //Bloquei de usuario
                                            $resultUpdateUserLock = $this->userInstance->userLock($encodedEmail, 6, $currentDateTime);

                                            if ($resultUpdateNumberAttempts == true && $resultUpdateUserLock == true) {
                                                //Enviar e-mail para desbloqueio
                                                try{
                                                    //Setando dados do e-mail
                                                    $data = [
                                                        'email' => Security::htmlEntitiesDecoding($encodedEmail),
                                                        'senhaUltimaTentativa' => $resultNumberAttempts->ltl_senha,
                                                        'assunto' => 'Bloqueio de usuário por tentativa de login',
                                                        'code' => $unLockCode];

                                                        //Enviando mensagem
                                                    Email::userLockSend($data);
                                                    return Redirect::route('/login', [
                                                        'errors' => ['Usuário bloqueado por número de tentativas 3/3!<br>Foi encaminhado um e-mail para desbloqueio do mesmo!']
                                                    ]);
                                                }catch(\Exception $e){
                                                    return Redirect::route('/', [
                                                        'errors' => ['Usuário bloqueado por número de tentativas 3/3!<br>Não conseguimos encaminhar um e-mail para desbloqueio do mesmo.</br>Entre em contato conosco para que possamos lhe orientar no desbloqueio!']
                                                    ]);
                                                }
                                            } else {
                                                //Não implementado caso não seja atualizado
                                            }
                                            break;
                                        case 3:
                                            return Redirect::route('/login', [
                                                'errors' => ['Este usuário está bloqueado!<br>Siga o passo a passo informado no e-mail para desbloqueio do mesmo!'],
                                                'inputs' => ['email' => $request->post->email]
                                            ]);
                                            break;
                                    }
                                } else {
                                    $numberAttempts = 1;

                                    //Cadastrando tentativa de login
                                    $resultAttemptInsert = $this->userInstance->attemptInsert($encodedEmail, $encodedPassword, $clientIp, $clientUserAgent, $numberAttempts, $currentDateTime);

                                    if ($resultAttemptInsert == true) {
                                        return Redirect::route('/login', [
                                            'errors' => ['Usuário/Senha incorretos!<br>Você possui mais 2 tentativas antes de ser bloqueado!'],
                                            'inputs' => ['email' => $request->post->email]
                                        ]);
                                    } else {
                                        //Não implementado caso não seja cadastrado
                                    }
                                }
                            }
                        } else {
                            return Redirect::route('/login', [
                                'errors' => ['Usuário/Senha incorretos!'],
                                'inputs' => ['email' => $request->post->email]
                            ]);
                        }
                    }
                } else {
					//Limpando Variaveis
                    if (isset($_POST)) {
                        unset($_POST);
                    }
                    if (isset($request->get)) {
                        unset($request->get);
                    }

                    return Redirect::route('/login', [
                        'errors' => ['Token não confere com o criado pelo sistema.']
                    ]);
                }
            }
        } else {
            return Redirect::route('/');
        }
    }


    //Logout
    public function logout($request)
    {
        //Verifica se esta sendo passado post request
        if (!Security::hasRequestPost($request->post)) {
            //Verifica se esta sendo passado get request
            if(!Security::hasRequestGet($request->get)){
                return Redirect::route('/');
            } else {

                //Verificando a quandidade de parametros passados
                $counterResponse = 0;
                foreach ($request->get as $key => $value) {
                    ++$counterResponse;
                }

                if ($counterResponse != 1) {
                    return Redirect::route('/');
                } else {
                    //Verifica nome dos parametros
                    foreach ($request->get as $key => $value) {
                        if ($key != 'code') {
                            return Redirect::route('/');
                            break;
                        } elseif ($value = '' || $value == null || empty($value) || !isset($value)) {
                            return Redirect::route('/');
                            break;
                        }
                    }

                    //Obtendo valor do parametro
                    $requestCode = $request->get->code;
                }

                //Obtem codigo no banco
                $resultFindLog = $this->userInstance->findLogUser($this->auth->id(), 1);
                //$resultFindLog = 'asdas';

                //Verificando se parametro passado é igual ao codigo do banco
                if ($resultFindLog == true && $resultFindLog->log_codigoLogout == $requestCode) {
                    //Atualiza o status do log obtido para inativo
                    $resultUpdateLog = $this->userInstance->updateLogUser($resultFindLog->log_id);

                    if ($resultUpdateLog == true) {
                        //Cadastro log (logout) do usuario
                        $currentDateTime = Security::getCurrentDateTime();
                        $clientIp = Security::getClientIp();
                        $clientUserAgent = Security::getClientUserAgent();

                        //Criando codigo de logout
                        $logoutCode = null;

                        //Gavando log (Logout)
                        $resultLogInsert = $this->userInstance->logUserInsert($this->auth->id(), $clientIp, $clientUserAgent, 2, $currentDateTime, $logoutCode, 2);

                        if($resultLogInsert == true){
                            //Destroi as sessões
                            Session::destroy('user');
                            Session::destroy('logged');

                            //Retornando rota
                            return Redirect::route('/login');
                        } else {
                            //Não implementado caso não seja inserido
                        }
                    } else {
                        //Não implementado caso não seja atualizado
                    }
                } else {
                    return Redirect::route('/');
                }
            }
        } else {
            return Redirect::route('/');
        }
    }
}
