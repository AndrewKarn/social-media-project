<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 6/17/18
 * Time: 8:49 PM
 */

namespace Views;


class SingleBoxView extends BaseView
{
    const SINGLE_BOX_STYLE = ['single-box'];

    private $content;

    public function __construct(array $content)
    {
        $this->setTitle($content['title']);
        $this->setContent($content['content']);
        $this->setStyles(self::SINGLE_BOX_STYLE);
        if (isset($content['scripts'])) {
            $this->setUniqueScripts($content['scripts']);
        } else {
            $this->setUniqueScripts();
        }
        $this->setTemplate('one-box-view');
    }

    private function setContent($content) {
        $this->content = $content;
    }

    public function getContent() {
        return $this->content;
    }
}