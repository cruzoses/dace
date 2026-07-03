function generarCodigoPreview() {
    var dia = $('#dia').val();
    var turno = $('#turno').val();
    var desde = $('#desde').val();
    var hasta = $('#hasta').val();

    if (dia && turno && desde && hasta) {
        var diaTexto = aDias[dia] || '';
        var turnoTexto = aTurnos[turno] || '';
        var prefijoDia = diaTexto.substring(0, 2).toUpperCase();
        var prefijoTurno = turnoTexto.substring(0, 2).toUpperCase();
        var codigo = prefijoDia + prefijoTurno + desde.replace(/ /g, '') + 'A' + hasta.replace(/ /g, '');
        $('#codigo').val(codigo);
    }
}

$(document).on('change', '#dia, #turno, #desde, #hasta', generarCodigoPreview);
