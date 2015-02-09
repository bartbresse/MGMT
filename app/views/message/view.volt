  {{ content() }}
<h2><?php if(isset($user->titel)){ echo $user->titel; } ?></h2>
<div class="wrap">

<div class="content container">
	<div class="row">
		<div class="col-md-12"> 
		    <h2 class="page-title">message<small></small></h2>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<section class="widget tiny-x2">
				<div class="body">
					<table>	
						
						<tr>
						   <td><b><?=('titel');?></b></td>
						   <td><?php echo $message->titel; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('slug');?></b></td>
						   <td><?php echo $message->slug; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('subject');?></b></td>
						   <td><?php echo $message->subject; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('html');?></b></td>
						   <td><?php echo $message->html; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('to');?></b></td>
						   <td><?php echo $message->to; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('bcc');?></b></td>
						   <td><?php echo $message->bcc; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('footer');?></b></td>
						   <td><?php echo $message->footer; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('lastedit');?></b></td>
						   <td><?php echo $message->lastedit; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('creationdate');?></b></td>
						   <td><?php echo $message->creationdate; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('tags');?></b></td>
						   <td><?php echo $message->tags; ?></td>	
						</tr>
						
					</table>
				 </div>
			</section>
		</div>
		<? if(isset($message->file->path)){ ?>
		<div class="col-md-5">
			<section class="widget tiny-x2">
				<header>
					<div class="body">
						<img src="<?=$message->file->path; ?>" />	
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
						<a href="#emailmessage" data-toggle="tab">emailmessages</a>
					</li>
					
                    </ul>
                </header>
                <div class="body tab-content">
					
					<div id="emailmessage" class="tab-pane active">
						<script>
							loadform({action:'<?=$this->url->get('emailmessage/clean');?>',id:'emailmessage-control',data:{field:'messageid',value:'<?=$_GET['id'];?>'}});
						</script>	
						<div id="emailmessage-control">
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

