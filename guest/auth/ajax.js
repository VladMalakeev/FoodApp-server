$(document).ready(function(){
	$("#form").submit(function(){
		var form_data $(this).serialize();;
		$.ajax({
			url: 'guest/auth/auth.php',
			type:'POST',
			data:,
			success: function(){
				document.write('success');
			}
		})
	})
});