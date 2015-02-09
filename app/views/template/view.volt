  {{ content() }}
<h2><?php if(isset($user->titel)){ echo $user->titel; } ?></h2>
<div class="wrap">

<div class="content container">
	<div class="row">
		<div class="col-md-12"> 
		    <h2 class="page-title">template<small></small></h2>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<section class="widget tiny-x2">
				<div class="body">
					<table>	
						
						<tr>
						   <td><b><?=('titel');?></b></td>
						   <td><?php echo $template->titel; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('slug');?></b></td>
						   <td><?php echo $template->slug; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('filename');?></b></td>
						   <td><?php echo $template->filename; ?></td>	
						</tr>
						
					</table>
				 </div>
			</section>
		</div>
		<? if(isset($template->file->path)){ ?>
		<div class="col-md-5">
			<section class="widget tiny-x2">
				<header>
					<div class="body">
						<img src="<?=$template->file->path; ?>" />	
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
						<a href="#message" data-toggle="tab">messages</a>
					</li>
					
                    </ul>
                </header>
                <div class="body tab-content">
					
					<div id="message" class="tab-pane active">
						<script>
							loadform({action:'<?=$this->url->get('message/clean');?>',id:'message-control',data:{field:'templateid',value:'<?=$_GET['id'];?>'}});
						</script>	
						<div id="message-control">
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

