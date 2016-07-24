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

var XMLHttpRequestObject1 = false;
try {
	XMLHttpRequestObject1 = new ActiveXObject("MSXML2.XMLHTTP");
}
catch (exception3) {
	try {
		XMLHttpRequestObject1 = new ActiveXObject("Microsoft.XMLHTTP");
	}
	catch (exception4) {
		XMLHttpRequestObject1 = false;
	}
}
if (!XMLHttpRequestObject1 && window.XMLHttpRequest) {
	XMLHttpRequestObject1 = new XMLHttpRequest();
}

function strlen( string ){
    return string.length;
}

function editVenue()	{
	
	var venue_id = document.getElementById('venue_id').value;
	var venue_name = document.getElementById('venue_name').value;
	
	if(venue_name == '')	{
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please provide a valid venue.</p> </div>';
	}
	else	{
		var obj = document.getElementById("displayvenue");
		var url = ""+wpurl+"/wp-content/plugins/bandstatistic/process.php";
		if(XMLHttpRequestObject) {
			XMLHttpRequestObject.open("POST", url);
			XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			XMLHttpRequestObject.onreadystatechange = 
				function(){
					if (XMLHttpRequestObject.readyState == 1) {
						obj.innerHTML = "<div align='center'><img src='"+wpurl+"/wp-content/plugins/bandstatistic/icons/loading.gif' alt='loading...' title='loading...' width='50' height='50' style='color:#33cc33'/></div>";
					}
					if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
							obj.innerHTML = XMLHttpRequestObject.responseText;
							document.getElementById('venue_id').value = '';
							document.getElementById('venue_name').value = '';
							if(venue_id == '')	{
								document.getElementById('displaymessage').innerHTML = 
									'<div class="updated" id="message"><p>Venue successfully added.</p></div>';
							}
							else	{
								document.getElementById('displaymessage').innerHTML = 
									'<div class="updated" id="message"><p>Venue successfully updated.</p></div>';
								document.getElementById('header_display').innerHTML = 
									'Add New';
							}
					}
				}
			XMLHttpRequestObject.send('&venue_id=' + venue_id + '&venue_name=' + venue_name + '&task=editVenue');
		}
	}
}

function removeVenue()	{
	var allVals = '';
	var inputs = document.getElementsByTagName("input");
	var cbs = [];
	var checked = [];
	for (var i = 0; i < inputs.length; i++) {
	  if (inputs[i].type == "checkbox") {
		cbs.push(inputs[i]);
		if (inputs[i].checked) {
		  allVals = allVals + ',' + inputs[i].value;
		}
	  }
	}
	
	if(allVals == '')	{
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please select from the list.</p> </div>';
	}
	else	{
		if(confirm("Are you sure you want to delete this record?")){
			var obj = document.getElementById("displayvenue");
			var url = ""+wpurl+"/wp-content/plugins/bandstatistic/process.php";
			if(XMLHttpRequestObject) {
				XMLHttpRequestObject.open("POST", url);
				XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
				XMLHttpRequestObject.onreadystatechange = 
					function(){
						if (XMLHttpRequestObject.readyState == 1) {
							obj.innerHTML = "<div align='center'><img src='"+wpurl+"/wp-content/plugins/bandstatistic/icons/loading.gif' alt='loading...' title='loading...' width='50' height='50' style='color:#33cc33'/></div>";
						}
						if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
								obj.innerHTML = XMLHttpRequestObject.responseText;
								document.getElementById('displaymessage').innerHTML = 
									'<div class="updated" id="message"><p>Venue successfully deleted.</p></div>';
						}
					}
				XMLHttpRequestObject.send('&allVals=' + allVals + '&task=deleteVenue');
			}			
		}
	}
}

function SaveBand()	{

	var bandid = document.getElementById('bandid').value;
	var bandname = document.getElementById('bandname').value;
	var bio = document.getElementById('bio').value;
	var image = document.getElementById('image').value;

	if(bandname == '')	{
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please provide a valid band name.</p> </div>';
	}
	else	{
		var form=document.getElementById("formband");
		form.method="post";
		taskElement=document.getElementById("task");
		taskElement.value='Save';
		form.submit();
	}
}

function addRow(tableID) {

	var url = document.getElementById('url').value;
	var url_title = document.getElementById('url_title').value;

	
	if((url == '')	|| (url == 'Enter Url')){
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please provide a valid link.</p> </div>';
	}
	else if((url_title == '') || (url_title == 'Enter Link Title'))	{
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please provide a valid link title.</p> </div>';
	}
	else	{

		var table = document.getElementById(tableID);

		var rowCount = table.rows.length;
		var row = table.insertRow(rowCount);

		var cell1 = row.insertCell(0);
		var element1 = document.createElement("input");
		element1.type = "checkbox";
		cell1.appendChild(element1);

		var cell2 = row.insertCell(1);
		//cell2.innerHTML = rowCount + 1;
		//cell2.innerHTML = url;
		cell2.innerHTML = url_title;
		

		var cell3 = row.insertCell(2);
		var element2 = document.createElement("input");
		element2.type = "hidden";
		element2.name = "links[]";
		element2.id = "links[]";
		element2.value = url;
		cell3.appendChild(element2);
		
		var element3 = document.createElement("input");
		element3.type = "hidden";
		element3.name = "linktitle[]";
		element3.value = url_title;
		cell3.appendChild(element3);
	}

}

function addLinksBand()	{

	var url = document.getElementById('url').value;
	var url_title = document.getElementById('url_title').value;
	var storage_url = document.getElementById('storage_url').value;
	var storage_urltitle = document.getElementById('storage_urltitle').value;
	
	if((url == '')	|| (url == 'Enter Url')){
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please provide a valid link.</p> </div>';
	}
	else if((url_title == '') || (url_title == 'Enter Link Title'))	{
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please provide a valid link title.</p> </div>';
	}
	else	{
	
		var urls_inputted  = storage_url + ',' + url;
		var urltitle_inputted = storage_urltitle + ',' + url_title;
		
		document.getElementById('storage_url').value = urls_inputted;
		document.getElementById('storage_urltitle').value = urltitle_inputted;
		
		/*
		var urls_inputted = urls_inputted.split(',');			
		for(i=0;i<urls_inputted.length;i++) {	
			alert(urls_inputted[i]);
		}
		
		
		var urltitle_inputted = urltitle_inputted.split(',');
		for(i=0;i<urltitle_inputted.length;i++) {	
			alert(urltitle_inputted[i]);
		}
		*/
		

		var urls_inputted = urls_inputted.split(',');
		var linklistdisplay = '<table id="dataTable"><tbody>';
			for(i=0;i<urls_inputted.length;i++) {
				
				if(urls_inputted[i] != '')	{
					linklistdisplay += '	<tr>';
					linklistdisplay += '		<td><input type="checkbox" value="'+i+'"></td>';
					linklistdisplay += '		<td>';
					linklistdisplay += '		<a href="'+urls_inputted[i]+'">';
					linklistdisplay +=			urls_inputted[i];
					linklistdisplay += '		</a>';
					linklistdisplay +=			'</td>';
					linklistdisplay += '		<td> </td>';
					linklistdisplay += '	</tr>';
				}
			}

			for(i=0;i<urls_inputted.length;i++) {
				if(urls_inputted[i] != '')	{
					linklistdisplay +=	'<input type="hidden" value="'+urls_inputted[i]+'" name="links[]" id="links[]">';
				}
			}
			
			var urltitle_inputted = urltitle_inputted.split(',');
			for(i=0;i<urltitle_inputted.length;i++) {
				if(urltitle_inputted[i] != '')	{
					linklistdisplay +=	'<input type="hidden" value="'+urltitle_inputted[i]+'" name="linktitle[]" id="linktitle[]">';
				}
			}

		linklistdisplay +=	'</tbody></table>';
		document.getElementById('displaylinks').innerHTML = linklistdisplay;
	}
}

function removeEditBandlinks()	{
	var bandid = document.getElementById('bandid').value;

	var allVals = '';
	var inputs = document.getElementsByTagName("input");	
	var cbs = [];
	var checked = [];
	for (var i = 0; i < inputs.length; i++) {
	  if (inputs[i].type == "checkbox") {
		cbs.push(inputs[i]);
		if (inputs[i].checked) {
		  allVals = allVals + ',' + inputs[i].value;
		}
	  }
	}

	if(allVals == '')	{
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please select from the list.</p> </div>';
	}
	else	{
		if(confirm("Are you sure you want to delete this record?")){
			var obj = document.getElementById("displaylinks");
			var url = ""+wpurl+"/wp-content/plugins/bandstatistic/process.php";
			if(XMLHttpRequestObject) {
				XMLHttpRequestObject.open("POST", url);
				XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
				XMLHttpRequestObject.onreadystatechange = 
					function(){
						if (XMLHttpRequestObject.readyState == 1) {
							obj.innerHTML = "<div align='center'><img src='"+wpurl+"/wp-content/plugins/bandstatistic/icons/loading.gif' alt='loading...' title='loading...' width='50' height='50' style='color:#33cc33'/></div>";
						}
						if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
								obj.innerHTML = XMLHttpRequestObject.responseText;
								document.getElementById('displaymessage').innerHTML = 
									'<div class="updated" id="message"><p>Band Links successfully deleted.</p></div>';
						}
					}
				XMLHttpRequestObject.send('&bandid=' + bandid + '&allVals=' + allVals + '&task=deleteBandLinks');
			}			
		}
	}
}

function addEditLinksBand()	{

	var bandid = document.getElementById('bandid').value;
	var link = document.getElementById('url').value;
	var link_title = document.getElementById('url_title').value;
	
	if((link == '') || (link == 'Enter Url'))	{
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please provide a valid link.</p> </div>';
	}
	else if((link_title == '') || (link_title == 'Enter Link Title'))	{
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please provide a valid link title.</p> </div>';
	}
	else	{
		var obj = document.getElementById("displaylinks");
		var url = ""+wpurl+"/wp-content/plugins/bandstatistic/process.php";
		if(XMLHttpRequestObject) {
			XMLHttpRequestObject.open("POST", url);
			XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			XMLHttpRequestObject.onreadystatechange = 
				function(){
					if (XMLHttpRequestObject.readyState == 1) {
						obj.innerHTML = "<div align='center'><img src='"+wpurl+"/wp-content/plugins/bandstatistic/icons/loading.gif' alt='loading...' title='loading...' width='50' height='50' style='color:#33cc33'/></div>";
					}
					if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
							obj.innerHTML = XMLHttpRequestObject.responseText;
							document.getElementById('url').value = '';
							document.getElementById('url_title').value = '';
								document.getElementById('displaymessage').innerHTML = 
									'<div class="updated" id="message"><p>Band Links successfully added.</p></div>';
					}
				}
			XMLHttpRequestObject.send('&bandid=' + bandid + '&link=' + link + '&link_title=' + link_title + '&task=addEditLinksBand');
		}
	}

}

function editSong()	{
	var song_id = document.getElementById('song_id').value;
	var songtitle = document.getElementById('songtitle').value;
	var bandid = document.getElementById('bandid').value;
	
	if(songtitle == '') {
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please provide a valid song title.</p> </div>';
	}
	else if(bandid == '') {
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please select a band name.</p> </div>';
	}
	else	{
		var obj = document.getElementById("displaysong");
		var url = ""+wpurl+"/wp-content/plugins/bandstatistic/process.php";
		if(XMLHttpRequestObject) {
			XMLHttpRequestObject.open("POST", url);
			XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			XMLHttpRequestObject.onreadystatechange = 
				function(){
					if (XMLHttpRequestObject.readyState == 1) {
						obj.innerHTML = "<div align='center'><img src='"+wpurl+"/wp-content/plugins/bandstatistic/icons/loading.gif' alt='loading...' title='loading...' width='50' height='50' style='color:#33cc33'/></div>";
					}
					if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
							obj.innerHTML = XMLHttpRequestObject.responseText;
							document.getElementById('song_id').value = '';
							document.getElementById('songtitle').value = '';
							document.getElementById('bandid').selectedIndex = '';
							if(song_id == '')	{
								document.getElementById('displaymessage').innerHTML = 
									'<div class="updated" id="message"><p>Song successfully added.</p></div>';
							}
							else	{
								document.getElementById('displaymessage').innerHTML = 
									'<div class="updated" id="message"><p>Song successfully updated.</p></div>';
								document.getElementById('header_display').innerHTML = 
									'Add New';
							}									
					}
				}
			XMLHttpRequestObject.send('&song_id=' + song_id +'&bandid=' + bandid + '&songtitle=' + songtitle + '&task=editSong');
		}
	}
}

function removeSong()	{
	var allVals = '';
	var inputs = document.getElementsByTagName("input");
	var cbs = [];
	var checked = [];
	for (var i = 0; i < inputs.length; i++) {
	  if (inputs[i].type == "checkbox") {
		cbs.push(inputs[i]);
		if (inputs[i].checked) {
		  allVals = allVals + ',' + inputs[i].value;
		}
	  }
	}
	
	if(allVals == '')	{
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please select from the list.</p> </div>';
	}
	else	{
		if(confirm("Are you sure you want to delete this record?")){
			var obj = document.getElementById("displaysong");
			var url = ""+wpurl+"/wp-content/plugins/bandstatistic/process.php";
			if(XMLHttpRequestObject) {
				XMLHttpRequestObject.open("POST", url);
				XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
				XMLHttpRequestObject.onreadystatechange = 
					function(){
						if (XMLHttpRequestObject.readyState == 1) {
							obj.innerHTML = "<div align='center'><img src='"+wpurl+"/wp-content/plugins/bandstatistic/icons/loading.gif' alt='loading...' title='loading...' width='50' height='50' style='color:#33cc33'/></div>";
						}
						if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
								obj.innerHTML = XMLHttpRequestObject.responseText;
								document.getElementById('displaymessage').innerHTML = 
									'<div class="updated" id="message"><p>Song successfully deleted.</p></div>';
						}
					}
				XMLHttpRequestObject.send('&allVals=' + allVals + '&task=deleteSong');
			}			
		}
	}
}

function editShow()	{
	var show_id = document.getElementById('show_id').value;
	var showname = document.getElementById('showname').value;
	var dateshow = document.getElementById('dateshow').value;
	var venueid = document.getElementById('venueid').value;
	var songid = document.getElementById('songid').value;
	var order = document.getElementById('order').value;
	var set = document.getElementById('set').value;

	if(showname == '') {
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please provide a valid show name.</p> </div>';
	}
	else if(dateshow == '') {
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please select a date for the show.</p> </div>';
	}
	else if(venueid == '') {
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please select a venue.</p> </div>';
	}
	else if(songid == '') {
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please select a song.</p> </div>';
	}
	else if(order == '') {
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please enter a song order.</p> </div>';
	}
	else	{
		var obj = document.getElementById("displayshow");
		var url = ""+wpurl+"/wp-content/plugins/bandstatistic/process.php";
		if(XMLHttpRequestObject) {
			XMLHttpRequestObject.open("POST", url);
			XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			XMLHttpRequestObject.onreadystatechange = 
				function(){
					if (XMLHttpRequestObject.readyState == 1) {
						obj.innerHTML = "<div align='center'><img src='"+wpurl+"/wp-content/plugins/bandstatistic/icons/loading.gif' alt='loading...' title='loading...' width='50' height='50' style='color:#33cc33'/></div>";
					}
					if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
							obj.innerHTML = XMLHttpRequestObject.responseText;
							document.getElementById('displaymessage').innerHTML = 
								'<div class="updated" id="message"><p>Song successfully added.</p></div>';
					}
				}
			XMLHttpRequestObject.send('&show_id=' + show_id +'&showname=' + showname + '&dateshow=' + dateshow + '&venueid=' + venueid + '&songid=' + songid + '&order=' + order + '&set=' + set + '&task=editShow');
		}
	}
}

function removeShow()	{
	var allVals = '';
	var inputs = document.getElementsByTagName("input");
	var cbs = [];
	var checked = [];
	for (var i = 0; i < inputs.length; i++) {
	  if (inputs[i].type == "checkbox") {
		cbs.push(inputs[i]);
		if (inputs[i].checked) {
		  allVals = allVals + ',' + inputs[i].value;
		}
	  }
	}

	if(allVals == '')	{
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please select from the list.</p> </div>';
	}
	else	{
		if(confirm("Are you sure you want to delete this record?")){
			var obj = document.getElementById("displayshow");
			var url = ""+wpurl+"/wp-content/plugins/bandstatistic/process.php";
			if(XMLHttpRequestObject) {
				XMLHttpRequestObject.open("POST", url);
				XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
				XMLHttpRequestObject.onreadystatechange = 
					function(){
						if (XMLHttpRequestObject.readyState == 1) {
							obj.innerHTML = "<div align='center'><img src='"+wpurl+"/wp-content/plugins/bandstatistic/icons/loading.gif' alt='loading...' title='loading...' width='50' height='50' style='color:#33cc33'/></div>";
						}
						if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
								obj.innerHTML = XMLHttpRequestObject.responseText;
								document.getElementById('displaymessage').innerHTML = 
									'<div class="updated" id="message"><p>Song successfully deleted.</p></div>';
						}
					}
				XMLHttpRequestObject.send('&allVals=' + allVals + '&task=deleteShow');
			}			
		}
	}
}

function addSongShow(tableID) {

	var w = document.formshow.songid.selectedIndex;
	var selected_text = document.formshow.songid.options[w].text;

	var songid = document.getElementById('songid').value;
	var order = document.getElementById('order').value;
	var songsets = document.getElementById('songsets').value;

	if((songid == '')){
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please select a song.</p> </div>';
	}
	else if((order == ''))	{
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please provide the song order.</p> </div>';
	}
	else	{

		var table = document.getElementById(tableID);

		var rowCount = table.rows.length;
		var row = table.insertRow(rowCount);

		var cell1 = row.insertCell(0);
		var element1 = document.createElement("input");
		element1.type = "checkbox";
		cell1.appendChild(element1);

		var cell2 = row.insertCell(1);
		//cell2.innerHTML = rowCount + 1;
		cell2.innerHTML = selected_text;

		var cell3 = row.insertCell(2);
		var element2 = document.createElement("input");
		element2.type = "hidden";
		element2.name = "songids[]";
		element2.id = "songids[]";
		element2.value = songid;
		cell3.appendChild(element2);
		cell3.innerHTML = order;
		
		var element3 = document.createElement("input");
		element3.type = "hidden";
		element3.name = "order[]";
		element3.id = "order[]";
		element3.value = order;
		cell3.appendChild(element2);		// make sure the hidden field songids exist
		cell3.appendChild(element3);
		
		
		var cell4 = row.insertCell(3);
		var element4 = document.createElement("input");
		element4.type = "hidden";
		element4.name = "sets[]";
		element4.id = "sets[]";
		element4.value = songsets;
		cell4.appendChild(element4);
		cell4.innerHTML = songsets;

		cell3.appendChild(element4);		// make sure the hidden field sets exist
		
		document.getElementById('order').value = '';
		document.getElementById('songid').selectedIndex = '';
	}
}

function deleteRow(tableID) {
	var allVals = '';
	var inputs = document.getElementsByTagName("input");
	var cbs = [];
	var checked = [];
	for (var i = 0; i < inputs.length; i++) {
	  if (inputs[i].type == "checkbox") {
		cbs.push(inputs[i]);
		if (inputs[i].checked) {
		  allVals = allVals + ',' + inputs[i].value;
		}
	  }
	}
	
	if(allVals == '')	{
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please select from the list.</p> </div>';
	}
	else	{
		try {
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;

		for(var i=0; i<rowCount; i++) {
			var row = table.rows[i];
			var chkbox = row.cells[0].childNodes[0];
			if(null != chkbox && true == chkbox.checked) {
				table.deleteRow(i);
				rowCount--;
				i--;
			}

		}
		}catch(e) {
			alert(e);
		}
	}
}

function SaveShows()	{

	var show_id = document.getElementById('show_id').value;
	var showname = document.getElementById('showname').value;
	var dateshow= document.getElementById('dateshow').value;
	var venueid = document.getElementById('venueid').value;

	if(showname == '')	{
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please provide a valid show name.</p> </div>';
	}
	else if(dateshow == '')	{
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please select a date.</p> </div>';
	}
	else if(venueid == '')	{
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please select a venue.</p> </div>';
	}
	else	{
		var form=document.getElementById("formshow");
		form.method="post";
		taskElement=document.getElementById("task");
		taskElement.value='Save';
		form.submit();
	}
}

function removeEditShowSongs()	{
	var show_id = document.getElementById('show_id').value;
	var allVals = '';
	var inputs = document.getElementsByTagName("input");	
	var cbs = [];
	var checked = [];
	for (var i = 0; i < inputs.length; i++) {
	  if (inputs[i].type == "checkbox") {
		cbs.push(inputs[i]);
		if (inputs[i].checked) {
		  allVals = allVals + ',' + inputs[i].value;
		}
	  }
	}

	if(allVals == '')	{
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please select from the list.</p> </div>';
	}
	else	{
		if(confirm("Are you sure you want to delete this record?")){
			var obj = document.getElementById("displayshow");
			var url = ""+wpurl+"/wp-content/plugins/bandstatistic/process.php";
			if(XMLHttpRequestObject) {
				XMLHttpRequestObject.open("POST", url);
				XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
				XMLHttpRequestObject.onreadystatechange = 
					function(){
						if (XMLHttpRequestObject.readyState == 1) {
							obj.innerHTML = "<div align='center'><img src='"+wpurl+"/wp-content/plugins/bandstatistic/icons/loading.gif' alt='loading...' title='loading...' width='50' height='50' style='color:#33cc33'/></div>";
						}
						if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
								obj.innerHTML = XMLHttpRequestObject.responseText;
								document.getElementById('displaymessage').innerHTML = 
									'<div class="updated" id="message"><p>Song successfully deleted.</p></div>';
						}
					}
				XMLHttpRequestObject.send('&show_id=' + show_id + '&allVals=' + allVals + '&task=deleteShowSongs');
			}			
		}
	}
}

function addEditSongShow()	{

	var show_id = document.getElementById('show_id').value;
	var songid = document.getElementById('songid').value;
	var order = document.getElementById('order').value;
	var songsets = document.getElementById('songsets').value;
	
	if((songid == ''))	{
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please select a song.</p> </div>';
	}
	else if((order == ''))	{
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please provide a valid order of the song.</p> </div>';
	}
	else	{
		var obj = document.getElementById("displayshow");
		var url = ""+wpurl+"/wp-content/plugins/bandstatistic/process.php";
		if(XMLHttpRequestObject) {
			XMLHttpRequestObject.open("POST", url);
			XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			XMLHttpRequestObject.onreadystatechange = 
				function(){
					if (XMLHttpRequestObject.readyState == 1) {
						obj.innerHTML = "<div align='center'><img src='"+wpurl+"/wp-content/plugins/bandstatistic/icons/loading.gif' alt='loading...' title='loading...' width='50' height='50' style='color:#33cc33'/></div>";
					}
					if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
							obj.innerHTML = XMLHttpRequestObject.responseText;
							document.getElementById('order').value = '';
							document.getElementById('songid').selectedIndex = '';
								document.getElementById('displaymessage').innerHTML = 
									'<div class="updated" id="message"><p>Song successfully added.</p></div>';
					}
				}
			XMLHttpRequestObject.send('&show_id=' + show_id + '&songid=' + songid + '&order=' + order + '&songsets=' + songsets + '&task=addEditSongShow');
		}
	}

}

function getSongsByBand()	{
	var bandid = document.getElementById('bandid').value;
	if(bandid == '')	{
		document.getElementById('displaymessage').innerHTML = 
			'<div class="error below-h2"> <p><strong>ERROR</strong>: Please select a band.</p> </div>';
	}
	else	{
		var obj = document.getElementById("displaySongsByBand");
		var url = ""+wpurl+"/wp-content/plugins/bandstatistic/process.php";
		if(XMLHttpRequestObject1) {
			XMLHttpRequestObject1.open("POST", url);
			XMLHttpRequestObject1.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			XMLHttpRequestObject1.onreadystatechange = 
				function(){
					if (XMLHttpRequestObject1.readyState == 1) {
						obj.innerHTML = "<img src='"+wpurl+"/wp-content/plugins/bandstatistic/icons/loading.gif' alt='loading...' title='loading...' width='50' height='50' style='color:#33cc33'/>";
					}
					if (XMLHttpRequestObject1.readyState == 4 && XMLHttpRequestObject1.status == 200) {
							obj.innerHTML = XMLHttpRequestObject1.responseText;
					}
				}
			XMLHttpRequestObject1.send('&bandid=' + bandid + '&task=getSongsByBand');
		}
	}
}