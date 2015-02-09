  {{ content() }}
<h2><?php if(isset($user->titel)){ echo $user->titel; } ?></h2>
<div class="wrap">

<div class="content container">
	<div class="row">
		<div class="col-md-12"> 
		    <h2 class="page-title">user<small></small></h2>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<section class="widget tiny-x2">
				<div class="body">
					<table>	
						<tr>
						   <td><b><?=$lang->translate('firstname');?></b></td>
						   <td><?php echo $user->firstname; ?></td>	
						</tr>
						<tr>
						   <td><b><?=$lang->translate('insertion');?></b></td>
						   <td><?php echo $user->insertion; ?></td>	
						</tr>
						<tr>
						   <td><b><?=$lang->translate('lastname');?></b></td>
						   <td><?php echo $user->lastname; ?></td>	
						</tr>
						<tr>
						   <td><b><?=$lang->translate('status');?></b></td>
						   <td><?php echo $user->status; ?></td>	
						</tr>
						<tr>
						   <td><b><?=$lang->translate('email');?></b></td>
						   <td><?php echo $user->email; ?></td>	
						</tr>
						<tr>
						   <td><b><?=$lang->translate('postcode');?></b></td>
						   <td><?php echo $user->postcode; ?></td>	
						</tr>
						<tr>
						   <td><b><?=$lang->translate('city');?></b></td>
						   <td><?php echo $user->city; ?></td>	
						</tr>
						<tr>
						   <td><b><?=$lang->translate('street');?></b></td>
						   <td><?php echo $user->street; ?></td>	
						</tr>
						<tr>
						   <td><b><?=$lang->translate('streetnumber');?></b></td>
						   <td><?php echo $user->streetnumber; ?></td>	
						</tr>
						<tr>
						   <td><b><?=$lang->translate('telephone');?></b></td>
						   <td><?php echo $user->telephone; ?></td>	
						</tr>
						<tr>
						   <td><b><?=$lang->translate('mobile');?></b></td>
						   <td><?php echo $user->mobile; ?></td>	
						</tr>
					</table>
				 </div>
			</section>
		</div>
		<? if(isset($user->file->path)){ ?>
		<div class="col-md-5">
			<section class="widget tiny-x2">
				<header>
					<div class="body">
						<img src="<?=$user->file->getbackendpath(); ?>" />	
					</div>
				</header>
			</section>
		</div>
		<? } ?>
	</div>
	<div class="row">
		<div class="col-md-10">
			  <section class="widget widget-tabs">
                <?
					function url_exists($url) 
					{
						if (!$fp = curl_init($url)) return false;
						return true;
					}
				?>
				<header>
                    <ul class="nav nav-tabs">
						<li class="active">
							<a href="#bericht" data-toggle="tab"><?=$lang->translate("Berichten");?></a>
						</li>
						<li class="">
							<a href="#berichtreactie" data-toggle="tab"><?=$lang->translate("Bericht reacties");?></a>
						</li>
						
						<? 	if($auth['clearance'] > 800){ ?>   
						<li class="">
							<a href="#category" data-toggle="tab"><?=$lang->translate("Plaatsen/Teams");?></a>
						</li>
						<? } ?>
						<li class="">
							<a href="#doelgroep" data-toggle="tab"><?=$lang->translate("doelgroepen");?></a>
						</li>
						<li class="">
							<a href="#event" data-toggle="tab"><?=$lang->translate("Agenda");?></a>
						</li>
						<li class="">
							<a href="#pagina" data-toggle="tab"><?=$lang->translate("pagina's");?></a>
						</li>
						<li class="">
							<a href="#sponsor" data-toggle="tab"><?=$lang->translate("Partners");?></a>
						</li>
						<li class="">
							<a href="#tag" data-toggle="tab"><?=$lang->translate("tags");?></a>
						</li>
						<li class="">
							<a href="#workshop" data-toggle="tab"><?=$lang->translate("workshops");?></a>
						</li>
						<li class="">
							<a href="#event" data-toggle="tab"><?=$lang->translate("events");?></a>
						</li>
                    </ul>
                </header>
                <div class="body tab-content">
					<div id="bericht" class="tab-pane active">
						<script>
							loadform({action:'<?=$this->url->get('bericht/clean');?>',id:'bericht-control',data:{field:'userid',value:'<?=$_GET['id'];?>'}});
						</script>	
						<div id="bericht-control">
							loading form 
						</div>
					</div>
					<div id="berichtreactie" class="tab-pane ">
						<script>
							loadform({action:'<?=$this->url->get('berichtreactie/clean');?>',id:'berichtreactie-control',data:{field:'userid',value:'<?=$_GET['id'];?>'}});
						</script>	
						<div id="berichtreactie-control">
							loading form 
						</div>
					</div>
					
					<div id="category" class="tab-pane ">
						<script>
							loadform({action:'<?=$this->url->get('category/clean');?>',id:'category-control',data:{field:'userid',value:'<?=$_GET['id'];?>'}});
						</script>	
						<div id="category-control">
							loading form 
						</div>
					</div>
					<div id="doelgroep" class="tab-pane ">
						<script>
							loadform({action:'<?=$this->url->get('doelgroep/clean');?>',id:'doelgroep-control',data:{field:'userid',value:'<?=$_GET['id'];?>'}});
						</script>	
						<div id="doelgroep-control">
							loading form 
						</div>
					</div>
					
					<div id="event" class="tab-pane ">
						<script>
							loadform({action:'<?=$this->url->get('event/clean');?>',id:'event-control',data:{field:'userid',value:'<?=$_GET['id'];?>'}});
						</script>	
						<div id="event-control">
							loading form 
						</div>
					</div>
					<div id="pagina" class="tab-pane ">
						<script>
							loadform({action:'<?=$this->url->get('pagina/clean');?>',id:'pagina-control',data:{field:'userid',value:'<?=$_GET['id'];?>'}});
						</script>	
						<div id="pagina-control">
							loading form 
						</div>
                                        </div>
					<div id="sponsor" class="tab-pane ">
						<script>
							loadform({action:'<?=$this->url->get('sponsor/clean');?>',id:'sponsor-control',data:{field:'userid',value:'<?=$_GET['id'];?>'}});
						</script>	
						<div id="sponsor-control">
							loading form 
						</div>
					</div>
					
					<div id="tag" class="tab-pane ">
						<script>
							loadform({action:'<?=$this->url->get('tag/clean');?>',id:'tag-control',data:{field:'userid',value:'<?=$_GET['id'];?>'}});
						</script>	
						<div id="tag-control">
							loading form 
						</div>
					</div>
					<div id="workshop" class="tab-pane ">
						<script>
							loadform({action:'<?=$this->url->get('workshop/clean');?>',id:'workshop-control',data:{field:'userid',value:'<?=$_GET['id'];?>'}});
						</script>	
						<div id="workshop-control">
							loading form 
						</div>
					</div>
					<div id="event" class="tab-pane ">
						<?php if (url_exists(BASEURL.'backend/user/index')){ $this->partial("event/clean"); } ?>
				    </div>
					
                </div>
            </section>
		</div>
	</div>
	
	
	
	<div class="row">
		<div class="col-md-10">
			<section class="widget tiny-x2" style="height:29px;">
				<button style="float:right;" onclick="go('<?=$this->url->get('user/edit&id='.$_GET['id']);?>');" class="btn btn-primary right">Wijzigen</button>
				<button style="float:right;" onclick="go('<?=$this->url->get('user/index');?>');" class="btn btn-primary right">Terug</button>
			</section>
		</div>
	</div>
</div>

