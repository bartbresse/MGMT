  {{ content() }}
<h2><?php if(isset($user->titel)){ echo $user->titel; } ?></h2>
<div class="wrap">

<div class="content container">
	<div class="row">
		<div class="col-md-12"> 
		    <h2 class="page-title">workshop<small></small></h2>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<section class="widget tiny-x2">
				<div class="body">
					<table>	
						
						<tr>
						   <td><b><?=('titel');?></b></td>
						   <td><?php echo $workshop->titel; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('slug');?></b></td>
						   <td><?php echo $workshop->slug; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('beschrijving');?></b></td>
						   <td><?php echo $workshop->beschrijving; ?></td>	
						</tr>
						
					</table>
				 </div>
			</section>
		</div>
		<? if(isset($workshop->file->path)){ ?>
		<div class="col-md-5">
			<section class="widget tiny-x2">
				<header>
					<div class="body">
						<img src="<?=$workshop->file->path; ?>" />	
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
                       
                    </ul>
                </header>
                <div class="body tab-content">
					
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

