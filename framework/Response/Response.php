<?php
/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 24.09.15
 * Time: 2:01
 */

namespace Framework\Response;

use Framework\Response\AbstractResponse;

class Response extends AbstractResponse
{
    public function __construct($content) {
        parent::__construct();
        $this->setResponseCode(200);
        $this->setContent($content);
    }
}
