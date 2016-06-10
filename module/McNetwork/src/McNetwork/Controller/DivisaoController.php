<?php

namespace McNetwork\Controller;

use Estrutura\Controller\AbstractCrudController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class DivisaoController extends AbstractCrudController {

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

        $viewModel = new ViewModel([]);
        return $viewModel->setTerminal(TRUE);
    }

    /**
     * 
     * @return type
     */
    public function divisao1Action() {

        //Somente na segunda feira
        if (date('N') == 1) {

            $dateInicial = new \DateTime(date('Y-m-d'));
            $dateInicial->sub(new \DateInterval('P7D'));

            $dateFinal = new \DateTime(date('Y-m-d'));
            $dateFinal->sub(new \DateInterval('P1D'));

            $usuarioService = new \Usuario\Service\UsuarioService();
            $listUsuariosIndicou = $usuarioService->toArrayResult($usuarioService->getUsuariosIndicou1($this->getConfigList()));

            $qtdUsuariosIndicou = count($listUsuariosIndicou);

            $valorArrecadadoParaDivisao = current($usuarioService->toArrayResult($usuarioService->getValorArrecadadoDivisao($this->getConfigList())->current()));

            bcscale(10);
            if ($this->getConfigList()['divisao_lucro']) {

                $valorFinalArrecadadoParaDivisao = bcmul($valorArrecadadoParaDivisao, bcdiv($this->getConfigList()['divisao_lucro'], 100, 2));
            } else {

                $valorFinalArrecadadoParaDivisao = 0;
            }

            if ($valorFinalArrecadadoParaDivisao && $this->getConfigList()['divisao_lucro_1'] && $qtdUsuariosIndicou) {

                $valorSugeridoPagamentoUnitario = bcdiv(bcmul($valorFinalArrecadadoParaDivisao, bcdiv($this->getConfigList()['divisao_lucro_1'], 100, 2)), $qtdUsuariosIndicou, 2);
            } else {

                $valorSugeridoPagamentoUnitario = 0;
            }

            $viewModel = new ViewModel([
                'dateInicial' => $dateInicial->format('d/m/Y'),
                'dateFinal' => $dateFinal->format('d/m/Y'),
                'listUsuariosIndicou' => $listUsuariosIndicou,
                'qtdUsuariosIndicou' => $qtdUsuariosIndicou,
                'valorArrecadadoParaDivisao' => $valorArrecadadoParaDivisao,
                'valorFinalArrecadadoParaDivisao' => $valorFinalArrecadadoParaDivisao,
                'valorSugeridoPagamentoUnitario' => $valorSugeridoPagamentoUnitario,
                'configList' => $this->getConfigList(),
            ]);
        } else {

            $viewModel = new ViewModel([]);
        }

        return $viewModel->setTerminal(TRUE);
    }

    /**
     * 
     * @return type
     */
    public function divisao2Action() {

        //Somente na segunda feira
        if (date('N') == 1) {

            $dateInicial = new \DateTime(date('Y-m-d'));
            $dateInicial->sub(new \DateInterval('P7D'));

            $dateFinal = new \DateTime(date('Y-m-d'));
            $dateFinal->sub(new \DateInterval('P1D'));

            $usuarioService = new \Usuario\Service\UsuarioService();
            $listUsuariosIndicou = $usuarioService->toArrayResult($usuarioService->getUsuariosIndicou2($this->getConfigList()));

            $qtdUsuariosIndicou = count($listUsuariosIndicou);

            $valorArrecadadoParaDivisao = current($usuarioService->toArrayResult($usuarioService->getValorArrecadadoDivisao($this->getConfigList())->current()));

            bcscale(10);
            if ($this->getConfigList()['divisao_lucro']) {

                $valorFinalArrecadadoParaDivisao = bcmul($valorArrecadadoParaDivisao, bcdiv($this->getConfigList()['divisao_lucro'], 100, 2));
            } else {

                $valorFinalArrecadadoParaDivisao = 0;
            }

            if ($valorFinalArrecadadoParaDivisao && $this->getConfigList()['divisao_lucro_2'] && $qtdUsuariosIndicou) {

                $valorSugeridoPagamentoUnitario = bcdiv(bcmul($valorFinalArrecadadoParaDivisao, bcdiv($this->getConfigList()['divisao_lucro_2'], 100, 2)), $qtdUsuariosIndicou, 2);
            } else {

                $valorSugeridoPagamentoUnitario = 0;
            }

            $viewModel = new ViewModel([
                'dateInicial' => $dateInicial->format('d/m/Y'),
                'dateFinal' => $dateFinal->format('d/m/Y'),
                'listUsuariosIndicou' => $listUsuariosIndicou,
                'qtdUsuariosIndicou' => $qtdUsuariosIndicou,
                'valorArrecadadoParaDivisao' => $valorArrecadadoParaDivisao,
                'valorFinalArrecadadoParaDivisao' => $valorFinalArrecadadoParaDivisao,
                'valorSugeridoPagamentoUnitario' => $valorSugeridoPagamentoUnitario,
                'configList' => $this->getConfigList(),
            ]);
        } else {

            $viewModel = new ViewModel([]);
        }

        return $viewModel->setTerminal(TRUE);
    }

    /**
     * 
     * @return type
     */
    public function divisao3Action() {

        //Somente na segunda feira
        if (date('N') == 1) {

            $dateInicial = new \DateTime(date('Y-m-d'));
            $dateInicial->sub(new \DateInterval('P7D'));

            $dateFinal = new \DateTime(date('Y-m-d'));
            $dateFinal->sub(new \DateInterval('P1D'));

            $usuarioService = new \Usuario\Service\UsuarioService();
            $listUsuariosIndicou = $usuarioService->toArrayResult($usuarioService->getUsuariosIndicou3($this->getConfigList()));

            $qtdUsuariosIndicou = count($listUsuariosIndicou);

            $valorArrecadadoParaDivisao = current($usuarioService->toArrayResult($usuarioService->getValorArrecadadoDivisao($this->getConfigList())->current()));

            bcscale(10);
            if ($this->getConfigList()['divisao_lucro']) {

                $valorFinalArrecadadoParaDivisao = bcmul($valorArrecadadoParaDivisao, bcdiv($this->getConfigList()['divisao_lucro'], 100, 2));
            } else {

                $valorFinalArrecadadoParaDivisao = 0;
            }

            if ($valorFinalArrecadadoParaDivisao && $this->getConfigList()['divisao_lucro_3'] && $qtdUsuariosIndicou) {

                $valorSugeridoPagamentoUnitario = bcdiv(bcmul($valorFinalArrecadadoParaDivisao, bcdiv($this->getConfigList()['divisao_lucro_3'], 100, 2)), $qtdUsuariosIndicou, 2);
            } else {

                $valorSugeridoPagamentoUnitario = 0;
            }

            $viewModel = new ViewModel([
                'dateInicial' => $dateInicial->format('d/m/Y'),
                'dateFinal' => $dateFinal->format('d/m/Y'),
                'listUsuariosIndicou' => $listUsuariosIndicou,
                'qtdUsuariosIndicou' => $qtdUsuariosIndicou,
                'valorArrecadadoParaDivisao' => $valorArrecadadoParaDivisao,
                'valorFinalArrecadadoParaDivisao' => $valorFinalArrecadadoParaDivisao,
                'valorSugeridoPagamentoUnitario' => $valorSugeridoPagamentoUnitario,
                'configList' => $this->getConfigList(),
            ]);
        } else {

            $viewModel = new ViewModel([]);
        }

        return $viewModel->setTerminal(TRUE);
    }

    /**
     * 
     * @return type
     */
    public function divisao4Action() {

        //Somente na segunda feira
        if (date('N') == 1) {

            $dateInicial = new \DateTime(date('Y-m-d'));
            $dateInicial->sub(new \DateInterval('P7D'));

            $dateFinal = new \DateTime(date('Y-m-d'));
            $dateFinal->sub(new \DateInterval('P1D'));

            $usuarioService = new \Usuario\Service\UsuarioService();
            $listUsuariosIndicou = $usuarioService->toArrayResult($usuarioService->getUsuariosIndicou4($this->getConfigList()));

            $qtdUsuariosIndicou = count($listUsuariosIndicou);

            $valorArrecadadoParaDivisao = current($usuarioService->toArrayResult($usuarioService->getValorArrecadadoDivisao($this->getConfigList())->current()));

            bcscale(10);
            if ($this->getConfigList()['divisao_lucro']) {

                $valorFinalArrecadadoParaDivisao = bcmul($valorArrecadadoParaDivisao, bcdiv($this->getConfigList()['divisao_lucro'], 100, 2));
            } else {

                $valorFinalArrecadadoParaDivisao = 0;
            }

            if ($valorFinalArrecadadoParaDivisao && $this->getConfigList()['divisao_lucro_4'] && $qtdUsuariosIndicou) {

                $valorSugeridoPagamentoUnitario = bcdiv(bcmul($valorFinalArrecadadoParaDivisao, bcdiv($this->getConfigList()['divisao_lucro_4'], 100, 2)), $qtdUsuariosIndicou, 2);
            } else {

                $valorSugeridoPagamentoUnitario = 0;
            }

            $viewModel = new ViewModel([
                'dateInicial' => $dateInicial->format('d/m/Y'),
                'dateFinal' => $dateFinal->format('d/m/Y'),
                'listUsuariosIndicou' => $listUsuariosIndicou,
                'qtdUsuariosIndicou' => $qtdUsuariosIndicou,
                'valorArrecadadoParaDivisao' => $valorArrecadadoParaDivisao,
                'valorFinalArrecadadoParaDivisao' => $valorFinalArrecadadoParaDivisao,
                'valorSugeridoPagamentoUnitario' => $valorSugeridoPagamentoUnitario,
                'configList' => $this->getConfigList(),
            ]);
        } else {

            $viewModel = new ViewModel([]);
        }

        return $viewModel->setTerminal(TRUE);
    }

    /**
     * 
     * @return type
     */
    public function divisao5Action() {

        //Somente na segunda feira
        if (date('N') == 1) {

            $dateInicial = new \DateTime(date('Y-m-d'));
            $dateInicial->sub(new \DateInterval('P7D'));

            $dateFinal = new \DateTime(date('Y-m-d'));
            $dateFinal->sub(new \DateInterval('P1D'));

            $usuarioService = new \Usuario\Service\UsuarioService();
            $listUsuariosIndicou = $usuarioService->toArrayResult($usuarioService->getUsuariosIndicou5($this->getConfigList()));

            $qtdUsuariosIndicou = count($listUsuariosIndicou);

            $valorArrecadadoParaDivisao = current($usuarioService->toArrayResult($usuarioService->getValorArrecadadoDivisao($this->getConfigList())->current()));

            bcscale(10);

            if ($this->getConfigList()['divisao_lucro']) {

                $valorFinalArrecadadoParaDivisao = bcmul($valorArrecadadoParaDivisao, bcdiv($this->getConfigList()['divisao_lucro'], 100, 2));
            } else {

                $valorFinalArrecadadoParaDivisao = 0;
            }

            if ($valorFinalArrecadadoParaDivisao && $this->getConfigList()['divisao_lucro_5'] && $qtdUsuariosIndicou) {

                $valorSugeridoPagamentoUnitario = bcdiv(bcmul($valorFinalArrecadadoParaDivisao, bcdiv($this->getConfigList()['divisao_lucro_5'], 100, 2)), $qtdUsuariosIndicou, 2);
            } else {

                $valorSugeridoPagamentoUnitario = 0;
            }

            $viewModel = new ViewModel([
                'dateInicial' => $dateInicial->format('d/m/Y'),
                'dateFinal' => $dateFinal->format('d/m/Y'),
                'listUsuariosIndicou' => $listUsuariosIndicou,
                'qtdUsuariosIndicou' => $qtdUsuariosIndicou,
                'valorArrecadadoParaDivisao' => $valorArrecadadoParaDivisao,
                'valorFinalArrecadadoParaDivisao' => $valorFinalArrecadadoParaDivisao,
                'valorSugeridoPagamentoUnitario' => $valorSugeridoPagamentoUnitario,
                'configList' => $this->getConfigList(),
            ]);
        } else {

            $viewModel = new ViewModel([]);
        }

        return $viewModel->setTerminal(TRUE);
    }

    /**
     * 
     * @return type
     */
    public function divisaoMais5Action() {

        //Somente na segunda feira
        if (date('N') == 1) {

            $dateInicial = new \DateTime(date('Y-m-d'));
            $dateInicial->sub(new \DateInterval('P7D'));

            $dateFinal = new \DateTime(date('Y-m-d'));
            $dateFinal->sub(new \DateInterval('P1D'));

            $usuarioService = new \Usuario\Service\UsuarioService();
            $listUsuariosIndicou = $usuarioService->toArrayResult($usuarioService->getUsuariosIndicouMais5($this->getConfigList()));

            $qtdUsuariosIndicou = count($listUsuariosIndicou);

            $valorArrecadadoParaDivisao = current($usuarioService->toArrayResult($usuarioService->getValorArrecadadoDivisao($this->getConfigList())->current()));

            bcscale(10);
            if ($this->getConfigList()['divisao_lucro']) {
                $valorFinalArrecadadoParaDivisao = bcmul($valorArrecadadoParaDivisao, bcdiv($this->getConfigList()['divisao_lucro'], 100, 2));
            } else {

                $valorFinalArrecadadoParaDivisao = 0;
            }

            if ($valorFinalArrecadadoParaDivisao && $this->getConfigList()['divisao_lucro_mais5'] && $qtdUsuariosIndicou) {

                $valorSugeridoPagamentoUnitario = bcdiv(bcmul($valorFinalArrecadadoParaDivisao, bcdiv($this->getConfigList()['divisao_lucro_mais5'], 100, 2)), $qtdUsuariosIndicou, 2);
            } else {

                $valorSugeridoPagamentoUnitario = 0;
            }

            $viewModel = new ViewModel([
                'dateInicial' => $dateInicial->format('d/m/Y'),
                'dateFinal' => $dateFinal->format('d/m/Y'),
                'listUsuariosIndicou' => $listUsuariosIndicou,
                'qtdUsuariosIndicou' => $qtdUsuariosIndicou,
                'valorArrecadadoParaDivisao' => $valorArrecadadoParaDivisao,
                'valorFinalArrecadadoParaDivisao' => $valorFinalArrecadadoParaDivisao,
                'valorSugeridoPagamentoUnitario' => $valorSugeridoPagamentoUnitario,
                'configList' => $this->getConfigList(),
            ]);
        } else {

            $viewModel = new ViewModel([]);
        }

        return $viewModel->setTerminal(TRUE);
    }

}
