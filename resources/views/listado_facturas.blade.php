<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Facturas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body {
            background-color: #181818;
            color: white;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            max-width: 90%;
            margin: auto;
            padding: 20px;
        }
        h1 {
            font-size: 2rem;
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            color: #ffcc00;
        }
        .table-dark {
            background-color: #212529;
            border-radius: 10px;
            overflow: hidden;
        }
        thead {
            background-color: #343a40;
        }
        th {
            text-align: center;
            padding: 12px;
        }
        td {
            text-align: center;
            vertical-align: middle;
            padding: 12px;
        }
        .badge-validado {
            background-color: #28a745; /* Verde */
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            font-weight: bold;
        }
        .badge-pendiente {
            background-color: #ffc107; /* Amarillo */
            color: black;
            padding: 6px 12px;
            border-radius: 5px;
            font-weight: bold;
        }
        .table-hover tbody tr:hover {
            background-color: #333;
            transition: 0.3s;
        }
        .btn-ver {
            background-color: #17a2b8;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
        }
        .btn-ver:hover {
            background-color: #138496;
        }
        .pagination-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .btn-pagination {
            padding: 10px 15px;
            font-size: 1rem;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1>📄 Listado de Facturas</h1>

    <!-- Mensaje de carga -->
    <div class="loading text-center">
        <div class="spinner-border text-light" role="status"></div>
        <p>Cargando facturas...</p>
    </div>

    <table class="table table-dark table-hover mt-4">
        <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Número</th>
                <th>Tipo</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="facturas-list">
            <!-- Las facturas se llenarán dinámicamente con JS -->
        </tbody>
    </table>

    <!-- Controles de paginación -->
    <div class="pagination-controls">
        <button id="btn-prev" class="btn btn-secondary btn-pagination" onclick="cambiarPagina(-1)" disabled>⬅ Anterior</button>
        <span id="pagina-actual" class="text-white">Página 1</span>
        <button id="btn-next" class="btn btn-secondary btn-pagination" onclick="cambiarPagina(1)">Siguiente ➡</button>
    </div>
</div>

<script>
let paginaActual = 1;
let totalPaginas = 1;

document.addEventListener("DOMContentLoaded", function () {
    obtenerFacturas();
});

function obtenerFacturas() {
    document.querySelector('.loading').style.display = 'block';

    axios.get('/api/facturas?page=' + paginaActual)
        .then(response => {
            document.querySelector('.loading').style.display = 'none';
            const data = response.data;

            if (data.error) {
                console.error("Error:", data.error);
                alert("Error al obtener las facturas: " + data.error);
                return;
            }

            const facturas = data.facturas;
            totalPaginas = data.total_pages;
            actualizarPaginacion();

            const tbody = document.getElementById('facturas-list');
            tbody.innerHTML = ""; // Limpiar la tabla antes de agregar datos

            if (facturas.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" class="text-center">No hay facturas disponibles</td></tr>`;
                return;
            }

            facturas.forEach((factura, index) => {
                let estadoClass = factura.status == 1 ? 'badge-validado' : 'badge-pendiente';
                let estadoTexto = factura.status == 1 ? 'Válido' : 'Pendiente';

                let row = `
                    <tr>
                        <td>${(paginaActual - 1) * 10 + (index + 1)}</td>
                        <td>${factura.api_client_name || 'N/A'}</td>
                        <td>${factura.number || 'N/A'}</td>
                        <td>${factura.document ? factura.document.name : 'N/A'}</td>
                        <td>${factura.created_at ? factura.created_at.split('T')[0] : 'N/A'}</td>
                        <td><span class="badge ${estadoClass}">${estadoTexto}</span></td>
                        <td>
                            <a href="/factura/${factura.number}" class="btn-ver">🔍 Ver</a>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        })
        .catch(error => {
            document.querySelector('.loading').style.display = 'none';
            console.error("Error al obtener facturas:", error);
            alert("No se pudieron cargar las facturas.");
        });
}

function cambiarPagina(direccion) {
    paginaActual += direccion;
    obtenerFacturas();
}

function actualizarPaginacion() {
    document.getElementById('pagina-actual').textContent = `Página ${paginaActual}`;
    document.getElementById('btn-prev').disabled = (paginaActual === 1);
    document.getElementById('btn-next').disabled = (paginaActual >= totalPaginas);
}
</script>

</body>
</html>
