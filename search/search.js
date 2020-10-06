function test() {
	if (window.productNames != undefined)
	window.location.replace(location.protocol + '//' + location.host + location.pathname + '?ids=' + window.productNames);
}

$(function(){
$('.form-control').bind("change keyup input click", function() {
    if(this.value.length >= 2){
        $.ajax({
            type: 'post',
            url: "search/search.php", //Путь к обработчику
            data: {'products':this.value},
            response: 'text',
            success: function(data){
				if (data != null) {
				var productArrays = data.split(';');
				if (productArrays.length == 2) {
				var productIds = productArrays[0].split(',');
				var productNames = productArrays[1].split(',');
				window.productNames = productArrays[0];
				var div = document.querySelector('.search_result');
				div.innerHTML = '';
				for (var i=0; i<productIds.length; i++) {
					var a = document.createElement('a');
					var li = document.createElement('li');
					a.href = 'catalog.php?ids=' + productIds[i];
					a.innerHTML = productNames[i];
					li.appendChild(a);
					div.appendChild(li);
					$('.search_result').fadeIn();
				} } else {$('.search_result').fadeOut();}
			}}
       })
    }
})
    
$(".search_result").hover(function(){
    $(".who").blur(); //Убираем фокус с input
})
    
//При выборе результата поиска, прячем список и заносим выбранный результат в input
$(".search_result").on("click", "li", function(){
    s_user = $(this).text();
    //$(".who").val(s_user).attr('disabled', 'disabled'); //деактивируем input, если нужно
    $(".search_result").fadeOut();
})
})

