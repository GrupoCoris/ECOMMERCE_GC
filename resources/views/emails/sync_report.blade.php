<!DOCTYPE html>
<html>

<head>
    <style>
        .status-EXITOSA {
            color: green;
            font-weight: bold;
        }

        .status-FALLIDA {
            color: red;
            font-weight: bold;
        }

        .container {
            font-family: sans-serif;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Reporte de Sincronización de Productos ERP - Bagisto</h2>
        <p>Estado: <span class="status-{{ $status }}">{{ $status }}</span></p>
        <hr>
        <p><strong>Detalles:</strong></p>
        <pre>{{ $details }}</pre>
        <p>Fecha: {{ now()->format('d-m-Y H:i:s') }}</p>
    </div>
</body>

</html>