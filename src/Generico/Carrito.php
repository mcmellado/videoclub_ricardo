<?php

namespace App\Generico;

use App\Tablas\Pelicula;
use ValueError;

class Carrito extends Modelo
{
    private array $lineas;

    public function __construct()
    {
        $this->lineas = [];
    }

    public function insertar($id)
    {
        if (!($pelicula = Pelicula::obtener($id))) {
            throw new ValueError('La pelicula no existe.');
        }

        if (isset($this->lineas[$id])) {
            throw new ValueError('La pelicula ya está alquilada.');
        } else {
            $this->lineas[$id] = new Linea($pelicula);
        }
    }

    public function eliminar($id)
    {
        if (isset($this->lineas[$id])) {
            unset($this->lineas[$id]);
        } else {
            throw new ValueError('Pelicula inexistente en el carrito');
        }
    }

    public function vacio(): bool
    {
        return empty($this->lineas);
    }

    public function getLineas(): array
    {
        return $this->lineas;
    }
}
