$(document).ready(function() {
    $('.eliminar-libro').click(function(e) {
        e.preventDefault();
        console.log("Evento click en .eliminar-libro capturado correctamente");

        const url = $(this).attr('href');
        if (confirm('¿Estás seguro de eliminar este libro?')) {
            const id = url.split('id=')[1];


            const urlParams = new URLSearchParams(window.location.search);
            const pagina = urlParams.get('pagina') || 1;

            eliminarLibro(id, pagina);
        }

        return false;
    });

    function eliminarLibro(id, pagina) {
        $.ajax({
            url: '../Vista/Eliminar.php?id=' + id,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response.success) {
                    // Si el libro fue eliminado correctamente
                    alert(response.message);

                    // Redirige a la página indicada
                    window.location.href = 'http://localhost/MVC/Vista/VistaLibros.php?pagina=' + pagina;
                } else {
                    // Si la respuesta es "Eliminado", reinicia o recarga
                    if (response.message === 'Eliminado') {
                        alert("El libro ya ha sido eliminado.");
                        window.location.href = 'http://localhost/MVC/Vista/VistaLibros.php?pagina=' + pagina;
                    } else {
                        alert(response.message);

                    }
                }
            },
            error: function() {
                alert('Ha ocurrido un error al intentar eliminar el libro.');

            }
        });
    }

});