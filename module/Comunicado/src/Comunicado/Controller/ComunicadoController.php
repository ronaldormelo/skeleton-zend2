<?php

namespace Comunicado\Controller;

use Estrutura\Controller\AbstractCrudController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class ComunicadoController extends AbstractCrudController {

    /**
     * @var \Comunicado\Service\Comunicado
     */
    protected $service;

    /**
     * @var \Comunicado\Form\Comunicado
     */
    protected $form;

    public function __construct() {
        parent::init();
    }

    /**
     * 
     * @return type
     */
    public function indexAction() {

        $comunicadoService = new \Comunicado\Service\ComunicadoService();

        $dadosView = [
            'service' => $comunicadoService,
            'lista' => $comunicadoService->filtrarObjeto(),
            'controller' => $this->params('controller'),
        ];

        $viewModel = new ViewModel($dadosView);
        return $viewModel->setTerminal(TRUE);
    }

    /**
     * 
     * @return JsonModel
     * @throws \Exception
     */
    public function gravarAction() {
        try {
            $comunicadoService = new \Comunicado\Service\ComunicadoService();
            $form = $this->getFormObj();

            $request = $this->getRequest();

            if (!$request->isPost()) {
                throw new \Exception('Dados Inválidos');
            }

            $post = \Estrutura\Helpers\Utilities::arrayMapArray('trim', $request->getPost()->toArray());

            if (isset($post['id'])) {
                $post['id'] = \Estrutura\Helpers\Cript::dec($post['id']);
            }

            if (isset($post['dt_comunicado']) && $post['dt_comunicado']) {

                $post['dt_comunicado'] = \DateTime::createFromFormat('d/m/Y H:i', $post['dt_comunicado'])->format('Y-m-d H:i:s');
            }
            if (isset($post['dt_comunicado']) && $post['dt_comunicado']) {

                $post['dt_expiracao'] = \DateTime::createFromFormat('d/m/Y H:i', $post['dt_expiracao'])->format('Y-m-d H:i:s');
            }

            $form->setData($post);

            if (!$form->isValid()) {

                return new JsonModel(
                        array(
                    'status' => 'error',
                    'message' => 'Dados do formulário inválidos',
                        )
                );
            }

            $comunicadoService->exchangeArray($form->getData());
            if ($comunicadoService->salvar()) {

                return new JsonModel(
                        array(
                    'status' => 'success',
                    'message' => 'Salvo com sucesso',
                        )
                );
            } else {
                return new JsonModel(
                        array(
                    'status' => 'error',
                    'message' => 'Dados inválidos',
                        )
                );
            }
        } catch (\Exception $e) {

            return new JsonModel(
                    array(
                'status' => 'error',
                'message' => 'Dados inválidos',
                    )
            );
        }
    }

    /**
     * 
     * @return type
     */
    public function cadastroAction() {

        $comunicadoService = new \Comunicado\Service\ComunicadoService();
        $form = $this->getFormObj();

        $id = \Estrutura\Helpers\Cript::dec($this->getRequest()->getPost()->get('id'));
        $post = $this->getPost();

        if ($id) {

            $data = $comunicadoService->buscar($id)->toArray();

            if (isset($data['dt_comunicado']) && $data['dt_comunicado']) {

                $data['dt_comunicado'] = (new \DateTime($data['dt_comunicado']))->format('d/m/Y H:i');
            }
            if (isset($data['dt_comunicado']) && $data['dt_comunicado']) {

                $data['dt_expiracao'] = (new \DateTime($data['dt_expiracao']))->format('d/m/Y H:i');
            }

            $form->setData($data);
        }

        if (!empty($post)) {

            $form->setData($post);
        }

        $dadosView = [
            'service' => $comunicadoService,
            'form' => $form,
            'controller' => $this->params('controller'),
        ];

        $viewModel = new ViewModel($dadosView);
        return $viewModel->setTerminal(TRUE);
    }

    /**
     * 
     * @return JsonModel
     */
    public function excluirAction() {

        try {
            $comunicadoService = new \Comunicado\Service\ComunicadoService();

            $id = \Estrutura\Helpers\Cript::dec($this->getRequest()->getPost()->get('id'));
            $comunicadoService->setId($id);

            $dados = $comunicadoService->filtrarObjeto()->current();

            if (!$dados) {

                return new JsonModel(
                        array(
                    'status' => 'success',
                    'message' => 'Não foi possível excluir',
                        )
                );
            }

            $comunicadoService->excluir();
            return new JsonModel(
                    array(
                'status' => 'success',
                'message' => 'Dados Excluídos com sucesso',
                    )
            );
        } catch (\Exception $e) {

            return new JsonModel(
                    array(
                'status' => 'success',
                'message' => 'Não foi possível excluir',
                    )
            );
        }
    }

}
