<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/15/18
 * Time: 5:44 PM
 */

namespace Controllers;


class Response
{
    private $responseData;

    public function buildResponse (array $data) {
        $this->setResponseData(array_merge($this->getResponseData(), $data));
    }

    private function getResponseData () {
        return $this->responseData;
    }

    private function setResponseData (array $data) {
        $this->responseData = $data;
    }

    public function send () {
        echo json_encode($this->getResponseData());
    }
}