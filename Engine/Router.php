<?php

namespace TestProject\Engine;

class Router
{
    public static function run(array $aParams)
    {
        $sNamespace = 'TestProject\Controller\\';
        $sDefCtrl = $sNamespace . 'Home';
        $sCtrlPath = ROOT_PATH . 'Controller/';
        $sCtrl = ucfirst($aParams['ctrl']);

        // If no controller is specified, default to the Home controller
        if (empty($sCtrl) || !is_file($sCtrlPath . $sCtrl . '.php')) {
            $sCtrl = $sNamespace . 'Home';
            $oCtrl = new $sCtrl;
            call_user_func(array($oCtrl, 'index')); // Call the index method of the Home controller
        } else {
            $sCtrl = $sNamespace . $sCtrl;
            $oCtrl = new $sCtrl;

            if ((new \ReflectionClass($oCtrl))->hasMethod($aParams['act']) && (new \ReflectionMethod($oCtrl, $aParams['act']))->isPublic()) {
                call_user_func(array($oCtrl, $aParams['act']));
            } else {
                call_user_func(array($oCtrl, 'notFound'));
            }
        }
    }
}
