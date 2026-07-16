$(document).ready( function () {
    $('.isUpper').keyup(function (e) { 
        $(this).val($(this).val().toUpperCase());
    });

    $('.isLower').keyup(function (e) { 
        $(this).val($(this).val().toLowerCase());
    });

    $('.isCualitativa').on('input', function (evt) {
        $(this).val($(this).val().toUpperCase());
        $(this).val($(this).val().replace(/[^AR]/g, ''));
    });

    $('#goBack').click(function (e) { 
        e.preventDefault();
        let url = "<?= $this->Url->build('/');?>";
        window.location.href = "/";
    });

    $('#resetForm').click(function (e) { 
        e.preventDefault();
        oForm = $(this).closest('form');
        oForm.find('input:text').each( function(nIdx,sField){
            setTimeout(function(){
                $(sField).val('')
            },100);
        })
    });

    $('.isNumeric').on('input', function() {
        this.value = this.value.replace(/\D/g, '');
        //$(this).val($(this).val().replace(/[^0-9]/g, ''));
    });

    $('.isDecimal').on('input', function() {
        this.value = this.value.replace(/[^0-9.]/g, '')
            .replace(/(\..*?)\./g, '$1')
            .replace(/(\.\d{2})\d+/g, '$1');
    });


    $('.select2').select2({
        language: 'es',
        placeholder: 'Seleccione una Opción',
        allowClear: true,
        width: 'resolve',
    });

    $('.multiValue').select2({
        language: 'es',
        tags: true,
        tokenSeparators: [''],
        placeholder: 'Seleccione una Opción',
        allowClear: true,
        width: 'resolve'
    });

    // Datepicker
    $('.calendario').datepicker({
		language: "es",
		format: 'dd-mm-yyyy',
		icons: {
			date: "fa fa-calendar",
			up:   "fa fa-arrow-up",
			down: "fa fa-arrow-down"
		},
        orientation: "bottom",
		changeMonth: true,
		changeYear: true,
		todayHighlight: true,
		showButtonPanel: true,
		todayHighlight: true,
      	autoclose: true,
		onSelect: function( dateText, inst){
			$('.calendario').val(dateText);
			$(".calendario").datepicker("destroy");
            showMessage(dateText);
		}
    });

    $('.yearList').datepicker({
        language: "es",
        format: "yyyy",
        weekStart: 1,
        orientation: "bottom",       
        keyboardNavigation: false,
        viewMode: "years",
        minViewMode: "years",
        todayHighlight: true,
        autoclose: true,
    });

    $('.dateYear').datepicker({
        language: "es",
        format: "yyyy",
        weekStart: 1,
        orientation: "bottom",       
        keyboardNavigation: false,
        viewMode: "years",
        minViewMode: "years",
        todayHighlight: true,
        autoclose: true,
        clearBtn: false
    });

    $('.datepicker').datepicker({
		language: "es",
		format: "dd-mm-yyyy",
		icons: {
			date: "fa fa-calendar",
			up:   "fa fa-arrow-up",
			down: "fa fa-arrow-down"
		},
        orientation: "bottom",
        immediateUpdates: true,
		changeMonth: true,
		changeYear: true,
		todayHighlight: true,
		showButtonPanel: true,
		todayHighlight: true,
      	autoclose: true,
		onSelect: function( dateText, inst){
			$('.datepicker').val(dateText);
			$(".datepicker").datepicker("destroy");
		}
    });

    $('.timepicker').timepicker({
        showInputs: false,
        minuteStep: 5,
    });

    $('#goSearch').click(function (e) {
        e.preventDefault();
        if ( $('#buscar').is(':visible') ) 
        {
            $('#load').hide();
			$('#buscar').hide();
		} else {
			$('#buscar').show();
		}
	});

    $('#goPrint').click(function (e) {
        e.preventDefault();
        if ( $('#printform').is(':visible') ) 
        {            
			$('#printform').hide();
		} else {
			$('#printform').show();
		}
	});

	$('#buscadorForm').submit(function (e) { 
		$('#load').show();
	});

	$('#reportBuilder').submit(function (e) { 
		$('#load').show();
	});


});

function DateAndTime() 
{
 var dias = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
    var meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                 'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    var ahora = new Date();
    var dia = dias[ahora.getDay()];
    var fecha = ahora.getDate() + ' de ' + meses[ahora.getMonth()] + ' de ' + ahora.getFullYear();
    var h = ahora.getHours();
    var ampm = h >= 12 ? 'PM' : 'AM';
    h = h % 12 || 12;
    var hora = h.toString().padStart(2,'0') + ':' +
               ahora.getMinutes().toString().padStart(2,'0') + ':' +
               ahora.getSeconds().toString().padStart(2,'0') + ' ' + ampm;
    //return dia + ', ' + fecha + ' ' + hora;
    var currentDateTime = dia + ', ' + fecha + ' ' + hora;
	$('#time').html( "<strong><i class='fa fa-calendar'></i>&nbsp;" + currentDateTime + "</strong>");
	setTimeout(function() {
		DateAndTime()
	}, 500	);    
}

function showMessage(sText,sImage){
    Swal.fire({
        title: sText,
        text: "S.A.C.E",
        icon: sImage === null ? "success" : sImage
    });
}

// Intercept postLink confirm() con SweetAlert2 (fase de captura para evitar el confirm nativo)
document.addEventListener('click', function(e) {
    var target = e.target.closest('a[onclick*="confirm("]');
    if (!target) return;

    e.stopPropagation();
    e.preventDefault();

    var onclick = target.getAttribute('onclick');
    var match = onclick.match(/confirm\s*\(\s*"([^"]*)"\s*\)/);
    if (!match) return;
    var message = JSON.parse('"' + match[1] + '"');
    var formMatch = onclick.match(/document\.(\w+)\.submit/);
    var formName = formMatch ? formMatch[1] : null;

    var confirmText = target.getAttribute('data-confirm-text') || 'Sí, eliminar';
    var confirmColor = target.getAttribute('data-confirm-color') || '#d33';

    Swal.fire({
        title: '¿Está seguro?',
        text: message,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: confirmColor,
        cancelButtonColor: '#3085d6',
        confirmButtonText: confirmText,
        cancelButtonText: 'Cancelar'
    }).then(function(result) {
        if (result.isConfirmed && formName) {
            document[formName].submit();
        }
    });
}, true);
