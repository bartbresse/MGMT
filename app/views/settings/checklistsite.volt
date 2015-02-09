<div class="wrap">
	<div class="content container">
		<div class="row">
			<div class="col-md-12"> 
				<h2 class="page-title">Pagina checklist <small></small></h2>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" style="background-color:#fff;">
			<header>
				<h4>
				   <i class="fa fa-file"></i>
				   Dit is een pagina checklist, als er voor iedere pagina/view een design is kan er begonnen worden met het realiseren van het front-end voor de applicatie.
				</h4>
			</header>
		
			<br />
	
			<table class="table table-bordered">
			<tr>
				<th>path</th>
				<th>Categorie/Controller</th>
				<th>Pagina/View</th>
				<th>Onderdeel/Entity</th>
			<tr>
			<?php 
				$cc3=0;$cc2=0;$cc4=1000;
				foreach($controllers as $controller)
				{
					if(count($controllers) > 0){
					?><tr><td></td><td colspan="3"><?=$controller->title;?></td></tr><?
					foreach($controller->Controllerview as $view)
					{
						$notablex[$cc3] = $notables;
						?><tr><td>/views/<?=$controller->title;?>/<?=$view->title;?></td>
							  <input type="hidden" value="<?=$view->id;?>" class="view-control<?=$cc4;?>" id="viewid"/>
							  <input type="hidden" value="<?=$controller->id;?>" class="view-control<?=$cc4;?>" id="controllerid"/>	
							  <td>&nbsp;</td><td colspan="2"><?=$view->title;?></td>
						  </tr><?
							$cc4++;
							foreach($view->Entity as $entity)
							{
								$notablex[$cc4] = $notables;
								array_push($notablex[$cc3],$entity->title);
								?><tr><td></td>
									  <td>&nbsp;</td>
									  <td>&nbsp;</td>	
									  <td><?=$entity->title; ?></td><?
									 
									$cc4++;	
							}			
							$cc3++;
					} 
						$cc2++;	
				}
			}	   
				$cc3++; 
			?>
			<tr>
				<td></td>
				<td>session</td>
				<td></td>
				<td></td>			
			</tr>
			<tr>
				<td>/views/session/login</td>
				<td></td>
				<td>login</td>
				<td></td>			
			</tr>
			<tr>
				<td>/views/session/register</td>
				<td></td>
				<td>register</td>
				<td></td>			
			</tr>	
			<tr>
				<td>/views/session/verification</td>
				<td></td>
				<td>verification</td>
				<td></td>			
			</tr>	
			<tr>
				<td>/views/session/rout404</td>
				<td></td>
				<td>404</td>
				<td></td>			
			</tr>		
			</table>
		</div>
	</div>
</div>
