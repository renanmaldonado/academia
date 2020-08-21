$('body').attr('onunload', 'window.opener.location.reload()');


function Url(url){
	window.location.href = url;
}
$('.voltar, .b-voltar').click(function(){
	window.history.back(-1);
});
$('.fechar').click(function(){
	window.close();
});
function NewWindow(mypage, myname, w, h, scroll) {
var winl = (screen.width - w) / 2;
var wint = (screen.height - h) / 2;
winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable'
win = window.open(mypage, myname, winprops)
if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
};

$('.delete').click(function()
{
	var msg = $(this).attr('rel');
	return confirm(msg)
});


$('.moeda').focus(function(){
	$(this).val('');
});
$('.moeda').focusout(function(e) {
    if($(this).val() == '')
	{
		$(this).val('0,00');
	}
});

//$('.moeda').mask("#.##0,00", {reverse: true});	
$('.moeda').maskMoney({allowNegative: false, thousands:'.', decimal:',', affixesStay: false});

$('.telefone').mask("(99) 9999-9999?9").ready(function(event) {
	var target, phone, element;
	target = (event.currentTarget) ? event.currentTarget : event.srcElement;
	phone = target.value.replace(/\D/g, '');
	element = $(target);
	element.unmask();
	if(phone.length > 10) {
	element.mask("(99) 99999-999?9");
	} else {
	element.mask("(99) 9999-9999?9");
	}
}); 
$(".data").mask("99/99/9999");
$(".hora").mask("99:99");
$(".hora").attr("placeholder", "hh:mm");

$(".cep").mask("99999-999");

function customRange() {
	var minDate = $('.data.dt1').val();
	return {
		minDate: minDate
	
	};
}
$('.data.dt1').datepicker({
	minDate: new Date()
});

$('.data.dt2').datepicker({
	beforeShow: customRange
});

function customRange2() {
	var minDate = $('.data.dt1b').val();
	return {
		minDate: minDate
	
	};
}
$('.data.dt1b').datepicker({
	maxDate: new Date()
});

$('.data.dt2b').datepicker({
	beforeShow: customRange2
});

$(".numero").numeric({negative: false, decimal: true});

$(document).ready(function(e) {
//mask
$(".numero").numeric({negative: false, decimal: true});
//fim mask

});

