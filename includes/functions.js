function validateRegistration(id, inputs){
	
	var email		 = inputs[0].value;
	var retyped_email	 = inputs[1].value;
	var password		 = inputs[2].value;
	var retyped_password	 = inputs[3].value;
	var error_msg		 = '';
	var error		 = null;
	
	if(id == 0 || email == '' || retyped_email == '' || password == '' || retyped_password == ''){
	
		error_msg += '<p>Please complete the registration form.<br />Don\'t forget to pick your name from the list.</p>';
		error = 1;
	}

	else{
		if(email == password){
			error_msg += '<p>Your password can not be the same as your email address.</p>';
			error = 1;
		}
	
		if(email != retyped_email){
			error_msg += '<p>Please ensure that you typed the email address correctly in both places.</p>';
			error = 1;
		}
	
		if(password != retyped_password){
			error_msg += '<p>Please ensure that you typed the password correctly in both places.</p>';
			error = 1;
		}
	}

	if(error){
		error_msg += '<span id="error_toggler"><a href="javascript:hideError(\'c_error\');">Hide</a></span>';
		document.getElementById('c_error').innerHTML = error_msg;
		showError('c_error');
		error_msg = '';
		return false;
	}
	
	else{
		return true;
	} 
}


function validateLogin(inputs){
	
	var email		 = inputs[0].value;
	var password		 = inputs[1].value;
	var error_msg		 = '';
	var error		 = null;
	
	if(email == '' || password == ''){
	
		error_msg += '<p>Please type your email and password to log in.</p>';
		error = 1;
	}

	if(error){
		error_msg += '<span id="error_toggler"><a href="javascript:hideError(\'c_error\');">Hide</a></span>';
		document.getElementById('c_error').innerHTML = error_msg;
		showError('c_error');
		error_msg = '';
		return false;
	}
	
	else{
		return true;
	} 
}

function validateWishlist(title, description){
	
	var error_msg		 = '';
	var error		 = null;
	
	if((title == '') || (description == '')){
	
		error_msg += '<p>You must type a Title and Description for each item.</p>';
		error = 1;
	}

	if(error){
		error_msg += '<span id="error_toggler"><a href="javascript:hideError(\'c_error\');">Hide</a></span>';
		document.getElementById('c_error').innerHTML = error_msg;
		showError('c_error');
		error_msg = '';
		return false;
	}
	
	else{
		return true;
	} 
}


function validateInfoChange(inputs){
	
	var oldemail		 = inputs[0].value;
	var newemail		 = inputs[1].value;
	var re_newemail		 = inputs[2].value;
	var oldpass		 = inputs[3].value;
	var newpass		 = inputs[4].value;
	var re_newpass		 = inputs[5].value;

	var error_msg		 = '';
	var error		 = null;
	
	if(newemail == '' && re_newemail == '' && oldpass == '' && newpass == '' && re_newpass == ''){
	
		error_msg += '<p>Please complete the form.</p>';
		error = 1;
	}

	else{

		if(newemail != re_newemail){

			error_msg += '<p>New email and retyped new email must match.</p>';
			error = 1;
		}

		else if(newemail == oldemail){

			error_msg += '<p>New email must be different than current email.</p>';
			error = 1;
		}

		if(newpass != re_newpass){

			error_msg += '<p>New password and retyped new password must match.</p>';
			error = 1;
		}

	}

	if(error){
		error_msg += '<span id="error_toggler"><a href="javascript:hideError(\'c_error\');">Hide</a></span>';
		document.getElementById('c_error').innerHTML = error_msg;
		showError('c_error');
		error_msg = '';
		return false;
	}
	
	else{
		return true;
	} 
}



function appendLink(theSel, newText, newValue){

	if(newText == ''){
		return;
	}

	if (theSel.length == 0) {
		var newOpt1 = new Option(newText, newValue);
		theSel.options[0] = newOpt1;
		theSel.selectedIndex = 0;
	}

	else if (theSel.selectedIndex != -1) {
		var selText = new Array();
		var selValues = new Array();
		var selIsSel = new Array();
		var newCount = -1;
		var newSelected = -1;
		var i;

    		for(i=0; i<theSel.length; i++){
			newCount++;
			selText[newCount] = theSel.options[i].text;
			selValues[newCount] = theSel.options[i].value;
			selIsSel[newCount] = theSel.options[i].selected;
      
			if (newCount == theSel.selectedIndex){
				newCount++;
				selText[newCount] = newText;
				selValues[newCount] = newValue;
				selIsSel[newCount] = false;
				newSelected = newCount - 1;
			}
		}

		for(i=0; i<=newCount; i++){
			var newOpt = new Option(selText[i], selValues[i]);
			theSel.options[i] = newOpt;
			theSel.options[i].selected = selIsSel[i];
		}
	}
}

function removeLink(theSel)
{
  var selIndex = theSel.selectedIndex;
  if (selIndex != -1) {
    for(i=theSel.length-1; i>=0; i--)
    {
      if(theSel.options[i].selected)
      {
        theSel.options[i] = null;
      }
    }
    if (theSel.length > 0) {
      theSel.selectedIndex = selIndex == 0 ? 0 : selIndex - 1;
    }
  }
}

function selectAllOptions(selStr)
{
  var selObj = document.getElementById(selStr);
  for (var i=0; i<selObj.options.length; i++) {
    selObj.options[i].selected = true;
  }
}


function showError(id) {
	var e = document.getElementById(id);
	if(e.style.display == 'none')
		e.style.display = 'block';
}

function hideError(id){
	var e = document.getElementById(id);
	if(e.style.display == 'block')
		e.style.display = 'none';
}

function toggleVisibility(id, toggler){
	var e = document.getElementById(id);
	if(e.style.display == 'block'){
		e.style.display = 'none';
		document.getElementById(toggler).innerHTML = '<a href="javascript:toggleVisibility(\'desc\',  \'toggler\');">show instructions</a>';
	}

	else{
		e.style.display = 'block';
		document.getElementById(toggler).innerHTML = '<a href="javascript:toggleVisibility(\'desc\', \'toggler\');">hide instructions</a>';
	}


	
}