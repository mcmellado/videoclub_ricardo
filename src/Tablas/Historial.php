<?php

namespace App\Tablas;

use PDO;

class Historial extends Modelo
{
    protected static string $tabla = 'historial';

    public $id;
    public $created_at;
    public $usuario_id;


    public function __construct(array $campos)
    {
        $this->id = $campos['id'];
        $this->created_at = $campos['created_at'];
        $this->usuario_id = $campos['usuario_id'];

    }

    public static function existe(int $id, ?PDO $pdo = null): bool
    {
        return static::obtener($id, $pdo) !== null;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getUsuarioId()
    {
        return $this->usuario_id;
    }

    public function getLineas(?PDO $pdo = null): array
    {
        $pdo = $pdo ?? conectar();

        $sent = $pdo->prepare('SELECT *
                                 FROM peliculas_historial
                                WHERE historial_id = :historial_id');
        $sent->execute([':historial_id' => $this->id]);
        $lineas = $sent->fetchAll(PDO::FETCH_ASSOC);
        $res = [];
        foreach ($lineas as $linea) {
            $res[] = new Linea($linea);
        }
        return $res;
    }

    public static function todos(
        array $where = [],
        array $execute = [],
        ?PDO $pdo = null
    ): array
    {
        $pdo = $pdo ?? conectar();

        $where = !empty($where)
            ? 'WHERE ' . implode(' AND ', $where)
            : '';
        $sent = $pdo->prepare("SELECT h.*
                                 FROM historial h
                                 JOIN peliculas_historial l
                                   ON l.historial_id = h.id
                                 JOIN peliculas p
                                   ON l.pelicula_id = p.id
                               $where
                             GROUP BY h.id");
        $sent->execute($execute);
        $filas = $sent->fetchAll(PDO::FETCH_ASSOC);
        $res = [];
        foreach ($filas as $fila) {
            $res[] = new static($fila);
        }
        return $res;
    }
}
