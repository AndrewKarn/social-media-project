<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/18/18
 * Time: 9:23 PM
 */

namespace Views;
use Shared\Constants;

abstract class BaseView
{
    const TEMPLATES_DIR = __DIR__ . '/../templates/';
    const HEADER = self::TEMPLATES_DIR . 'header.php';
    const SCRIPTS = self::TEMPLATES_DIR . 'shared-scripts.php';

    protected $template;
    protected $title;

    public function render() {
        echo $this->getTemplate();
        die();
    }

    protected function setTitle($title) {
        $this->title = $title;
    }

    protected function getTitle() {
        return $this->title;
    }

    public function getTemplate() {
        return $this->template;
    }

    protected function getHeader() {
        ob_start();
            include self::HEADER;
        return ob_get_clean();
    }

    protected function getSharedScripts() {
        ob_start();
            include self::SCRIPTS;
        return ob_get_clean();
    }

    protected function setTemplate($name) {
        $file = self::TEMPLATES_DIR . $name . '.php';
        if (!file_exists($file)) {
            error_log($file . ' not found.');
            die();
        }
        ob_start();
            include $file;
        $this->template = ob_get_clean();
    }
}