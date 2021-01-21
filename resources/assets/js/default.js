$(document).ready(function () {
	$('#main-container a[href]:not(".link-fx"):not(".btn")').addClass('link-fx');
	
	
	$('[data-plugin="timepicker"]').each(function () {
		$(this).flatpickr({
			enableTime: true,
			noCalendar: true,
			dateFormat: "H:i",
			time_24hr: true,
			allowInput: true,
		});
	});
	
	$('[data-plugin="durationpicker"]').each(function () {
		$(this).flatpickr({
			enableTime: true,
			noCalendar: true,
			dateFormat: "H:i",
			time_24hr: true,
			allowInput: true,
			minDate: "00:10",
			maxDate: "18:00",
		});
	});
	
	$('[data-plugin="flatpickr"]').each(function () {
		let elt = $(this);
		let hiddencalendar = elt.attr('data-noopenpicker') ? true : false;
		let mode = elt.attr('data-mode') ? elt.attr('data-mode') : 'single';
		let eltname = elt.attr('name');
		let options = {
			mode: mode,
			altInput: true,
			altFormat: "d/m/Y", //@TODO change format for internationalization
			allowInput:true,
			changeYear:true,
			clickOpens: !hiddencalendar,
		};
		if (mode === 'range'){
			options['onChange'] = function(values){
				if (values[0] && values[1]) {
					$('#'+eltname+'_start').val(moment(values[0]).format('YYYY-MM-DD'));
					$('#'+eltname+'_end').val(moment(values[1]).format('YYYY-MM-DD'));
				}
			}
		}
		
		let flat = elt.flatpickr(options);
		
		if (mode === 'range' && flat.selectedDates.length) {
			// initialisation des input hidden si range
			if (flat.selectedDates[0] && flat.selectedDates[1]) {
				$('#'+eltname+'_start').val(moment(flat.selectedDates[0]).format('YYYY-MM-DD'));
				$('#'+eltname+'_end').val(moment(flat.selectedDates[1]).format('YYYY-MM-DD'));
			}
		}
		
		if (hiddencalendar) {
			// si le datepicker n'est pas visible on valide la date manuellement et on remplis le champ hidden
			elt.siblings('input:visible').on('change', function () {
				var newdate = moment($(this).val(), 'DD/MM/YYYY');
				if (newdate.isValid()) {
					elt.val(newdate.format('YYYY-MM-DD'))
				} else {
					elt.val('');
				}
			})
		} else {
			// si le champ est vidé manuellement on enregistre la valeur vide dans le champ hidden
			elt.siblings('input:visible').on('change', function () {
				if ($(this).val() == '') {
					elt.val('');
				}
			})
		}
	});
	
	$('[data-plugin="timeflatpickr"]').each(function () {
		var hiddencalendar = $(this).attr('data-noopenpicker') ? true : false;
		var elt = $(this);
		elt.flatpickr({
			altInput: true,
			altFormat: "d/m/Y H:i",
			dateFormat: 'Y-m-d H:i:S',
			allowInput: true,
			enableTime: true,
			time_24hr: true,
			clickOpens: !hiddencalendar,
		});
		if (hiddencalendar) {
			// si le datepicker n'est pas visible on valide la date manuellement et on remplis le champ hidden
			elt.siblings('input:visible').on('change', function () {
				var newdate = moment($(this).val(), 'DD/MM/YYYY HH:mm');
				if (newdate.isValid()) {
					elt.val(newdate.format('YYYY-MM-DD HH:mm'))
				} else {
					elt.val('');
				}
			})
		} else {
			// si le champ est vidé manuellement on enregistre la valeur vide dans le champ hidden
			elt.siblings('input:visible').on('change', function () {
				if ($(this).val() == '') {
					elt.val('');
				}
			})
		}
	});

});