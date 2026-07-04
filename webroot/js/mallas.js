function cargarProgramas(carreraId, selectedValue) {
    var $programa = $('#programa-id');
    if (!carreraId) {
        $programa.empty().append('<option value="" selected>-- Programa --</option>');
        $programa.val('').trigger('change');
        return;
    }
    $.ajax({
        url: MALLAS_PROGRAMAS_URL,
        type: 'GET',
        data: { carrera_id: carreraId },
        dataType: 'json',
        beforeSend: function () {
            $programa.empty().append('<option value="">Cargando...</option>');
        }
    }).done(function (response) {
        $programa.empty().append('<option value="" selected>-- Programa --</option>');
        $.each(response.programas, function (value, text) {
            var selected = (value == selectedValue) ? ' selected' : '';
            $programa.append('<option value="' + value + '"' + selected + '>' + text + '</option>');
        });
        $programa.val(selectedValue || '').trigger('change');
    }).fail(function () {
        $programa.empty().append('<option value="" selected>Error al cargar programas</option>');
    });
}

function initMallasBuscar() {
    var initialCarrera = $('#carrera-id').val();
    var initialPrograma = $('#programa-id').val();

    if (!initialCarrera) {
        $('#programa-id').empty().append('<option value="" selected>-- Programa --</option>');
    }

    if (initialCarrera && initialPrograma) {
        cargarProgramas(initialCarrera, initialPrograma);
    }

    $('#carrera-id').on('change', function () {
        cargarProgramas($(this).val(), null);
    });
}

function initMallasReporte() {
    if (!$('#carrera-id').length) return;

    var initialCarrera = $('#carrera-id').val();
    var initialPrograma = $('#programa-id').val();

    if (!initialCarrera) {
        $('#programa-id').empty().append('<option value="" selected>-- Todos los Programas --</option>');
    }

    if (initialCarrera && initialPrograma) {
        cargarProgramas(initialCarrera, initialPrograma);
    }

    $('#carrera-id').on('change', function () {
        cargarProgramas($(this).val(), null);
    });
}
