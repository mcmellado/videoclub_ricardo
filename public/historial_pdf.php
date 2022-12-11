<?php
session_start();

use App\Tablas\Historial;

require '../vendor/autoload.php';

if (!($usuario = \App\Tablas\Usuario::logueado())) {
    return volver();
}

$id = obtener_get('id');

if (!isset($id)) {
    return volver();
}

$pdo = conectar();

$historial = Historial::obtener($id, $pdo);

if (!isset($historial)) {
    return volver();
}

if ($historial->getUsuarioId() != $usuario->id) {
    return volver();
}

$filas_tabla = '';
$total = 0;

foreach ($historial->getLineas($pdo) as $linea) {
    $pelicula = $linea->getPelicula();
    $codigo = $pelicula->getCodigo();
    $genero = $pelicula->getGenero();
    $titulo = $pelicula->getTitulo();
    $filas_tabla .= <<<EOF
        <tr>
            <td>$codigo</td>
            <td>$titulo</td>
            <td>$genero</td>

        </tr>
    EOF;
}


$res = <<<EOT
<p>historial número: {$historial->id}</p>

<table border="1" class="font-sans mx-auto">
    <tr>
        <th>Código</th>
        <th>Título</th>
        <th>Género</th>
    </tr>
    <tbody>
        $filas_tabla
    </tbody>
</table>

<p>Total: $total</p>
EOT;

// Create an instance of the class:
$mpdf = new \Mpdf\Mpdf();

// Write some HTML code:
$mpdf->WriteHTML(file_get_contents('css/output.css'), \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($res, \Mpdf\HTMLParserMode::HTML_BODY);

// Output a PDF file directly to the browser
$mpdf->Output();
