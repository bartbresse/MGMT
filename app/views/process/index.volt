<h2>4 Backend management </h2>

<p>Selecteer hieronder de te managen entiteiten in het backend.</p>

<script>
function entity()
{
	val = $('.form-control').val();
	html = '<tr><input type="hidden" value="'+val+'" class="entity-control" id="'+val+'" /><td colspan="2">'+val+'<input type="hidden" value="'+val+'" class="entity-control" name="cat_'+val+'" /></td></tr>';
	$('#table').prepend(html);
}
</script>
<table id="table">
<tr>
	<td>
	<select class="form-control" id="entity">
	<? foreach($tables as $table){ 
			if(!in_array($table,$notables)){ ?>
			<option value="<?=$table;?>"><?=$table;?></option>
			<? }
		} ?>
	</select>
	</td>
	<td><button onclick="entity();">add</button></td>									
</tr>
</table>
<button onclick="save({action:'process/init',goto:'',class:'entity-control',});return false;">
	GO
</button>



