<?php

namespace McNetwork\Controller;

use Estrutura\Controller\AbstractCrudController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class RedeController extends AbstractCrudController {

    /**
     * @var \McNetworko\Service\RedeService
     */
    protected $service;

    /**
     * @var \McNetwork\Form\RedeForm
     */
    protected $form;
    protected $storage;
    protected $configList;

    /**
     * 
     */
    public function __construct() {
        parent::init();
    }

    /**
     * 
     * @return type
     */
    public function indexAction() {
        return parent::index($this->service, $this->form);
    }

    /**
     * 
     * @return type
     */
    public function gravarAction() {
        return parent::gravar($this->service, $this->form);
    }

    /**
     * 
     * @return type
     */
    public function cadastroAction() {
        return parent::cadastro($this->service, $this->form);
    }

    /**
     * 
     * @return type
     */
    public function excluirAction() {
        return parent::excluir($this->service, $this->form);
    }

    /**
     * 
     * @return type
     */
    public function upLineAction() {

        $auth = $this->getServiceLocator()->get('AuthService')->getStorage()->read();

        //#######
        //Cadastra novas empresas que ainda não foram cadastradas para o usuário

        /* @var $empresaService Empresa/Service/EmpresaService */
        $empresaService = $this->getServiceLocator()->get('\Empresa\Service\EmpresaService');
//@TODO 07/09/2015 - Alterado para trazer somente as empresas ativas        
//        $empresas = $empresaService->fetchAll()->toArray();

        $empresas = $empresaService->toArrayResult($empresaService->fetchAllAtivo());

        $empresasUsuario = $empresaService->toArrayResult($empresaService->listEmpresasUsuario($auth->id_usuario));

        if (!empty($empresas)) {

            foreach ($empresas as $empresa) {

                $temCadastroEmpresa = FALSE;

                if (!empty($empresasUsuario)) {
                    foreach ($empresasUsuario as $empresaUsuario) {

                        if ($empresaUsuario['id_empresa'] == $empresa->getId()) {

                            $temCadastroEmpresa = TRUE;
                            break;
                        }
                    }
                }

                if (!$temCadastroEmpresa) {

                    $empresaAsContratoService = new \EmpresaAsContrato\Service\EmpresaAsContratoService();
                    $empresaAsContratoService->setIdEmpresa($empresa->getId());
                    $empresaAsContratoService->setIdContrato($auth->id_contrato);
                    $empresaAsContratoService->setIdSituacaoEmpresaContrato($this->getConfigList()['situacao_empresa_contrato_inativo']);
                    $empresaAsContratoService->salvar();
                }
            }
        }

        //Fim Cadastra novas empresas que ainda não foram cadastradas para o usuário
        //#######
        // Habilita aba para o cadastro de novas empresas
//@TODO 07/09/2015 - Retirado consulta redudante 
//        $empresasUsuario = $empresaService->toArrayResult($empresaService->listEmpresasUsuario($auth->id_usuario));
        $podeHabilitarNovaAba = TRUE;
        foreach ($empresasUsuario as $empresaUsuario) {

            if ($empresaUsuario['id_situacao_empresa_contrato'] == $this->getConfigList()['situacao_empresa_contrato_congelado']) {
                $podeHabilitarNovaAba = FALSE;
                break;
            }
        }

        if ($podeHabilitarNovaAba) {

            foreach ($empresasUsuario as $empresaUsuario) {

                if ($empresaUsuario['id_situacao_empresa_contrato'] == $this->getConfigList()['situacao_empresa_contrato_inativo']) {

                    $empresaAsContratoService = new \EmpresaAsContrato\Service\EmpresaAsContratoService();
                    $empresaAsContratoEntity = $empresaAsContratoService->buscar($empresaUsuario['id_empresa_as_contrato']);
                    $empresaAsContratoEntity->setIdSituacaoEmpresaContrato($this->getConfigList()['situacao_empresa_contrato_congelado']);
                    $empresaAsContratoEntity->salvar();
                    break;
                }
            }
        }
        //Fim habilita aba para o cadastro de novas empresas
        //#######
        //Verifica se usuário pai já cadastrou um link
        $soliciatacaoEmpresaService = new \SolicitacaoEmpresa\Service\SolicitacaoEmpresaService();
        $soliciatacaoEmpresaService->setConfigList($this->getConfigList());

        $solicitacoesUsuarioSolicitante = $soliciatacaoEmpresaService->toArrayResult($soliciatacaoEmpresaService->listSolicitacoesUsuarioSolicitante($auth));

        if (!empty($solicitacoesUsuarioSolicitante)) {
            foreach ($solicitacoesUsuarioSolicitante as $key => $solicitacaoUsuarioSolicitante) {

                if ($solicitacaoUsuarioSolicitante['id_situacao_solicitacao_empresa'] == $this->getConfigList()['situacao_solicitacao_empresa_pendente']) {

                    $empresaAsContratoService = new \EmpresaAsContrato\Service\EmpresaAsContratoService();
                    $empresaAsContratoService->setIdEmpresa($solicitacaoUsuarioSolicitante['id_empresa']);
                    $empresaAsContratoService->setIdContrato($solicitacaoUsuarioSolicitante['id_contrato_solicitado']);
                    $empresaAsContratoEntity = $empresaAsContratoService->filtrarObjeto()->current();

                    if ($empresaAsContratoEntity->getIdSituacaoEmpresaContrato() == $this->getConfigList()['situacao_empresa_contrato_ativo']) {

                        $empresaAsContratoService = new \EmpresaAsContrato\Service\EmpresaAsContratoService();
                        $empresaAsContratoService->setIdEmpresa($solicitacaoUsuarioSolicitante['id_empresa']);
                        $empresaAsContratoService->setIdContrato($solicitacaoUsuarioSolicitante['id_contrato']);
                        $empresaAsContratoEntity = $empresaAsContratoService->filtrarObjeto()->current();

                        $empresaAsContratoEntity->setIdContratoPai($solicitacaoUsuarioSolicitante['id_contrato_solicitado']);
                        $empresaAsContratoEntity->salvar();

                        $solicitacaoEmpresaService = new \SolicitacaoEmpresa\Service\SolicitacaoEmpresaService();
                        $solicitacaoEmpresaEntity = $solicitacaoEmpresaService->buscar($solicitacaoUsuarioSolicitante['id_solicitacao_empresa']);

                        $solicitacaoEmpresaEntity->setIdSituacaoSolicitacaoEmpresa($this->getConfigList()['situacao_solicitacao_empresa_aprovado']);
                        $solicitacaoEmpresaEntity->salvar();

                        unset($solicitacoesUsuarioSolicitante[$key]);
                    }
                }
            }
        }

        $solicitacoesUsuarioSolicitado = $soliciatacaoEmpresaService->toArrayResult($soliciatacaoEmpresaService->listSolicitacoesUsuarioSolicitado($auth));

        $redeService = new \McNetwork\Service\RedeService();
        //Seta o config no service
        $redeService->setConfigList($this->getConfigList());

//      @TODO 07/09/2015 - Retirado consulta redudante 
//      $empresasUsuario = $empresaService->toArrayResult($empresaService->listEmpresasUsuario($auth->id_usuario));

        if (!empty($empresasUsuario)) {
            foreach ($empresasUsuario as $key => $empresaUsuario) {

                $empresasUsuario[$key]['videos'] = $redeService->toArrayResult($redeService->listVideos($empresaUsuario['id_empresa']));
            }
        }
        
        $view = new ViewModel([
            'controller' => $this->params('controller'),
            'configList' => $this->getConfigList(),
            'listUpLine' => $redeService->toArrayResult($redeService->listUpline($auth)),
            'listIndicador' => $redeService->toArrayResult($redeService->listIndicador($auth)),
            'mensalidadeFoiPaga' => $this->getServiceLocator()->get('Pagamento/Service/PagamentoService')->mensalidadeFoiPaga($auth),
            'empresasUsuario' => $empresasUsuario,
            'codigoVideoApresentacao' => $this->getConfigList()['codigo_video_apresentacao'],
            'solicitacoesUsuarioSolicitante' => $solicitacoesUsuarioSolicitante,
            'solicitacoesUsuarioSolicitado' => $solicitacoesUsuarioSolicitado,
        ]);
        return $view->setTerminal(true);
    }

    /**
     * 
     * @return type
     */
    public function uniLevelAction() {

        $auth = $this->getServiceLocator()->get('AuthService')->getStorage()->read();
        $redeService = new \McNetwork\Service\RedeService();
        //Seta o config no service
        $redeService->setConfigList($this->getConfigList());

        $view = new ViewModel([
            'service' => $this->service,
            'list1' => $redeService->toArrayResult($redeService->listRede($auth, 1)),
            'list2' => $redeService->toArrayResult($redeService->listRede($auth, 2)),
            'list3' => $redeService->toArrayResult($redeService->listRede($auth, 3)),
            'listUniLevel' => $redeService->toArrayResult($redeService->listUniLevel($auth)),
            'listIndicacao' => $redeService->toArrayResult($redeService->listIndicacao($auth)),
            'listPendentes' => $redeService->toArrayResult($redeService->listPendentes($auth)),
            'controller' => $this->params('controller'),
            'atributos' => ['auth' => $auth],
            'configList' => $this->getConfigList(),
            'mensalidadeFoiPaga' => $this->getServiceLocator()->get('Pagamento/Service/PagamentoService')->mensalidadeFoiPaga($auth),
        ]);
        return $view->setTerminal(true);
    }

    /**
     * 
     * @return type
     */
    public function viewVideoAction() {

        $view = new ViewModel([
            'cod' => $this->getRequest()->getPost()->get('cod'),
        ]);
        return $view->setTerminal(true);
    }

    /**
     * 
     * @return type
     */
    public function viewVideoApresentacaoAction() {

        $view = new ViewModel([
            'cod' => $this->getRequest()->getPost()->get('cod'),
        ]);
        return $view->setTerminal(true);
    }

    /**
     * 
     */
    public function solicitarPatrocinadorAction() {

        $idEmpresa = \Estrutura\Helpers\Cript::dec($this->getRequest()->getPost()->get('cod_empresa'));
        $auth = $this->getServiceLocator()->get('AuthService')->getStorage()->read();

        if ($idEmpresa && is_numeric($idEmpresa)) {

            //verifica se o usuario solicitante já tem solicitações para essa empresa
            $solicitacaoEmpresaService = new \SolicitacaoEmpresa\Service\SolicitacaoEmpresaService();
            $solicitacoesUsuarioSolicitante = $solicitacaoEmpresaService->toArrayResult(
                    $solicitacaoEmpresaService->listSolicitacoesUsuarioSolicitante($auth, $idEmpresa)
            );

            if (empty($solicitacoesUsuarioSolicitante)) {

                $contratoService = new \Contrato\Service\ContratoService();
                $contratoEntity = $contratoService->buscar($auth->id_contrato);

                //Verificar qual usuario pai ainda não negou ou já tem um link para patrocinar
                $contratoAsContratoService = new \ContratoAsContrato\Service\ContratoAsContratoService();

                //Seta o config no service
                $contratoAsContratoService->setConfigList($this->getConfigList());

                $contratoAsContratoService->solicitarPatrocinador($contratoEntity, $idEmpresa, $contratoEntity);
            }

            return new JsonModel(
                    array(
                'status' => 'success',
                'message' => '<b>Solicitação enviada com sucesso!</b><br> Dentro de algumas horas será disponibilizado um link/código de quem te recomendou para realizar o cadastro!',
                    )
            );
        }
        return new JsonModel(
                array(
            'status' => 'error',
            'message' => 'Campo obrigatório.',
                )
        );
    }

    /**
     * 
     */
    public function recusarPatrocinadorAction() {

        $idEmpresa = \Estrutura\Helpers\Cript::dec($this->getRequest()->getPost()->get('cod_empresa'));
        $auth = $this->getServiceLocator()->get('AuthService')->getStorage()->read();


        if ($idEmpresa && is_numeric($idEmpresa)) {

            //verifica se o usuario solicitante já tem solicitações para essa empresa
            $solicitacaoEmpresaService = new \SolicitacaoEmpresa\Service\SolicitacaoEmpresaService();

            $solicitacoesUsuarioSolicitante = $solicitacaoEmpresaService->toArrayResult(
                    $solicitacaoEmpresaService->listSolicitacoesUsuarioSolicitante($auth, $idEmpresa)
            );
            
            
            if (empty($solicitacoesUsuarioSolicitante)) {

                $contratoService = new \Contrato\Service\ContratoService();
                $contratoEntity = $contratoService->buscar($auth->id_contrato);

                //Verificar qual usuario pai ainda não negou ou já tem um link para patrocinar
                $contratoAsContratoService = new \ContratoAsContrato\Service\ContratoAsContratoService();
                $contratoAsContratoService->setConfigList($this->getConfigList());
                $contratoAsContratoService->recusarPatrocinador($contratoEntity, $idEmpresa);
            } else {
                
                foreach ($solicitacoesUsuarioSolicitante as $key => $solicitacaoUsuarioSolicitante) {
                
                    $solicitacaoEmpresaService->setId($solicitacaoUsuarioSolicitante['id_solicitacao_empresa']);
                    $solicitacaoEmpresaService->excluir();
                    
                }
                
                $contratoService = new \Contrato\Service\ContratoService();
                $contratoEntity = $contratoService->buscar($auth->id_contrato);
                
                //Verificar qual usuario pai ainda não negou ou já tem um link para patrocinar
                $contratoAsContratoService = new \ContratoAsContrato\Service\ContratoAsContratoService();
                $contratoAsContratoService->setConfigList($this->getConfigList());
                $contratoAsContratoService->recusarPatrocinador($contratoEntity, $idEmpresa);
            }

            return new JsonModel(
                    array(
                'status' => 'success',
                'message' => '<b>Empresa recusada com sucesso!</b><br> A empresa não aparecerá mais para você.',
                    )
            );
        }

        return new JsonModel(
                array(
            'status' => 'error',
            'message' => 'Campo obrigatório.',
                )
        );
    }

    /**
     * 
     */
    public function enviarIdAction() {

        $id = trim($this->getRequest()->getPost()->get('id'));
        $idEmpresa = \Estrutura\Helpers\Cript::dec($this->getRequest()->getPost()->get('cod_empresa'));
        $auth = $this->getServiceLocator()->get('AuthService')->getStorage()->read();

        if ($id && $idEmpresa && is_numeric($idEmpresa)) {

            //verifica se o usuario solicitante já tem solicitações para essa empresa
            $empresaAsContratoService = new \EmpresaAsContrato\Service\EmpresaAsContratoService();
            $empresaAsContratoService->setIdEmpresa($idEmpresa);
            $empresaAsContratoService->setIdContrato($auth->id_contrato);
            $empresaAsContratoEntity = $empresaAsContratoService->filtrarObjeto()->current();

            $empresaService = new \Empresa\Service\EmpresaService();
            $empresaEntity = $empresaService->buscar($idEmpresa);

            $empresaAsContratoEntity->setUrLinkEmpresa(str_replace($empresaEntity->getUrLink(), '', $id));
            $empresaAsContratoEntity->salvar();

            return new JsonModel(
                    array(
                'status' => 'success',
                'message' => '<b>ID enviado com sucesso!</b><br> Seu ID será analisado e liberado dentro de alguns minutos.',
                    )
            );
        } else {

            return new JsonModel(
                    array(
                'status' => 'error',
                'message' => 'Campo é obrigatório.',
                    )
            );
        }
    }

    /**
     * 
     * @return type
     */
    public function listAtivacaoAction() {

        /* @var $empresaService Empresa/Service/EmpresaService */
        $empresaService = $this->getServiceLocator()->get('\Empresa\Service\EmpresaService');
//@TODO 07/09/2015 - Alterado para trazer somente as empresas ativas        
//        $empresas = $empresaService->fetchAll()->toArray();        
        $empresas = $empresaService->toArrayResult($empresaService->fetchAllAtivo());

        $listEmpresaAsContratos = [];
        if (!empty($empresas)) {

            $empresaAsContratoService = new \EmpresaAsContrato\Service\EmpresaAsContratoService();
            $empresaAsContratoService->setConfigList($this->getConfigList());
            foreach ($empresas as $key => $empresa) {

                $listEmpresaAsContratos[$key] = $empresaAsContratoService->toArrayResult($empresaAsContratoService->getEmpresasContratoParaAtivacao($empresa->getId()));
            }
        }

        $view = new ViewModel([
            'controller' => $this->params('controller'),
            'configList' => $this->getConfigList(),
            'empresas' => $empresas,
            'listEmpresaAsContratos' => $listEmpresaAsContratos,
        ]);
        return $view->setTerminal(true);
    }

    /**
     * 
     * @return type
     */
    public function ativarIdAction() {

        $idEmpresaAsContrato = \Estrutura\Helpers\Cript::dec($this->getRequest()->getPost()->get('id_empresa_as_contrato'));

        if ($idEmpresaAsContrato && is_numeric($idEmpresaAsContrato)) {

            $empresaAsContratoService = new \EmpresaAsContrato\Service\EmpresaAsContratoService();
            $empresaAsContratoEntity = $empresaAsContratoService->buscar($idEmpresaAsContrato);

            if (!empty($empresaAsContratoEntity)) {

                $empresaAsContratoEntity->setIdSituacaoEmpresaContrato($this->getConfigList()['situacao_empresa_contrato_ativo']);
                $empresaAsContratoEntity->salvar();

                //16/09/2015
                //Cadastra suporte para a empresa liberada
                ##################
                $suporteAsContratoService = new \SuporteAsContrato\Service\SuporteAsContratoService();
                $suporteAsContratoService->setIdContrato($empresaAsContratoEntity->getIdContrato());
                $suporteAsContratoService->setIdEmpresa($empresaAsContratoEntity->getIdEmpresa());
                $suporteAsContratoService->excluir();

                $suporteAsContratoService = new \SuporteAsContrato\Service\SuporteAsContratoService();
                $suporteAsContratoService->exchangeArray([
                    'id_suporte_as_contrato' => NULL,
                    'id_empresa' => $empresaAsContratoEntity->getIdEmpresa(),
                    'id_contrato' => $empresaAsContratoEntity->getIdContrato(),
                    'id_situacao' => $this->getConfigList()['situacao_ativo'],
                ]);
                $suporteAsContratoService->salvar();

                //Envio de e-mail
                ##################

                /* @var $empresaService \Empresa\Service\EmpresaServic */
                $empresaService = $this->getServiceLocator()->get('\Empresa\Service\EmpresaService');
                $empresaEntity = $empresaService->buscar($empresaAsContratoEntity->getIdEmpresa());

                /* @var $contratoService \Contrato\Service\ContratoService */
                $contratoService = $this->getServiceLocator()->get('\Contrato\Service\ContratoService');
                $contratoEntity = $contratoService->buscar($empresaAsContratoEntity->getIdContrato());

                $usuarioService = new \Usuario\Service\UsuarioService();
                $usuarioEntity = $usuarioService->buscar($contratoEntity->getIdUsuario());

                $emailService = $this->getServiceLocator()->get('\Email\Service\EmailService');
                $emailEntity = $emailService->buscar($usuarioEntity->getIdEmail());

                $contaEmail = 'no-reply';

                $message = new \Zend\Mail\Message();
                $message->addFrom($contaEmail . '@mcnetwork.com.br', 'MC Network')
                        ->addTo($emailEntity->getEmEmail())
                        ->addBcc('mcnetwork@mcnetwork.com.br')
                        ->setSubject('Seu ID da empresa ' . $empresaEntity->getNmEmpresa() . ' foi liberado!');

                $applicationService = new \Application\Service\ApplicationService();
                $transport = $applicationService->getSmtpTranport($contaEmail);

                $htmlMessage = $applicationService->tratarModelo(
                        [
                    'BASE_URL' => BASE_URL,
                    'nomeUsuario' => $usuarioEntity->getNmUsuario(),
                    'email' => $emailEntity->getEmEmail(),
                    'empresa' => $empresaEntity->getNmEmpresa(),
                    'id' => $empresaAsContratoEntity->getUrLinkEmpresa(),
                        ], $applicationService->getModelo('ativar-id'));

                $html = new \Zend\Mime\Part($htmlMessage);
                $html->type = 'text/html';

                $body = new \Zend\Mime\Message();
                $body->addPart($html);

                $message->setBody($body);
                $transport->send($message);
                #############################
            }
        }

        return new JsonModel(
                array(
            'status' => 'success',
            'message' => 'ID ativado com secesso.',
                )
        );
    }

    /**
     * 
     * @return type
     */
    public function negarIdAction() {

        $idEmpresaAsContrato = \Estrutura\Helpers\Cript::dec($this->getRequest()->getPost()->get('id_empresa_as_contrato'));

        if ($idEmpresaAsContrato && is_numeric($idEmpresaAsContrato)) {

            $empresaAsContratoService = new \EmpresaAsContrato\Service\EmpresaAsContratoService();
            /* @var $empresaAsContratoEntity \EmpresaAsContrato\Service\EmpresaAsContratoService */
            $empresaAsContratoEntity = $empresaAsContratoService->buscar($idEmpresaAsContrato);

            $id = $empresaAsContratoEntity->getUrLinkEmpresa();

            if (!empty($empresaAsContratoEntity)) {

                $empresaAsContratoEntity->exchangeArray([
                    'ur_link_empresa' => new \Zend\Db\Sql\Predicate\Expression('NULL')
                ]);
                $empresaAsContratoEntity->salvar();

                //Envio de e-mail
                ##################

                /* @var $empresaService \Empresa\Service\EmpresaServic */
                $empresaService = $this->getServiceLocator()->get('\Empresa\Service\EmpresaService');
                $empresaEntity = $empresaService->buscar($empresaAsContratoEntity->getIdEmpresa());

                /* @var $contratoService \Contrato\Service\ContratoService */
                $contratoService = $this->getServiceLocator()->get('\Contrato\Service\ContratoService');
                $contratoEntity = $contratoService->buscar($empresaAsContratoEntity->getIdContrato());

                $usuarioService = new \Usuario\Service\UsuarioService();
                $usuarioEntity = $usuarioService->buscar($contratoEntity->getIdUsuario());

                $emailService = $this->getServiceLocator()->get('\Email\Service\EmailService');
                $emailEntity = $emailService->buscar($usuarioEntity->getIdEmail());

                $contaEmail = 'no-reply';

                $message = new \Zend\Mail\Message();
                $message->addFrom($contaEmail . '@mcnetwork.com.br', 'MC Network')
                        ->addTo($emailEntity->getEmEmail())
                        ->addBcc('mcnetwork@mcnetwork.com.br')
                        ->setSubject('Seu ID da empresa ' . $empresaEntity->getNmEmpresa() . ' não foi liberado');

                $applicationService = new \Application\Service\ApplicationService();
                $transport = $applicationService->getSmtpTranport($contaEmail);

                $htmlMessage = $applicationService->tratarModelo(
                        [
                    'BASE_URL' => BASE_URL,
                    'nomeUsuario' => $usuarioEntity->getNmUsuario(),
                    'email' => $emailEntity->getEmEmail(),
                    'empresa' => $empresaEntity->getNmEmpresa(),
                    'id' => $id,
                        ], $applicationService->getModelo('negar-id'));

                $html = new \Zend\Mime\Part($htmlMessage);
                $html->type = 'text/html';

                $body = new \Zend\Mime\Message();
                $body->addPart($html);

                $message->setBody($body);
                $transport->send($message);
                #############################
            }
        }

        return new JsonModel(
                array(
            'status' => 'success',
            'message' => 'ID negado com secesso.',
                )
        );
    }

}
