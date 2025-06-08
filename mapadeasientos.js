document.addEventListener("DOMContentLoaded", function () {
    const filas = 7;
    const columnas = 12;
    const contenedor = document.getElementById("mapa_asientos");
    const contador = document.getElementById("count");
    const horarioSelect = document.querySelector('select[name="horarioId"]');

    let seleccionados = [];
    let asientosOcupados = [];

    function letraFila(i) {
        return String.fromCharCode(65 + i);
    }

    function renderMapa() {
        contenedor.innerHTML = "";
        seleccionados = [];
        contador.textContent = 0;

        for (let i = 0; i < filas; i++) {
            const filaLetra = letraFila(i);
            const fila = document.createElement("div");
            fila.classList.add("fila");

            const etiqueta = document.createElement("div");
            etiqueta.classList.add("fila_letra");
            etiqueta.textContent = filaLetra;
            fila.appendChild(etiqueta);

            for (let j = 1; j <= columnas; j++) {
                const asiento = document.createElement("div");
                asiento.classList.add("asiento");
                asiento.textContent = j;

                if (asientosOcupados.some(([f, c]) => f === filaLetra && c === j)) {
                    asiento.classList.add("ocupado");
                } else {
                    asiento.addEventListener("click", () => {
                        asiento.classList.toggle("seleccionado");
                        const key = `${filaLetra}${j}`;
                        if (seleccionados.includes(key)) {
                            seleccionados = seleccionados.filter(item => item !== key);
                        } else {
                            seleccionados.push(key);
                        }
                        contador.textContent = seleccionados.length;
                    });
                }

                fila.appendChild(asiento);
            }

            contenedor.appendChild(fila);
        }
    }

    // Cargar asientos ocupados al seleccionar horario
    horarioSelect.addEventListener('change', async () => {
        const selectedOption = horarioSelect.selectedOptions[0];
        const funcionId = selectedOption.dataset.funcionid;

        if (!funcionId) return;

        try {
            const res = await fetch(`php/asientos_ocupados.php?funcionId=${funcionId}`);
            const data = await res.json();
            asientosOcupados = data;
            renderMapa();
        } catch (error) {
            console.error("Error al cargar asientos ocupados:", error);
        }
    });
////////////////ESTA SECCION DE CODIGO CUMPLE CON RESERVAR Y ENVIAR A insertar_reserva.php
    document.querySelector('.btn_reservar').addEventListener('click', async () => {
    const horarioSelect = document.querySelector('select[name="horarioId"]');
    const funcionId = horarioSelect.selectedOptions[0]?.dataset.funcionid;
    if (!funcionId) return alert("Debes seleccionar un horario.");

    const cantidad = seleccionados.length;
    if (cantidad === 0) {
        alert("Debes seleccionar al menos un asiento para continuar.");
        return;
    }

    const response = await fetch('/php/session.php');
    const sessionData = await response.json();
    if (!sessionData.logueado) {
        alert("Debes iniciar sesión para reservar.");
        window.location.href = "/login/login.html";
        return;
    }

    const clienteId = sessionData.clienteId;
    const precioPorAsiento = 50;
    const total = cantidad * precioPorAsiento;

    const datosReserva = {
        clienteId,
        funcionId,
        cantidad,
        total,
        asientos: seleccionados 
    };

    const res = await fetch('/php/insertar_reserva.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datosReserva)
    });

    const resultado = await res.json();

    if (resultado.success) {
        window.location.href = `/ticket/ticket.html?reservaId=${resultado.reservaId}`;
    } else {
        alert("Error al registrar la reserva.");
    }
});

});
