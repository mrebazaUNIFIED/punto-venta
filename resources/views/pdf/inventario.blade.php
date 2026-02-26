<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inventario</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 5px;
            text-align: center;
        }
    </style>
</head>

<body>
    <h2 style="text-align: center;">Reporte de Inventario</h2>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Producto</th>
                <th>Stock</th>
                <th>Precio Compra</th>
                <th>Precio Venta</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventario as $item)
            <tr>
                <td>{{ $item->articulo->codigo }}</td>
                <td>{{ $item->articulo->nombre }}</td>
                <td>{{ $item->stock }}</td>
                <td>S/ {{ number_format($item->articulo->p_compra, 2) }}</td>
                <td>S/ {{ number_format($item->articulo->p_venta, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>