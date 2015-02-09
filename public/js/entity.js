

json = {};

function setjson(j)
{
	json = j;
}

function loadjson(args)
{
	return $.ajax({
		url: args.action,
		type: 'POST',
		data: args.data,
		dataType: "json",	
		async:false,
		success:args.success
	});
}

line = 1;

function getnextline()
{
	return line;
}

function addline()
{
	var callback = function(data, textStatus, xhr)
	{
		html  = '<tr id="'+line+'" class="form-line">';
		html += '<td><i class="fa fa-trash-o fa-2x" onclick="deleteLine(\''+line+'\');"></i> &nbsp;&nbsp;</td>';
		html += '<td><input type="checkbox" value="" class="form-control" id="show'+line+'" /></td>';
		html += '<td><select onchange="setline(\'mgmttype'+line+'\',this.value)" class="form-control mgmttype" id="mgmttype'+line+'"><option value=""></option>'+data.mgmtcolumns+'</select></td>';
		html += '<td><input type="text" value="" class="form-control" id="name'+line+'" /></td>';
		html += '<td><input type="text" value="" class="form-control" id="alias'+line+'" /></td>';
		html += '<td><select class="form-control" id="type'+line+'"><option value=""></option>'+data.columns+'</select></td>';
		html += '<td><input type="text" class="form-control" id="length'+line+'" /></td>';
		html += '<td><input type="text" class="form-control" id="default'+line+'" /></td>';
		html += '<td><input type="checkbox" class="form-control" id="null'+line+'" /></td>';
		html += '<td><input type="checkbox" class="form-control" id="unique'+line+'" /></td>';
		html += '<td><input type="text" class="form-control" id="comments'+line+'" /></td>';
		html += '</tr>';
		$('#line-table').append(html);
	}
	
	line += 1;
	$('#form-num').val(line);
	loadjson({action:view.baseurl+'entity/getjsonentity',success:callback});
}

function deleteLine(id)
{
	$('#'+id).remove();
}

function setline(num,mgmttype)
{
	var callback = function(data, textStatus, xhr)
	{
		num = data.num
	
		$('#type'+num).val(data.column.type);
		$('#type'+num).attr('readonly','readonly');
	
		$('#name'+num).val(data.column.titel);
                $('#alias'+num).val(data.column.titel);
                
		if(data.column.bindingtitle == 1)
		{
                    $('#name'+num).attr('readonly','readonly');
		}
		else
		{
                    $('#name'+num).removeAttr('readonly','readonly');
		}
	
		if(data.column.length > 0)
		{
                    $('#length'+num).val(data.column.length);
		}	
		else
		{
                    $('#length'+num).val('');
		}
		
		if(data.column.bindinglength == 1)
		{
                    $('#length'+num).attr('readonly','readonly');
		}
	
		if(data.column.nullcolumn == 1)
		{
                    $('#null'+num).prop('checked', true);	
		}
		else
		{
                    $('#null'+num).prop('checked', false);
		}
		$('#null'+num).attr('readonly','readonly');

		if(data.column.show == 1)
		{
			$('#show'+num).prop('checked', true);	
		}
		else
		{
			$('#show'+num).prop('checked', false);
		}
		$('#default'+num).attr('readonly','readonly');
		
		if(data.column.uniquecolumn == 1)
		{
			$('#unique'+num).prop('checked', true);	
		}
		else
		{
			$('#unique'+num).prop('checked', false);
		}
		$('#null'+num).attr('readonly','readonly');
		
		
		/*
		$('#default'+num).val();
		$('#default'+num).attr('readonly','readonly');
		
		$('#null'+num).val();
		$('#null'+num).attr('readonly','readonly');
		
		

		$('#comments'+num).val();
		$('#comments'+num).attr('readonly','readonly');
		*/
	}
	
	loadjson({data:{num:num,mgmttype:mgmttype},action:view.baseurl+'entity/setline',success:callback});
}

$(function() {
    if(args.baseurl != null)
	{	
		view.baseurl = args.baseurl;
	}	
	
	console.log( "ready!" );
	
	$('#addline').click(function(e){ addline(); });
	
});

