$(document).ready(function() {
    function openModal(modalId) {
        document.getElementById(modalId).style.display = "block";
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }

    $('.actualizar-libro').on('submit', function(e) {

        e.preventDefault();


        var PeriodicoID = $(this).find('input[name="id"]').val();


        alert('El ID del periodico que se enviará es: ' + PeriodicoID);



        var formData = new FormData(this);
        formData.append('id', PeriodicoID);


        $.ajax({
            url: '../Controlador/ActualizarPeriodico.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Periodico actualizada correctamente.');
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
            url: '../Controlador/AgregarAutor2.php',
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


    function agregarPeriodico() {
        var nuevoPeriodico = $('#nuevoPeriodico').val();


        $.ajax({
            url: '../Controlador/AgregarPeriodico.php',
            method: 'POST',
            data: { nombrePeriodico: nuevoPeriodico },
            success: function(response) {
                try {
                    const jsonResponse = JSON.parse(response);
                    if (jsonResponse.success) {
                        alert('Periodico agregado exitosamente');
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
    window.agregarPeriodico = agregarPeriodico;
});