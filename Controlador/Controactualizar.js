$(document).ready(function() {
    $('.actualizar-libro').on('submit', function(e) {
        e.preventDefault();


        var libroID = $(this).find('input[name="id"]').val();


        alert('El ID del libro que se enviará es: ' + libroID);

        var autor = $(this).find('input[name="Autor"]').val();
        alert('Autor: ' + autor);



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
});