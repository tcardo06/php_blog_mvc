<?php

namespace TestProject\Engine;

class Util
{
    public function getView($sViewName)
    {
        $this->get($sViewName, 'View');
    }

    public function getModel($sModelName)
    {
        $this->get($sModelName, 'Model');
    }

    private function get($sFileName, $sType)
    {
        $sFullPath = ROOT_PATH . $sType . '/' . $sFileName . '.php';
        if (is_file($sFullPath))
            require $sFullPath;
        else
            exit('The "' . $sFullPath . '" file doesn\'t exist');
    }

    /**
     * Set variables for the template views.
     *
     * @return void
     */
    public function __set($sKey, $mVal)
    {
        $this->$sKey = $mVal;
    }
}
