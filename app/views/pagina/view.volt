  {{ content() }}
<h2><?php if(isset($user->titel)){ echo $user->titel; } ?></h2>
<div class="wrap">

<div class="content container">
	<div class="row">
		<div class="col-md-12"> 
		    <h2 class="page-title">pagina<small></small></h2>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<section class="widget tiny-x2">
				<div class="body">
					<table>	
						
						<tr>
						   <td><b><?=('titel');?></b></td>
						   <td><?php echo $pagina->titel; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('slug');?></b></td>
						   <td><?php echo $pagina->slug; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('beschrijving');?></b></td>
						   <td><?php echo $pagina->beschrijving; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('creationdate');?></b></td>
						   <td><?php echo $pagina->creationdate; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('lastedit');?></b></td>
						   <td><?php echo $pagina->lastedit; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('url');?></b></td>
						   <td><?php echo $pagina->url; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('categorie');?></b></td>
						   <td><?php echo $pagina->categorie; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('niveau');?></b></td>
						   <td><?php echo $pagina->niveau; ?></td>	
						</tr>
						
					</table>
				 </div>
			</section>
		</div>
		<? if(isset($pagina->file->path)){ ?>
		<div class="col-md-5">
			<section class="widget tiny-x2">
				<header>
					<div class="body">
						<img src="<?=$pagina->file->path; ?>" />	
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
						<a href="#category" data-toggle="tab">categorys</a>
					</li>
					
                    </ul>
                </header>
                <div class="body tab-content">
					
					<div id="category" class="tab-pane active">
						<script>
							loadform({action:'<?=$this->url->get('category/clean');?>',id:'category-control',data:{field:'paginaid',value:'<?=$_GET['id'];?>'}});
						</script>	
						<div id="category-control">
							loading form 
						</div>
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

