<?php

namespace TestProject\Engine;

class Util
{
    private array $properties = []; // Store dynamic properties

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
        if (is_file($sFullPath)) {
            require $sFullPath;
        } else {
            throw new \Exception('The "' . $sFullPath . '" file doesn\'t exist');
        }
    }

    /**
     * Set a dynamic property.
     */
    public function __set(string $key, $value): void
    {
        $this->properties[$key] = $value;
    }

    /**
     * Get a dynamic property.
     */
    public function __get(string $key)
    {
        return $this->properties[$key] ?? null; // Return null if property does not exist
    }

    /**
     * Check if a dynamic property is set.
     */
    public function __isset(string $key): bool
    {
        return isset($this->properties[$key]);
    }

    /**
     * Unset a dynamic property.
     */
    public function __unset(string $key): void
    {
        unset($this->properties[$key]);
    }
}
