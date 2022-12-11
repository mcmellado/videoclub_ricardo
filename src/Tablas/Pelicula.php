<?php

namespace App\Tablas;

use PDO;

class Pelicula extends Modelo
{
    protected static string $tabla = 'peliculas';

    public $id;
    private $codigo;
    private $titulo;
    private $genero;
    private $alquilado;

    public function __construct(array $campos)
    {
        $this->id = $campos['id'];
        $this->codigo = $campos['codigo'];
        $this->titulo = $campos['titulo'];
        $this->genero = $campos['genero'];
        $this->$alquilado = false;
    }

    public static function existe(int $id, ?PDO $pdo = null): bool
    {
        return static::obtener($id, $pdo) !== null;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function getGenero()
    {
        return $this->genero;
    }

    public function alquilar()
    {
        $this->alquilado = true;
    }

     public function devolver()
    {
        $this->alquilado = false;
    }

    public function GetAlquilar()
    {
        return $this->alquilar;
    }
}

