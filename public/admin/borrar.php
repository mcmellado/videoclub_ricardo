<?php
session_start();

require '../../src/auxiliar.php';

$id = obtener_post('id');

// if (!comprobar_csrf()) {
//     return volver_admin();
// }

if (!isset($id)) {
    return volver_admin();
}

// TODO: Validar id
// Comprobar si el departamento tiene empleados

$pdo = conectar();
$sent = $pdo->prepare("DELETE FROM peliculas WHERE id = :id");
$sent->execute([':id' => $id]);

$_SESSION['exito'] = 'La película se ha borrado correctamente.';

volver_admin();
