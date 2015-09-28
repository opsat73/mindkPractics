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

    public function __construct($redirectURL)
    {
        parent::__construct();

        $this->setRedirectCode();
        $this->redirectURL = $redirectURL;
        $this->addHeader('Location: '.$this->redirectURL, true, $this->getResponseCode());
    }

    public function send() {
        $this->sendHeaders();
    }

    private function setRedirectCode($code = 302) {
        $this->setResponseCode($code);
        /**
         * todo need to refactor because this is DRAFT version;
         */
    }
}
?>