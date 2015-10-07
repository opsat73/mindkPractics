<?php
/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 24.09.15
 * Time: 18:30
 */

namespace Framework\Response;


class JsonResponse extends AbstractResponse
{
    /**
     * construct hson response whit content type "application/json"
     * @param $content content which will be converted to json fomrat
     */
    public function __construct($content) {
        $this->addHeader('Content-Type: application/json');
        $this->setContent(json_encode($content));
    }
}