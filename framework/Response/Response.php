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
    /**
     * construct default response with response code 200
     * @param null $content content of response
     */
    public function __construct($content=null) {
        parent::__construct();
        $this->setResponseCode(200);
        $this->setContent($content);
    }

    /**
     * method return response content as string
     * @return string response content
     */
    public function __toString() {
        $result = $this->getContent();
        return $result.'';
    }
}
