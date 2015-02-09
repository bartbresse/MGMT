


function Widgets()
{
	this.widgetarray = [];
	
	this.addWidget = function(o)
	{
		this.widgetarray.push(o.action);
		this.loadWidget(o);
	}
	
	this.loadWidget = function(o)
	{
		loadform(o);
	}
	
	this.saveWidgets = function()
	{
	
	}
}