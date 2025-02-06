$(document).ready(function() {

    function openModal(modalId) {
        document.getElementById(modalId).style.display = "block";
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }


    $('#form-libro').submit(function(e) {
        e.preventDefault();


        var formData = new FormData(this);
        console.log('Datos del formulario:', formData);

        $.ajax({
            url: '../Controlador/InsertarLibro.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Libro agregado exitosamente.');
                    $('#form-libro')[0].reset();
                    actualizarListaLibros(response.libros);

                    var paginaActual = response.paginaActual || 1;
                    window.location.href = 'VistaLibros.php?pagina=' + paginaActual;
                } else {
                    alert('Error al agregar el libro: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log('Estado:', status);
                console.log('Error:', error);
                console.log('Respuesta del servidor:', xhr.responseText);

                alert('Ha ocurrido un error al enviar los datos: ' + xhr.responseText);
            }
        });
    });


    $('#verLibrosBtn').click(function() {

        var paginaActual = getUrlParameter('pagina') || 1;
        window.location.href = 'VistaLibros.php?pagina=' + paginaActual;
    });


    function getUrlParameter(name) {
        var url = window.location.href;
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(url);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }


    function agregarAutor() {
        var nuevoAutor = $('#nuevoAutor').val();

        $.ajax({
            url: '../Controlador/AgregarAutor.php',
            method: 'POST',
            data: { nombreAutor: nuevoAutor },
            success: function(response) {
                alert('Autor agregado exitosamente. Vamos a recargar esta pagina');
                $('#autorID').append('<option value="' + response.id + '" data-nombre="' + response.nombre + '">' + response.nombre + '</option>');

                closeModal('autorModal');

                window.location.href = 'Insertar.php';

            },
            error: function() {
                alert('Error al agregar autor');
            }
        });
    }

    function agregarEditorial() {
        var nuevaEditorial = $('#tipoactividad').val();


        $.ajax({
            url: '../Controlador/AgregarEditorial.php',
            method: 'POST',
            data: { nombreEditorial: nuevaEditorial },
            success: function(response) {
                try {
                    const jsonResponse = JSON.parse(response);
                    if (jsonResponse.success) {
                        alert('Préstamo agregado exitosamente');
                        closeModal('prestamoModal');
                        location.reload();
                    } else {
                        alert('Error: ' + (jsonResponse.error || 'Ocurrió un problema inesperado.'));
                    }
                } catch (e) {
                    console.error("Error en la respuesta JSON:", response);
                    alert('Error en el formato de respuesta');
                }
            },

            error: function() {
                alert('Error al agregar editorial');
            }
        });
    }

    window.openModal = openModal;
    window.closeModal = closeModal;
    window.agregarAutor = agregarAutor;
    window.agregarEditorial = agregarEditorial;
});