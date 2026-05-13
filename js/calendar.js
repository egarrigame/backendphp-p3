document.addEventListener("DOMContentLoaded", () => {

    const contenedor = document.getElementById("calendario");
    const selectorFecha = document.getElementById("selectorMes");
    const selectorVista = document.getElementById("selectorVista");

    if (!contenedor || !selectorFecha || !selectorVista) return;

    const hoy = new Date();

    // ====================
    // CONFIG INPUT
    // ====================
    function setModoMes() {
        selectorFecha.type = "month";
        selectorFecha.value = `${hoy.getFullYear()}-${String(hoy.getMonth() + 1).padStart(2, "0")}`;
    }

    function setModoDia() {
        selectorFecha.type = "date";
        selectorFecha.value = `${hoy.getFullYear()}-${String(hoy.getMonth() + 1).padStart(2, "0")}-${String(hoy.getDate()).padStart(2, "0")}`;
    }

    setModoMes();

    selectorVista.addEventListener("change", () => {
        if (selectorVista.value === "mes") {
            setModoMes();
        } else {
            setModoDia();
        }
        render();
    });

    // ====================
    // UTILIDADES
    // ====================
    function limpiar() {
        contenedor.innerHTML = "";
    }

    function esUrgente(tipo) {
        return (tipo || "").toLowerCase() === "urgente";
    }

    function getFechaStr(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    function parseFecha(fechaStr) {
        return new Date(fechaStr.replace(" ", "T"));
    }

    function crearEvento(i) {
        const evento = document.createElement("div");

        evento.className = "mt-1 p-1 text-white rounded evento-calendario";

        evento.classList.add(
            esUrgente(i.tipo_urgencia)
                ? "evento-urgente"
                : "evento-estandar"
        );

        evento.innerText = i.nombre_especialidad;

        evento.onclick = () => {
            const fecha = parseFecha(i.fecha_servicio);

            const contenido = `
                <p><strong>Localizador:</strong> ${i.localizador}</p>
                <p><strong>Cliente:</strong> ${i.cliente_nombre}</p>
                <p><strong>Servicio:</strong> ${i.nombre_especialidad}</p>
                <p><strong>Fecha:</strong> ${fecha.toLocaleString('es-ES')}</p>
                <p><strong>Urgencia:</strong> ${esUrgente(i.tipo_urgencia) ? "Urgente" : "Estándar"}</p>
            `;

            document.getElementById("modalContenido").innerHTML = contenido;
            new bootstrap.Modal(document.getElementById('modalIncidencia')).show();
        };

        return evento;
    }

    // ====================
    // MES
    // ====================
    function renderMes() {
        limpiar();

        const [year, month] = selectorFecha.value.split("-").map(Number);

        const primerDia = new Date(year, month - 1, 1);
        const ultimoDia = new Date(year, month, 0).getDate();

        let offset = primerDia.getDay();
        offset = offset === 0 ? 6 : offset - 1;

        for (let i = 0; i < offset; i++) {
            const empty = document.createElement("div");
            empty.className = "col-md-2 border p-2 bg-light";
            contenedor.appendChild(empty);
        }

        for (let dia = 1; dia <= ultimoDia; dia++) {

            const fecha = new Date(year, month - 1, dia);
            const fechaStr = getFechaStr(fecha);

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

        const fechaBase = new Date(selectorFecha.value);

        let diaSemana = fechaBase.getDay();
        diaSemana = diaSemana === 0 ? 7 : diaSemana;

        const lunes = new Date(fechaBase);
        lunes.setDate(fechaBase.getDate() - (diaSemana - 1));

        const dias = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];

        for (let i = 0; i < 7; i++) {

            const dia = new Date(lunes);
            dia.setDate(lunes.getDate() + i);

            const fechaStr = getFechaStr(dia);
            const nombreDia = dias[(dia.getDay() + 6) % 7];

            const div = document.createElement("div");
            div.className = "col-md border p-2 bg-white";
            div.style.minHeight = "120px";

            div.innerHTML = `
                <strong>${nombreDia}</strong><br>
                ${dia.getDate()}
            `;

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

        const fecha = new Date(selectorFecha.value);
        const fechaStr = getFechaStr(fecha);

        const div = document.createElement("div");
        div.className = "col-12 border p-3 bg-white";

        div.innerHTML = `
            <h5>${fecha.toLocaleDateString('es-ES', {
                weekday: 'long',
                day: 'numeric',
                month: 'long'
            })}</h5>
        `;

        incidencias.forEach(i => {
            if (i.fecha_servicio.slice(0, 10) === fechaStr) {
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

    selectorFecha.addEventListener("change", render);

    render();
});
