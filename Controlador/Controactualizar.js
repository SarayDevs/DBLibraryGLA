$(document).ready(function() {
    function openModal(modalId) {
        document.getElementById(modalId).style.display = "block";
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }

    $('.actualizar-libro').on('submit', function(e) {
        e.preventDefault();


        var libroID = $(this).find('input[name="id"]').val();


        alert('El ID del libro que se enviará es: ' + libroID);



        var formData = new FormData(this);
        formData.append('id', libroID);


        $.ajax({
            url: '../Controlador/ActualizarLibro.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Libro actualizado correctamente.');
                    window.location.href = document.referrer;
                } else {
                    alert('Error al actualizar el libro: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error al realizar la actualización: ' + error);
            }
        });
    });

    function agregarAutor() {
        var nuevoAutor = $('#nuevoAutor').val();

        $.ajax({
            url: '../Controlador/AgregarAutor.php',
            method: 'POST',
            data: { nombreAutor: nuevoAutor },
            dataType: 'json',
            success: function(response) {
                alert('Autor agregado exitosamente.');
                $('#autorID').append('<option value="' + response.id + '" data-nombre="' + response.nombre + '">' + response.nombre + '</option>');
                closeModal('autorModal');
                location.reload();
            },
            error: function() {
                alert('Error al agregar autor');
            }
        });
    }


    function agregarEditorial() {
        var nuevaEditorial = $('#nuevaEditorial').val();


        $.ajax({
            url: '../Controlador/AgregarEditorial.php',
            method: 'POST',
            data: { nombreEditorial: nuevaEditorial },
            success: function(response) {
                try {
                    const jsonResponse = JSON.parse(response);
                    if (jsonResponse.success) {
                        alert('Editorial agregada exitosamente');
                        closeModal('editorialModal');
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

    function agregarUbicacion() {
        var nuevaubicacion = $('#nuevaubicacion').val();

        $.ajax({
            url: '../Controlador/Agregarubicacion.php',
            method: 'POST',
            data: { nombreUbicacion: nuevaubicacion },
            dataType: 'json',
            success: function(response) {
                alert('Ubicación agregada exitosamente.');
                closeModal('UbicacionModal');
                location.reload();
            },
            error: function() {
                alert('Error al agregar autor corporativo');
            }
        });
    }

    window.openModal = openModal;
    window.closeModal = closeModal;
    window.agregarAutor = agregarAutor;
    window.agregarEditorial = agregarEditorial;
    window.agregarUbicacion = agregarUbicacion;
});