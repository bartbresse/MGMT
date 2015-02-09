

$(function() {

    $('#mainsearch').keyup(function(){    
        if($(this).val().length > 2)
        {
            data = saveData({data:{search:$(this).val()},action:args.baseurl+'search/mocksearch',goto:false});
            $('#searchresults').show();
            $('#searchresults').html(data.html);
            
            
            $(document).mouseup(function (e)
            {
                var container = $("#searchresults");

                if (!container.is(e.target) // if the target of the click isn't the container...
                    && container.has(e.target).length === 0) // ... nor a descendant of the container
                {
                    container.hide();
                }
            });
        }
    });
    
   
    
    

    function resultsBox(data)
    {
       currentcat = ''; 
        
       for(i=0;i<data.results.length;i++)
       {
           
            if(currentcat)
            data.results[i]
           
       } 
        
        
       $('#searchresults').html();  
    }
    
   
    
    
    
});

/*
    $(function() {
        var cache = {};
        $( "#mainsearch" ).autocomplete({
            minLength: 2,
            source: function( request, response ) {
                var term = request.term;
                if ( term in cache ) {
                    response( cache[ term ] );
                    return;
                }
                $.getJSON( "search/mocksearch", request, function( data, status, xhr ) {
                    cache[ term ] = data;
                    response( data );
                });
            }
        });
    });
    
    $.widget( "custom.catcomplete", $.ui.autocomplete, {
        _create: function() {
            this._super();
            this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );
        },
        _renderMenu: function( ul, items ) 
        {
            var that = this,
                currentCategory = "";
                $.each( items, function( index, item ) {
                var li;
                if ( item.category != currentCategory ) {
                    ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
                    currentCategory = item.category;
                }
                li = that._renderItemData( ul, item );
                if ( item.category ) {
                li.attr( "aria-label", item.category + " : " + item.label );
                }
            });
        }
    });
*/