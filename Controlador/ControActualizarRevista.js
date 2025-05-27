$(document).ready(function() {
    function openModal(modalId) {
        document.getElementById(modalId).style.display = "block";
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }

    $('.actualizar-libro').on('submit', function(e) {

        e.preventDefault();


        var RevistaID = $(this).find('input[name="id"]').val();


        alert('El ID de la revista que se enviará es: ' + RevistaID);



        var formData = new FormData(this);
        formData.append('id', RevistaID);


        $.ajax({
            url: '../Controlador/ActualizarRevista.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Revista actualizada correctamente.');
                    window.location.href = document.referrer;
                } else {
                    alert('Error al actualizar revista: ' + response.message);
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
            url: '../Controlador/AgregarAutor3.php',
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


    function agregarRevista() {
        var nuevaRevista = $('#nuevaRevista').val();


        $.ajax({
            url: '../Controlador/AgregarRevista.php',
            method: 'POST',
            data: { nombreRevista: nuevaRevista },
            success: function(response) {
                try {
                    const jsonResponse = JSON.parse(response);
                    if (jsonResponse.success) {
                        alert('Revista agregada exitosamente');
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

    window.openModal = openModal;
    window.closeModal = closeModal;
    window.agregarAutor = agregarAutor;
    window.agregarRevista = agregarRevista;
});