$(document).ready(function() {
	$.post('/admin/getMerchant', function(data) {
		$('#xmpayBal').text(data);
	});
	$.post('/admin/getMerchantFK', function(data) {
		$('#fkBal').text(data);
	});
	$.post('/admin/getMerchantGet', function(data) {
		$('#getpayBal').text(data);
	});
	$.post('/admin/getMerchantPias', function(data) {
		$('#piasBal').text(data);
	});
	$(document).on('click', '.versionUpdate', function () {
		$.post('/admin/versionUpdate')
		.then(e => {
			if(e.success) {
				$.notify({
	                type: 'success',
	                message: e.msg
	            });
			}
		})
		.fail(() => {
			$.notify({
	            type: 'error',
	            message: 'Ошибка на стороне сервера'
	        });
		})
	})
});