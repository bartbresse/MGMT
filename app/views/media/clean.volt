<? 
if(isset($thumbs)){
	foreach($thumbs as $thumb){ ?>
		<table>
			<tr id="<?=$bericht->id;?>">
			<?	foreach($columns as $column)
				{
					if(in_array($column,$columns)){ ?><td class="<?=$column;?>"><?=$bericht->$column;?></td><? }
				}
				?>
				<td id="bewerken" style="width:100px;">
					<a href="<?php echo  $this->url->get("bericht/view&id=".$bericht->id);?>"><i class="fa fa-search"></i></a>
					<a href="<?php echo  $this->url->get('bericht/edit&id='.$bericht->id);?>"><i class="fa fa-edit"></i></a>
					<a onclick="del('<?=$bericht->id;?>','bericht');"><i class="fa fa-trash-o"></i></a>
				</td>
			</tr>
		</table><?		
		}
	} ?>