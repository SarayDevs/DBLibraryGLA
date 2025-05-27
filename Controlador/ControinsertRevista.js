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

    $('#revista-form').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        console.log('Datos del formulario:', formData);

        $.ajax({
            url: '../Controlador/InsertarRevista.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                // ✅ Muestra el JSON correctamente

                let jsonResponse = response;

                console.log("JSON decodificado:", jsonResponse);

                if (jsonResponse.success) {
                    alert("Revista agregada correctamente");
                    $('#revista-form')[0].reset();
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