<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crear Factura</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #121212;
            color: white;
        }
        .form-section {
            background-color: #1f1f1f;
            padding: 20px;
            border-radius: 8px;
            margin-block-end: 20px;
        }
        .form-section h4 {
            border-block-end: 2px solid #444;
            padding-block-end: 10px;
            margin-block-end: 20px;
        }
        .form-control, .form-select {
            background-color: #2a2a2a;
            color: white;
            border: 1px solid #444;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">📄 Nueva Factura</h2>

        <!-- Información de Factura -->
        <div class="form-section">
            <h4>📑 Información de Factura</h4>
            <div class="row">
                <div class="col-md-4">
                    <label>Rango de Numeración *</label>
                    <select class="form-select" id="rangoNumeracion">
                        <option value="">Seleccione...</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Código de Referencia *</label>
                    <input type="text" class="form-control" id="codigoReferencia" readonly>
                </div>
                <div class="col-md-4">
                    <label>Observaciones (Opcional)</label>
                    <input type="text" class="form-control" id="observaciones">
                </div>
            </div>
        </div>

        <!-- Datos del Cliente -->
        <div class="form-section">
            <h4>🧑‍💼 Datos del Cliente</h4>
            <div class="row">
                <div class="col-md-4">
                    <label>Tipo de Persona *</label>
                    <select class="form-select" id="tipoPersona">
                        <option value="">Seleccione...</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Dígito de Verificación (Si es Persona Juridica)</label>
                    <input type="text" class="form-control" id="dv">
                </div>
                <div class="col-md-4">
                    <label>Tipo de Documento *</label>
                    <select class="form-select" id="tipoDocumento">
                        <option value="">Seleccione...</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Número de Identificación *</label>
                    <input type="text" class="form-control" id="identificacion">
                </div>
                <div class="col-md-4">
                    <label>Razón Social (Si es Persona Juridica)</label>
                    <input type="text" class="form-control" id="razonSocial">
                </div>
                <div class="col-md-4">
                    <label>Nombre Comercial (Si es Persona Juridica)</label>
                    <input type="text" class="form-control" id="nombreComercial">
                </div>
                <div class="col-md-4">
                    <label>Nombre del Cliente (Si es Persona Natural) *</label>
                    <input type="text" class="form-control" id="nombreCliente">
                </div>
                <div class="col-md-4">
                    <label>Correo Electrónico *</label>
                    <input type="email" class="form-control" id="correoElectronico">
                </div>
                <div class="col-md-4">
                    <label>Teléfono *</label>
                    <input type="text" class="form-control" id="telefono">
                </div>
                <div class="col-md-4">
                    <label>Dirección *</label>
                    <input type="text" class="form-control" id="direccion">
                </div>
                <div class="col-md-4">
                    <label>Municipio *</label>
                    <select id="municipio" class="form-select">
                        <option value="">Seleccione...</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Información de Productos -->
        <div class="form-section">
            <h4>📦 Productos</h4>
            <div id="productos-container">
                <div class="producto row p-3 mb-2">
                    <div class="col-md-4">
                        <label>Código de Referencia *</label>
                        <input type="text" class="form-control codigo-referencia">
                    </div>
                    <div class="col-md-4">
                        <label>Nombre del Producto *</label>
                        <input type="text" class="form-control nombre-producto">
                    </div>
                    <div class="col-md-4">
                        <label>Cantidad *</label>
                        <input type="number" class="form-control cantidad" value="1">
                    </div>
                    <div class="col-md-4">
                        <label>Precio *</label>
                        <input type="number" class="form-control precio" placeholder="COP">
                    </div>
                    <div class="col-md-4">
                        <label>Impuesto *</label>
                        <input type="number" class="form-control impuesto" placeholder="%">
                    </div>
                    <div class="col-md-4">
                        <label>Unidad de Medida *</label>                       
                            <select class="form-select unidad-medida" id="unidadMedida">
                                <option value="">Seleccione...</option>
                            </select>
                    </div>
                    <div class="col-md-4">
                        <label>Código Estándar *</label>
                        <select class="form-select codigo-estandar">
                            <option value="">Seleccione...</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Tributo Cliente *</label>
                        <select class="form-select tributo-producto" id="tributoProducto">
                            <option value="">Seleccione...</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input excluir-iva" type="checkbox">
                            <label class="form-check-label">Excluir del IVA</label>
                        </div>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary" id="agregar-producto">+ Agregar Producto</button>
            <button class="btn btn-danger" id="eliminar-producto">- Eliminar Último Producto</button>
        </div>

        <!-- Resumen de Factura -->
        <div class="form-section">
            <h4>📜 Resumen de Factura</h4>
            <p><strong>Subtotal:</strong> <span id="subtotal">COP 0</span></p>
            <p><strong>IVA:</strong> <span id="iva">COP 0</span></p>
            <p><strong>Total:</strong> <span id="total">COP 0</span></p>
        </div>
        <!-- Información de Pago -->
    <div class="form-section">
            <h4>💳 Información de Pago</h4>
            <div class="row">
                <div class="col-md-4">
                    <label>Forma de Pago *</label>
                    <select class="form-select" id="formaPago">
                        <option value="">Seleccione...</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Método de Pago *</label>
                    <select class="form-select" id="metodoPago">
                        <option value="">Seleccione...</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Fecha de Vencimiento *</label>
                    <input type="date" class="form-control" id="fechaVencimiento">
                </div>
            </div>
        </div>
        <!-- Botón para generar la factura -->
        <div class="text-center">
            <button class="btn btn-success btn-lg" id="generar-factura">💾 Generar Factura</button>
        </div>
    </div>
    </div>
    

    <script>
document.addEventListener("DOMContentLoaded", function () {
    const productosContainer = document.getElementById("productos-container");
    const subtotalElement = document.getElementById("subtotal");
    const ivaElement = document.getElementById("iva");
    const totalElement = document.getElementById("total");

    function calcularResumen() {
        let subtotal = 0;
        let ivaTotal = 0;

        productosContainer.querySelectorAll(".producto").forEach(producto => {
            let cantidad = parseFloat(producto.querySelector(".cantidad").value) || 0;
            let precio = parseFloat(producto.querySelector(".precio").value) || 0;
            let impuesto = parseFloat(producto.querySelector(".impuesto").value) || 0;
            let excluirIVA = producto.querySelector(".excluir-iva").checked;

            let totalProducto = cantidad * precio;
            let ivaProducto = excluirIVA ? 0 : (totalProducto * (impuesto / 100));

            subtotal += totalProducto;
            ivaTotal += ivaProducto;
        });

        subtotalElement.textContent = `COP ${subtotal.toLocaleString("es-CO", { minimumFractionDigits: 2 })}`;
        ivaElement.textContent = `COP ${ivaTotal.toLocaleString("es-CO", { minimumFractionDigits: 2 })}`;
        totalElement.textContent = `COP ${(subtotal + ivaTotal).toLocaleString("es-CO", { minimumFractionDigits: 2 })}`;
    }

    // 📌 Detectar cambios en los inputs de productos para actualizar el resumen automáticamente
    function actualizarEventos() {
        productosContainer.querySelectorAll(".producto input, .producto select").forEach(input => {
            input.addEventListener("input", calcularResumen);
        });
    }

    // 📌 Asegurar que al agregar productos se mantengan los eventos
    document.getElementById("agregar-producto").addEventListener("click", function () {
        const nuevoProducto = productosContainer.firstElementChild.cloneNode(true);
        nuevoProducto.querySelectorAll("input").forEach(input => input.value = "");
        nuevoProducto.querySelector(".cantidad").value = 1;
        nuevoProducto.querySelector(".excluir-iva").checked = false;
        productosContainer.appendChild(nuevoProducto);
        actualizarEventos(); // Actualizar eventos para nuevos productos
    });

    document.getElementById("eliminar-producto").addEventListener("click", function () {
        if (productosContainer.children.length > 1) {
            productosContainer.removeChild(productosContainer.lastElementChild);
            calcularResumen();
        }
    });

    // Inicializar eventos
    actualizarEventos();
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const municipioSelect = document.getElementById("municipio");

    async function cargarMunicipios() {
        try {
            const response = await axios.get("/municipios");

            if (response.data.length > 0) {
                municipioSelect.innerHTML = '<option value="">Seleccione...</option>';
                response.data.forEach(municipio => {
                    municipioSelect.innerHTML += `<option value="${municipio.id}">${municipio.name} (${municipio.department})</option>`;
                });
            } else {
                console.error("No se recibieron municipios");
            }
        } catch (error) {
            console.error("Error al cargar los municipios:", error);
        }
    }

    cargarMunicipios();
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const rangoSelect = document.getElementById("rangoNumeracion");
    const codigoReferenciaInput = document.getElementById("codigoReferencia");

    async function cargarRangosNumeracion() {
        try {
            const response = await axios.get("/rangos-numeracion");

            if (Array.isArray(response.data)) {
                rangoSelect.innerHTML = '<option value="">Seleccione...</option>';
                
                response.data.forEach(rango => {
                    // Agregamos cada opción con el nombre visible y los valores ocultos en atributos
                    let option = document.createElement("option");
                    option.value = rango.id;
                    option.textContent = rango.document; // Lo que el usuario verá en el select
                    option.dataset.prefix = rango.prefix; // Prefijo del rango
                    option.dataset.current = rango.current; // Número actual

                    rangoSelect.appendChild(option);
                });

                // Evento para actualizar el código de referencia en la vista
                rangoSelect.addEventListener("change", function () {
                    let selectedOption = this.options[this.selectedIndex];

                    if (selectedOption.value) {
                        let prefix = selectedOption.dataset.prefix;
                        let current = parseInt(selectedOption.dataset.current, 10) || 0;

                        // Se muestra el prefijo + siguiente número en el input visible
                        codigoReferenciaInput.value = `${prefix}-${current + 1}`;
                    } else {
                        codigoReferenciaInput.value = ""; // Si no se selecciona nada, limpiar el input
                    }
                });

            } else {
                console.error("La API no devolvió un array válido:", response.data);
            }
        } catch (error) {
            console.error("Error al obtener los rangos de numeración:", error);
        }
    }

    cargarRangosNumeracion();
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const unidadMedidaSelect = document.getElementById("unidadMedida");

    async function cargarUnidadesMedida() {
        try {
            const response = await axios.get("/unidades-medida");

            if (response.data) {
                unidadMedidaSelect.innerHTML = '<option value="">Seleccione...</option>';
                response.data.forEach(unidad => {
                    unidadMedidaSelect.innerHTML += `<option value="${unidad.id}">${unidad.name}</option>`;
                });
            }
        } catch (error) {
            console.error("Error al cargar las unidades de medida:", error);
        }
    }

    cargarUnidadesMedida();
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const tributoSelect = document.getElementById("tributoProducto");

    async function cargarTributosProductos() {
        try {
            const response = await axios.get("/tributos-productos");

            if (response.data) {
                tributoSelect.innerHTML = '<option value="">Seleccione...</option>';
                response.data.forEach(tributo => {
                    tributoSelect.innerHTML += `<option value="${tributo.id}">${tributo.name}</option>`;
                });
            }
        } catch (error) {
            console.error("Error al cargar los tributos de productos:", error);
        }
    }

    cargarTributosProductos();
});
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    // 📌 Seleccionamos los elementos de los selects
    const tipoDocumentoSelect = document.getElementById("tipoDocumento");
    const metodoPagoSelect = document.getElementById("metodoPago");
    const formaPagoSelect = document.getElementById("formaPago");
    const tipoPersonaSelect = document.getElementById("tipoPersona");
    const codigoEstandarSelect = document.querySelectorAll(".codigo-estandar");

    // 📌 Lista de Tipos de Documento de Identidad
    const tiposDocumento = [
        { id: 1, name: "Registro civil" },
        { id: 2, name: "Tarjeta de identidad" },
        { id: 3, name: "Cédula de ciudadanía" },
        { id: 4, name: "Tarjeta de extranjería" },
        { id: 5, name: "Cédula de extranjería" },
        { id: 6, name: "NIT" },
        { id: 7, name: "Pasaporte" },
        { id: 8, name: "Documento de identificación extranjero" },
        { id: 9, name: "PEP" },
        { id: 10, name: "NIT otro país" },
        { id: 11, name: "NUIP *" }
    ];

    // 📌 Lista de Métodos de Pago
    const mediosPago = [
        { code: 10, name: "Efectivo" },
        { code: 42, name: "Consignación" },
        { code: 20, name: "Cheque" },
        { code: 46, name: "Transferencia Débito Interbancario" },
        { code: 47, name: "Transferencia" },
        { code: 71, name: "Bonos" },
        { code: 72, name: "Vales" },
        { code: "ZZZ", name: "Otro*" },
        { code: 1, name: "Medio de pago no definido" },
        { code: 49, name: "Tarjeta Débito" },
        { code: 3, name: "Débito ACH" },
        { code: 25, name: "Cheque certificado" },
        { code: 26, name: "Cheque Local" },
        { code: 48, name: "Tarjeta Crédito" }
    ];

    // 📌 Lista de Formas de Pago
    const formasPago = [
        { id: 1, name: "Contado" },
        { id: 2, name: "Crédito" },
    ];

    // 📌 Lista de Tipos de Persona (Organización)
    const tiposPersona = [
        { id: 1, name: "Persona Jurídica" },
        { id: 2, name: "Persona Natural" }
    ];

    // 📌 Lista de Códigos de Estándar de Identificación del Producto
    const codigosEstandar = [
        { id: 1, name: "Estándar de adopción del contribuyente" },
        { id: 2, name: "UNSPSC" },
        { id: 3, name: "Partida Arancelaria" },
        { id: 4, name: "GTIN" }
    ];

    // 📌 Función para llenar los selects
    function llenarSelect(select, data) {
        if (!select) return;
        select.innerHTML = '<option value="">Seleccione...</option>';
        data.forEach(item => {
            select.innerHTML += `<option value="${item.id || item.code}">${item.name}</option>`;
        });
    }

    // Llenar los selects con sus respectivas listas
    llenarSelect(tipoDocumentoSelect, tiposDocumento);
    llenarSelect(metodoPagoSelect, mediosPago);
    llenarSelect(formaPagoSelect, formasPago);
    llenarSelect(tipoPersonaSelect, tiposPersona);

    // 📌 Llenar todos los selects de código estándar dentro de los productos
    codigoEstandarSelect.forEach(select => llenarSelect(select, codigosEstandar));
});

</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const btnGenerarFactura = document.getElementById("generar-factura");

        if (!btnGenerarFactura) return;

        btnGenerarFactura.addEventListener("click", async function () {
            // 🔄 Mostrar mensaje de carga con SweetAlert2
            Swal.fire({
                title: "Generando factura...",
                text: "Por favor, espera mientras se genera la factura.",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // 🟢 Obtener CSRF Token desde el HTML
            let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");
            if (!csrfToken) {
                Swal.fire("Error", "No se encontró el CSRF token.", "error");
                return;
            }

            function getValue(selector, isSelect = false) {
                let element = document.querySelector(selector);
                return element ? (isSelect ? element.options[element.selectedIndex]?.value || null : element.value) : null;
            }

            // 🟢 Obtener información del rango de numeración seleccionado
            let numberingRangeId = getValue("#rangoNumeracion", true);
            let selectedOption = document.querySelector("#rangoNumeracion option:checked");
            let prefix = selectedOption?.dataset.prefix || "N/A";
            let current = parseInt(selectedOption?.dataset.current, 10) || 0;
            let referenceCode = `${prefix}-${current + 1}`;

            let observation = getValue("#observaciones") || "";
            let paymentForm = getValue("#formaPago", true) || "1";
            let paymentMethodCode = getValue("#metodoPago", true) || "10";
            let paymentDueDate = getValue("#fechaVencimiento") || null;

            let customer = {
                identification_document_id: getValue("#tipoDocumento", true) || "3",
                identification: getValue("#identificacion") || "000000000",
                dv: getValue("#dv") || "",
                company: getValue("#razonSocial") || "",
                trade_name: getValue("#nombreComercial") || "",
                names: getValue("#nombreCliente") || "Cliente Genérico",
                address: getValue("#direccion") || "Sin dirección",
                email: getValue("#correoElectronico") || "email@example.com",
                phone: getValue("#telefono") || "0000000000",
                legal_organization_id: getValue("#tipoPersona", true) || "2",
                tribute_id: "21",
                municipality_id: getValue("#municipio", true) || "980",
            };

            // 🟢 Obtener productos
            let items = [];
            document.querySelectorAll(".producto").forEach(producto => {
                let item = {
                    code_reference: getValueFromElement(producto, ".codigo-referencia") || "000",
                    name: getValueFromElement(producto, ".nombre-producto") || "Producto Genérico",
                    quantity: parseInt(getValueFromElement(producto, ".cantidad")) || 1,
                    discount_rate: 0,
                    price: parseFloat(getValueFromElement(producto, ".precio")) || 0,
                    tax_rate: getValueFromElement(producto, ".impuesto") || "0",
                    unit_measure_id: getValueFromElement(producto, ".unidad-medida", true) || "70",
                    standard_code_id: getValueFromElement(producto, ".codigo-estandar", true) || "1",
                    is_excluded: producto.querySelector(".excluir-iva")?.checked ? 1 : 0,
                    tribute_id: getValueFromElement(producto, ".tributo-cliente", true) || "1",
                    withholding_taxes: []
                };
                items.push(item);
            });

            // 🟢 Validar que no falten datos obligatorios
            if (!numberingRangeId || !referenceCode || !paymentForm || !paymentMethodCode || !customer.identification) {
                Swal.fire("Error", "Faltan datos obligatorios en la factura.", "error");
                return;
            }

            let factura = {
                numbering_range_id: numberingRangeId,
                reference_code: referenceCode,
                observation: observation,
                payment_form: paymentForm,
                payment_due_date: paymentDueDate,
                payment_method_code: paymentMethodCode,
                billing_period: {
                    start_date: "2024-01-10",
                    start_time: "00:00:00",
                    end_date: "2024-02-09",
                    end_time: "23:59:59"
                },
                customer: customer,
                items: items
            };

            try {
                const response = await fetch("/generar-factura", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify(factura)
                });

                const result = await response.json();

                if (response.ok) {
                    let bill = result.data || {};
                    let company = result.company || {};
                    let invoiceNumber = bill.number || "Sin número";
                    let qrUrl = bill.qr || "#";
                    let qrImage = bill.qr_image || "";
                    let cufe = bill.cufe || "No disponible";
                    let companyLogo = company.url_logo || "";
                    let dianUrl = `https://catalogo-vpfe-hab.dian.gov.co/document/searchqr?documentkey=${cufe}`;

                    Swal.fire({
                        title: "✅ Factura Generada",
                        html: `
                            ${companyLogo ? `<img src="${companyLogo}" alt="Logo Empresa" style="max-inline-size:150px; margin-block-end:10px;">` : ""}
                            <h3>Factura Generada</h3>
                            <p><strong>Número de Factura:</strong> ${invoiceNumber}</p>
                            <p><strong>CUFE:</strong> ${cufe}</p>
                            <p><strong>Mensaje:</strong> ${result.message}</p>
                            ${qrImage ? `<p><strong>QR:</strong><br><img src="${qrImage}" alt="QR Factura" style="max-inline-size:200px;"></p>` : ""}
                            <p><a href="${dianUrl}" target="_blank">🔗 Consultar Factura en la DIAN</a></p>
                        `,
                        icon: "success"
                    });

                } else {
                    Swal.fire("Error", "Error al generar la factura. Revisa los datos.", "error");
                }
            } catch (error) {
                Swal.fire("Error", "Error en la conexión con la API de Factus.", "error");
            }

            function getValueFromElement(parent, selector, isSelect = false) {
                let element = parent.querySelector(selector);
                return element ? (isSelect ? element.options[element.selectedIndex]?.value || null : element.value) : null;
            }
        });
    });
</script>

</body>
</html>
