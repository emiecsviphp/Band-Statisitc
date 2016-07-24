var XMLHttpRequestObject = false;
try {
	XMLHttpRequestObject = new ActiveXObject("MSXML2.XMLHTTP");
}
catch (exception1) {
	try {
		XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
	}
	catch (exception2) {
		XMLHttpRequestObject = false;
	}
}
if (!XMLHttpRequestObject && window.XMLHttpRequest) {
	XMLHttpRequestObject = new XMLHttpRequest();
}

function saveUserShows()	{
	var current_userid = document.getElementById('current_userid').value;
	var allVals = '';
	var inputs = document.getElementsByTagName("input");	
	var cbs = [];
	var checked = [];
	var counter = 0;
	for (var i = 0; i < inputs.length; i++) {
	  //if (inputs[i].type == "checkbox") {
		if ((inputs[i].type == "checkbox")  && (inputs[i].name == "showid")){
		cbs.push(inputs[i]);
		if (inputs[i].checked) {
		  counter++;	
		  allVals = allVals + ',' + inputs[i].value;
		}
	  }
	}
	
	if(current_userid < 1)	{
		//alert('You are required to login in the forums!');
		document.getElementById('message').innerHTML = 
			'<div style="background:none repeat scroll 0 0 #FF0080;clear:both;color:#FFF;font-size:14px;margin-bottom:5px;padding:5px 10px;text-align:left;">You are required to login in the forums!</div>';
	}
	else if(allVals == '')	{
		//alert('Please select a show!');
		document.getElementById('message').innerHTML = 
			'<div style="background:none repeat scroll 0 0 #FF0080;clear:both;color:#FFF;font-size:14px;margin-bottom:5px;padding:5px 10px;text-align:left;">Please select from the list of unattended shows!</div>';		
	}
	else	{
		var obj = document.getElementById("diplayshows_unattended");
		var url = "/jf/wp-content/plugins/bandstatistic/mystats/mystatsprocess.php";
		if(XMLHttpRequestObject) {
			XMLHttpRequestObject.open("POST", url);
			XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			XMLHttpRequestObject.onreadystatechange = 
				function(){
					if (XMLHttpRequestObject.readyState == 1) {
						obj.innerHTML = "<div align='center'><img src='/jf/wp-content/plugins/bandstatistic/icons/loading.gif' alt='loading...' title='loading...' width='50' height='50' style='color:#33cc33'/></div>";
					}
					if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
							obj.innerHTML = XMLHttpRequestObject.responseText;
							//document.getElementById('message').innerHTML = 
								//'<div style="background:none repeat scroll 0 0 #FFEB90;clear:both;color:#000;font-size:14px;margin-bottom:5px;padding:5px 10px;text-align:left;">Show successfully added.</div>';
								//alert('Show successfully added.');
								//alert('You successfully join the show.');
								window.location.href="/misc.php?do=page&template=Edit_My_Show_Stats&message=savesuccessful&counter="+counter+"";
					}
				}
			XMLHttpRequestObject.send('&current_userid=' + current_userid + '&allVals=' + allVals + '&task=addShowUserAttended');
		}
	}

}

function removeUserShows()	{
	var current_userid = document.getElementById('current_userid').value;
	var allVals = '';
	var inputs = document.getElementsByTagName("input");	
	var cbs = [];
	var checked = [];
	var counter = 0;
	for (var i = 0; i < inputs.length; i++) {
		if ((inputs[i].type == "checkbox")  && (inputs[i].name == "my_showid")){
		cbs.push(inputs[i]);
		if (inputs[i].checked) {
		  counter++;	
		  allVals = allVals + ',' + inputs[i].value;
		}
	  }
	}
	
	if(current_userid < 1)	{
		document.getElementById('message').innerHTML = 
			'<div style="background:none repeat scroll 0 0 #FF0080;clear:both;color:#FFF;font-size:14px;margin-bottom:5px;padding:5px 10px;text-align:left;">You are required to login in the forums!</div>';
	}
	else if(allVals == '')	{
		document.getElementById('message').innerHTML = 
			'<div style="background:none repeat scroll 0 0 #FF0080;clear:both;color:#FFF;font-size:14px;margin-bottom:5px;padding:5px 10px;text-align:left;">Please select from the list of attended shows!</div>';
	}
	else	{
		var obj = document.getElementById("diplayshows_attended");
		var url = "/jf/wp-content/plugins/bandstatistic/mystats/mystatsprocess.php";
		if(XMLHttpRequestObject) {
			XMLHttpRequestObject.open("POST", url);
			XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			XMLHttpRequestObject.onreadystatechange = 
				function(){
					if (XMLHttpRequestObject.readyState == 1) {
						obj.innerHTML = "<div align='center'><img src='/jf/wp-content/plugins/bandstatistic/icons/loading.gif' alt='loading...' title='loading...' width='50' height='50' style='color:#33cc33'/></div>";
					}
					if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
							obj.innerHTML = XMLHttpRequestObject.responseText;
							//document.getElementById('message').innerHTML = 
								//'<div style="background:none repeat scroll 0 0 #FFEB90;clear:both;color:#000;font-size:14px;margin-bottom:5px;padding:5px 10px;text-align:left;">Show successfully remove.</div>';
							    //displayUnattendedShows(current_userid);
								//alert('You successfully leave the show.');
								window.location.href="/misc.php?do=page&template=Edit_My_Show_Stats&message=removesuccessful&counter="+counter+"";
					}
				}
			XMLHttpRequestObject.send('&current_userid=' + current_userid + '&allVals=' + allVals + '&task=removeShowUserAttended');
		}
		
		//setTimeout("alert('hello')",1250);
		//setTimeout("displayUnattendedShows('"+current_userid+"')",3250);
	}

}


function displayUnattendedShows(current_userid)	{
	var obj = document.getElementById("diplayshows_unattended");
	var url = "/jf/wp-content/plugins/bandstatistic/mystats/mystatsprocess.php";
	if(XMLHttpRequestObject) {
		XMLHttpRequestObject.open("POST", url);
		XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		XMLHttpRequestObject.onreadystatechange = 
			function(){
				if (XMLHttpRequestObject.readyState == 1) {
					obj.innerHTML = "<div align='center'><img src='/jf/wp-content/plugins/bandstatistic/icons/loading.gif' alt='loading...' title='loading...' width='50' height='50' style='color:#33cc33'/></div>";
				}
				if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
					//obj.innerHTML = XMLHttpRequestObject.responseText;
					obj.innerHTML = 'HELLO WORLD'
				}
			}
		XMLHttpRequestObject.send('&current_userid=' + current_userid + '&task=displayUnattendedShows');
	}
}

function paginateUnattendedShows(startlimit,end_limit,pageno,totalpage,rows_per_page,endsubtrahend)	{
	
	var bandid = document.getElementById("bandid").value;
	
	var allVals = '';
	var inputs = document.getElementsByTagName("input");	
	var cbs = [];
	var checked = [];
	for (var i = 0; i < inputs.length; i++) {
		if ((inputs[i].type == "checkbox")  && (inputs[i].name == "showid")){
		cbs.push(inputs[i]);
		if (inputs[i].checked) {
		  allVals = allVals + ',' + inputs[i].value;
		}
	  }
	}
	
	var checked_showids = document.getElementById("checked_showids").value;
	allVals = allVals + checked_showids;

	if(pageno == 'back')	{
		var current_page = document.getElementById('current_page').value;
		pageno = parseInt(current_page) - 1;
		if(current_page == 1)	{
			pageno = 1;
		}
		startlimit = (((rows_per_page * pageno) - endsubtrahend) - 1);
	}
	if(pageno == 'forward')	{
		var current_page = document.getElementById('current_page').value;
		pageno = parseInt(current_page) + 1;
		if(current_page == totalpage)	{
			pageno = current_page;
		}
		startlimit = (((rows_per_page * pageno) - endsubtrahend) - 1);
	}
	if(pageno == 'bandfilter')	{
		var current_page = document.getElementById('current_page').value;
		pageno = current_page;
		startlimit = (((rows_per_page * pageno) - endsubtrahend) - 1);
	}

	if(totalpage > 1)	{
		for(var i=1;i<=totalpage;i++)	{
			document.getElementById('pageno_'+i).style.color="#FFFFFF";
		}
		document.getElementById('pageno_'+pageno).style.color="#E91D6F";
	}
	
	var current_userid = document.getElementById('current_userid').value;
	//var obj = document.getElementById("diplayshows_unattended");
	var obj = document.getElementById("diplayshows_unattended");
	
	var url = "/jf/wp-content/plugins/bandstatistic/mystats/mystatsprocess.php";
	if(XMLHttpRequestObject) {
		XMLHttpRequestObject.open("POST", url);
		XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		XMLHttpRequestObject.onreadystatechange = 
			function(){
				if (XMLHttpRequestObject.readyState == 1) { 
					//obj.innerHTML = "<div align='center'><img src='/jf/wp-content/plugins/bandstatistic/icons/loading.gif' alt='loading...' title='loading...' width='50' height='50' style='color:#33cc33'/></div>"; 
					obj.innerHTML = "<div align='center'><img src='/jf/wp-content/plugins/bandstatistic/icons/loading.gif' alt='loading...' title='loading...' width='50' height='50' style='color:#33cc33'/></div>"; 
					
				}
				if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) { 
					obj.innerHTML = XMLHttpRequestObject.responseText; 
				}
					
					/*
					var find_NoRecordsFound = strpos(XMLHttpRequestObject.responseText, 'No Records Found', 0);
					if(find_NoRecordsFound != false)	{
						document.getElementById('nav_pagination').style.display = 'none';
					}
					else	{
						document.getElementById('nav_pagination').style.display = '';
					}
					*/

				//}
			}
		XMLHttpRequestObject.send('&bandid=' + bandid + '&checked_showids=' + allVals +'&current_page=' + pageno + '&current_userid=' + current_userid + '&startlimit=' + startlimit + '&end_limit=' + end_limit + '&task=paginateUnattendedShows');
	}
}

function strpos (haystack, needle, offset) {
	 var i = (haystack + '').indexOf(needle, (offset || 0));
	 return i === -1 ? false : i;
}