document.addEventListener("DOMContentLoaded", () => {

    const contenedor = document.getElementById("calendario");
    const selectorMes = document.getElementById("selectorMes");
    const selectorVista = document.getElementById("selectorVista");

    if (!contenedor || !selectorMes || !selectorVista) return;

    const hoy = new Date();
    selectorMes.value = hoy.toISOString().slice(0, 7);

    function limpiar() {
        contenedor.innerHTML = "";
    }

    function abrirModal(i) {
        const contenido = `
            <p><strong>Localizador:</strong> ${i.localizador}</p>
            <p><strong>Cliente:</strong> ${i.cliente_nombre}</p>
            <p><strong>Servicio:</strong> ${i.nombre_especialidad}</p>
            <p><strong>Fecha:</strong> ${i.fecha_servicio}</p>
            <p><strong>Urgencia:</strong> ${i.tipo_urgencia}</p>
        `;

        document.getElementById("modalContenido").innerHTML = contenido;

        const modal = new bootstrap.Modal(document.getElementById('modalIncidencia'));
        modal.show();
    }

    function crearEvento(i) {
        const evento = document.createElement("div");

        evento.className = "mt-1 p-1 text-white rounded evento-calendario";
        evento.style.fontSize = "12px";
        evento.style.cursor = "pointer";

        if (i.tipo_urgencia === "Urgente") {
            evento.style.backgroundColor = "#dc3545";
        } else {
            evento.style.backgroundColor = "#198754";
        }

        evento.innerText = i.nombre_especialidad;

        evento.onclick = () => abrirModal(i);

        return evento;
    }

    // ====================
    // MES
    // ====================
    function renderMes() {
        limpiar();

        const [year, month] = selectorMes.value.split("-").map(Number);

        const primerDia = new Date(year, month - 1, 1);
        const ultimoDia = new Date(year, month, 0).getDate();
        const offset = primerDia.getDay();

        for (let i = 0; i < offset; i++) {
            const empty = document.createElement("div");
            empty.className = "col-md-2 border p-2 bg-light";
            contenedor.appendChild(empty);
        }

        for (let dia = 1; dia <= ultimoDia; dia++) {

            const fechaStr = `${year}-${String(month).padStart(2, '0')}-${String(dia).padStart(2, '0')}`;

            const div = document.createElement("div");
            div.className = "col-md-2 border p-2 bg-white";
            div.style.minHeight = "100px";

            div.innerHTML = `<strong>${dia}</strong>`;

            incidencias.forEach(i => {
                if (i.fecha_servicio.slice(0, 10) === fechaStr) {
                    div.appendChild(crearEvento(i));
                }
            });

            contenedor.appendChild(div);
        }
    }

    // ====================
    // SEMANA
    // ====================
    function renderSemana() {
        limpiar();

        const fecha = new Date();
        const diaSemana = fecha.getDay();

        const inicio = new Date(fecha);
        inicio.setDate(fecha.getDate() - diaSemana);

        for (let i = 0; i < 7; i++) {

            const dia = new Date(inicio);
            dia.setDate(inicio.getDate() + i);

            const fechaStr = dia.toISOString().slice(0, 10);

            const div = document.createElement("div");
            div.className = "col-md-2 border p-2 bg-white";
            div.style.minHeight = "120px";

            div.innerHTML = `<strong>${dia.getDate()}</strong>`;

            incidencias.forEach(i => {
                if (i.fecha_servicio.slice(0, 10) === fechaStr) {
                    div.appendChild(crearEvento(i));
                }
            });

            contenedor.appendChild(div);
        }
    }

    // ====================
    // DÍA
    // ====================
    function renderDia() {
        limpiar();

        const fecha = new Date().toISOString().slice(0, 10);

        const div = document.createElement("div");
        div.className = "col-12 border p-3 bg-white";

        div.innerHTML = `<h5>${fecha}</h5>`;

        incidencias.forEach(i => {
            if (i.fecha_servicio.slice(0, 10) === fecha) {
                div.appendChild(crearEvento(i));
            }
        });

        contenedor.appendChild(div);
    }

    // ====================
    // CONTROL
    // ====================
    function render() {
        const vista = selectorVista.value;

        if (vista === "mes") renderMes();
        else if (vista === "semana") renderSemana();
        else renderDia();
    }

    selectorMes.addEventListener("change", render);
    selectorVista.addEventListener("change", render);

    render();
});