<?php

class FrontController extends FrontControllerCore
{
    public function init()
    {
        $module = Module::getInstanceByName('dbredirects');
        if ( is_object($module) && $module->active ) {
            $uri_var = $_SERVER['REQUEST_URI'];
            $redirect = DbRedirect::isRedirect($uri_var);

            if (isset($redirect['url_antigua']) && $uri_var == $redirect['url_antigua']) {
                switch ($redirect['type']) {
                    case '1':
                        Tools::redirect($redirect['url_nueva'], __PS_BASE_URI__, null, 'HTTP/1.1 301 Moved Permanently');
                        break;
                    case '2':
                        header("HTTP/1.1 410 Gone");
                        exit;
                        break;
                }
            }
        }

        parent::init();
    }
}