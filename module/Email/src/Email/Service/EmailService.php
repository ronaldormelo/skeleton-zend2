<?php

namespace Email\Service;

use \Email\Entity\EmailEntity as Entity;

class EmailService extends Entity {

    /**
     *
     * @var type 
     */
    protected $configList;

    /**
     * @param type $configList
     */
    public function setConfigList($configList) {
        $this->configList = $configList;
    }

}
