<?php
/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 24.09.15
 * Time: 18:30
 */

namespace Framework\Response;

use Framework\Response\AbstractResponse;

class ResponseRedirect extends AbstractResponse
{
    private $redirectURL;

    /**
     * construcn redirect response whith setted Location in header and response code 301 for defauls
     * @param $redirectURL URL for redirectiong
     */
    public function __construct($redirectURL)
    {
        parent::__construct();

        $this->setRedirectCode();
        $this->redirectURL = $redirectURL;
        $this->addHeader('Location: '.$this->redirectURL, true, $this->getResponseCode());
    }

    /**
     * send redirect response
     */
    public function send() {
        $this->sendHeaders();
    }

    /**
     * method for setting response code
     * @param int $code code of response. 301 as defaulst
     */
    public function setRedirectCode($code = 301) {
        $this->setResponseCode($code);
    }
}
?>