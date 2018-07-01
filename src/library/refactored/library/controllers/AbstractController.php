<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/24/18
 * Time: 6:28 PM
 */

namespace Controllers;


abstract class AbstractController
{
    protected $request;

    public function __construct(Request $req)
    {
        $this->setRequest($req);
    }

    protected function setRequest (Request $req) {
        $this->request = $req;
    }

    protected function getRequest () {
        return $this->request;
    }
}