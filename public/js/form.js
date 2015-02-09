//load form
function loadform(o)
{   
      //  alert(o.id);
    
	data = o.data;
	//data.template = o.template;
        data.id = o.id;

	$.ajax({
            url: o.action,
            type: 'POST',
            data: data, //template:template url
            success: function(data)
            { 
                $('#'+o.id).html(data);
            } 
	});	
}

//add to save function
function addform(o){ args.saves.push(o); }

//save form
function saveform(o)
{
	var data = {};
	if(o.class != null)
	{
		$("."+o.class).each(function(){
		
			id = $(this).attr('id');
			type = $(this).attr('type');			
			if(type == "checkbox")
			{
				if($(this).is(':checked'))
				{ data[id] = 1; }
				else
				{ data[id] = 0; }
			}
			else
			{
				data[id] = $(this).val(); 
			}		
		
		});
	}
	else
	{
		data = o.data;
	}

	if(o.filearray != null){ data['files'] = o.filearray; }

	for (var attrname in o) { data[attrname] = o[attrname]; }

	$.ajax({
		url: o.action,
		type: 'POST',
		dataType: 'json',
		data: data,
		async:false,
		success: function(data)
		{ 
			if(data.status == 'ok')
			{ }
			else
			{ 	
				$('.'+o.class).removeClass('has-error');
				$('.'+o.class+' .control-label').html('');
				
				for(var i =0;i<data.messages.length;i++)
				{
					$.each(data.messages[i], function(key,val){
						$('#'+key+'group .control-label ul').html('<li>'+val+'</li>');
					});
				}
			}	
		} 
	});	
	return false;
}
