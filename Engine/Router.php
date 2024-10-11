<?php

namespace TestProject\Engine;

class Router
{
    public static function run(array $aParams)
    {
        $sNamespace = 'TestProject\Controller\\';
        $sDefCtrl = $sNamespace . 'Home'; // Default to Home controller
        $sCtrlPath = ROOT_PATH . 'Controller/';

        // Default to 'home' if no 'p' parameter (controller) is provided
        $sCtrl = !empty($aParams['ctrl']) ? ucfirst($aParams['ctrl']) : 'Home';

        if (is_file($sCtrlPath . $sCtrl . '.php')) {
            $sCtrl = $sNamespace . $sCtrl;
            $oCtrl = new $sCtrl;

            // Check if the action exists and is public
            if ((new \ReflectionClass($oCtrl))->hasMethod($aParams['act']) && (new \ReflectionMethod($oCtrl, $aParams['act']))->isPublic()) {
                call_user_func(array($oCtrl, $aParams['act']));
            } else {
                call_user_func(array($oCtrl, 'notFound'));
            }
        } else {
            // If the controller file doesn't exist, load the default (Home) controller
            call_user_func(array(new $sDefCtrl, 'notFound'));
        }
    }
}
