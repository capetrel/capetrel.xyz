<?php
namespace App\Framework\Session;

class ArraySession implements SessionInterface
{

    private $session = [];

    /**
     * Récupère une information dans la session
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        if (array_key_exists($key, $this->session)) {
            return $this->session[$key];
        }
        return $default;
    }

    /**
     * Ajoute une information en session
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void
    {
        $this->session[$key] = $value;
    }

    /**
     * Supprime une clé en session
     * @param string $key
     */
    public function delete(string $key): void
    {
        unset($this->session[$key]);
    }
}
