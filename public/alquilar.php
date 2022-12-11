<?php session_start() ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/output.css" rel="stylesheet">
    <title>Alquilar</title>
</head>

<body>
    <?php require '../vendor/autoload.php';

    if (!\App\Tablas\Usuario::esta_logueado()) {
        return redirigir_login();
    }

    $carrito = unserialize(carrito());

    if (obtener_post('_testigo') !== null) {
        // Crear historial
        $pdo = conectar();
        $usuario = \App\Tablas\Usuario::logueado();
        $usuario_id = $usuario->id;
        $pdo->beginTransaction();
        $sent = $pdo->prepare('INSERT INTO historial (usuario_id)
                               VALUES (:usuario_id)
                               RETURNING id');
        $sent->execute([':usuario_id' => $usuario_id]);
        $historial_id = $sent->fetchColumn();
        $lineas = $carrito->getLineas();
        $values = [];
        $execute = [':h' => $historial_id];
        $i = 1;

        foreach ($lineas as $id => $linea) {
            $values[] = "(:a$i, :h)";
            $execute[":a$i"] = $id;
        }

        $values = implode(', ', $values);
        $sent = $pdo->prepare("INSERT INTO peliculas_historial (pelicula_id, historial_id) 
                               VALUES $values");
        $sent->execute($execute);
        $pdo->commit();
        $_SESSION['exito'] = 'El historial se ha actualizado correctamente.';
        unset($_SESSION['carrito']);
        return volver();
    }

    ?>

    <div class="container mx-auto">
        <?php require '../src/_menu.php' ?>
        <div class="overflow-y-auto py-4 px-3 bg-gray-50 rounded dark:bg-gray-800">
            <table class="mx-auto text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <th scope="col" class="py-3 px-6">Código</th>
                    <th scope="col" class="py-3 px-6">Titulo</th>
                    <th scope="col" class="py-3 px-6">Genero</th>
                </thead>
                <tbody>
                    <?php $total = 0 ?>
                    <?php foreach ($carrito->getLineas() as $id => $linea) : ?>
                        <?php
                        $pelicula = $linea->getPelicula();
                        $codigo = $pelicula->getCodigo();
                        $titulo = $pelicula->getTitulo();
                        $genero = $pelicula->getGenero();
                        ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="py-4 px-6"><?= $pelicula->getCodigo() ?></td>
                            <td class="py-4 px-6"><?= $pelicula->getTitulo() ?></td>
                            <td class="py-4 px-6"><?= $pelicula->getGenero() ?></td>
                    <?php endforeach ?>
                </tbody>
            </table>
            <form action="" method="POST" class="mx-auto flex mt-4">
                <input type="hidden" name="_testigo" value="1">
                <button type="submit" href="" class="mx-auto focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-900">Alquilar</button>
            </form>
        </div>
    </div>
    <script src="/js/flowbite/flowbite.js"></script>
</body>

</html>
