<?php

namespace Gerador\Service;

use Estrutura\Helpers\String;
use Gerador\Service\GeradorColuna;

class Gerador {

    protected $mapa;
    protected $controllers;

    public function __construct($mapa) {
        $this->mapa = $mapa;
    }

    public function gerar() {

        foreach ($this->mapa as $tabela => $campos) {

            $nameSpace = $this->getNameSpace($tabela);
            $this->criarPastas($nameSpace);
            $this->gerarTable($nameSpace, $tabela, $campos);
            $this->gerarEntitys($nameSpace, $tabela, $campos);
            $this->gerarServices($nameSpace, $tabela, $campos);
            $this->gerarForms($nameSpace, $tabela, $campos);
            $this->gerarControllers($nameSpace, $tabela, $campos);
            $this->gerarScript($nameSpace, $tabela, $campos);
        }

        foreach ($this->controllers as $nameSpace => $item) {
            $string = '';
            foreach ($item as $controller) {
                $lower = strtolower($nameSpace);
                $class = strtolower($controller);
                $string .= '            \'' . $lower . '-' . $class . '\' => \'' . $nameSpace . '\Controller\\' . ucfirst($class) . 'Controller\',' . "\r\n";
            }

            $this->gerarConfigNameSpace($nameSpace, $string);
        }

        //Limpa Cache
        $directory = BASE_PATCH . '/data/cache/';
        $files = array_diff(scandir($directory), array('.', '..'));

        foreach ($files as $file) {
            (is_dir("$directory/$file")) ? \Estrutura\Helpers\Arquivo::delTree("$directory/$file") : unlink("$directory/$file");
        }
    }

    public function gerarTable($nameSpace, $tabela, $campos) {

        $stringCampos = $this->gerarTabelaCampos($campos);
        $modelo = $this->getModelo('Table');
        $classe = $this->getClasseName($nameSpace, $tabela);

        $arquivo = $this->tratarModelo(['namespace' => String::underscore2Camelcase($nameSpace), 'classe' => $classe, 'tabela' => $tabela, 'stringCampos' => $stringCampos], $modelo);

        $location = $this->getLocation($nameSpace, 'Table', $classe . 'Table');
        $this->escreverArquivo($location['file'], $arquivo);
    }

    public function getNameSpace($tabela) {

        return ucfirst(strtolower($tabela));
    }

    private function gerarTabelaCampos($campos) {
        $string = '';
        /** @var $item \Gerador\Service\GeradorColuna */
        foreach ($campos as $item) {

            if (strcasecmp($item->getDataType(), 'int') == 0 &&
                    preg_match('/auto_increment/', $item->getExtra())) {

                $string .= "        '{$item->getColumnName()}'=>'id', \r\n";
            } else {

                $string .= "        '{$item->getColumnName()}'=>'{$item->getColumnName()}', \r\n";
            }
        }

        return $string;
    }

    private function gerarEntityCampos($campos) {
        $string = '';
        /** @var $item \Gerador\Service\GeradorColuna */
        foreach ($campos as $item) {

            if (strcasecmp($item->getDataType(), 'int') == 0 &&
                    preg_match('/auto_increment/', $item->getExtra())) {

                $string .= "        " . 'protected $' . "id; \r\n";
            } else {

                $string .= "        " . 'protected $' . "{$item->getColumnName()}; \r\n";
            }
        }
        return $string;
    }

    private function gerarEntityGetset($campos) {
        $string = '';
        /** @var $item \Gerador\Service\GeradorColuna */
//        foreach($campos as $item){
//            $campo = $this->tratarNome($item->getColumnName());
//            $string .= "        ".'public function get'."{$campo}()
//            {
//                return ".'$this->'."{$campo};
//            } \r\n";
//
//            $string .= "        ".'public function set'."{$campo}(".'$'."{$campo})
//            {
//                return ".'$this->'."{$campo} = ".'$'."{$campo};
//            } \r\n";
//        }

        return $string;
    }

    public function getModelo($modelo) {
        $file = __DIR__ . '/../Modelo/' . $modelo . '.modelo';
        return file_get_contents($file);
    }

    private function getClasseName($nameSpace, $tabela) {
        $nameSpace = strtolower($nameSpace);
        $tabela = strtolower($tabela);
        $principal = str_replace($nameSpace . '_', '', $tabela);
        return $this->tratarNome($principal);
    }

    private function tratarNome($principal) {
        $principal = strtolower($principal);
        $dados = explode('_', $principal);
        $string = '';
        foreach ($dados as $item) {
            $string .= ucfirst($item);
        }
        return $string;
    }

    private function gerarConfigNamespace($nameSpace, $controllers) {
        $folder = $this->getLocation($nameSpace);

        /// Module.php
        $modelo = $this->getModelo('Module');
        $module = $this->tratarModelo(
                [
            'nameSpace' => String::underscore2Camelcase($nameSpace)
                ], $modelo);
        $this->escreverArquivo($folder . '/Module.php', $module);

        /// module.config.php
        $folder = $folder . '/config';
        $modelo = $this->getModelo('Module.Config');
        $module = $this->tratarModelo(
                [
            'invokables' => $controllers,
            'namespace' => String::underscore2Camelcase($nameSpace),
            'route' => strtolower($nameSpace)
                ], $modelo);
        $this->escreverArquivo($folder . '/module.config.php', $module);
    }

    public function tratarModelo($array, $modelo) {
        $modeloArray = [];
        foreach ($array as $name => $item) {
            $modeloArray[] = '{{' . $name . '}}';
        }

        $tratado = str_replace($modeloArray, array_values($array), $modelo);
        return $tratado;
    }

    public function escreverArquivo($arquivo, $conteudo) {
        if (!file_exists($arquivo)) {
            $handle = fopen($arquivo, 'x+');
            fwrite($handle, $conteudo);
            fclose($handle);
        };
    }

    private function criarPastas($nameSpace) {
        $pastas = ['config',
            'view/' . str_replace('_', '-', strtolower($nameSpace)),
            'src/' . String::underscore2Camelcase($nameSpace) . '/Controller',
            'src/' . String::underscore2Camelcase($nameSpace) . '/Service',
            'src/' . String::underscore2Camelcase($nameSpace) . '/Table',
            'src/' . String::underscore2Camelcase($nameSpace) . '/Entity',
            'src/' . String::underscore2Camelcase($nameSpace) . '/Form'];

        $folder = $this->getLocation($nameSpace);

        foreach ($pastas as $pasta) {
            if (!file_exists($folder . '/' . $pasta)) {
                mkdir($folder . '/' . $pasta, 0777, true);
            }
        }
    }

    private function gerarEntitys($nameSpace, $tabela, $campos) {

        $stringCampos = $this->gerarEntityCampos($campos);
        $stringGetSet = $this->gerarEntityGetset($campos);

        $modelo = $this->getModelo('Entity');
        $classe = $this->getClasseName($nameSpace, $tabela);

        $arquivo = $this->tratarModelo(
                [
            'namespace' => String::underscore2Camelcase($nameSpace),
            'classe' => $classe,
            'campos' => $stringCampos,
            'getAndSet' => $stringGetSet
                ], $modelo);

        $location = $this->getLocation($nameSpace, 'Entity', $classe . 'Entity');
        $this->escreverArquivo($location['file'], $arquivo);
    }

    private function gerarServices($nameSpace, $tabela, $campos) {

        $modelo = $this->getModelo('Service');
        $classe = $this->getClasseName($nameSpace, $tabela);
        $column = $this->gerarColumn($campos);

        $arquivo = $this->tratarModelo(
                [
            'namespace' => String::underscore2Camelcase($nameSpace),
            'classe' => $classe,
            'tabela' => $tabela,
            'column' => $column,
                ], $modelo);

        $location = $this->getLocation($nameSpace, 'Service', $classe . 'Service');
        $this->escreverArquivo($location['file'], $arquivo);
    }

    private function gerarForms($nameSpace, $tabela, $campos) {
        $stringCampos = $this->gerarFormCampos($campos, $nameSpace);
        $modelo = $this->getModelo('Form');
        $classe = $this->getClasseName($nameSpace, $tabela);

        $arquivo = $this->tratarModelo([
            'namespace' => String::underscore2Camelcase($nameSpace),
            'classe' => $classe,
            'campos' => $stringCampos,
            'formName' => strtolower($classe . 'Form')], $modelo);

        $location = $this->getLocation($nameSpace, 'Form', $classe . 'Form');
        $this->escreverArquivo($location['file'], $arquivo);
    }

    /**
     * 
     * @param type $campos
     * @param type $nameSpace
     * @return string
     */
    private function gerarFormCampos($campos, $nameSpace) {

        $string = '';
        /** @var $item \Gerador\Service\GeradorColuna */
        foreach ($campos as $item) {

            $tipo = $this->getTypeForm($item);

            if ($tipo == 'hidden') {

                $item->setIsNullable('YES');
            }

            $name = $item->getColumnName();
            $label = ucfirst(trim(str_replace(['id_', '_'], ' ', $item->getColumnName())));

            $comment = json_decode($item->getColumnComment(), true);
            if (isset($comment['not_create']) && $comment['not_create'] == 'true') {

                continue;
            }

            if (isset($comment['label'])) {

                $label = $comment['label'];
            }

            if ($item->getIsNullable() == 'YES') {

                $nulo = 'false';
            } else {
                $label .= ' *';
                $nulo = 'true';
            }
            switch ($tipo) {

                case 'combo':
                    $service = str_ireplace('id_', '', $item->getColumnName());
                    $serviceTratado = $this->tratarNome($service);
                    $valorFk = 'nm_' . strtolower(String::camelcase2underscore($service));
                    if (isset($comment['valor_fk'])) {

                        $valorFk = $comment['valor_fk'];
                    }
                    $string .= "        " . '$objForm->' . $tipo . '("' . $name . '", \'\\' . $serviceTratado . '\Service\\' . $serviceTratado . 'Service\', \'id\', \'' . $valorFk . '\')->required(' . $nulo . ')->label("' . $label . '");' . "  \r\n";
                    break;

                case 'number':

                    $lenght = 1;
                    if ($item->getCharacterMaximumLength()) {

                        $lenght = $item->getCharacterMaximumLength();
                    } elseif ($item->getNumericPrecision()) {

                        $lenght = $item->getCharacterMaximumLength();
                    }

                    $string .= "        " . '$objForm->' . $tipo . '(\'' . $name . '\', ' . $lenght . ')->required(' . $nulo . ')->label(\'' . $label . '\');' . "  \r\n";
                    break;

                default:
                    if (strcasecmp($item->getDataType(), 'int') == 0 &&
                            preg_match('/auto_increment/', $item->getExtra())) {

                        $string .= "        " . '$objForm->' . $tipo . '("id")->required(' . $nulo . ')->label("Id");' . "  \r\n";
                    } else {

                        $string .= "        " . '$objForm->' . $tipo . '("' . $name . '")->required(' . $nulo . ')->label("' . $label . '");' . "  \r\n";
                    }
                    break;
            }
        }

        return $string;
    }

    private function getTypeForm($item) {

        switch ($item->getDataType()) {
            case 'int':
                if (preg_match('/auto_increment/', $item->getExtra())) {
                    $tipo = 'hidden';
                } else {
                    $tipo = 'combo';
                }
                break;
            case 'float':
                $tipo = 'money';
                break;
            case 'text':
                $tipo = 'textarea';
                break;
            case 'datetime':
                $tipo = 'date';
                break;
            default:
                switch (true) {
                    case (preg_match('/nu_cpf/', $item->getColumnName())) :

                        $tipo = 'cpf';
                        break;
                    case (preg_match('/nu_cnpj/', $item->getColumnName())) :

                        $tipo = 'cnpj';
                        break;
                    case (preg_match('/em_/', $item->getColumnName())) :

                        $tipo = 'email';
                        break;
                    case (preg_match('/pw_/', $item->getColumnName())) :

                        $tipo = 'password';
                        break;
                    case (preg_match('/nr_telefone/', $item->getColumnName())) :

                        $tipo = 'telefone';
                        break;
                    case (preg_match('/nu_/', $item->getColumnName())) :

                        $tipo = 'number';
                        break;
                    case (preg_match('/nr_cep/', $item->getColumnName())) :

                        $tipo = 'cep';
                        break;
                    default:
                        $tipo = 'text';
                        break;
                }
                break;
        }

        return $tipo;
    }

    private function gerarControllers($nameSpace, $tabela, $campos) {

        $modelo = $this->getModelo('Controller');
        $classe = $this->getClasseName($nameSpace, $tabela);
        $filter = $this->gerarFilter($campos);
        $controller = strtolower(str_replace('_', '-', $nameSpace));

        $arquivo = $this->tratarModelo([
            'namespace' => String::underscore2Camelcase($nameSpace),
            'classe' => $classe,
            'filter' => $filter,
                ], $modelo);

        $location = $this->getLocation($nameSpace, 'Controller', $classe . 'Controller');
        $this->escreverArquivo($location['file'], $arquivo);

        //VIEW
        $location = $this->folderView($nameSpace, $classe);

        /// Index
        $modelo = $this->getModelo('Index');

        $arquivo = $this->tratarModelo([
            'controller' => $controller,
            'nome' => str_replace('_', ' ', $nameSpace),
                ], $modelo);

        $this->escreverArquivo($location . '/index.phtml', $arquivo);

        /// Index Pagination
        $qtdCampos = $this->getQtdCampos($campos);
        $campoAcoes = $qtdCampos - 1;
        $modelo = $this->getModelo('IndexPagination');
        $th = $this->gerarIndexTh($campos);
        $td = $this->gerarIndexTd($campos, $controller);

        $arquivo = $this->tratarModelo([
            'th' => $th,
            'td' => $td,
            'namespace' => String::underscore2Camelcase($nameSpace),
            'classe' => $classe,
            'controller' => $controller,
            'qtdCampos' => $qtdCampos,
                ], $modelo);

        $this->escreverArquivo($location . '/index-pagination.phtml', $arquivo);

        /// Cadastro
        $modelo = $this->getModelo('Cadastro');
        $stringCampos = $this->gerarCampos($campos);
        $arquivo = $this->tratarModelo([
            'campos' => $stringCampos,
            'namespace' => String::underscore2Camelcase($nameSpace),
            'classe' => $classe,
            'controller' => $controller,
            'route' => strtolower($nameSpace)], $modelo);

        $this->escreverArquivo($location . '/cadastro.phtml', $arquivo);

        $this->controllers[$nameSpace][] = $classe;

        /// Edita
        $modelo = $this->getModelo('Edita');
        $stringCampos = $this->gerarCampos($campos);
        $arquivo = $this->tratarModelo([
            'campos' => $stringCampos,
            'namespace' => String::underscore2Camelcase($nameSpace),
            'classe' => $classe,
            'controller' => $controller,
            'route' => strtolower($nameSpace)], $modelo);

        $this->escreverArquivo($location . '/edita.phtml', $arquivo);

        $this->controllers[$nameSpace][] = $classe;

        //JS
        $location = $this->folderJS($nameSpace, $classe);

        /// Index.js
        $modelo = $this->getModelo('Index.Js');

        $arquivo = $this->tratarModelo([
            'namespace' => String::underscore2Camelcase($nameSpace),
            'classe' => $classe,
            'controller' => $controller,
                ], $modelo);

        $this->escreverArquivo($location . '/index.js', $arquivo);
        /// Cadastro.js
        $modelo = $this->getModelo('Cadastro.Js');

        $arquivo = $this->tratarModelo([
            'ScriptJs' => $this->gerarCadastroJS($campos)
                ], $modelo);
        $this->escreverArquivo($location . '/cadastro.js', $arquivo);

        /// Edita.js
        $modelo = $this->getModelo('Edita.Js');

        $arquivo = $this->tratarModelo([
            'ScriptJs' => $this->gerarCadastroJS($campos)
                ], $modelo);

        $this->escreverArquivo($location . '/edita.js', $arquivo);

        /// IndexPagination.js
        $modelo = $this->getModelo('IndexPagination.Js');

        $arquivo = $this->tratarModelo([
            'namespace' => String::underscore2Camelcase($nameSpace),
            'classe' => $classe,
            'controller' => $controller,
            'campoAcoes' => $campoAcoes,
                ], $modelo);

        $this->escreverArquivo($location . '/index-pagination.js', $arquivo);
    }

    private function gerarScript($nameSpace, $tabela, $campos) {

        $service = new \Gerador\Service\GeradorTabela();
        $service->setConfigDb();
        $adapter = $service->getAdapter();

        $sql = "SELECT id_controller FROM controller WHERE nm_controller = '" . $tabela . "'";
        $result = $service->toArrayResult($adapter->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE));

        if (count($result) == 0) {

            $sql1 = "INSERT INTO controller (nm_controller) VALUES ('" . $tabela . "')";
            $adapter->query($sql1, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

            $sql2 = "SELECT id_controller FROM controller WHERE nm_controller = '" . $tabela . "'";
            $result = $service->toArrayResult($adapter->query($sql2, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE));
        }

        $controller = current($result);

        for ($i = 1; $i <= 6; $i++) {

            $sql3 = "SELECT id_perfil_controller_action FROM perfil_controller_action WHERE id_controller = '" . $controller['id_controller'] . "' AND id_action = '" . $i . "'";

            $perfilControllerAction = $adapter->query($sql3, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

            if (count($perfilControllerAction) == 0) {
                $sql4 = "INSERT INTO perfil_controller_action (
                    id_controller,
                    id_action,
                    id_perfil
                ) 
                VALUES
                (" . $controller['id_controller'] . ", $i, 1)";

                $adapter->query($sql4, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
            }
        }
        $modelo = $this->getModelo('Script');

        $arquivo = $this->tratarModelo([
            'tabela' => $tabela,
            'id_controller' => $controller['id_controller'],
                ], $modelo);

        $this->escreverArquivo(BASE_PATCH . '/data/db/' . $tabela . '.sql', $arquivo);
    }

    private function folderView($namespace, $classe) {

        $location = $this->getLocation($namespace);

        $view = $location . '/view/' . str_replace('_', '-', strtolower($namespace . '/' . String::camelcase2underscore($classe)));

        if (!file_exists($view)) {

            mkdir($view, 0777, true);
        }

        return $view;
    }

    private
            function folderJS($namespace, $classe) {

        $location = BASE_PATCH . '/public/assets/js/' . str_replace('_', '-', strtolower($namespace . '/' . String::camelcase2underscore($classe)));


        if (!file_exists($location)) {

            mkdir($location, 0777, true);
        } return $location;
    }

    private
            function getLocation($nameSpace, $tipo = false, $classe = '') {

        $gerador = require( BASE_PATCH . '/config/autoload/gerador.php' );

        if (!$tipo) {

            return $gerador['location'] . String::underscore2Camelcase($nameSpace);
        } $caminho = $gerador['location'] . String::underscore2Camelcase($nameSpace) . '/src/' . String::underscore2Camelcase($nameSpace) . '/' . $tipo . '/';
        $dados = [
            'file' => $caminho . $classe . '.php',
            'folder' => $caminho
        ];

        return $dados;
    }

    private function gerarCampos($campos) {
        $string = '';
        /** @var $item \Gerador\Service\GeradorColuna */
        foreach ($campos as $item) {

            $comment = json_decode($item->getColumnComment(), true);
            if (isset($comment[
                            'not_create']) && $comment['not_create'] == 'true') {

                continue;
            }

            $tipo = $this->getTypeForm($item);
            switch ($tipo) {
                case 'hidden':
                    continue;
                    break;
                case 'date':
                    $string .= '
                    
                    <?php
                    if ($form->get(\'' . $item->getColumnName() . '\')->getValue()) {
                        $form->get(\'' . $item->getColumnName() . '\')->setValue((new \DateTime($form->get(\'' . $item->getColumnName() . '\')->getValue()))->format(\'d/m/Y\'));
                    }
                    ?>
                    <div class="form-group">
                        <div class="col-md-7">
                            <?= $this->formLabel($form->get(\'' . $item->getColumnName() . '\')) ?>
                            <div class="input-group" id="datetimepicker">
                                <?= $this->formInput($form->get(\'' . $item->getColumnName() . '\')) ?>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>                            
                            </div>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>';
                    break;

                default:
                    $string .= '<div class="form-group">
                        <div class="col-md-7">
                            <?=$this->formRow($form->get(\'' . $item->getColumnName() . '\'))?>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>';
                    break;
            }
        }

        return $string;
    }

    private
            function gerarCadastroJS($campos) {
        $string = '';

        $control = [];
        /** @var $item \Gerad or\Service\GeradorColuna */
        foreach ($campos as $item) {

            $tipo = $this->getTypeForm($item);
            switch ($tipo) {
                case 'date':

                    if (!array_key_exists('date', $control)) {

                        $control['date'] = true;

                        $string .= 'moment.locale(\'en\', {
                            days: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado", "Domingo"],
                            daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb", "Dom"],
                            weekdays: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb", "Dom"],
                            daysMin: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa", "Do"],
                            weekdaysShort: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa", "Do"],
                            weekdaysMin: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa", "Do"],
                            months: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
                            monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
                            today: "Hoje"
                        });

                        $(\'#datetimepicker\').datetimepicker({
                            pickTime: false
                        });' . "\r\n";
                    }
                    break;
                case 'password':

                    if (!array_key_exists('password', $control)) {
                        $control['password'] = true;

                        $string .= 'var optionsPwstrength = {};
                        optionsPwstrength.common = {
                            minChar: 8,
                            onKeyUp: function (evt, data) {
                                if (!$(evt.target).is(":focus")) {
                                    if (data.verdictLevel == 0) {

                                        $(evt.target).val(\'\');
                                        $(evt.target).pwstrength("forceUpdate");
                                    }
                                }
                            }
                        };
                        optionsPwstrength.ui = {
                            showStatus: true,
                            verdicts: ["fraca", "fraca", "media", "media", "forte"],
                            showVerdicts: true,
                            showVerdictsInsideProgressBar: true
                        };
                        optionsPwstrength.rules = {
                            activated: {
                                wordThreeNumbers: false,
                                wordSequences: false
                            }
                        };
                        $(\'#' . $item->getColumnName() . '\').pwstrength(optionsPwstrength);' . "\r\n";
                    }
                    break;
            }
        }

        return $string;
    }

    private
            function gerarIndexTh($campos) {
        $string = '';
        /** @var $item \Gerador\Service\GeradorColuna */
        foreach ($campos as $item) {

            if (strcasecmp($item->getDataType(), 'int') != 0 && !( preg_match('/auto_increment/', $item->getExtra()))) {

                $comment = $label = json_decode($item->getColumnComment(), true);
                if (isset($comment['label'])) {

                    $label = $comment['label'];
                }

                $html = "<th class='text-center'>{$label}</th> \r\n                                ";
                $string .= $html;
            }
        }

        return $string;
    }

    private
            function gerarFilter($campos) {

        $count = 0;

        $string = '$camposFilter = [' . "\r\n";
        /** @var $item \Gerador\Service\GeradorColuna */
        foreach ($campos as $item) {

            if (strcasecmp($item->getDataType(), 'int') != 0 && !( preg_match('/auto_increment/', $item->getExtra()))) {

                $tipo = $this->getTypeForm($item);
                switch ($tipo) {
                    case 'date':

                        $string .= "'" . $count . "' => [" . "\r\n";
                        $string .= "    'filter' => \"DATE_FORMAT(" . $item->getColumnName() . ",'%d/%m/%Y') LIKE ? \"" . "\r\n";
                        $string .= "]," . "\r\n";
                        break;

                    case 'cpf':
                        $string .= "'" . $count . "' => [" . "\r\n";
                        $string .= "    'filter' => \"" . $item->getColumnName() . " LIKE ? \"," . "\r\n";
                        $string .= "    'mascara' => '\Estrutura\Helpers\Cpf::cpfFilter(\$value)'," . "\r\n";
                        $string .= "]," . "\r\n";
                        break;
                    case 'cnpj':
                        $string .= "'" . $count . "' => [" . "\r\n";
                        $string .= "    'filter' => \"" . $item->getColumnName() . " LIKE ? \"," . "\r\n";
                        $string .= "    'mascara' => '\Estrutura\Helpers\Cnpj::cnpjFilter(\$value)'," . "\r\n";
                        $string .= "]," . "\r\n";
                        break;
                    case 'cep':
                        $string .= "'" . $count . "' => [" . "\r\n";
                        $string .= "    'filter' => \"" . $item->getColumnName() . " LIKE ? \"," . "\r\n";
                        $string .= "    'mascara' => '\Estrutura\Helpers\Cep::cepFilter(\$value)'," . "\r\n";
                        $string .= "]," . "\r\n";
                        break;
                    case 'telefone':
                        $string .= "'" . $count . "' => [" . "\r\n";
                        $string .= "    'filter' => \"" . $item->getColumnName() . " LIKE ? \"," . "\r\n";
                        $string .= "    'mascara' => '\Estrutura\Helpers\Telefone::telefoneFilter(\$value)'," . "\r\n";
                        $string .= "]," . "\r\n";
                        break;
                    case 'money':
                        $string .= "'" . $count . "' => [" . "\r\n";
                        $string .= "    'filter' => \"" . $item->getColumnName() . " LIKE ? \"," . "\r\n";
                        $string .= "    'mascara' => '\Estrutura\Helpers\Valor::valorfilter(\$value)'," . "\r\n";
                        $string .= "]," . "\r\n";
                        break;
                    default:
                        $string .= "'" . $count . "' => [" . "\r\n";
                        $string .= "    'filter' => \"LOWER(" . $item->getColumnName() . ") LIKE ? \"," . "\r\n";
                        $string .= "    'mascara' => 'strtolower(\$value)'," . "\r\n";
                        $string .= "]," . "\r\n";
                        break;
                }



                $count++;
            }
        }

        $string .= '    \'' . $count . '\' => NULL,' . "\r\n"
                . '];';

        return $string;
    }

    private
            function gerarColumn($campos) {

        $string = '';
        /** @var $item \Gerador\Service\GeradorColuna */
        foreach ($campos as $item) {

            $string .= "'" . $item->getColumnName() . "'," . "\r\n";
        }
        return $string;
    }

    private function gerarIndexTd($campos, $controller) {

        $string = '';
        /** @var $item \Gerador\Service\GeradorColuna */
        foreach ($campos as $item) {

            if (strcasecmp($item->getDataType(), 'int') != 0 && !( preg_match('/auto_increment/', $item->getExtra()))) {

                $tipo = $this->getTypeForm($item);
                switch ($tipo) {
                    case 'hidden':
                        continue;
                        break;
                    case 'date':
                        $string .= '<td class="text-center"><?= $pagina[\'' . $item->getColumnName() . '\'] ? (new \DateTime($pagina[\'' . $item->getColumnName() . '\']))->format(\'d/m/Y\') : null ?></td>' . "\r\n" . '                                ';
                        break;
                    case 'cpf':
                        $string .= '<td class="text-center"><?= $pagina[\'' . $item->getColumnName() . '\'] ? \Estrutura\Helpers\Cpf::cpfMask($pagina[\'' . $item->getColumnName() . '\']) : null ?></td>' . "\r\n" . '                                ';
                        break;
                    case 'cnpj':
                        $string .= '<td class="text-center"><?= $pagina[\'' . $item->getColumnName() . '\'] ? \Estrutura\Helpers\Cnpj::cnpjMask($pagina[\'' . $item->getColumnName() . '\']) : null ?></td>' . "\r\n" . '                                ';
                        break;
                    case 'cep':
                        $string .= '<td class="text-center"><?= $pagina[\'' . $item->getColumnName() . '\'] ? \Estrutura\Helpers\Cep::cepMask($pagina[\'' . $item->getColumnName() . '\']) : null ?></td>' . "\r\n" . '                                ';
                        break;
                    case 'telefone':
                        $string .= '<td class="text-center"><?= $pagina[\'' . $item->getColumnName() . '\'] ? \Estrutura\Helpers\Telefone::telefoneMask($pagina[\'' . $item->getColumnName() . '\']) : null ?></td>' . "\r\n" . '                                ';
                        break;
                    case 'money':
                        $string .= '<td class="text-center"><?= $pagina[\'' . $item->getColumnName() . '\'] ? \Estrutura\Helpers\Valor::reais($pagina[\'' . $item->getColumnName() . '\']) : null ?></td>' . "\r\n" . '                                ';
                        break;
                    default:
                        $string .= '<td class="text-center"><?= $pagina[\'' . $item->getColumnName() . '\']?></td>' . "\r\n" . '                                ';
                        break;
                }
            }
        }

        foreach ($campos as $item) {
            if (strcasecmp($item->getDataType(), 'int') == 0 && ( preg_match('/auto_increment/', $item->getExtra()))) {

                $string .= '<td class="text-center">' .
                        '<span class="btn-group ' . $controller . '-acoes-group" style="width: 140px;">' .
                        '<?php 
                        if ($this->layout()->acl->hasResource($controller . \'/edita\') && 
                        $this->layout()->acl->isAllowed($this->layout()->role, $controller . \'/edita\')) { 
                        ?>' .
                        '<?= \Estrutura\Service\HtmlHelper::botaoAlterar(' .
                        '$this->url(\'navegacao\', array(\'controller\' => $controller, \'action\' => \'edita\', \'id\' => Estrutura\Helpers\Cript::enc($pagina[\'' . $item->getColumnName() . '\'])))) ?>' .
                        '<?php 
                        } 
                        ?>' .
                        '<?php 
                        if ($this->layout()->acl->hasResource($controller . \'/excluir\') && 
                        $this->layout()->acl->isAllowed($this->layout()->role, $controller . \'/excluir\')) { 
                        ?>' .
                        '<?= \Estrutura\Service\HtmlHelper::botaoExcluirConfirm(' .
                        'Estrutura\Helpers\Cript::enc($pagina[\'' . $item->getColumnName() . '\'])) ?>' .
                        '<?php 
                        } 
                        ?>' .
                        '</span>' .
                        '</td>';
            }
        }

        return $string;
    }

    private
            function getQtdCampos($campos) {

        $qtdCampos = 1;
        /** @var $item \Gerador\Service\GeradorColuna */
        foreach ($campos as $item) {

            if (strcasecmp($item->getDataType(), 'int') != 0 && !( preg_match('/auto_increment/', $item->getExtra()))) {

                $qtdCampos++;
            }
        }

        return $qtdCampos;
    }

}
