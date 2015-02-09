  {{ content() }}
<h2><?php if(isset($user->titel)){ echo $user->titel; } ?></h2>
<div class="wrap">

<div class="content container">
	<div class="row">
		<div class="col-md-12"> 
		    <h2 class="page-title">contact<small></small></h2>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<section class="widget tiny-x2">
				<div class="body">
					<table>	
						
						<tr>
						   <td><b><?=('naam');?></b></td>
						   <td><?php echo $contact->naam; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('email');?></b></td>
						   <td><?php echo $contact->email; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('telefoon');?></b></td>
						   <td><?php echo $contact->telefoon; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('bericht');?></b></td>
						   <td><?php echo $contact->bericht; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('post');?></b></td>
						   <td><?php echo $contact->post; ?></td>	
						</tr>
						
						<tr>
						   <td><b><?=('nieuwsbrief');?></b></td>
						   <td><?php echo $contact->nieuwsbrief; ?></td>	
						</tr>
						
					</table>
				 </div>
			</section>
		</div>
		<? if(isset($contact->file->path)){ ?>
		<div class="col-md-5">
			<section class="widget tiny-x2">
				<header>
					<div class="body">
						<img src="<?=$contact->file->path; ?>" />	
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

