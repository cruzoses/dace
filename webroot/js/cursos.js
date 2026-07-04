function cargarAsignaturas(programaId, trayectoId, selectedValue) {
    var $asignatura = $('#asignatura-id');
    if (!programaId || !trayectoId) {
        $asignatura.empty().append('<option value="" selected>Seleccione una Opción</option>');
        $asignatura.val('').trigger('change');
        return;
    }
    $.ajax({
        url: CURSOS_ASIGNATURAS_URL,
        type: 'GET',
        data: { programa_id: programaId, trayecto_id: trayectoId },
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

function cargarProgramas(carreraId, selectedValue) {
    var $programa = $('#programa-id');
    if (!carreraId) {
        $programa.empty().append('<option value="" selected>Seleccione una Opción</option>');
        $programa.val('').trigger('change');
        return;
    }
    $.ajax({
        url: CURSOS_PROGRAMAS_URL,
        type: 'GET',
        data: { carrera_id: carreraId },
        dataType: 'json',
        beforeSend: function () {
            $programa.empty().append('<option value="">Cargando...</option>');
        }
    }).done(function (response) {
        $programa.empty().append('<option value="" selected>Seleccione una Opción</option>');
        $.each(response.programas, function (value, text) {
            var selected = (value == selectedValue) ? ' selected' : '';
            $programa.append('<option value="' + value + '"' + selected + '>' + text + '</option>');
        });
        $programa.val(selectedValue || '').trigger('change');
    }).fail(function () {
        $programa.empty().append('<option value="" selected>Error al cargar programas</option>');
    });
}

function cargarHorarios(sedeId, periodoId, selectedValue) {
    var $horario = $('#horario');
    if (!sedeId || !periodoId) {
        $horario.empty().append('<option value="" selected>Seleccione una Opción</option>');
        $horario.val('').trigger('change');
        return;
    }
    $.ajax({
        url: CURSOS_HORARIOS_URL,
        type: 'GET',
        data: { sede_id: sedeId, periodo_id: periodoId },
        dataType: 'json',
        beforeSend: function () {
            $horario.empty().append('<option value="">Cargando...</option>');
        }
    }).done(function (response) {
        $horario.empty().append('<option value="" selected>Seleccione una Opción</option>');
        $.each(response.horarios, function (value, text) {
            var selected = (value == selectedValue) ? ' selected' : '';
            $horario.append('<option value="' + value + '"' + selected + '>' + text + '</option>');
        });
        $horario.val(selectedValue || '').trigger('change');
    }).fail(function () {
        $horario.empty().append('<option value="" selected>Error al cargar horarios</option>');
    });
}

function initCursos() {
    var initialCarrera = $('#carrera-id').val();
    var initialPrograma = $('#programa-id').val();
    var initialTrayecto = $('#trayecto-id').val();
    var initialAsignatura = $('#asignatura-id').val();
    var initialSede = $('#sede-id').val();
    var initialPeriodo = $('#periodo-id').val();
    var initialHorario = $('#horario').val();

    if (initialCarrera) {
        cargarProgramas(initialCarrera, initialPrograma);
    } else {
        $('#programa-id').empty().append('<option value="" selected>Seleccione una Opción</option>');
    }

    if (initialPrograma && initialTrayecto) {
        cargarAsignaturas(initialPrograma, initialTrayecto, initialAsignatura);
    } else {
        $('#asignatura-id').empty().append('<option value="" selected>Seleccione una Opción</option>');
    }

    if (initialSede && initialPeriodo) {
        cargarHorarios(initialSede, initialPeriodo, initialHorario);
    } else {
        $('#horario').empty().append('<option value="" selected>Seleccione una Opción</option>');
    }

    $('#carrera-id').on('change', function () {
        cargarProgramas($(this).val(), null);
        $('#asignatura-id').empty().append('<option value="" selected>Seleccione una Opción</option>');
    });

    $('#programa-id').on('change', function () {
        var trayectoId = $('#trayecto-id').val();
        cargarAsignaturas($(this).val(), trayectoId, null);
    });

    $('#trayecto-id').on('change', function () {
        var programaId = $('#programa-id').val();
        cargarAsignaturas(programaId, $(this).val(), null);
    });

    $('#sede-id').on('change', function () {
        var periodoId = $('#periodo-id').val();
        cargarHorarios($(this).val(), periodoId, null);
    });

    $('#periodo-id').on('change', function () {
        var sedeId = $('#sede-id').val();
        cargarHorarios(sedeId, $(this).val(), null);
    });

    $('#aula-id').on('change', function () {
        var sedeId = $('#sede-id').val();
        var periodoId = $('#periodo-id').val();
        cargarHorarios(sedeId, periodoId, null);
    });
}
