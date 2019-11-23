$(document).ready(function(){
	$('#range_rad').change(function(){
		$('#autocomplete').trigger('click');
		cityCircle.setRadius(+document.getElementById('range_rad').value*1000);
	});
	$('form button.btn-delete').click(function(e){
		e.preventDefault();
		if(confirm('Are you sure you want to delete this item? (Warning: This action cannot be undone)')){
			$(this).parent().submit();
		}else{
			return false;
		}
	});
});
