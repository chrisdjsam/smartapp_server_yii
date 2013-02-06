	<script type="text/javascript" src="jquery-1.8.1.min.js"></script>
	<script type="text/javascript" src="jquery.form.js"></script>
	<script type="text/javascript" src="json2.js"></script>
	<script>
$(document).ready(function(){
	$('.toggle_details').text('');
	$('#apply_api_key').click(function(){
		apiKeyVal = $('#main_api_key').val();
		$('.api_keys').val(apiKeyVal);
	});
	$('.submit_form').click(function(){
		formId = $(this).attr('dummy');

		// Avoid sending all the attributes that are not required at the web service end.
		$('.removeFromRequest').each(function(){
			$(this).attr("disabled",true);
		})
		$('#'+formId).submit();
	});
	labelLinkClick();
var options = {
 		beforeSubmit:  showRequest,  // pre-submit callback
        success:       showResponse,  // post-submit callback
        complete:		showComplete
    };
    // bind form using 'ajaxForm'
    $('.ajaxified_forms').ajaxForm(options);

    $('.create_account_type_select').change(function(){
		accountTypeVal = $(this).val();
		$('.create_account_type_dependent').hide();
		$('.'+accountTypeVal).show();
    });

    $('.account_type_select').change(function(){
		accountTypeVal = $(this).val();
		if(accountTypeVal == 'Native'){
			$('.external_social_id_class').hide();
		}else{
			$('.external_social_id_class').show();
		}
    });
    $('.expand-all').click(function(){

    	$('.toggle_details').each(function(){
    		$(this).next().toggle();
    	});	

    	if($(this).text() == 'Expand all'){
    		$(this).text('Collapse all');
    	}else{
    		$(this).text('Expand all');
    	}
    	/*$(this).next().toggle();
    	if($(this).text() == 'More'){
    		$(this).text('Less');
    	}else{
    		$(this).text('More');
    	}*/
    });
});
var formId;
function showComplete(responseText, statusText, xhr, $form){
	try{
		$('#'+formId +  ' .response_div').html("<pre><label>Received Response</label> <br/>Response: " + responseText.responseText + "</pre>");
		$('.removeFromRequest').each(function(){
			$(this).removeAttr("disabled");
		})
	}catch(Exception){
		alert(Exception);
	}
}
// pre-submit callback
function showRequest(formData, jqForm, options) {
	formId = jqForm.attr('id');
	methodType = (jqForm.attr('method'));
	action = (jqForm.attr('action'));
	queryStringData = $.param(formData);
	queryStringArray = queryStringData.split('&');
	queryString = '';
	for(i=0; i<queryStringArray.length;i++){
		queryString = queryString + '<br/>' + decodeURIComponent(queryStringArray[i]);
	}
	$('#'+formId +  ' .request_div').html("<pre><label>Sent Request</label><br/> Action: " + action + '<br/> Method:' + methodType + '<br/> Parameters:' + queryString + "</pre>");
	$('#'+formId +  ' .response_div').html('<label>Waiting for Response....</label>');
    return true;
}
// post-submit callback
function showResponse(responseText, statusText, xhr, $form)  {
	try{
		formId = $form.attr('id');
		$('#'+formId +  ' .response_div').html("<pre><label>Received Response</label> <br/> Status: " + statusText + "<br/>Response: " + JSON.stringify(responseText) + "</pre>");
		$('.removeFromRequest').each(function(){
			$(this).removeAttr("disabled");
		})
	}catch(Exception){
		alert(Exception);
	}
}
function labelLinkClick(){
	existingLabelNameArray = new Array();
	$('#addLabelLink').click(function(){
		labelNameVal = $('#labelName').val();
		if($.trim(labelNameVal)!=''){
			labelExists = false;
			for(i=0; i<existingLabelNameArray.length; i++){
				existingLabel = existingLabelNameArray[i];
				if(existingLabel == labelNameVal){
					labelExists = true;
				}
			}
			if(labelExists){
				alert('Key already added');
			}else{
				existingLabelNameArray.push(labelNameVal);
				$('#labelPlaceholderRow').append("<table style='width:100%'><tr><td class = \"label_field\">" + labelNameVal+"</td><td class = \" value_field\"><input type='text' name='profile[" + labelNameVal + "]'></tr></td><table>");

			}
		}else{
			alert('Key can NOT be empty');
		}
	});
}
</script>
</body>
</html>