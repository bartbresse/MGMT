//view setting array
//each view exists of different actions: those actions work together in this array
view = [];

args = [];	
args.saves = []; 
args.data = [];

//delete function 
function del(id,entity)
{
    if(confirm('Weet je het zeker?'))
    {
        $.ajax({
                url: args.baseurl+entity+'/delete',
                type: 'POST',
                dataType: 'json',
                data: { id:id },
                success: function(data){   
                        window.location.reload();	
        }});
    }
}

//deactiveren function 
function deactiveren(id,entity)
{
    if(confirm('Weet je het zeker?'))
    {
        $.ajax({
                url: args.baseurl+entity+'/deactivate',
                type: 'POST',
                dataType: 'json',
                data: { id:id },
                success: function(data){   
                        window.location.reload();	
        }});
    }
}

//save function
function save(o)
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
                    {
                            data[id] = 1; 
                    }
                    else
                    {
                            data[id] = 0; 
                    }
                }
                else
                {
                    dval = $(this).val();
                    if(typeof dval == "string" || typeof dval == "number")
                    {
                            if(dval.trim().length == 0) 
                            {	
                                    htmlval = $(this).html();		
                                    if(htmlval.length > 0)
                                    { dval = htmlval;	}
                            }
                    }
                    data[id] = dval;  
                }	
            });
	}
	else
	{
		data = o.data;
	}

	/*
	TODO: check if still relevant, is triggered in entity/new
	*/
	if(typeof widgets != 'undefined')
	{
            //alert('widgets');	
	}
	
	//send events after main form is submitted & confirmed
	if(args.saves != null && args.saves.length > 0)
	{
		for(i=0;i<args.saves.length;i++)
		{
			saveform(args.saves[i]);
		}
	}
	
	
	if(o.filearray != null){ data['files'] = o.filearray; }
	$.ajax({
		url: o.action,
		type: 'POST',
		dataType: 'json',
		data: data,
		async:false,
		success: function(data)
		{ 
			//clear old messages
			$(".parsley-errors-list").each(function(){ $(this).html(' '); });
			if(data.status == 'ok')
			{ 
				if(o.goto == false)
				{
					//TODO logic if there is no location
					if(data.goto != null)
					{ go(data.goto); }
					return false;
				}
				else
				{
                                    go(o.goto);
				}
			}
			else
			{ 	
				$('.'+o.class).removeClass('has-error');
				$('.'+o.class+' .control-label').html('');
			
				if(data.messages != null){
				for(var i =0;i<data.messages.length;i++){
					//set new messages						
					$.each(data.messages[i], function(key,val){
						//$('#'+key+'group').addClass('has-error');
						
						//alert('#'+key+'group .control-label ul');
						
						
						
						$('#'+key+'group .control-label ul').html('<li>'+val+'</li>');
				});	}}
			}	
		} 
	});	
	return false;
}



//save function
function saveData(o)
{
    var returndata;
    var data = {};
    if(o.class != null)
    {
        $("."+o.class).each(function(){
            id = $(this).attr('id');
            type = $(this).attr('type');			
            if(type == "checkbox")
            {
                if($(this).is(':checked'))
                {
                        data[id] = 1; 
                }
                else
                {
                        data[id] = 0; 
                }
            }
            else
            {
                dval = $(this).val();
                if(typeof dval == "string" || typeof dval == "number")
                {
                    if(dval.trim().length == 0) 
                    {	
                        htmlval = $(this).html();		
                        if(htmlval.length > 0)
                        { dval = htmlval; }
                    }
                }
                data[id] = dval;  
            }	
        });
    }
    else
    {
            data = o.data;
    }

    //send events after main form is submitted & confirmed
    if(args.saves != null && args.saves.length > 0)
    {
            for(i=0;i<args.saves.length;i++)
            {
                    saveform(args.saves[i]);
            }
    }


    if(o.filearray != null){ data['files'] = o.filearray; }
    $.ajax({
        url: o.action,
        type: 'POST',
        dataType: 'json',
        data: data,
        async:false,
        success: function(data)
        { 
            if(data.status == 'ok')
            { 
                returndata = data;    
               // return data;
            }
            else
            { 	

            }	
        } 
    });	
    return returndata;
}



//load function for sorting
function load(args)
{
	$.ajax({
		url: args.action,
		type: 'POST',
		data: args.data,
		/* CHROME: CANT HANDLE ASYNC IN THE RIGHT ORDER*/
		async:false,
		success: function(data)
		{ 	
			if(args.container != null)
			{
				$(args.container).html(data);				
			}
			else
			{
				$('#general-content').html(data);
			}
		} 
	});
}


//window location function
function go(loc)
{
	window.location.href = loc;
}

//datepickers
$('.date').datepicker();
$('.datetime').datetimepicker();
$(".form-control").chosen({ width: '100%' });
