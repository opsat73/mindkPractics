<?php
/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 28.09.15
 * Time: 0:29
 */

namespace Framework\Response;

class AbstractResponse
{
    private $responseCode;
    private $headers;
    private $statusCode;
    private $responseStatus;
    private $content;
    private $cooks;
    private $protocolVersion;

    public function __construct() {
        $this->protocolVersion = 'html 1.1';
    }

    public function setResponseCode($code) {
        $this->responseCode = $code;
    }

    public function getResponseCode($code) {
        return $this->responseCode;
    }

    public function setStatus($status) {
        /**
         * todo utochnit;
         */
    }

    public function getStatus() {
        return $this->status;
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function addHeader($header) {
        /**
         * todo utochnit
         */
        $this->headers[] = $header;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function getContent() {
        return $this->content;
    }

    private function sendStatus() {
        $header = "HTTP/".$this->protocolVersion." ".$this->responseCode." ".$this->responseStatus;
        header($header, true, $this->responseCode);
    }

    public function sendHeaders() {
        $this->sendStatus();
        foreach ($this->headers as $head) {
            header($this->headers[$head],true, $this->statusCode);
        }
    }

    public function sendContent() {
        echo $this->content;
    }

    public function send() {
        $this->sendStatus();
        $this->sendHeaders();
        $this->sendContent();
    }

    public function setProtocolVersion($protocol) {
        $this->protocolVersion = $protocol;
    }
}
?>