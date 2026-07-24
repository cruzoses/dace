var S2_OPTS = {
    language: 'es',
    placeholder: 'Seleccione una Opción',
    allowClear: true,
    width: 'resolve'
};

function cargarAsignaturas(programaIds, trayectoId, selectedValue) {
    var $asignatura = $('#asignatura-id');
    if (!programaIds || !programaIds.length || !trayectoId) {
        $asignatura.empty().append('<option value="" selected>Seleccione una Opción</option>');
        $asignatura.val('').trigger('change');
        return;
    }
    $.ajax({
        url: CURSOS_ASIGNATURAS_URL,
        type: 'GET',
        data: { programa_ids: programaIds, trayecto_id: trayectoId },
        dataType: 'json',
        beforeSend: function () {
            $asignatura.empty().append('<option value="">Cargando...</option>');
        }
    }).done(function (response) {
        $asignatura.empty().append('<option value="" selected>Seleccione una Opción</option>');
        $.each(response.asignaturas, function (value, text) {
            var selected = (value == selectedValue) ? ' selected' : '';
            $asignatura.append('<option value="' + value + '"' + selected + '>' + text + '</option>');
        });
        $asignatura.val(selectedValue || '').trigger('change');
    }).fail(function () {
        $asignatura.empty().append('<option value="" selected>Error al cargar asignaturas</option>');
    });
}

function cargarAulas(sedeId, selectedValue) {
    var $aula = $('#aula-id');
    if (!sedeId) {
        $aula.empty().append('<option value="" selected>Seleccione una Opción</option>');
        $aula.val('').trigger('change');
        return;
    }
    $.ajax({
        url: CURSOS_AULAS_URL,
        type: 'GET',
        data: { sede_id: sedeId },
        dataType: 'json',
        beforeSend: function () {
            $aula.empty().append('<option value="">Cargando...</option>');
        }
    }).done(function (response) {
        $aula.empty().append('<option value="" selected>Seleccione una Opción</option>');
        $.each(response.aulas, function (value, text) {
            var selected = (value == selectedValue) ? ' selected' : '';
            $aula.append('<option value="' + value + '"' + selected + '>' + text + '</option>');
        });
        $aula.val(selectedValue || '').trigger('change');
    }).fail(function () {
        $aula.empty().append('<option value="" selected>Error al cargar aulas</option>');
    });
}

function filterHorarios(sedeId, periodoId, selectedValues) {
    var $h = $('#horario');
    var all = (typeof CURSOS_HORARIOS_ALL !== 'undefined') ? CURSOS_HORARIOS_ALL : {};
    var selected = selectedValues || [];

    $h.empty();
    $h.append('<option value="">Seleccione una Opción</option>');

    $.each(all, function (codigo, info) {
        if (sedeId && info.sede_id !== String(sedeId)) return;
        if (periodoId && info.periodo_id !== String(periodoId)) return;
        var isSel = selected.indexOf(codigo) !== -1;
        $h.append('<option value="' + codigo + '"' + (isSel ? ' selected' : '') + '>' + codigo + '</option>');
    });

    if (selected.length) {
        $h.val(selected);
    }
    $h.trigger('change');
}

function initCursos() {
    var esEdicion = typeof CURSOS_ASIGNATURA_ACTUAL !== 'undefined';
    var initialCarrera = $('#carrera-id').val();
    var initialTrayecto = $('#trayecto-id').val();
    var initialSede = $('#sede-id').val();
    var initialPeriodo = $('#periodo-id').val();
    var initialHorario = typeof CURSOS_HORARIO_ACTUAL !== 'undefined' ? CURSOS_HORARIO_ACTUAL : [];

    if (!initialTrayecto) {
        $('#asignatura-id').empty().append('<option value="" selected>Seleccione una Opción</option>');
    }

    filterHorarios(initialSede, initialPeriodo, initialHorario);

    $('#carrera-id').on('change', function () {
        if (!esEdicion) {
            $('#asignatura-id').empty().append('<option value="" selected>Seleccione una Opción</option>');
        }
    });

    $('#trayecto-id').on('change', function () {
        var checkedProgramas = $('#programas-checkbox input:checked').map(function () {
            return $(this).val();
        }).get();
        cargarAsignaturas(checkedProgramas, $(this).val(), esEdicion ? CURSOS_ASIGNATURA_ACTUAL : null);
    });

    $(document).on('change', '#programas-checkbox input[type=checkbox]', function () {
        var trayectoId = $('#trayecto-id').val();
        var checkedProgramas = $('#programas-checkbox input:checked').map(function () {
            return $(this).val();
        }).get();
        cargarAsignaturas(checkedProgramas, trayectoId, esEdicion ? CURSOS_ASIGNATURA_ACTUAL : null);
    });

    if (initialSede) {
        var aulaActual = esEdicion && typeof CURSOS_AULA_ACTUAL !== 'undefined' ? CURSOS_AULA_ACTUAL : $('#aula-id').val();
        cargarAulas(initialSede, aulaActual);
    } else {
        $('#aula-id').empty().append('<option value="" selected>Seleccione una Opción</option>');
    }

    $('#sede-id').on('change', function () {
        var sedeId = $(this).val();
        var periodoId = $('#periodo-id').val();
        cargarAulas(sedeId, null);
        filterHorarios(sedeId, periodoId, null);
    });

    $('#periodo-id').on('change', function () {
        var sedeId = $('#sede-id').val();
        filterHorarios(sedeId, $(this).val(), null);
    });

    if (esEdicion && CURSOS_ASIGNATURA_ACTUAL) {
        $('#asignatura-id').val(CURSOS_ASIGNATURA_ACTUAL).trigger('change');
    }
}

$(document).ready(function () {
    if ($('#horario').length) {
        $('#horario').select2(S2_OPTS);
    }
});
