function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('collapsed');
}
$(document).ready(function() {
    function openModal(modalId) {
        document.getElementById(modalId).style.display = "block";
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }

    $('#periodico-form').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        console.log('Datos del formulario:', formData);


        $.ajax({
            url: '../Controlador/InsertarPeriodico.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                // ✅ Muestra el JSON correctamente

                let jsonResponse = JSON.parse(response);
                console.log("JSON decodificado:", jsonResponse);

                if (jsonResponse.success) {
                    alert("Periodico agregada correctamente");
                    $('#periodico-form')[0].reset();
                } else {
                    alert("Error: " + jsonResponse.message);
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