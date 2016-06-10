<?php

namespace McNetwork\Controller;

use Estrutura\Controller\AbstractCrudController;
use Zend\View\Model\ViewModel;
use Estrutura\Helpers\Cript;

class CursosController extends AbstractCrudController {

    /**
     * @var \McNetworko\Service\CursosService
     */
    protected $service;

    /**
     * @var \McNetwork\Form\CursosForm
     */
    protected $form;

    /**
     *
     * @var type 
     */
    protected $storage;

    /**
     *
     * @var type 
     */
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
    public function listAction() {

        $auth = $this->getServiceLocator()->get('AuthService')->getStorage()->read();

        $cursosService = new \McNetwork\Service\CursosService();
        $listCursos = $cursosService->getCursos();

        $dadosView = [
            'form' => $this->form,
            'listCursos' => $listCursos,
            'controller' => $this->params('controller'),
            'atributos' => [],
            'configList' => $this->getConfigList(),
            'mensalidadeFoiPaga' => $this->getServiceLocator()->get('Pagamento/Service/PagamentoService')->mensalidadeFoiPaga($auth),
            'liberaPagamentoMensalidade' => $this->getServiceLocator()->get('Pagamento/Service/PagamentoService')->liberaPagamentoMensalidade($auth),
        ];

        $view = new ViewModel($dadosView);
        return $view->setTerminal(true);
    }

    /**
     * 
     * @return type
     */
    public function courseInstructionsAction() {

        $auth = $this->getServiceLocator()->get('AuthService')->getStorage()->read();
        $usuarioService = new \Usuario\Service\UsuarioService();
        $usuarioWP = $usuarioService->getUsuarioWP($auth->em_email);

        $idCurso = Cript::dec($this->getRequest()->getPost()->id);
        $message = $this->getRequest()->getPost()->message;

        $cursosService = new \McNetwork\Service\CursosService();

        $courseInstruction = ($cursosService->getPostMeta($idCurso, 'vibe_course_instructions')['meta_value']);

        $listCurriculum = unserialize($cursosService->getPostMeta($idCurso, 'vibe_course_curriculum')['meta_value']);

        $conclusionContent = [];
        $unit = -1;
        $idContent = NULL;
        $idUnit = NULL;
        $courseStarts = TRUE;

        if (!empty($listCurriculum)) {

            foreach ($listCurriculum as $key => $curriculum) {

                if (is_numeric($curriculum)) {

                    $conclusionContent[$unit][$curriculum] = $cursosService->getUserMetaPost($usuarioWP['ID'], $curriculum)['meta_value'];
                    if (!$conclusionContent[$unit][$curriculum]) {

                        if (is_null($idContent)) {

                            $idContent = $curriculum;
                            $idUnit = $unit;
                        }
                    } else {
                        $courseStarts = FALSE;
                    }
                } else {

                    $unit++;
                }
            }
        }

        $dadosView = [
            'idCurso' => $idCurso,
            'idUnit' => $idUnit,
            'idContent' => $idContent,
            'courseStarts' => $courseStarts,
            'courseInstruction' => $courseInstruction,
            'controller' => $this->params('controller'),
            'configList' => $this->getConfigList(),
            'message' => $message,
        ];
        $view = new ViewModel($dadosView);
        return $view->setTerminal(true);
    }

    /**
     * 
     * @return type
     */
    public function courseContentAction() {

        $auth = $this->getServiceLocator()->get('AuthService')->getStorage()->read();
        $usuarioService = new \Usuario\Service\UsuarioService();
        $usuarioWP = $usuarioService->getUsuarioWP($auth->em_email);

        $id = Cript::dec($this->getRequest()->getPost()->id);

        $explode = explode('_', $id);

        $idCurso = $explode[0];
        $idContent = $explode[1];

        $cursosService = new \McNetwork\Service\CursosService();

        $content = $cursosService->getPost($idContent);

        $listCurriculum = unserialize($cursosService->getPostMeta($idCurso, 'vibe_course_curriculum')['meta_value']);

        $unit = -1;
        $idUnit = NULL;
        $conclusionContent = [];
        $initQuiz = [];

        if (!empty($listCurriculum)) {

            foreach ($listCurriculum as $curriculum) {

                if (is_numeric($curriculum)) {

                    if ($idContent == $curriculum) {

                        $idUnit = $unit;
                        $conclusionContent[$unit][$curriculum] = $cursosService->getUserMetaPost($usuarioWP['ID'], $curriculum, FALSE)['meta_value'];
                    }
                } else {

                    $unit++;
                }
            }
        }

        //Verifica se é um quiz e se o conteúdo do módulo foi marcado como concluido
        if ($content['post_type'] == 'quiz') {

            if (!empty($listCurriculum)) {

                $current_index = array_search($idContent, $listCurriculum);

                while (is_numeric($listCurriculum[$current_index]) && $current_index > 0) {

                    if (is_null($cursosService->getUserMetaPost($usuarioWP['ID'], $listCurriculum[$current_index], FALSE)['meta_value'])) {

                        $this->getRequest()->getPost()->set('id', \Estrutura\Helpers\Cript::enc($idCurso));
                        $this->getRequest()->getPost()->set('message', 'Para iniciar o teste, você deve marcar todas as aulas do módulo como concluída.');

                        return $this->forward()->dispatch('mcnetwork-cursos', [
                                    'action' => 'course-instructions',
                                    'controller' => 'mcnetwork-cursos'
                        ]);
                    }
                    $current_index--;
                }

                foreach ($listCurriculum as $curriculum) {

                    if (is_numeric($curriculum)) {

                        if ($idContent == $curriculum) {

                            $idUnit = $unit;
                            $conclusionContent[$unit][$curriculum] = $cursosService->getUserMetaPost($usuarioWP['ID'], $curriculum, FALSE)['meta_value'];
                        }
                    } else {

                        $unit++;
                    }
                }
            }
        }

        $nextContent = NULL;
        $prevContent = NULL;

        // Find the index of the current item
        $current_index = array_search($idContent, $listCurriculum);

        // Find the index of the next/prev items
        $i = 1;
        while ((($current_index + $i) < count($listCurriculum)) && !is_numeric($listCurriculum[$current_index + $i])) {
            $i++;
        }

        if (($current_index + $i) < count($listCurriculum)) {
            $nextContent = $listCurriculum[$current_index + $i];
        }

        $x = 1;
        while (!is_numeric($listCurriculum[$current_index - $x]) && $x > 0) {
            $x--;
        }
        if ($x > 0) {
            $prevContent = $listCurriculum[$current_index - $x];
        }

        $dadosView = [
            'idCurso' => $idCurso,
            'idUnit' => $idUnit,
            'idContent' => $idContent,
            'prevContent' => $prevContent,
            'nextContent' => $nextContent,
            'conclusionContent' => $conclusionContent,
            'initQuiz' => $initQuiz,
            'content' => $content,
        ];
        $view = new ViewModel($dadosView);
        return $view->setTerminal(true);
    }

    /**
     * 
     * @return type
     */
    public function startTestAction() {

        $auth = $this->getServiceLocator()->get('AuthService')->getStorage()->read();
        $usuarioService = new \Usuario\Service\UsuarioService();
        $usuarioWP = $usuarioService->getUsuarioWP($auth->em_email);

        $id = Cript::dec($this->getRequest()->getPost()->id);

        $explode = explode('_', $id);

        $idCurso = $explode[0];
        $idContent = $explode[1];

        $cursosService = new \McNetwork\Service\CursosService();

        $content = $cursosService->getPost($idContent);

        $quizQuestions = $cursosService->getPostMeta($idContent, 'quiz_questions' . $usuarioWP['ID'])['meta_value'];

        //Gera perguntas do quiz automático
        if (!$quizQuestions) {
            //Gera perguntas das quiz automático
            $aTags = unserialize($cursosService->getPostMeta($idContent, 'vibe_quiz_tags')['meta_value']);
            $numberQuestions = $cursosService->getPostMeta($idContent, 'vibe_quiz_number_questions')['meta_value'];
            $marksPerQuestion = $cursosService->getPostMeta($idContent, 'vibe_quiz_marks_per_question')['meta_value'];
            $aQuestion = [];

            if (!empty($aTags)) {

                foreach ($aTags as $key => $tag) {

                    $aQuestion[] = $cursosService->toArrayResult($cursosService->getQuestionsTag($tag));
                }
            }

            $aQuestion = \Estrutura\Helpers\ArrayHelper::unidim($aQuestion);

            shuffle($aQuestion);

            $quizQuestions = [];
            for ($index = 0; $index < $numberQuestions; $index++) {

                $quizQuestions['ques'][$index] = (int) $aQuestion[$index];
                $quizQuestions['marks'][$index] = $marksPerQuestion;
            }

            $quizQuestions = serialize($quizQuestions);

            $cursosService->inserirPostMeta($idContent, 'quiz_questions' . $usuarioWP['ID'], $quizQuestions);

            $cursosService->inserirUserMeta($usuarioWP['ID'], $idContent, strtotime("now"));
        }

        $quizQuestions = unserialize($quizQuestions);

        foreach ($quizQuestions['ques'] as $key => $ques) {

            $quizQuestions['post'][$key] = $cursosService->getPost($ques);
            $quizMeta = $cursosService->toArrayResult($cursosService->getPostMeta($ques));

            foreach ($quizMeta as $value) {

                $quizQuestions['postMeta'][$key][$value['meta_key']] = $value['meta_value'];
            }
        }

        $progress = $cursosService->getUserMetaPost($usuarioWP['ID'], 'progress' . $idCurso);
        if (isset($progress['meta_value'])) {

            $cursosService->updateUserMetaPost(
                    [
                'meta_value' => 0
                    ], [
                'user_id' => $usuarioWP['ID'],
                'meta_key' => 'progress' . $idCurso
                    ]
            );
        }

        $dadosView = [
            'idCurso' => $idCurso,
            'idContent' => $idContent,
            'content' => $content,
            'quizQuestions' => $quizQuestions,
        ];
        $view = new ViewModel($dadosView);
        return $view->setTerminal(true);
    }

    /**
     * 
     */
    public function markCompletionAction() {

        $id = Cript::dec($this->getRequest()->getPost()->id);

        $explode = explode('_', $id);

        $idCurso = $explode[0];
        $idContent = $explode[1];

        $auth = $this->getServiceLocator()->get('AuthService')->getStorage()->read();
        $usuarioService = new \Usuario\Service\UsuarioService();
        $usuarioWP = $usuarioService->getUsuarioWP($auth->em_email);

        $cursosService = new \McNetwork\Service\CursosService();
        $cursosService->inserirUserMeta($usuarioWP['ID'], $idContent, strtotime("now"));

        return $this->forward()->dispatch('mcnetwork-cursos', [
                    'action' => 'course-content',
                    'controller' => 'mcnetwork-cursos'
        ]);
    }

    /**
     * 
     */
    public function listUnitAction() {

        $auth = $this->getServiceLocator()->get('AuthService')->getStorage()->read();

        $usuarioService = new \Usuario\Service\UsuarioService();
        $usuarioWP = $usuarioService->getUsuarioWP($auth->em_email);

        $idCurso = Cript::dec($this->getRequest()->getPost()->id);

        $cursosService = new \McNetwork\Service\CursosService();
        $listCurriculum = unserialize($cursosService->getPostMeta($idCurso, 'vibe_course_curriculum')['meta_value']);

        $conclusionContent = [];
        $conclusionUnit = [];
        $unit = -1;

        if (!empty($listCurriculum)) {

            foreach ($listCurriculum as $key => $curriculum) {

                if (is_numeric($curriculum)) {
                    $conclusionContent[$unit][$curriculum] = $cursosService->getUserMetaPost($usuarioWP['ID'], $curriculum)['meta_value'];
                    if (!$conclusionContent[$unit][$curriculum]) {

                        $conclusionUnit[$unit] = FALSE;
                    }
                } else {

                    $unit++;
                    $conclusionUnit[$unit] = TRUE;
                }
            }
        }

        $dadosView = [
            'form' => $this->form,
            'idCurso' => $idCurso,
            'conclusionContent' => $conclusionContent,
            'conclusionUnit' => $conclusionUnit,
            'listCurriculum' => $listCurriculum,
            'controller' => $this->params('controller'),
            'atributos' => [],
            'configList' => $this->getConfigList(),
        ];

        $view = new ViewModel($dadosView);
        return $view->setTerminal(true);
    }

    /**
     * 
     */
    public function listContentAction() {

        $auth = $this->getServiceLocator()->get('AuthService')->getStorage()->read();

        $usuarioService = new \Usuario\Service\UsuarioService();
        $usuarioWP = $usuarioService->getUsuarioWP($auth->em_email);

        $id = $this->getRequest()->getPost()->id;

        $explode = explode('_', $id);

        $idCurso = $explode[0];
        $idUnit = $explode[1];

        $cursosService = new \McNetwork\Service\CursosService();
        $listCurriculum = unserialize($cursosService->getPostMeta($idCurso, 'vibe_course_curriculum')['meta_value']);

        $curriculuns = [];
        $conclusionContent = [];
        $unit = -1;

        if (!empty($listCurriculum)) {

            foreach ($listCurriculum as $key => $curriculum) {

                if (is_numeric($curriculum)) {

                    if ($idUnit == $unit) {

                        $curriculuns[$unit][$curriculum] = $cursosService->getPost($curriculum);
                        $conclusionContent[$unit][$curriculum] = $cursosService->getUserMetaPost($usuarioWP['ID'], $curriculum)['meta_value'];
                    }
                } else {

                    $unit++;
                }
            }
        }

        $dadosView = [
            'form' => $this->form,
            'idCurso' => $idCurso,
            'idUnit' => $idUnit,
            'conclusionContent' => $conclusionContent,
            'curriculuns' => $curriculuns,
            'listCurriculum' => $listCurriculum,
            'controller' => $this->params('controller'),
            'atributos' => [],
            'configList' => $this->getConfigList(),
        ];

        $view = new ViewModel($dadosView);
        return $view->setTerminal(true);
    }

    /**
     * 
     * @return type
     */
    public function courseInformationAction() {

        $auth = $this->getServiceLocator()->get('AuthService')->getStorage()->read();
        $cursosService = new \McNetwork\Service\CursosService();
        $cursosService->setIdSituacao($this->getConfigList()['situacao_ativo']);
        $list = $cursosService->filtrarObjeto();

        $loginService = new \Login\Service\LoginService();
        $loginService->setIdUsuario($auth->id_usuario);
        $loginEntity = $loginService->filtrarObjeto()->current();


        $loginAtivo = FALSE;
        if ($loginEntity->getIdSituacao() == $this->getConfigList()['situacao_ativo']) {

            $loginAtivo = TRUE;
        }

        $dadosView = [
            'list' => $list,
            'controller' => $this->params('controller'),
            'atributos' => [],
            'configList' => $this->getConfigList(),
            'liberaPagamentoMensalidade' => $this->getServiceLocator()->get('Pagamento/Service/PagamentoService')->liberaPagamentoMensalidade($auth),
            'pagamento' => $this->getServiceLocator()->get('Pagamento/Service/PagamentoService')->listPagamento($auth),
            'loginAtivo' => $loginAtivo,
            'auth' => $auth,
        ];

        $view = new ViewModel($dadosView);
        return $view->setTerminal(true);
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
     */
    public function uploadAction() {

        $auth = $this->getServiceLocator()->get('AuthService')->getStorage()->read();
        $files = $this->getRequest()->getFiles();

        if (!empty($files)) {
            foreach ($files as $file) {

                if (($file['type'] != 'image/x-png') &&
                        ($file['type'] != 'image/gif') &&
                        ($file['type'] != 'image/jpeg')) {

                    echo json_encode([
                        'status' => 'error',
                        'message' => 'O recibo está em formato inválido. Faça o ulpoad somente de imagens (jpg, gif, png).',
                    ]);
                    exit;
                }
            }
        }

        $local = BASE_PATCH . '/data/arquivos/pagamentos';
        if (!file_exists($local)) {

            mkdir($local, 0755);
        }

        $local .= '/' . date('Y');
        if (!file_exists($local)) {

            mkdir($local, 0755);
        }

        $local .= '/' . date('m');
        if (!file_exists($local)) {

            mkdir($local, 0755);
        }

        $local .= '/' . date('d');
        if (!file_exists($local)) {

            mkdir($local, 0755);
        }

        $local .= '/' . $auth->id_usuario;
        if (!file_exists($local)) {

            mkdir($local, 0755);
        }

        //Apaga todos os arquivos na pasta
        $dirHandle = opendir($local);
        while ($file = readdir($dirHandle)) {

            if (!is_dir($file)) {

                unlink($local . '/' . $file);
            }
        }
        closedir($dirHandle);

        //Move os arquivos para o respectivo diretório
        $aFiles = $this->uploadFile($files, $local);

        $aRetorno = ['status' => 'error'];

        foreach ($aFiles as $file) {

            if (isset($file['tmp_name'])) {

                /**
                 * @var $pagamentoService \Pagamento\Service\PagamentoService
                 */
                $pagamentoService = $this->getServiceLocator()->get('Pagamento/Service/PagamentoService');
                $idComprovante = $pagamentoService->uploadPagamento($auth, str_replace(BASE_PATCH, '', $file['tmp_name']));
                $aRetorno = [
                    'id' => Cript::enc($idComprovante),
                    'status' => 'success'
                ];
            }
        }
        echo json_encode($aRetorno);
        exit;
    }

    /**
     * 
     * @return type
     */
    public function viewPayAction() {
        /* @var $pagamentoService \Pagamento\Service\PagamentoService */
        $pagamentoService = $this->getServiceLocator()->get('Pagamento/Service/PagamentoService');
        $pagamentoEntity = $pagamentoService->listUsuarioByIdPagamento(Cript::dec($this->getRequest()->getPost()->id));

        $view = new ViewModel([
            'pagamento' => $pagamentoEntity,
            'id' => $this->getRequest()->getPost()->id
        ]);
        return $view->setTerminal(true);
    }

    /**
     * 
     */
    public function downloadImgPayAction() {

        $id = Cript::dec($this->params('id'));
        /**
         * @var $pagamentoService \Pagamento\Service\PagamentoService
         */
        $pagamentoService = $this->getServiceLocator()->get('Pagamento/Service/PagamentoService');
        $pagamentoEntity = $pagamentoService->buscar($id);

        $fileData = file_get_contents(BASE_PATCH . $pagamentoEntity->getArComprovantePagamento());
        $size = getimagesize(BASE_PATCH . $pagamentoEntity->getArComprovantePagamento());

        header('Content-type: ' . $size['mime']);
        echo $fileData;
        exit;
    }

    /**
     * 
     */
    public function downloadVideoCourseAction() {

        ini_set('memory_limit', '256M');
        $id = Cript::dec($this->params('id'));
        /**
         * @var $pagamentoService \Curso\Service\CursoService
         */
        $cursoService = $this->getServiceLocator()->get('Curso/Service/CursoService');
        $cursoEntity = $cursoService->buscar($id);

        $stream = new \Estrutura\Helpers\VideoStream(BASE_PATCH . $cursoEntity->getArVideo());
        $stream->start();
        exit;
    }

    /**
     * 
     * @return type
     */
    public function accessCourseAction() {

        $id = Cript::dec($this->getRequest()->getPost()->id);

        $auth = $this->getServiceLocator()->get('AuthService')->getStorage()->read();
        $mensalidadeFoiPaga = $this->getServiceLocator()->get('Pagamento/Service/PagamentoService')->mensalidadeFoiPaga($auth);

        if ($mensalidadeFoiPaga) {

            $cursoService = new \Curso\Service\CursoService();
            $cursoEntity = $cursoService->buscar($id);

            $view = new ViewModel([
                'curso' => $cursoEntity
            ]);
            return $view->setTerminal(true);
        } else {


            $this->getServiceLocator()->get('Pagamento/Service/PagamentoService')->liberaPagamentoMensalidade($auth);

            $cursosService = new \McNetwork\Service\CursosService();
            $cursosService->setIdSituacao($this->getConfigList()['situacao_ativo']);
            $list = $cursosService->filtrarObjeto();

            $loginService = new \Login\Service\LoginService();
            $loginService->setIdUsuario($auth->id_usuario);
            $loginEntity = $loginService->filtrarObjeto()->current();

            $loginAtivo = FALSE;
            if ($loginEntity->getIdSituacao() == $this->getConfigList()['situacao_ativo']) {

                $loginAtivo = TRUE;
            }

            $dadosView = [
                'list' => $list,
                'controller' => $this->params('controller'),
                'atributos' => [],
                'configList' => $this->getConfigList(),
                'liberaPagamentoMensalidade' => $this->getServiceLocator()->get('Pagamento/Service/PagamentoService')->liberaPagamentoMensalidade($auth),
                'pagamento' => $this->getServiceLocator()->get('Pagamento/Service/PagamentoService')->listPagamento($auth),
                'loginAtivo' => $loginAtivo,
                'auth' => $auth,
            ];

            $view = new ViewModel($dadosView);
            $view->setTemplate('mc-network/cursos/course-information.phtml'); // path to phtml file under view folder
            return $view->setTerminal(true);
        }
    }

    /**
     * 
     */
    public function enviarCodigoAction() {

        $auth = $this->getServiceLocator()->get('AuthService')->getStorage()->read();

        $codigoPagamento = trim($this->getRequest()->getPost()->codigo);
        /**
         * @var $pagamentoService \Pagamento\Service\PagamentoService
         */
        $pagamentoService = $this->getServiceLocator()->get('Pagamento/Service/PagamentoService');
        $return = $pagamentoService->salvarCodigoPagamento($auth, $codigoPagamento);

        if ($return) {

            echo json_encode([
                'status' => 'success',
                'message' => '']);
        } else {

            echo json_encode([
                'status' => 'error',
                'message' => 'Código já utilizado em outro pagamento, favor preencher com um código válido.'
            ]);
        }
        exit;
    }

    /**
     * 
     */
    public function saveTestAction() {

        $cursosService = new \McNetwork\Service\CursosService();
        $usuarioService = new \Usuario\Service\UsuarioService();

        $auth = $this->getServiceLocator()->get('AuthService')->getStorage()->read();

        $usuarioWP = $usuarioService->getUsuarioWP($auth->em_email);

        $data = $this->getRequest()->getPost()->data;

        $idCurso = null;
        $idContent = null;

        if (!empty($data)) {

            foreach ($data as $key => $question) {

                if (isset($question['name']) && strcasecmp($question['name'], 'id') == 0) {

                    $id = Cript::dec($question['value']);

                    $explode = explode('_', $id);

                    $idCurso = $explode[0];
                    $idContent = $explode[1];

                    unset($data[$key]);
                    break;
                }
            }

            //Verifica se existe questões já respondidas e seta 'trash'
            $quizQuestions = unserialize($cursosService->getPostMeta($idContent, 'quiz_questions' . $usuarioWP['ID'])['meta_value']);

            if (isset($quizQuestions['ques']) && !empty($quizQuestions['ques'])) {

                foreach ($quizQuestions['ques'] as $ques) {

                    $cursosService->updateComments(
                            [
                        'comment_approved' => 'trash'
                            ]
                            , [
                        'mc_comments.comment_post_ID = ?' => $ques,
                        'mc_comments.user_id = ?' => $usuarioWP['ID']
                            ]
                    );
                }
            }

            $resultado = 0;
            
            
            echo '<pre>';
print_r($quizQuestions);
exit();
            
            //Salva as novas respostas
            foreach ($data as $key => $question) {

                $answer = Cript::dec(str_replace('answer', '', $question['name']));
                $commentId = $cursosService->inserirComments($answer, trim($question['value']), $usuarioWP['ID']);

                if (trim($question['value']) == trim($cursosService->getPostMeta($answer, 'vibe_question_answer')['meta_value'])) {

                    //$resultado = + $quizQuestions['marks'][array_search(, $haystack)$answer];
                    $cursosService->inserirCommentsMeta($commentId, 'marks', $resultado);
                }
            }

            //Calcula o resultado do questionário
            //Exclui resultado já computado anteriormente
            $cursosService->excluirPostMeta($idContent, $usuarioWP['ID']);
            //Salva resultado
            $cursosService->inserirPostMeta($idContent, $usuarioWP, $resultado);
            //Salva o progresso
            $progress = $cursosService->getUserMetaPost($usuarioWP['ID'], 'progress' . $idCurso);
            if (isset($progress['meta_value'])) {

                $qtdItens = 0;
                $listCurriculum = unserialize($cursosService->getPostMeta($idCurso, 'vibe_course_curriculum')['meta_value']);
                if (!empty($listCurriculum)) {
                    foreach ($listCurriculum as $curriculum) {
                        if (is_numeric($curriculum)) {
                            $qtdItens++;
                        }
                    }
                }

                $qtdQuiz = 0;
                if (!empty($listCurriculum)) {
                    foreach ($listCurriculum as $curriculum) {
                        if (is_numeric($curriculum)) {
                            $content = $cursosService->getPost($curriculum);
                            if ($content['post_type'] == 'quiz') {
                                $qtdQuiz++;
                                if ($idContent == $curriculum) {
                                    break;
                                }
                            }
                        }
                    }
                }


                echo '<pre>';
                print_r($qtdItens);
                echo '<pre>';
                print_r($qtdQuiz);
                exit();


                $cursosService->updateUserMetaPost(
                        [
                    'meta_value' => '9.09'
                        ], [
                    'user_id' => $usuarioWP['ID'],
                    'meta_key' => 'progress' . $idCurso
                        ]
                );
            } else {

                $cursosService->inserirUserMeta($usuarioWP['ID'], 'progress' . $idCurso, '9.09');
            }
        }

        if ($return) {

            echo json_encode([
                'status' => 'success',
                'message' => '']);
        } else {

            echo json_encode([
                'status' => 'error',
                'message' => 'Código já utilizado em outro pagamento, favor preencher com um código válido.'
            ]);
        }
        exit;
    }

}
