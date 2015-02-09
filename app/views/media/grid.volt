<div style="margin-left:10px;">
<style>

</style>

<? if(isset($showupload)){ ?>
<div class="col-md-2" style="padding:5px;">
	<section id="mgmt-table-container" style="border:1px solid #ccc;margin-top:0px;padding-top:0px;padding:0px;margin:0px;">
		<table style="width:100%;">
			<tr colspan="0">
				<td>
					<?=$form->render('file2');?>					
				</td>
			</tr>
		</table>
	</section>
</div>
<? } ?>

<?php
if(isset($files)){
foreach($files->items as $file){
 ?>
<div class="col-md-2" style="padding:5px;" onclick="cropimage('<?=$this->url->get('x/'.$file->getPath());?>',{crop:true});">
	<section id="mgmt-table-container" style="border:1px solid #ccc;margin-top:0px;padding-top:0px;padding:0px;margin:0px;">
		<table style="width:100%;">
			<tr colspan="0">
				<td>
				<img src="<?=$this->url->get('x/'.$file->getThumb('mgmtmediagrid'));?>"  /></td>
			</tr>
			<tr>
			<?	foreach($columns as $column)
				{
					if(in_array($column,$columns)){ ?><td class="<?=$column;?>"><?=$file->$column;?></td><? }
				}
				?>
				<td id="bewerken" style="width:100px;">
					<a href="<?php echo  $this->url->get("media/view&id=".$file->id);?>"><i class="fa fa-search"></i></a>
					<a href="<?php echo  $this->url->get('media/edit&id='.$file->id);?>"><i class="fa fa-edit"></i></a>
					<a onclick="del('<?=$file->id;?>','file');"><i class="fa fa-trash-o"></i></a>
				</td>
			</tr>
		</table>
	</section>
</div>	<?		
	}
} ?>
</div>
