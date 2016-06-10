<?php

namespace Auth\Table;
 
use Zend\Authentication\Storage;
 
class MyAuth extends Storage\Session
{
    public function setRememberMe($rememberMe = 0, $time = 30)
    {
         if ($rememberMe == 1) {
             
             $this->session->getManager()->rememberMe($time);
         }
    }
     
    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
    } 
}