<?php
/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 24.09.15
 * Time: 2:01
 */

namespace Framework\Response;

class Response extends AbstractResponse
{
    public function __construct($content=null) {
        parent::__construct();
        $this->setResponseCode(200);
        $this->setContent($content);
    }

    public function __toString() {
        $result = $this->getContent();
        return $result.'';
    }
}
