  {{ content() }}
<h2><?php if(isset($user->titel)){ echo $user->titel; } ?></h2>
<div class="wrap">

<div class="content container">
	<div class="row">
		<div class="col-md-12"> 
		    <h2 class="page-title">bericht<small></small></h2>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<section class="widget tiny-x2">
				<div class="body">
					<table>	
						
						<tr>
						   <td><b><?=('titel');?></b></td>
						   <td><?php echo $bericht->titel; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('slug');?></b></td>
						   <td><?php echo $bericht->slug; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('creationdate');?></b></td>
						   <td><?php echo $bericht->creationdate; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('lastedit');?></b></td>
						   <td><?php echo $bericht->lastedit; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('beschrijving');?></b></td>
						   <td><?php echo $bericht->beschrijving; ?></td>	
						</tr>
						
					</table>
				 </div>
			</section>
		</div>
		<? if(isset($bericht->file->path)){ ?>
		<div class="col-md-5">
			<section class="widget tiny-x2">
				<header>
					<div class="body">
						<img src="<?=$bericht->file->getbackendpath(); ?>" />	
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
						<a href="#berichtreactie" data-toggle="tab">berichtreacties</a>
					</li>
					<li class="active">
						<a href="#tag" data-toggle="tab">tags</a>
					</li>
					
                    </ul>
                </header>
                <div class="body tab-content">
					
					<div id="berichtreactie" class="tab-pane active">
						<script>
							loadform({action:'<?=$this->url->get('berichtreactie/clean');?>',id:'berichtreactie-control',data:{field:'berichtid',value:'<?=$_GET['id'];?>'}});
						</script>	
						<div id="berichtreactie-control">
							loading form 
						</div>
					</div>
					<div id="tag" class="tab-pane">
						<script>
							loadform({action:'<?=$this->url->get('tag/clean');?>',id:'tag-control',data:{field:'entityid',value:'<?=$_GET['id'];?>'}});
						</script>	
						<div id="tag-control">
							loading form 
						</div>
					</div>
					<div id="tag" class="tab-pane ">
						<?php if (url_exists(BASEURL.'backend/bericht/index')){ $this->partial("tag/clean"); } ?>
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

