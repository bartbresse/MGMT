  {{ content() }}
<h2><?php if(isset($user->titel)){ echo $user->titel; } ?></h2>
<div class="wrap">

<div class="content container">
	<div class="row">
		<div class="col-md-12"> 
		    <h2 class="page-title">Plaats/Team</h2>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<section class="widget tiny-x2">
				<div class="body">
					<table>	
						<tr>
						   <td><b>titel</b></td>
						   <td><?php echo $category->titel; ?></td>	
						</tr>
						<tr>
						   <td><b>beschrijving1</b></td>
						   <td><?php echo $category->beschrijving1; ?></td>	
						</tr>
					
						<tr>
						   <td><b>niveau</b></td>
						   <td><?php echo $category->niveau; ?></td>	
						</tr>
						
						<tr>
						   <td><b>hoofdtitel</b></td>
						   <td><?php echo $category->hoofdtitel; ?></td>	
						</tr>
						
						<tr>
						   <td><b>header</b></td>
						   <td><?php echo $category->header; ?></td>	
						</tr>
						
						<tr>
						   <td><b>laatste wijziging</b></td>
						   <td><?php echo $category->lastedit; ?></td>	
						</tr>
						
						<tr>
						   <td><b>aangemaakt op</b></td>
						   <td><?php echo $category->creationdate; ?></td>	
						</tr>
						
					</table>
				 </div>
			</section>
		</div>
		<? if(isset($category->file->path)){ ?>
		<div class="col-md-5">
			<section class="widget tiny-x2">
				<header>
					<div class="body">
						<img src="<?=$category->file->path; ?>" />	
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
							<a href="#bericht" data-toggle="tab">Berichten</a>
						</li>
						<li class="">
							<a href="#event" data-toggle="tab">Agenda</a>
						</li>
						<li class="">
							<a href="#pagina" data-toggle="tab">Pagina's</a>
						</li>
						<li class="">
							<a href="#gebruiker" data-toggle="tab">Gebruikers</a>
						</li>
					</ul>
                </header>
                <div class="body tab-content">
					
					<div id="bericht" class="tab-pane active">
						<script>
							loadform({action:'<?=$this->url->get('bericht/clean');?>',id:'bericht-control',data:{field:'categoryid',value:'<?=$_GET['id'];?>'}});
						</script>	
						<div id="bericht-control">
							loading form 
						</div>
					</div>
					<div id="event" class="tab-pane ">
						<script>
							loadform({action:'<?=$this->url->get('event/clean');?>',id:'event-control',data:{field:'categoryid',value:'<?=$_GET['id'];?>'}});
						</script>	
						<div id="event-control">
							loading form 
						</div>
					</div>
					<div id="pagina" class="tab-pane ">
						<script>
							loadform({action:'<?=$this->url->get('pagina/clean');?>',id:'pagina-control',data:{field:'categoryid',value:'<?=$_GET['id'];?>'}});
						</script>	
						<div id="pagina-control">
							loading form 
						</div>
					</div>
					<div id="gebruiker" class="tab-pane ">
						<script>
							loadform({action:'<?=$this->url->get('user/clean');?>',id:'gebruiker-control',data:{field:'categoryid',value:'<?=$_GET['id'];?>'}});
						</script>	
						<div id="gebruiker-control">
							loading form 
						</div>
					</div>
                </div>
            </section>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10">
			<section class="widget">
				<div id="message-control">
					<?=$this->partial("file/email");?> 
				</div>
			</section>
		</div>
	</div>
	<?/*
	<div class="row">
		<div class="col-md-10">
			<section class="widget">
				<div id="acl-control">
					<?=$this->partial("file/relaties");?> 
				</div>
			</section>
		</div>
	</div>*/?>
	<div class="row">
		<div class="col-md-10">
			<section class="widget tiny-x2" style="height:29px;">
				<?/*
					save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('category/add');?>',goto:'<?=$this->url->get('category/index/&niveau=3');?>'});
				*/?>
				<?/*go('<?=$this->url->get('user/edit&id='.$_GET['id']);?>');*/
				
					//UPDATING FOR WIDGETS NEEDS TO BE POSSIBLE NEXT TIME
				
				?>
				<button style="float:right;" onclick="go('<?=$this->url->get('category/edit&id='.$_GET['id']);?>');" class="btn btn-primary right">Wijzigen</button>
				<button style="float:right;" onclick="go('<?=$this->url->get('category/index');?>');" class="btn btn-primary right">Terug</button>
			</section>
		</div>
	</div>
</div>

