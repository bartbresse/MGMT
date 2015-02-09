{{ content() }}
	<header>
		<? if(count($emails->items) > 39){ $this->partial("table/numberofresults"); } ?>		       		
	</header>
	<div>
   <? if(isset($emails->items) && count($emails->items) > 0){ ?>
		<table id="datatable-table" class="table table-striped">
			<thead class="table table-striped table-editable no-margin">
			   <tr>
				<th><input type="checkbox" /></th>
				<?
				$cc = 0;
				foreach($columns as $column)
				{
					?><th><a id="<?=$column;?>" <?/*class="<? if(isset($post) && $post['key'] == $column){ echo $post['order']; } ?>" */?>><?=$lang->translate($column);?></a></th><? 
					$cc++;
				}
				?>
				<th>bewerken</th>
			   </tr>
			</thead>
			<tbody class="table table-striped table-editable no-margin">
			<?
				$sessionuser = $this->session->get("auth");
				$clearance = $sessionuser['clearance'];			
				if(count($emails) > 0){
					foreach($emails->items as $user)
					{
					?><tr id="<?=$user->id;?>">
						<td><input type="checkbox" /></td>
					
					<?	
						foreach($columns as $column)
						{
							 if(in_array($column,$columns)){ ?><td class="<?=$column;?>"><?=$user->$column;?></td><? }
						}
						?>
						<td id="bewerken" style="width:100px;">
						<?	
						if($user->id != $sessionuser['id']) {
							if(in_array('edit',$actions))
							{ ?><a href="<?php echo  $this->url->get("user/view&id=".$user->id);?>"> <i class="fa fa-search"></i> </a><? }
							if(in_array('edit',$actions))
							{ ?><a href="<?php echo  $this->url->get('user/edit&id='.$user->id);?>"> <i class="fa fa-edit"></i> </a><? }

							if($clearance >= $user->clearance && in_array('deactiveren',$actions))
							{ ?>
								<a alt="deactiveren" style="cursor:pointer;" onclick="deactiveren('<?=$user->id;?>','user');"> 
									<i class="fa fa-thumbs-<?php if($user->status==1) echo 'down'; else{ echo 'up'; } ?>"></i> 
								</a>
						<?	} ?>
						<?	if(in_array('delete',$actions))
							{ ?><a alt="verwijderen" style="cursor:pointer;" onclick="del('<?=$user->id;?>','user');"> <i class="fa fa-trash-o"></i> </a><?	} 
						}
						?>
					   </td>
					 </tr>
					 <? if(false){ ?>
					 <tr style="display:none;">
						<td>
						
						</td>
					 </tr>
					 <?	}	
					}
				} ?>
			</tbody>
		</table>
	 <? } //end if entities
		else
		{
		?>
		<table>
			<tr><td> Er zijn nog geen rijen gevonden. </td></tr>
		</table>
		<?
		} ?>
	</div>
	<div class="clearfix">		
		<div class="pull-right">
			<? if(isset($_GET['id'])){ $id = '&categoryid='.$_GET['id']; }else{ $id ='';} ?>
			<button class="btn btn-primary" onclick="go('<?php echo $this->url->get('mail/new'.$id);?>');">
				<i class="fa fa-plus"></i>
				Nieuw
			</button>
		</div>
		<?=$this->partial("table/export");?> 	
		<div>
<? 	$controller = 'emails';
$model = 'MgmtEmail';	?>
<?=$this->partial("table/pagination");?> 
		</div>
	</div>




