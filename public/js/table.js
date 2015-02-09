//TODO make loaded content editable
//http://stackoverflow.com/questions/14692396/how-to-re-attach-jquery-functions-after-ajax-content-loa

//if a table is reloaded with ajaxed content

//edit table cell
//$('.table-editable tr td').click(edittablecell(this));
function edittablecell(e)
{	
	if (!$('.savefield')[0])
	{
		clas = $(e).attr('class').split('edit ');
		parent = $(e).closest('tr').attr('id');
	
		var $div=$(e), isEditable=$div.is('.editable');
		$div.attr('contenteditable','true');
	
		$(e).parent().append('<a class="savefield">save</a>');
		
		$('.savefield').click(function(){

			html = $(e).html();
			save({data:{entity:view.entity,html:html,field:clas[1],id:parent}, action:view.baseurl+view.entity+'update', goto:false});
		
			$('.savefield').remove();
		});
	}
	/*
	if(!$(e).hasClass('editable') && $(e).attr('id') != 'bewerken' && $(e).attr('id') != 'select')
	{
		value = $(e).html();	
		$(e).addClass('editable');
		$(e).css( "padding", 0);
		id = $(e).parent().attr('id');
		classes = $(e).attr('class');
		fieldid = classes.split(' ');
		//$(e).html('<input type="hidden" value="'+id+'" class="editable-field" id="id"  /><input class="editable-field" id="'+fieldid[0]+'" type="text" style="background-color: transparent;border:none;" value="'+value+'" onblur="fieldedited(\''+id+'\');" />');
		$(e).html('<input type="hidden" value="'+id+'" class="editable-field" id="id"  /><textarea class="editable-field" id="'+fieldid[0]+'" type="text" style="background-color: transparent;border:none;" onblur="fieldedited(\''+id+'\');">'+value+'</textarea>');
	}	*/
}

function fieldedited()
{	
	save({ class:'editable-field',action:view.baseurl+view.entity+'update',goto:false});
	$('.editable').html($('.editable textarea').val());	
	$('.editable').css("padding", 8);
	$('.editable').removeClass('editable');
	inittable();
}

//sort by column
function sortbycolumn(e)
{
	field = $(e).attr('id');
	
	if($(e).hasClass('ASC')){	order = field+' DESC'; /*$(e).removeClass('ascending');$(e).addClass('descending');*/  }
	else if($(e).hasClass('DESC')){ order =  field+' ASC'; /*$(e).removeClass('descending');$(e).addClass('ascending');*/	}
	else{ order =  field+' ASC'; /*$(e).addClass('ascending');*/ }	

	view.order = order;
	load({container:'#mgmt-table-container',action:view.baseurl+view.entity+'sort',data:{search:view.search,order:view.order,niveau:args.data['niveau']}});
	inittable(args);
}

//search a entity table
function searchtable(e)
{
	search = $('.search-query').val();
	view.search = search;
	load({container:'#mgmt-table-container',action:view.baseurl+view.entity+'sort',data:{search:search,order:view.order,niveau:args.data['niveau']}});
	inittable(args);
}

//export
function exporttable()
{
	if($('#export-options').val() == 'csv')
	{
		view.export = $('#export-options').val();
		save({ data:{val:view.export,search:view.search,order:view.order},action:view.baseurl+view.entity+'export',goto:false});
		inittable(args);
	}
}

//select visible columns
function selecttable()
{
	$('#entity').val(args.entity);
	id = $(this).attr('id');
	save({ class:'column-select', data:{entity:view.entity}, action:view.baseurl+view.entity+'updatecolumn', goto:false});
	load({ container:'#mgmt-table-container',action:view.baseurl+view.entity+'sort',data:{search:view.search,order:view.order,niveau:args.data['niveau']}  });
	inittable(args);
}

function sorttable()
{
	$('#entity').val(args.entity);
	id = $(this).attr('id');
	columnorder = [];
	
	$('.tablecolumn-name').each(function(){
		name = $(this).val();
		columnorder.push(name);
	});
	
	
	
	save({ data:{entity:view.entity,order:columnorder}, action:view.baseurl+view.entity+'updatecolumnorder', goto:false});
	load({ container:'#mgmt-table-container',action:view.baseurl+view.entity+'sort',data:{search:view.search,order:view.order,niveau:args.data['niveau']}  });
	
	inittable(args);
}

function gridview()
{
	save({ data:{entity:view.entity,order:view.columnorder}, action:view.baseurl+view.entity+'updatecolumnorder', goto:false});
	load({ container:'#mgmt-table-container',action:view.baseurl+'media/grid',data:{search:view.search,order:view.order,niveau:args.data['niveau']}  });
	
	inittable(args);
}


//toggle column select columns/settings
function togglecolumnselect(){
	//column-select-slide
	$('.column-select-slide').toggle();	
}
function togglecolumnselect2(){
	$('.column-select-slide2').toggle();	
}


//set number of rows
function numberofrows(e)
{
	view.rows =	$(e).html();
	load({action:view.baseurl+view.entity+'sort',data:{rows:view.rows,search:view.search,order:view.order,niveau:args.data['niveau']}});
	inittable(args);	
}

//select current visible functions on table
function visiblefunctions()
{

}

function selectall()
{
	if($('#selectall').is(':checked')) 
	{
		$('.selected').each(function() { //loop through each checkbox
			this.checked = true;  //select all checkboxes with class "checkbox1"              
		});
	}
	else
	{
		$('.selected').each(function() { //loop through each checkbox
			this.checked = false; //deselect all checkboxes with class "checkbox1"                      
		});        
	}	
}

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

function setargs(key,value)
{
	args.data[key] = value;
}


/* MGMT 0.2 */
function setInlineEdit(e)
{
	if($(e).is(':checked')) 
	{ value = 1; }
	else
	{ value = 0; }
	
	save({ data:{entity:view.entity,value:value,key:'inlineedit'}, action:view.baseurl+view.entity+'updatesettings', goto:false});
	load({ container:'#mgmt-table-container',action:view.baseurl+view.entity+'sort',data:{search:view.search,order:view.order,niveau:args.data['niveau']}  }); 
	
	inittable(args);
}
function setGridView(e)
{
	if ($(e).is(':checked')) 
	{ value = 1; }
	else
	{ value = 0; }
	
	save({ data:{entity:view.entity,value:value,key:'showgrid'}, action:view.baseurl+view.entity+'updatesettings', goto:false});
	load({ container:'#mgmt-table-container',action:view.baseurl+view.entity+'sort',data:{search:view.search,order:view.order,niveau:args.data['niveau']}  }); 
	
	inittable(args);
}

function gridView()
{
	//save({ data:{entity:view.entity,order:view.columnorder}, action:view.baseurl+view.entity+'updatecolumnorder', goto:false});
	load({ container:'#mgmt-table-container',action:view.baseurl+view.entity+'grid',data:{search:view.search,order:view.order,niveau:args.data['niveau']}  });
	inittable(args);
}

function listView()
{
	//save({ data:{entity:view.entity,order:view.columnorder}, action:view.baseurl+view.entity+'updatecolumnorder', goto:false});
	load({ container:'#mgmt-table-container',action:view.baseurl+view.entity+'sort',data:{search:view.search,order:view.order,niveau:args.data['niveau']}  });
	inittable(args);
}




//set current page
//set search filter
//set sort
function inittable(args)
{
	if(args.baseurl != null)
	{	
		view.baseurl = args.baseurl;
		view.entity = args.entity; 
	}

	$('#selectall').change(function(){ selectall(this); });
	
//	$('.table-editable tr td').click(function(){ edittablecell(this); });
	
	//order by column name
	$('thead tr th a').click(function(){ sortbycolumn(this) });
	
	//$('.search-query').keyup(function(){ searchtable(this) });
	$('.search-query').keyup(function(){
		delay(function(){ searchtable(); }, 1000);
	});
	
	$('.export-button').click(function(){ exporttable(this) });
	
	//selecteer kolommen
	$('.column-select').click(function(){ selecttable(this) });
	
	//$('.column-select').click(function(){ selecttable(this) });

	//order of columns from left to right
	$('.column-select-list').sortable({
		stop: function( event, ui ){ sorttable(this); }
	});
	
	$('#column-select-show2').click(function(){ togglecolumnselect2(this) });

	$('.number-of-rows li a').click(function(){ numberofrows(this) });
	
	/*mgmt 0.2*/
	$('.edit').click(function(){ edittablecell(this); });
	
	$('#enableinlineedit').click(function(){ setInlineEdit(this); });
	
	$('#enablegridview').click(function(){ setGridView(this); });
	
	
	$('#gridview').click(function(){ gridView(this); });
	
	$('#listview').click(function(){ listView(this); });
	
}

$('#column-select-show').click(function(){
	//togglecolumnselect(this);
	$('.column-select-slide').toggle();
});


