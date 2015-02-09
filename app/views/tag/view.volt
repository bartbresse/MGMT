  {{ content() }}
<h2><?php if(isset($user->titel)){ echo $user->titel; } ?></h2>
<div class="wrap">

<div class="content container">
	<div class="row">
		<div class="col-md-12"> 
		    <h2 class="page-title">tag<small></small></h2>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<section class="widget tiny-x2">
				<div class="body">
					<table>	
						
						<tr>
						   <td><b>titel</b></td>
						   <td><?php echo $tag->titel; ?></td>	
						</tr>
						
						<tr>
						   <td><b>slug</b></td>
						   <td><?php echo $tag->slug; ?></td>	
						</tr>
						
						<tr>
						   <td><b>lastedit</b></td>
						   <td><?php echo $tag->lastedit; ?></td>	
						</tr>
						
						<tr>
						   <td><b>creationdate</b></td>
						   <td><?php echo $tag->creationdate; ?></td>	
						</tr>
						
						<tr>
						   <td><b>entity</b></td>
						   <td><?php echo $tag->entity; ?></td>	
						</tr>
						
					</table>
				 </div>
			</section>
		</div>
		<? if(isset($tag->file->path)){ ?>
		<div class="col-md-5">
			<section class="widget tiny-x2">
				<header>
					<div class="body">
						<img src="<?=$tag->file->path; ?>" />	
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
						<a href="#bericht" data-toggle="tab">berichts</a>
					</li>
					<li class="">
						<a href="#event" data-toggle="tab">events</a>
					</li>
					
                    </ul>
                </header>
                <div class="body tab-content">
					<div id="bericht" class="tab-pane active">
						<?php if (url_exists(BASEURL.'backend/tag/index')){ $this->partial("bericht/clean"); } ?>
				    </div>
					<div id="event" class="tab-pane ">
						<?php if (url_exists(BASEURL.'backend/tag/index')){ $this->partial("event/clean"); } ?>
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

