

//general search function
function search(e)
{
	view.search = $(e).val();
	if(view.search.length > 3 || view.search.length == 0)
	{
		load({action:view.baseurl+view.entity+'sort',data:{rows:view.rows,search:view.search,order:view.order}});
		inittable();	
	}
}



$('.search-query').keyup(function(){ search(this) });	

