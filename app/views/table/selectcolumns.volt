<? if($user->clearance >= 900){ ?>
<div class="col-md-12">
	<section class="widget">
		<a id="column-select-show">
		   <i class="fa fa-cog"></i>
		   <span class="name">Selecteer kolommen</span>
		</a>
	<?/*	<input type="hidden" class="column-select" id="entity" value="<??>" />*/?>
		<div class="column-select-slide" style="display:none;">
			<p>Sleep de kolommen in de gewenste volgorde</p>
			<ul id="side-nav"  class="side-nav column-select-list" >
				<?	foreach($allcolumns as $allcolumn){
				if($allcolumn != 'entity'){	?>
				<li class="panel" style="margin-left:20px;">
					<table>
						<tr style="cursor:pointer;"> 
							<input type="hidden" value="<?=$allcolumn;?>" class="tablecolumn-name" />
							<td  style="width:100px;"><?=$lang->translate($allcolumn);?></td>
							<td><input class="column-select" style="width:25px;" <? if(in_array($allcolumn,$columns)){ echo 'checked="checked"'; } ?> value="1" class="" id="<?=$allcolumn;?>" type="checkbox"/></td>
						</tr>
					</table>
				</li>
				<?} } ?>
			</ul>
			<p>Inline edit</p>
			<ul>
				<li><input id="enableinlineedit" type="checkbox" /> Enable inline editing.</li>
				<li><input id="enablegridview" type="checkbox" /> Enable grid view.</li>
			</ul>
		</div>
	</section>
</div>
<? } ?>