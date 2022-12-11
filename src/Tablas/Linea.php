<?php

namespace App\Tablas;

use App\Tablas\Pelicula;

class Linea extends Modelo
{
    private Pelicula $pelicula;

    public function __construct(array $campos)
    {
        $this->pelicula = Pelicula::obtener($campos['pelicula_id']);
    }

    public function getPelicula(): Pelicula
    {
        return $this->pelicula;
    }

}
