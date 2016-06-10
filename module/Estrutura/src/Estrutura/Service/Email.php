<?php

namespace Estrutura\Service;


class Email{

    public function enviar($para, $assunto, $mensagem){
        $sendgrid = new \SendGrid('alexmedeiros', "alex2011");
        $email    = new \SendGrid\Email();

        $email->addTo($para)->
            setFrom('email@teste.com')->
            setSubject($assunto)->
            setText($mensagem)->
            setHtml("<strong>$mensagem</strong>");


        return $sendgrid->send($email);
    }

} 