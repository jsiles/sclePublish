// Javascript Module
function showhide()
{
	//$("#op").toggle(500);
	$("#opOthers").toggle(500);
	$("#viewO").toggle();
	$("#closeO").toggle();
}
function showhideNews()
{
	$("#minutoN").toggle();
	$("#leidasN").toggle();
}
function restore(e)
{
    //document.getElementById('div'+e.id).innerHTML='';
    document.getElementById(e).className='inputB';
    document.getElementById('errorLog').style.display='none';
}
function Norestore()
{
    document.getElementById('username').className='req';
    document.getElementById('password').className='req';
    document.getElementById('errorLog').style.display='';
}
function GoNext()
{
    name=document.getElementById('username').value;
	paswd=document.getElementById('password').value;
	if (name!='' && paswd!='') document.userLogin.submit();
	else  Norestore();
}
function GoNext1()
{
	$("#username").keyup(function(e){
		if(e.keyCode==13) GoNext();
   	});
}
function GoNext2()
{
	$("#password").keyup(function(e){
		if(e.keyCode==13) GoNext();
   	});
}

function validate()
{
	sw=true;
	if (document.getElementById('search').value=='')
		{
		sw=false;
		scroll(0,0);
		}
	else{
		dato=document.getElementById('search').value;
		domainS=document.getElementById('dom').value;
		document.location.href=domain+'/'+domainS+'='+dato+'/'; 
		}
}

function validateF(){
	if (!document.getElementById('searchF').value){
		scroll(0,0);
	}
	else{
		dato=document.getElementById('searchF').value;
		domainS=document.getElementById('domainF').value;
		document.location.href=domainS+'='+dato+'/'; 
	}
}
function validate2()
{
		AdvHisto=document.getElementById('searchH').value;
		AdvDe=document.getElementById('dateH1').value;
		AdvHasta=document.getElementById('dateH2').value;
		AdvCateg=document.getElementById('categ').value;
		domain=document.getElementById('domi').value;
		domainS=document.getElementById('domS').value;
		document.location.href=domain+domainS+'='+AdvHisto+'_'+AdvDe+'_'+AdvHasta+'_'+AdvCateg+'/'; 
}
function validate3()
{
	$("#search").keyup(function(e){
		if(e.keyCode==13) validate();
   	});
}
function validateS()
{
	$("#searchH").keyup(function(e){
		if(e.keyCode==13) validate2();
   	});
}
 $(document).ready(function(){
	domain=document.getElementById('sd').value;
	var target = domain+'/code/login.php';
	
	$("#userLogin input").keypress(function(){
		$("#userLogin").attr('action',target);
		$('#lastURL').val(window.location.pathname);
	});
	
	$("#userLogin input").bind('paste', function(e) { 
    	$("#userLogin").attr('action',target);
		$('#lastURL').val(window.location.pathname);
   	});
	
	$('#logout').click(function(){
	 	location.href = domain+'/code/logout.php?lastURL='+window.location.pathname;						
	});
});