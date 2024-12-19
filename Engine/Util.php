<?php

namespace TestProject\Engine;

class Util
{
    private array $properties = []; // For dynamic properties

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

    // Dynamic properties handling
    public function __set(string $key, $value): void
    {
        $this->properties[$key] = $value;
    }

    public function __get(string $key)
    {
        return $this->properties[$key] ?? null;
    }

    // Session helpers
    public function setSessionData(array $data): void
    {
        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }

    public function getSessionData(): array
    {
        return [
            'isLogged' => $_SESSION['is_logged'] ?? false,
            'userName' => $_SESSION['name'] ?? 'Utilisateur',
            'role' => $_SESSION['role'] ?? null,
        ];
    }

    public function isLogged(): bool
    {
        return !empty($_SESSION['is_logged']);
    }

    public function getRole(): ?string
    {
        return $_SESSION['role'] ?? null;
    }

    public function getUserName(): ?string
    {
        return $_SESSION['name'] ?? 'Utilisateur';
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_unset();
        session_destroy();
    }
}
