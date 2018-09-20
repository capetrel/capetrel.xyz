<?php
namespace App\Framework\Session;

interface SessionInterface
{

    /**
     * Récupère une information dans la session
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Ajoute une information en session
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void ;

    /**
     * Supprime une clé en session
     * @param string $key
     */
    public function delete(string $key): void ;
}
