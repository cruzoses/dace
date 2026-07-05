function cargarEstados(paisId, selectedValue) {
    var $estado = $('#estado-id');
    if (!paisId) {
        $estado.empty().append('<option value="" selected>Seleccione una Opción</option>');
        $estado.val('').trigger('change');
        $('#municipio-id').empty().append('<option value="" selected>Seleccione una Opción</option>');
        $('#municipio-id').val('').trigger('change');
        $('#parroquia-id').empty().append('<option value="" selected>Seleccione una Opción</option>');
        $('#parroquia-id').val('').trigger('change');
        return $.Deferred().resolve().promise();
    }
    return $.ajax({
        url: ESTUDIANTES_ESTADOS_URL,
        type: 'GET',
        data: { pais_id: paisId },
        dataType: 'json',
        beforeSend: function () { $estado.empty().append('<option value="">Cargando...</option>'); }
    }).done(function (response) {
        $estado.empty().append('<option value="" selected>Seleccione una Opción</option>');
        $.each(response.estados, function (value, text) {
            var selected = (value == selectedValue) ? ' selected' : '';
            $estado.append('<option value="' + value + '"' + selected + '>' + text + '</option>');
        });
        $estado.val(selectedValue || '').trigger('change');
    }).fail(function () {
        $estado.empty().append('<option value="" selected>Error al cargar estados</option>');
    });
}

function cargarMunicipios(estadoId, selectedValue) {
    var $municipio = $('#municipio-id');
    if (!estadoId) {
        $municipio.empty().append('<option value="" selected>Seleccione una Opción</option>');
        $municipio.val('').trigger('change');
        $('#parroquia-id').empty().append('<option value="" selected>Seleccione una Opción</option>');
        $('#parroquia-id').val('').trigger('change');
        return $.Deferred().resolve().promise();
    }
    return $.ajax({
        url: ESTUDIANTES_MUNICIPIOS_URL,
        type: 'GET',
        data: { estado_id: estadoId },
        dataType: 'json',
        beforeSend: function () { $municipio.empty().append('<option value="">Cargando...</option>'); }
    }).done(function (response) {
        $municipio.empty().append('<option value="" selected>Seleccione una Opción</option>');
        $.each(response.municipios, function (value, text) {
            var selected = (value == selectedValue) ? ' selected' : '';
            $municipio.append('<option value="' + value + '"' + selected + '>' + text + '</option>');
        });
        $municipio.val(selectedValue || '').trigger('change');
    }).fail(function () {
        $municipio.empty().append('<option value="" selected>Error al cargar municipios</option>');
    });
}

function cargarParroquias(municipioId, selectedValue) {
    var $parroquia = $('#parroquia-id');
    if (!municipioId) {
        $parroquia.empty().append('<option value="" selected>Seleccione una Opción</option>');
        $parroquia.val('').trigger('change');
        return $.Deferred().resolve().promise();
    }
    return $.ajax({
        url: ESTUDIANTES_PARROQUIAS_URL,
        type: 'GET',
        data: { municipio_id: municipioId },
        dataType: 'json',
        beforeSend: function () { $parroquia.empty().append('<option value="">Cargando...</option>'); }
    }).done(function (response) {
        $parroquia.empty().append('<option value="" selected>Seleccione una Opción</option>');
        $.each(response.parroquias, function (value, text) {
            var selected = (value == selectedValue) ? ' selected' : '';
            $parroquia.append('<option value="' + value + '"' + selected + '>' + text + '</option>');
        });
        $parroquia.val(selectedValue || '').trigger('change');
    }).fail(function () {
        $parroquia.empty().append('<option value="" selected>Error al cargar parroquias</option>');
    });
}
