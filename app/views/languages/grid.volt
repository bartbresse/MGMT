
<div style="margin-left:-15px;margin-bottom:10px;">
<?php
	foreach($Languagess->items as $file)
	{
	 ?>
<div class="col-md-2" onclick="">
	<section id="mgmt-table-container" style="border:1px solid #ccc;margin-top:0px;padding-top:0px;padding:0px;margin:0px;">
		<table style="width:100%;">
			<tr colspan="0">
				<td>
				<? if(isset($file->fileid)){ ?>
				<img src="<?=$this->url->get('x/'.$file->getThumb('mgmtmediagrid'));?>"  /></td>
				<? } ?>
			</tr>
			<tr>
				<td>
					<table style="margin:10px;">		
					<?	foreach($columns as $column)
						{
							if(in_array($column,$columns)){ ?><tr><td><?=ucfirst($column);?>: </td><td class="<?=$column;?>"><?=$file->$column;?></td></tr><? }
						}
						?>
						<tr>
							<td></td>
							<td>
								<a href="<?php echo  $this->url->get("languages/view&id=".$file->id);?>"><i class="fa fa-search"></i></a>
								<a href="<?php echo  $this->url->get('languages/edit&id='.$file->id);?>"><i class="fa fa-edit"></i></a>
								<a onclick="del('<?=$file->id;?>','languages');"><i class="fa fa-trash-o"></i></a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</section>
</div>	<?		
	}
 ?>
</div>
