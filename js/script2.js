//data
jQuery.validator.addMethod("dateBR", function (value, element) {
//contando chars
if (value.length != 10) return (this.optional(element) || false);
// verificando data
var data = value;
var dia = data.substr(0, 2);
var barra1 = data.substr(2, 1);
var mes = data.substr(3, 2);
var barra2 = data.substr(5, 1);
var ano = data.substr(6, 4);
if (data.length != 10 || barra1 != "/" || barra2 != "/" || isNaN(dia) || isNaN(mes) || isNaN(ano) || dia > 31 || mes > 12) return (this.optional(element) || false);
if ((mes == 4 || mes == 6 || mes == 9 || mes == 11) && dia == 31) return (this.optional(element) || false);
if (mes == 2 && (dia > 29 || (dia == 29 && ano % 4 != 0))) return (this.optional(element) || false);
if (ano < 1900) return (this.optional(element) || false);
return (this.optional(element) || true);
}, "Informe uma data válida"); // Mensagem padrão 

jQuery.validator.addMethod("timerbr", function (value, element) {
if (value.length != 5) return false;
var data = value;
var hor = data.substr(0, 2);
var se1 = data.substr(2, 1);
var min = data.substr(3, 2);
if (data.length != 5 || se1 != ':' || isNaN(hor) || isNaN(min)){
return false;
}
if (!((hor>=0 && hor<=23) && (min>=0 && min<=59))){
return false;
}
return true;
}, "Por favor, um hora válida"); 

function customRange() {
	var minDate = $('.dt1').val();
	return {
		minDate: minDate
	
	};
}

$('.dt1').datepicker({
	minDate: new Date()
});

$('.dt2').datepicker({
	beforeShow: customRange
});
//fim data
$('.moeda').mask("#.##0,00", {reverse: true});
//$('.moeda').maskMoney({decimal:",", thousands:"."});


function Url(url){
	window.location.href = url;
}

function Alert(msg){
	alert(msg);
}


function NewWindow(mypage, myname, w, h, scroll) {
var winl = (screen.width - w) / 2;
var wint = (screen.height - h) / 2;
winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable'
win = window.open(mypage, myname, winprops)
if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}


$(document).ready(function(e) {
 
	$('body').attr('onunload', 'window.opener.location.reload()');

	//voltar
	$('.voltar, .b-voltar').click(function(){
		window.history.back();
	});
	//fim voltar
	//voltar
	$('.fechar').click(function(){
		window.close();
	});
	//fim voltar
	
	$(".data").mask("99/99/9999");
$(".data").datepicker({
	showOtherMonths: true,
	selectOtherMonths: true,
	dateFormat: 'dd/mm/yy',
	dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
	dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
	dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
	monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
	monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
	nextText: 'Próximo',
	prevText: 'Anterior'	
});
	
	//alerts
	$('.delete').click(function()
	{
		var msg = $(this).attr('rel');
		return confirm(msg)
	});
	//fim alerts
	
	//mask
	// jQuery Masked Input
	$(".numero").numeric({negative: false, decimal: true});

	$(".hora").mask("99:99");
	$(".cep").mask("99999-999");
	
	

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
	//fim mask

});

