<?php

namespace core\dominio\functions;

use core\util\auth\Auth;
use core\util\{i18n, Mongo, UtilFunctions, ValidaFunctions};
// use core\AppConfig;
use MongoDB\BSON\ObjectID;

class UsuarioFunctions {

    static function retornaDadosLoginAppWeb(array $usuario)
    {
        $retorno = array();

        //PERFIL
        $retorno["perfil"]["nome"] = $usuario['nome'];
        // $retorno["perfil"]["foto36x36"] = empty(!$usuario['foto']) ? AppConfig::getImgURL().$usuario['foto'] : null;

        $retorno["negocios"] = [];

        $negocios = UsuarioFunctions::getNegociosAtivosFromUsuario($usuario);
        $count_1 = count($negocios);
        for($i = 0; $i < $count_1; $i++)
        {
            $retorno["negocios"][$i]["id"]          = (string) $negocios[$i]['_id'];
            $retorno["negocios"][$i]["nome"]        = $negocios[$i]['nome'];
            $retorno["negocios"][$i]["abreviatura"] = $negocios[$i]['abreviacao'];
        }

        // $lstFuncionario = $FUNCIONARIODAO->retornaTodos(["usuario_id"], [$usuario->getId()], ["="]);

        // $count_1 = count($lstFuncionario);
        // for($i = 0; $i < $count_1; $i++)
        // {
        //     $funcionario = $lstFuncionario[$i];
        //     $academia = $ACADEMIADAO->retornaPorId($funcionario->getAcademia_id());

        //     $retorno["academias"][$i]["id"] = (string) $academia->getId();
        //     $retorno["academias"][$i]["nome"] = $academia->getNome();
        //     $retorno["academias"][$i]["logo"] = empty(!$academia->getLogo()) ? AppConfig::getImgURL().$academia->getLogo() : null;
        //     $retorno["academias"][$i]["bairro"] = $academia->getBairro();
        //     $retorno["academias"][$i]["telefone1"] = $academia->getTelefone1();
        //     $retorno["academias"][$i]["papelPrint"] = $academia->getPapel();
        //     $retorno["academias"][$i]["fonteGrande"] = $academia->getFonteGrande() ? true : false;
        //     $retorno["academias"][$i]["ocultarAnotacoes"] = $academia->getOcultarAnotacoes() ? true : false;
        //     $retorno["academias"][$i]["undCarga"] = $academia->getUndCarga() == null ? "kg" : $academia->getUndCarga();
        //     $retorno["academias"][$i]["undDescanso"] = $academia->getUndDescanso() == null ? "s" : $academia->getUndDescanso();
        //     $retorno["academias"][$i]["undTempo"] = $academia->getUndTempo() == null ? "m" : $academia->getUndTempo();
        //     $retorno["academias"][$i]["undDist"] = $academia->getUndDist() == null ? "km" : $academia->getUndDist();

        //     $retorno["academias"][$i]["papel"] = $funcionario->getCargo();
        // }

        //se for usuario master
        // if($usuario->getEmail() == AppConfig::$ADMIN_MAIL)
        // {
        //     $lstAcademias = $ACADEMIADAO->retornaTodos();

        //     $count2 = count($lstAcademias);
        //     for($i = 0; $i < $count2; $i++)
        //     {
        //         $academia = $lstAcademias[$i];

        //         $retorno["academias"][$i]["id"] = (string) $academia->getId();
        //         $retorno["academias"][$i]["nome"] = $academia->getNome();
        //         $retorno["academias"][$i]["logo"] = empty(!$academia->getLogo()) ? AppConfig::getImgURL().$academia->getLogo() : null;
        //         $retorno["academias"][$i]["bairro"] = $academia->getBairro();
        //         $retorno["academias"][$i]["telefone1"] = $academia->getTelefone1();
        //         $retorno["academias"][$i]["papelPrint"] = $academia->getPapel();
        //         $retorno["academias"][$i]["fonteGrande"] = $academia->getFonteGrande() ? true : false;
        //         $retorno["academias"][$i]["ocultarAnotacoes"] = $academia->getOcultarAnotacoes() ? true : false;
        //         $retorno["academias"][$i]["undCarga"] = $academia->getUndCarga() == null ? "kg" : $academia->getUndCarga();
        //         $retorno["academias"][$i]["undDescanso"] = $academia->getUndDescanso() == null ? "s" : $academia->getUndDescanso();
        //         $retorno["academias"][$i]["undTempo"] = $academia->getUndTempo() == null ? "m" : $academia->getUndTempo();
        //         $retorno["academias"][$i]["undDist"] = $academia->getUndDist() == null ? "km" : $academia->getUndDist();

        //         $retorno["academias"][$i]["papel"] = ColaboradorFunctions::$PAPEL_GERENTE;
        //     }
        // }
        return $retorno;
    }

    static function retornaDadosGerais($con, $usuario)
    {

        $USUARIODAO = new USUARIODAO($con);
        $usuario = $USUARIODAO->retornaPorId($usuario->getId());

        $retorno = array();

        $retorno["countNav"] = 8;

        //PERFIL
        $retorno["nome"] = $usuario->getNome();
        $retorno["sobrenome"] = $usuario->getSobrenome();
        $retorno["email"] = $usuario->getEmail();
        $retorno["sexo"] = $usuario->getSexo();
        $retorno["nascimento"] = UtilFunctions::converteDataFromBanco($usuario->getDataNascimento());

        $idade = null;
        if(UtilFunctions::converteDataFromBanco($usuario->getDataNascimento())){
            $inicio = strtotime($usuario->getDataNascimento());
            $fim = strtotime(date('Y-m-d'));
            $idade = floor( ($fim - $inicio) / (365 * 60 * 60 * 24) );
        }
        $retorno["idade"] = $idade;


        $retorno["foto90x79"] = empty(!$usuario->getFoto1()) ? AppConfig::getImgURL(true).$usuario->getFoto1() : null;
        $retorno["foto36x36"] = empty(!$usuario->getFoto2()) ? AppConfig::getImgURL(true).$usuario->getFoto2() : null;
        $retorno["telefone"] = $usuario->getTelefone();
        $retorno["celular"] = $usuario->getCelular();
        $retorno["facebook"] = $usuario->getLinkFacebook();

        $retorno["cep"] = $usuario->getCep();
        $retorno["rua"] = $usuario->getRua();
        $retorno["numero"] = $usuario->getNumero();
        $retorno["bairro"] = $usuario->getBairro();
        $retorno["cidade"] = $usuario->getCidade();
        $retorno["estado"] = $usuario->getEstado();


        //LISTA DE ACADEMIAS (SENDO ALUNO ATIVO)
        $ALUNODAO = new ALUNODAO($con);
        $ACADEMIADAO = new ACADEMIADAO($con);
        $EXERCICIODAO = new EXERCICIODAO($con);

        $lstALUNO = $ALUNODAO->retornaTodos(array("usuario_id", "ativo"), array($usuario->getId(), true));

        for($i=0;$i<count($lstALUNO);$i++)
        {
            $aluno = $lstALUNO[$i];

            $retorno["academias"][$i]["aluno_id"] = (string)$aluno->getId();
            $retorno["academias"][$i]["matricula"] = $aluno->getMatricula();
           // $retorno["academias"][$i]["dataMatricula"] = UtilFunctions::converteDataFromBanco($aluno->getDataMatricula(), 'Y-m-d H:i:s', 'd/m/Y');
           // $retorno["academias"][$i]["dataDesmatricula"] = UtilFunctions::converteDataFromBanco($aluno->getDataDesmatricula(), 'Y-m-d H:i:s', 'd/m/Y');

            $academia = $ACADEMIADAO->retornaPorId($aluno->getAcademia_id());

            $retorno["academias"][$i]["id"] = (string)$academia->getId();
            $retorno["academias"][$i]["nome"] = $academia->getNome();

            $retorno["academias"][$i]["descricao"] = $academia->getDescricao();

            $retorno["academias"][$i]["foto1"] = empty(!$academia->getFoto1()) ? AppConfig::getImgURL(true).$academia->getFoto1() : null;
            $retorno["academias"][$i]["foto2"] = empty(!$academia->getFoto2()) ? AppConfig::getImgURL(true).$academia->getFoto2() : null;
            $retorno["academias"][$i]["foto3"] = empty(!$academia->getFoto3()) ? AppConfig::getImgURL(true).$academia->getFoto3() : null;
            $retorno["academias"][$i]["foto4"] = empty(!$academia->getFoto4()) ? AppConfig::getImgURL(true).$academia->getFoto4() : null;

           // $retorno["academias"][$i]["atividades"] = $academia->getAtividades();
           // $retorno["academias"][$i]["estrutura"] = $academia->getEstrutura();

            $retorno["academias"][$i]["horarioFuncionamento"] = $academia->getHorarioFuncionamento();

            $retorno["academias"][$i]["logo"] = empty(!$academia->getLogo()) ? AppConfig::getImgURL(true).$academia->getLogo() : null;

            $retorno["academias"][$i]["rua"] = $academia->getRua();
            $retorno["academias"][$i]["numero"] = $academia->getNumero();
            $retorno["academias"][$i]["complemento"] = $academia->getComplemento();
            $retorno["academias"][$i]["bairro"] = $academia->getBairro();
            $retorno["academias"][$i]["cidade"] = $academia->getCidade();
            $retorno["academias"][$i]["estado"] = $academia->getEstado();

            $retorno["academias"][$i]["telefone1"] = $academia->getTelefone1();
            $retorno["academias"][$i]["telefone2"] = $academia->getTelefone2();

            $retorno["academias"][$i]["email"] = $academia->getEmail();

            $retorno["academias"][$i]["siteLink"] = $academia->getSiteLink();
            $retorno["academias"][$i]["socialLink"] = $academia->getSocialLink();

            $retorno["academias"][$i]["latitude"] = $academia->getLatitude();
            $retorno["academias"][$i]["longitude"] = $academia->getLongitude();
        }//for($i=0;$i<count($lstALUNO);$i++)

        //DADOS DAS FICHAS CORRENTES DE CADDA ACADEMIA ----------------------------------------
        $FICHADAO = new FICHADAO($con);
        $append = array("sort" => array("dataInicio" => -1));
        $lstFICHA = $FICHADAO->retornaTodos(["usuario_id"], [$usuario->getId()], ["="], $append);

        $letras = ["A","B", "C", "D", "E","F","G", "H", "I", "J","K","L", "M", "N", "O","P","Q", "R", "S", "T","U","V", "W", "X", "Y", "Z"];

        //TODO: limitar a quantidade de fichas a retornar para evitar sobrecarregar o localStorage
       // $retorno["fichas"] = array();
        foreach ($lstFICHA as $objFichaCorrente)
        {
            $array_ficha = array();
            $array_ficha["id"] = (string)$objFichaCorrente->getId();

           // $array_ficha["logo"] = $retorno["academias"][$i]["logo"];

            $array_ficha["dataInicio"] = UtilFunctions::converteDataFromBanco($objFichaCorrente->getDataInicio());
            $array_ficha["dataFim"] = UtilFunctions::converteDataFromBanco($objFichaCorrente->getDataFim());

            //FUNCIONARIO
            $FUNCIONARIODAO = new FUNCIONARIODAO($con);
            $objProfessor = $FUNCIONARIODAO->retornaPorId($objFichaCorrente->getProfessor_id());

            if($objProfessor)
            {
                $objProfessorUsuario = $USUARIODAO->retornaPorId($objProfessor->getUsuario_id());

                $array_ficha["professor"]["nome"] = $objProfessorUsuario->getNome();
                $array_ficha["professor"]["sobrenome"] = $objProfessorUsuario->getSobrenome();
            }

            //ACADEMIA
            if(!empty($objFichaCorrente->getAcademia_id()))
            {
                $academia = $ACADEMIADAO->retornaPorId($objFichaCorrente->getAcademia_id());
                $array_ficha["academia"]["nome"] = $academia->getNome();
            }

            //EXERCICIO DA FICHA
            $array_ficha["exercicios"] = FichaFunctions::retornaExercicioFicha($objFichaCorrente, null, $con, "appAluno");
            $array_ficha["treinosFeitos"] = FichaFunctions::retornaTreinosFeitos($objFichaCorrente);
            if(empty($array_ficha["treinosFeitos"]))
                unset($array_ficha["treinosFeitos"]);

            $array_ficha['letrasTreinos'] = "";
            for($t = 0; $t < count($array_ficha["exercicios"]); $t++) {
                $array_ficha['letrasTreinos'] = $array_ficha['letrasTreinos'] . $letras[$t] . " ";
            }

            $retorno["fichas"][] = $array_ficha;
        }
        //AVALIACOES ----------------------------------------------------------------------------
        $AVALIACAODAO = new AVALIACAODAO($con);
        $append = array("sort" => array("data" => -1));
        $lstAVALIACAO = $AVALIACAODAO->retornaTodos(["usuario_id"], [$usuario->getId()], ["="], $append);
        $count = count($lstAVALIACAO);
        if($lstAVALIACAO && $count > 0)
        {
            $array_avaliacoes = array();
            for($i = 0; $i < $count; $i++)
            {
                $academia_id = $lstAVALIACAO[$i]->getAcademia_id();
                $academia = $ACADEMIADAO->retornaPorId($academia_id);
                if($academia != null)
                {
                    $array_avaliacoes[$i]["id"] = (string)$lstAVALIACAO[$i]->getId();
                    $array_avaliacoes[$i]["academia_id"] = (string)$academia_id;
                    $array_avaliacoes[$i]["academia_nome"] = (string)$academia->getNome();
                    $array_avaliacoes[$i]["data"] = UtilFunctions::converteDataFromBanco($lstAVALIACAO[$i]->getData(), "Y-m-d", "d/m/Y");
                    $array_avaliacoes[$i]["dataTS"] = UtilFunctions::converteDataFromBancoParaTimeStamp($lstAVALIACAO[$i]->getData());
                    $array_avaliacoes[$i]["medidas"] = $lstAVALIACAO[$i]->getMedidas();
                    $array_avaliacoes[$i]["composicao"] = $lstAVALIACAO[$i]->getComposicao();
                }
            }
            $retorno["avaliacoes"] = $array_avaliacoes;
        }


        // CONVITES
        $CONVITEDAO = new CONVITEDAO($con);
        $lstConvites = $CONVITEDAO->retornaTodos(["email"], [$usuario->getEmail()], ["="]);

        $count = count($lstConvites);
        if($lstConvites && count($lstConvites) > 0)
        {
            $array_convites = array();

            for($i = 0; $i < $count; $i++)
            {
                $academia_id = $lstConvites[$i]->getAcademia_id();
                $academia = $ACADEMIADAO->retornaPorId($academia_id);
                if($academia != null)
                {
                    $array_convites[$i]["id"] = (string)$lstConvites[$i]->getId();
                    $array_convites[$i]["academia_id"] = (string)$academia_id;
                    $array_convites[$i]["academia_nome"] = $academia->getNome();
                    $array_convites[$i]["data"] = UtilFunctions::converteDataFromBanco($lstConvites[$i]->getDataCriacao(), "Y-m-d H:i:s", "d/m/Y");
                }
            }
            $retorno["convites"] = $array_convites;
        }

        $dataAtual = new \DateTime();
        $dataAtual->setTimezone(new \DateTimeZone("America/Sao_Paulo"));
        $dataAtual = $dataAtual->format('Y-m-d');

        // $array_comunicados = ComunicadoFunctions::loadComunicados($aluno, $academia_id, $dataAtual, $dataAtual);
        $array_comunicados = array();
        $count = 0;
        if(isset($retorno["academias"]))
            $count = count($retorno["academias"]);
        for($i = 0; $i < $count; $i++)
        {
            $academia_id = new ObjectID($retorno["academias"][$i]["id"]);
            $array_comunicados = array_merge($array_comunicados, ComunicadoFunctions::loadComunicadosAluno($con, $usuario->getId(), $academia_id, $dataAtual, $dataAtual));
        }

        $retorno["comunicados"] = $array_comunicados;

        return $retorno;
    }

    static function retornaDadosPerfil($con, $usuario, $atributosRetornados)
    {
        $retorno = array();

        $atributosRetornados = explode(";", $atributosRetornados);

        //USUARIO
        //////////////////////////////////////////////////////////////////////////////////////////////
        if(in_array("perfil", $atributosRetornados) || in_array("perfil.*", $atributosRetornados))
        {
            $tudoUsuario = in_array("perfil.*", $atributosRetornados);

            if($tudoUsuario || in_array("perfil.nome", $atributosRetornados))
                $retorno["nome"] = $usuario->getNome();

            if($tudoUsuario || in_array("perfil.sobrenome", $atributosRetornados))
                $retorno["sobrenome"] = $usuario->getSobrenome();

            if($tudoUsuario || in_array("perfil.email", $atributosRetornados))
                $retorno["email"] = $usuario->getEmail();

            if($tudoUsuario || in_array("perfil.sexo", $atributosRetornados))
                $retorno["sexo"] = $usuario->getSexo();

            if($tudoUsuario || in_array("perfil.dataNascimento", $atributosRetornados))
                $retorno["dataNascimento"] = $usuario->getDataNascimento();

            if($tudoUsuario || in_array("perfil.foto90x79", $atributosRetornados))
                $retorno["foto90x79"] = empty(!$usuario->getFoto1()) ? AppConfig::getImgURL(true).$usuario->getFoto1() : null;

            if($tudoUsuario || in_array("perfil.foto36x36", $atributosRetornados))
                $retorno["foto36x36"] = empty(!$usuario->getFoto2()) ? AppConfig::getImgURL(true).$usuario->getFoto2() : null;

            if($tudoUsuario || in_array("perfil.foto240x240", $atributosRetornados))
                $retorno["foto240x240"] = empty(!$usuario->getFoto3()) ? AppConfig::getImgURL(true).$usuario->getFoto3() : null;

            if($tudoUsuario || in_array("perfil.telefone", $atributosRetornados))
                $retorno["telefone"] = $usuario->getTelefone();

            if($tudoUsuario || in_array("perfil.celular", $atributosRetornados))
                $retorno["celular"] = $usuario->getCelular();

            if($tudoUsuario || in_array("perfil.facebook", $atributosRetornados))
                $retorno["facebook"] = $usuario->getLinkFacebook();

            if($tudoUsuario || in_array("perfil.idade", $atributosRetornados))
            {
                $inicio = strtotime($usuario->getDataNascimento());
                $fim = strtotime(date('Y-m-d'));

                $idade = floor( ($fim - $inicio) / (365 * 60 * 60 * 24) );

                $retorno["idade"] = $idade;
            }

        }
        //////////////////////////////////////////////////////////////////////////////////////////////


        //ALUNO
        //////////////////////////////////////////////////////////////////////////////////////////////
        if(in_array("aluno", $atributosRetornados) || in_array("aluno.*", $atributosRetornados))
        {
            $tudoAluno = in_array("aluno.*", $atributosRetornados);

            $ALUNODAO = new ALUNODAO($con);
            $lstALUNO = $ALUNODAO->retornaTodos(["usuario_id", "ativo"], [$usuario->getId(), true], ["=","="]);

            $FUNCIONARIODAO = new FUNCIONARIODAO($con);
            $ACADEMIADAO = new ACADEMIADAO($con);
            $OBSERVACAODAO = new OBSERVACAODAO($con);
            $FICHADAO = new FICHADAO($con);
            $EXERCICIODAO = new EXERCICIODAO($con);

            for($i=0;$i<count($lstALUNO);$i++)
            {
                $aluno = $lstALUNO[$i];

                //if($tudoAluno || in_array("aluno.ativo", $atributosRetornados))
                //    $retorno["alunos"] = $aluno->getAtivo();

                if($tudoAluno || in_array("aluno.academia.*", $atributosRetornados) || in_array("aluno.academia.ficha.*", $atributosRetornados))
                {
                    $academia = $ACADEMIADAO->retornaPorId($aluno->getAcademia_id());

                    if(in_array("aluno.academia.*", $atributosRetornados))
                    {
                        $retorno["academias"][$i]["id"]         = (string) $academia->getId();
                        $retorno["academias"][$i]["nome"]       = $academia->getNome();
                        $retorno["academias"][$i]["logo"]       = $academia->getLogo();
                        $retorno["academias"][$i]["bairro"]     = $academia->getBairro();
                        $retorno["academias"][$i]["telefone1"]  = $academia->getTelefone1();
                        $retorno["academias"][$i]["latitude"]   = $academia->getLatitude();
                        $retorno["academias"][$i]["longitude"]  = $academia->getLongitude();
                    }

                    if($tudoAluno || in_array("aluno.academia.ficha.*", $atributosRetornados))
                    {
                        //DADOS DAS FICHAS CORRENTES DE CADDA ACADEMIA ----------------------------------------
                        $lstFICHA = $FICHADAO->retornaTodos(["usuario_id", "academia_id"], [$usuario->getId(), $academia->getId()], ["=", "="], array("sort" => array("dataInicio" => -1)));

                        if($lstFICHA && count($lstFICHA) > 0)
                        {
                            $array_ficha = array();
                            $objFichaCorrente = $lstFICHA[0];

                            $array_ficha["id"]          = (string) $objFichaCorrente->getId();
                            $array_ficha["dataInicio"]  = $objFichaCorrente->getDataInicio();
                            $array_ficha["dataFim"]     = $objFichaCorrente->getDataFim();

                            //FUNCIONARIO
                            $objProfessor = $FUNCIONARIODAO->retornaPorId($objFichaCorrente->getProfessor_id());

                            if($objProfessor)
                            {
                                //$array_ficha["professor"]["id"] = $objProfessor->getUsuario_id();
                                $USUARIODAO = new USUARIODAO($con);
                                $objProfessorUsuario = $USUARIODAO->retornaPorId($objProfessor->getUsuario_id());

                                $array_ficha["professor"]["nome"]       = $objProfessorUsuario->getNome();
                                $array_ficha["professor"]["sobrenome"]  = $objProfessorUsuario->getSobrenome();
                            }

                            //ACADEMIA
                            //$array_ficha["academia"]["id"] = $academia->getId();
                            $array_ficha["academia"]["nome"] = $academia->getNome();

                            //EXERCICIO DA FICHA
                            if(isset($objFichaCorrente->doc["exerciciosFicha"]) && count($objFichaCorrente->doc["exerciciosFicha"]) > 0)
                            {
                                $array_ficha["exercicios"] = array();
                                foreach($objFichaCorrente->doc["exerciciosFicha"] as $exercicio_ficha)
                                {
                                    $array_exercicio = array();
                                    //TODO: garatir que o cast funcionará: se sem valor por 0 na hora de salvar o exercicio_ficha
                                    $array_exercicio["id"]          = (string) $exercicio_ficha->getId();
                                    $array_exercicio["series"]      = (int) $exercicio_ficha->getSeries();
                                    $array_exercicio["repeticoes"]  = (int) $exercicio_ficha->getRepeticoes();
                                    $array_exercicio["carga"]       = (float) $exercicio_ficha->getCarga();
                                    $array_exercicio["descanso"]    = (int) $exercicio_ficha->getDescanso();
                                    $array_exercicio["superset"]    = $exercicio_ficha->getSuperset();

                                    //OBSERVACOES
                                    //////////////////////////////////////////////////////////////////////////////////////
                                    if(isset($objFichaCorrente->doc["observacoes"]) && count($exercicio_ficha->doc["observacoes"]) > 0)
                                    {
                                        for ($k = 0; $k < count($exercicio_ficha["observacoes"]); $k++)
                                        {
                                            $observacao = $OBSERVACAODAO->retornaPorId($exercicio_ficha["observacoes"][$k]["_id"]);

                                            $array_exercicio["observacoes"][$k]["tag"]          = $observacao->getTag();
                                            $array_exercicio["observacoes"][$k]["descricao"]    = $observacao->getDescricao();
                                        }
                                    }

                                    //////////////////////////////////////////////////////////////////////////////////////
                                    $objExercicio = $EXERCICIODAO->retornaPorId($exercicio_ficha->getExercicio_id());

                                    $array_exercicio["idExercicio"] = $objExercicio->getId();
                                    $array_exercicio["nome"]        = $objExercicio->getNome();
                                    $array_exercicio["descricao"]   = $objExercicio->getDescricao();
                                    $array_exercicio["gif"]         = $objExercicio->getGif();

                                    $array_ficha["exercicios"][$exercicio_ficha->getDivisao()][$exercicio_ficha->getOrdem()] = $array_exercicio;
                                }
                            }

                            $retorno["fichas"]["$i"] = $array_ficha;

                            //para debug
                            //print_r($retorno);
                            //var_dump($retorno);
                            //echo json_encode($retorno, JSON_UNESCAPED_UNICODE);
                            //echo json_encode($retorno);

                            //return $retorno;

                        }//se possui fichas

                    }//retorna aluno.academia.ficha

                }//retorna aluno.academia / aluno.academia.ficha

            }//for aluno

        }//retorna aluno
        return $retorno;
    }

    static function getNegociosAtivosFromUsuario($usuario)
    {
        $campos         = ['colaboradores._id', 'removido'];
        $filtros        = [$usuario['_id'], false];
        $comparadores   = ["=", "="];
        $append         = array("sort" => array("nome" => 1));

        $negocios = Mongo::retornaTodos("negocio", $campos, $filtros, $comparadores, $append);
        return $negocios;
    }

    static function getUserPermission($usuario, $negocio_id = null)
    {
        return [1,2,3,4,5,6,7,8,9];
        // if($usuario !== null)
        // {
        //     if((string)$usuario->getId() == AppConfig::$ADMIN_ID)
        //     {
        //         return [1,2,3,4,5,6,7,8,9];
        //     }
        //     else
        //     {
        //         $negocios = UsuarioFunctions::getNegociosFromUsuario($usuario);
        //         return $negocios;
        //     }
        // }

        // return null;

        // if($usuario !== null && $academia_id !== null)
        // {
        //     if((string)$usuario->getId() == AppConfig::$ADMIN_ID)
        //     {
        //         return [1,2,3,4,5,6,7,8,9];
        //     }
        //     else
        //     {
        //         $FUNCIONARIODAO = new FUNCIONARIODAO($con);
        //         $colaborador = $FUNCIONARIODAO->retornaUm(["academia_id", "usuario_id", "ativo"], [$academia_id, $usuario->getId(), true], ["=", "=", "="]);
        //         if($colaborador !== null)
        //         {
        //             return explode(";", $colaborador->getPermissoes());
        //         }
        //         else
        //         {
        //             return null;
        //         }
        //     }
        // }
        // return null;
    }

    //Se não desejar validar um campo setar como NULL
    public static function validaInputRegister(array $params)
    {
        $retorno = array();
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($params["nome"], i18n::write('NOME')));
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($params["sobrenome"], i18n::write('SOBRENOME')));

        //ta errado, todo: fazer funcionar permitindo cadastro apenas se nome tiver letras
        //if( !preg_match("#[a-z]+#", $nome) || !preg_match("#[A-Z]+#", $nome) )
        //    $retorno[] = i18n::write("CAMPO_PRECISA_LETRAS").i18n::write('NOME');
        //if( !preg_match("#[a-z]+#", $sobrenome) || !preg_match("#[A-Z]+#", $sobrenome) )
        //    $retorno[] = i18n::write("CAMPO_PRECISA_LETRAS").i18n::write('SOBRENOME');

        $retorno = array_merge($retorno, ValidaFunctions::inputEmailDefault($params["email"], i18n::write('EMAIL')));
        $retorno = array_merge($retorno, ValidaFunctions::inputPasswordDefault($params["senha"], i18n::write("SENHA")));
        $retorno = array_merge($retorno, ValidaFunctions::inputInArrayDefault($params["sexo"], i18n::write("SEXO"), true, "SEXO"));
        $retorno = array_merge($retorno, ValidaFunctions::inputData($params["dataNascimento"], i18n::write("NASCIMENTO"), true, false, "d/m/Y", true) );

        if(!empty($retorno)){
            throw new \Exception(json_encode($retorno), 400);
        }
    }

    //Se não desejar validar um campo setar como NULL
    public static function validaInputConfirmaRegistro(array $params)
    {
        $retorno = array();
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($params["codigo"], i18n::write('ACCTOKEN'), true, 64, 63));

        if(!empty($retorno)){
            throw new \Exception(json_encode($retorno), 400);
        }
    }

    //Se não desejar validar um campo setar como NULL
    static function validaInput(
            $nome = null,
            $sobrenome = null,
            $email = null,
            $senha = null,
            $confirmSenha = null,
            $sexo = null,
            $dataNascimento = null,
            $foto = null,
            $celular = null,
            $telefone = null,
            $cep = null,
            $rua = null,
            $numero = null,
            $bairro = null,
            $cidade = null,
            $estado = null,
            $locale =  null,
            $timezone = null,
            $linkFacebook = null)
    {
        $retorno = array();

        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($nome, i18n::write('NOME')));
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($sobrenome, i18n::write('SOBRENOME')));

        $retorno = array_merge($retorno, ValidaFunctions::inputEmailDefault($email, i18n::write('EMAIL'), false));
        $retorno = array_merge($retorno, ValidaFunctions::inputPasswordDefault($senha, i18n::write("SENHA"), false, $confirmSenha));

        $retorno = array_merge($retorno, ValidaFunctions::inputInArrayDefault($sexo, i18n::write("SEXO"), true, "SEXO"));

        $retorno = array_merge($retorno, ValidaFunctions::inputData($dataNascimento, i18n::write("NASCIMENTO"), false, false, "d/m/Y") );

        $retorno = array_merge($retorno, ValidaFunctions::imgInputDefault($foto, i18n::write("FOTO"), false));

        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($celular, i18n::write('CELULAR'), false));
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($telefone, i18n::write('TELEFONE'), false));

        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($cep, i18n::write('CEP'), false));
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($rua, i18n::write('RUA'), false));
        $retorno = array_merge($retorno, ValidaFunctions::intInputDefault($numero, i18n::write('NUMERO'), false));
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($bairro, i18n::write('BAIRRO'), false));
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($cidade, i18n::write('CIDADE'), false));
        $retorno = array_merge($retorno, ValidaFunctions::inputInArrayDefault($estado, i18n::write("ESTADO"), false, "ESTADO"));

        if(!empty($retorno)){
            throw new \Exception(json_encode($retorno), 400);
        }
    }

    static function validaInputLogin(array $params)
    {
        $retorno = array();
        $retorno = array_merge($retorno, ValidaFunctions::inputEmailDefault($params["email"], i18n::write('EMAIL')));
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($params["senha"], i18n::write('SENHA')));

        if(!empty($retorno)){
            throw new \Exception(json_encode($retorno), 400);
        }
    }

    static function validaInputRecuperarSenha(array $params)
    {
        $retorno = array();
        $retorno = array_merge($retorno, ValidaFunctions::inputEmailDefault($params["email"], i18n::write('EMAIL')));

        if(!empty($retorno)){
            throw new \Exception(json_encode($retorno), 400);
        }
    }

    static function validaInputAlterarSenha(array $params)
    {
        $retorno = array();
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($params["codigo"], i18n::write('PWDTOKEN'), true, 64, 63));
        $retorno = array_merge($retorno, ValidaFunctions::inputEmailDefault($params["email"], i18n::write('EMAIL')));
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($params["novaSenha"], i18n::write('SENHA')));
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($params["confirmSenha"], i18n::write('CONFIRMACAO_SENHA')));
        $retorno = array_merge($retorno, ValidaFunctions::inputPasswordDefault($params["novaSenha"], i18n::write("SENHA"), true, $confirmSenha));

        if(!empty($retorno)){
            throw new \Exception(json_encode($retorno), 400);
        }
    }

    public static function fromRegister(array $params)
    {
        $usuario = [
            '_id' => new ObjectID(),
            'nome' => $params["nome"],
            'sobrenome' => $params["sobrenome"],
            'email' => $params["email"],
            'senha' => sodium_crypto_pwhash_str(
                $params["senha"],
                SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
                SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
            ),
            'sexo' => $params["sexo"],
            'dataNascimento' => UtilFunctions::converteDataToMongo($params["dataNascimento"]),
            'accToken' => UtilFunctions::randomCode(64),
            'validadeAccToken' => Auth::getNewValidadeToken(24),
            'pwdToken' => null,
            'validadePwdToken' => null,
            'dataCriacao' => UtilFunctions::obterDataAtualBanco(),
            'dataAlteracao' => UtilFunctions::obterDataAtualBanco(),
            'usuarioAlteracao' => null,
            'removido' => false,
        ];
        return $usuario;
    }

}
