function uuidv4() {
	return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
		var r = Math.random() * 16 | 0,
			v = c == 'x' ? r : (r & 0x3 | 0x8);
		return v.toString(16);
	});
}


$(document).ready(function () {


	if (_lastStoredStep != _currentStep) {
		window.location.href = _basePath + '/' + _lastStoredStep;
	}
	// Toolbar extra buttons
	var btnFinish = $('<button></button>').text('Finish')
		.addClass('btn btn-info')
		.on('click', function () {
			if (!$(this).hasClass('disabled')) {
				var elmForm = $("#myForm");
				if (elmForm) {
					elmForm.validator('validate');
					var elmErr = elmForm.find('.has-error');
					if (elmErr && elmErr.length > 0) {
						alert('Oops we still have error in the form');
						return false;
					} else {
						alert('Great! we are ready to submit form');
						elmForm.submit();
						return false;
					}
				}
			}
		});
	var btnCancel = $('<button></button>').text('Cancel')
		.addClass('btn btn-danger')
		.on('click', function () {
			$('#smartwizard').smartWizard("reset");
			$('#myForm').find("input, textarea").val("");
		});


	// Smart Wizard
	$('#smartwizard').smartWizard({
		selected: 0,
		theme: 'dots',
		transitionEffect: 'fade',
		toolbarSettings: {
			toolbarPosition: 'bottom',
			toolbarExtraButtons: [btnFinish, btnCancel]
		},
		anchorSettings: {
			markDoneStep: true, // add done css
			markAllPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
			removeDoneStepOnNavigateBack: true, // While navigate back done step after active step will be cleared
			enableAnchorOnDoneStep: true // Enable/Disable the done steps navigation
		}
	});

	$("#smartwizard").on("leaveStep", function (e, anchorObject, stepNumber, stepDirection) {
		var elmForm = $("#form-step-" + stepNumber);
		// stepDirection === 'forward' :- this condition allows to do the form validation
		// only on forward navigation, that makes easy navigation on backwards still do the validation when going next
		if (stepDirection === 'forward' && elmForm) {
			elmForm.validator('validate');
			var elmErr = elmForm.children('.has-error');
			if (elmErr && elmErr.length > 0) {
				// Form validation failed
				return false;
			} else {

				if (typeof $.cookie("unique_cookie_id") == "undefined") {

					var expDate = new Date();

					expDate.setTime(expDate.getTime() + (86400 * 60 * 1000)); // 60 days expiry

					$.cookie('unique_cookie_id', uuidv4(), {
						path: '/',
						expires: expDate
					});
				}

				$.ajax({
					url: _basePath + '/save-temp-form',
					type: 'POST',
					data: {
						formData: $('form#myForm').serialize(),
						csrf_token: $('[name="csrf_token"]').val(),
						stepNumber: stepNumber
					},
					success: function () {

					},
					error: function () {
						alert('Some internal problem. Please try again.');
					}
				});

			}
		}
		return true;
	});

	$("#smartwizard").on("showStep", function (e, anchorObject, stepNumber, stepDirection) {
		// Enable finish button only on last step
		if (stepNumber == 3) {
			$('.btn-finish').removeClass('disabled');
		} else {
			$('.btn-finish').addClass('disabled');
		}
	});

});