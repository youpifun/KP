function rejectReceipt(id){
    $.ajax({
        type: 'post',
        url: "profile/rejecter.php",
        data: {'sell_id':id},
        response: 'text',
        success: function(data){
			//window.location.reload(true); 
		}
	})
}