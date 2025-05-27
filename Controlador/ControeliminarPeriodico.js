$(document).ready(function() {
    $(document).on('click', '.eliminar-libro', function(e) {
        e.preventDefault(); // ✅ Evita que la página cambie
        console.log("Botón de eliminar clickeado"); // 🛠️ Verifica que el evento se ejecuta

        const url = $(this).attr('href');
        if (confirm('¿Estás seguro de eliminar este periodico?')) {
            const id = url.split('id=')[1];
            console.log("ID de periodico a eliminar:", id); // 🛠️ Verifica que el ID es correcto

            $.ajax({
                url: '../Vista/EliminarPeriodicos.php?id=' + id,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log("Respuesta AJAX recibida:", response); // 🛠️ Verifica la respuesta

                    if (response.success) {
                        alert(response.message);
                        location.reload(); // ✅ Recarga la página sin redirigir
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error AJAX:", status, error);
                    alert('Error al intentar eliminar la revista.');
                }
            });
        }
    });
});