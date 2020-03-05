function doOnLoad() {
    $('.btnEnviarNota').on('click', function (event) {
        var nota = $('.nota').val();

        $.ajax({
            url: './ajax_procesar_nota.php',
            data: { value: nota },
            dataType: 'json',
            type: 'POST',
            success: function (data) {
                if (data.response == 'Success') {
                    alert('Tu nota fue grabada');
                } else {
                    alert('Hubo un error al grabar tu nota');
                }
            },
            error: function (jxhr, msg, err) {
                alert('Hubo un error al grabar tu nota');
            }
        });

    });
}

window.onload=doOnLoad;