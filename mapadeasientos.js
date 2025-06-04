document.addEventListener("DOMContentLoaded", function () {
    const filas = 6; // A-J
    const columnas = 8; // 1-13
    const contenedor = document.getElementById("mapa_asientos");
    const contador = document.getElementById("count");

    // Asientos ocupados: [filaLetra, columnaNumero]
    const asientosOcupados = [
        ['A', 5], ['A', 6],
        ['E', 2], ['F', 2], ['F', 3], ['F', 4], ['F', 5], ['F', 6], ['F', 7], ['F', 8], ['F', 9],
        ['H', 4], ['H', 5], ['H', 6], ['H', 7], ['H', 8]
    ];

    let seleccionados = [];

    function letraFila(i) {
        return String.fromCharCode(65 + i); // A–J
    }

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

    document.querySelector('.btn_reservar').addEventListener('click', async () => {
    const horarioSelect = document.querySelector('select[name="horarioId"]');
    const funcionId = horarioSelect.selectedOptions[0]?.dataset.funcionid;
    if (!funcionId) return alert("Debes seleccionar un horario.");

    const response = await fetch('/php/session.php');
    const sessionData = await response.json();
    if (!sessionData.logueado) {
        return alert("Debes iniciar sesión para reservar.");
    }

    const clienteId = sessionData.clienteId;
    const precioPorAsiento = 50;
    const cantidad = seleccionados.length;
    const total = cantidad * precioPorAsiento;

    const datosReserva = {
        clienteId,
        funcionId,
        cantidad,
        total,
        asientos: seleccionados // ej. ["A5", "B3"]
    };

    const res = await fetch('/php/insertar_reserva.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datosReserva)
    });

    const resultado = await res.text();
    alert(resultado);
});

});
