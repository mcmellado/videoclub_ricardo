<?php

namespace App\Generico;

use App\Tablas\Pelicula;

class Linea extends Modelo
{
    private Pelicula $pelicula;

    public function __construct(Pelicula $pelicula)
    {
        $this->setPelicula($pelicula);
    }

    public function getPelicula(): Pelicula
    {
        return $this->pelicula;
    }

    public function setPelicula(Pelicula $pelicula)
    {
        $this->pelicula = $pelicula;
    }
}
