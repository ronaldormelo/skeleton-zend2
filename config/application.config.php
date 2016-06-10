<?php

return array(
    'modules' => array(
        'Admin',
        'Application',
        'Auth',
        'Action',
        
        
//'Usuario',
        
        'Estrutura',
        'Gerador',        
        'Banco',
        'Cidade',
        'CompactAsset',
        'Comunicado',
        'Config',
        'Contrato',
        'ContaBancaria',
        'DOMPDFModule',
        'EdpSuperluminal',
        'Email',
        'Empresa',
        'Endereco',
        'EsqueciSenha',
        'Estado',
        'EstadoCivil',
        
        'Gerador',
        
        'Login',
        'Perfil',
        'PhpBoletoZf2',
        'Sexo',
        'Situacao',
        'SituacaoEmpresaContrato',
        'SituacaoUsuario',
        'Telefone',
        'TipoConta',        
        'TipoComunicado',        
        'TipoTelefone',
        'TipoUsuario',
        'Usuario',
        
    ),
    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor',
        ),
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,' . APPLICATION_ENV . '}.php'            
        ),
    ) 
);
