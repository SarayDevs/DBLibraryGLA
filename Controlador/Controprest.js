$(document).ready(function() {
            console.log("Script Controprest.js cargado correctamente");

            $('#botonAgregarPrestamo').click(function() {
                agregarPrestamos(1); // Llamar la función y pasarle el estado

                // Escuchar cuando el préstamo se procese con éxito
                $(document).on('prestamoExitoso', function(event, idLibro) {
                    actualizarEstadoLibro(idLibro, 1);
                });
            });

            $('#botonDevolverPrestamo').click(function() {
                const idLibro = $('#libprest').val();
                actualizarEstadoLibro(idLibro, 0);

            });

            function openModal(modalId, idLibro, tituloLibro, siPrestado) {
                const modal = document.getElementById(modalId);

                document.getElementById('idescondido').value = idLibro;
                document.getElementById("libprest").value = idLibro;
                document.getElementById("idLibro").value = idLibro;
                document.getElementById("tituloLibro").value = tituloLibro;

                const agregarPrestamoCampos = document.getElementById("agregarPrestamoCampos");
                if (siPrestado == 1) {
                    agregarPrestamoCampos.style.display = "none";
                    $.ajax({
                                url: '../Controlador/verPrestamoPorLibro.php',
                                method: 'GET',
                                data: { id: idLibro },
                                dataType: 'json',
                                success: function(response) {
                                        console.log("Respuesta del servidor:", response);

                                        if (response.success) {
                                            const prestamo = response.prestamo;

                                            if (!prestamo) {
                                                console.error("No se encontró el préstamo en la respuesta");
                                                return;
                                            }

                                            const contenidoPrestamo = document.getElementById("contenidoPrestamo");
                                            contenidoPrestamo.innerHTML = '';

                                            const fila = document.createElement("tr");
                                            fila.innerHTML = `
                            <td>${prestamo.IDPREST}</td>
                            <td>${prestamo.libprest}</td>
                            <td><a href="VistaDetalleLibro.php?id=${prestamo.libprest}">${document.getElementById('tituloLibro').value}</a></td>
                            <td>${prestamo.estadoprest == 1 ? 'Prestado' : 'Devuelto'}</td>
                            <td>${prestamo.nombrep}</td>
                            <td>${
                            prestamo.tipoperson === 1 ? ' Docente' :
                            prestamo.tipoperson === 2 ? ' Estudiante' :
                            prestamo.tipoperson === 3 ? ' Directivo' : 
                            prestamo.tipoperson === 4 ? ' Oficios Varios' : 
                            prestamo.tipoperson === 5 ? ' Invitado' : ' Otros'
                            }</td>
                            <td>${prestamo.FECHA}</td>
                            ${prestamo.contacto ? `<td> ${prestamo.contacto}</td>` : ''}
                            ${prestamo.tiempo ? `<td> ${prestamo.tiempo} días</td>` : ''}
                        `;
                        contenidoPrestamo.appendChild(fila);
    
                        document.getElementById("idPrestamo").value = prestamo.IDPREST;
                        document.getElementById("detallesPrestamo").style.display = "block";
                        document.getElementById("botonDevolverPrestamo").style.display = "inline-block";
                        if (prestamo.contacto) {
                            document.getElementById("botonExtenderPrestamo").style.display = "inline-block";
                        } else {
                            document.getElementById("botonExtenderPrestamo").style.display = "none";
                        }
    
                    } else {
                        alert(response.error);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error AJAX al obtener detalles del préstamo:", textStatus, errorThrown);
                    console.log("Respuesta del servidor:", jqXHR.responseText);
                }
            });
    
        } else {
            agregarPrestamoCampos.style.display = "block";
            document.getElementById("detallesPrestamo").style.display = "none";
            document.getElementById("botonAgregarPrestamo").style.display = "inline-block";
            document.getElementById("botonDevolverPrestamo").style.display = "none";
        }
    
        modal.style.display = "block";
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = "none";
        }
    }
    

    function agregarPrestamos(estadoPrestamo) {
        const form = document.getElementById('form-prestamo');
        const formData = new FormData(form);
        formData.append('estadoprest', estadoPrestamo);

        const libprest = document.getElementById('libprest').value;
        const nombrep = document.getElementById('nombrep').value;
        const tipoperson = document.getElementById('tipoperson').value;
        const fecha = document.getElementById('fecha').value;
        const tipoPrestamo = document.getElementById('tipoPrestamo').value;
        const tipoMaterial = document.getElementById('tipoMaterial').value;
formData.append('tipoMaterial', tipoMaterial);


        console.log("Valor seleccionado de tipoperson:", tipoperson);

        // Si es externo, obtener los datos adicionales
        if (tipoPrestamo === "1") {
            const contacto = document.getElementById('contacto').value;
            const tiempo = document.getElementById('tiempo').value;

            if (!contacto || !tiempo) {
                alert('Debe llenar los campos de contacto y días de préstamo para préstamos externos.');
                return;
            }

            formData.append('contacto', contacto);
            formData.append('tiempo', tiempo);
        }

        formData.append('tipoPrestamo', tipoPrestamo);

        if (!libprest || !nombrep || !tipoperson || !fecha || !tipoPrestamo) {
            alert('Por favor, complete todos los campos obligatorios.');
            return;
        }

        $.ajax({
            url: '../Controlador/AgregarPrestamo.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                console.log("Respuesta del servidor:", response);
                if (response.success) {
                    alert('Préstamo procesado exitosamente');
                    closeModal('prestamoModal');

                    // Emitir un evento con el ID del libro
                    $(document).trigger('prestamoExitoso', [libprest]);

                    location.reload();
                } else {
                    alert('Error: ' + response.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error AJAX:", textStatus, errorThrown);
                console.log(jqXHR.responseText);
                alert('Error al procesar la solicitud AJAX.');
            }
        });
    }

    function EliminarPrestamos(estadoPrestamo) {
        const idPrestamo = document.getElementById("idPrestamo").value;


        if (!idPrestamo) {
            console.error("ID del préstamo no está definido");
            return;
        }

        $.ajax({
            url: '../Controlador/actualizarPrestamo.php',
            method: 'POST',
            data: {
                IDPREST: idPrestamo,
                estadoprest: estadoPrestamo
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert("El préstamo ha sido marcado como devuelto.");
                    document.getElementById("botonDevolverPrestamo").style.display = "none";
                    location.reload();
                } else {
                    alert("Error al actualizar el estado del préstamo: " + response.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
                console.log(jqXHR);
            }
        });
    }

    function actualizarEstadoLibro(idLibro, nuevoEstado, tipoMaterial) {
        $.ajax({
            url: '../Controlador/actualizarLibroPrestamo.php',
            method: 'POST',
            data: {
                id: idLibro,
                SiPrest: nuevoEstado,
                tipoMaterial: tipoMaterial
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Estado del libro actualizado correctamente.');
                } else {
                    alert('Error: ' + response.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error AJAX:", textStatus, errorThrown);
                console.log(jqXHR);
                alert('Error en la solicitud AJAX: ' + textStatus);
            }
        });
    }

    function actualizarActividad() {
        var idLibro = document.getElementById('idescondido').value;
        var nuevaActividad = $('#tipoactividad').val();

        $.ajax({
            url: '../Controlador/actualizarActividad.php',
            method: 'POST',
            data: {
                id: idLibro,
                TActividad: nuevaActividad
            },
            dataType: 'json',
            success: function(response) {
                alert('Actividad actualizada con éxito');
                $('#ActividadModal').modal('hide');
                location.reload();

            },
            error: function(xhr, status, error) {
                alert('Hubo un error al actualizar la actividad');
                console.error("Error AJAX:", xhr, status, error)
            }
        });
    }


    window.actualizarActividad = actualizarActividad;
    window.extenderPrestamo= extenderPrestamo;
    window.agregarPrestamos = agregarPrestamos;
    window.EliminarPrestamos = EliminarPrestamos;
    window.openModal = openModal;
    window.closeModal = closeModal;
});