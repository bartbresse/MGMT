  {{ content() }}
<h2><?php if(isset($user->titel)){ echo $user->titel; } ?></h2>
<div class="wrap">

<div class="content container">
	<div class="row">
		<div class="col-md-12"> 
		    <h2 class="page-title">emailmessage<small></small></h2>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10">
			<section class="widget ">
				<div class="body">
					<table>	
						<tr>
						   <td><b>html</b></td>
						   <td><?php echo $emailmessage->html; ?></td>	
						</tr>
						
						<tr>
						   <td><b>subject</b></td>
						   <td><?php echo $emailmessage->subject; ?></td>	
						</tr>
						
						<tr>
						   <td><b>from_email</b></td>
						   <td><?php echo $emailmessage->from_email; ?></td>	
						</tr>
						
						<tr>
						   <td><b>from_name</b></td>
						   <td><?php echo $emailmessage->from_name; ?></td>	
						</tr>
						
					</table>
				 </div>
			</section>
		</div>
		<?/*
		<? if(isset($emailmessage->file) && isset($emailmessage->file->path)){ ?>
		<div class="col-md-5">
			<section class="widget tiny-x2">
				<header>
					<div class="body">
						<img src="<?=$emailmessage->file->path; ?>" />	
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
			<section class="widget">
				<h3>Conversie</h3>
				
				<div>
					<?
						$info = $emailmessage->info();
						print_r($info);
					?>
					<span class="chart" data-percent="86">
						<span class="percent"></span>
					</span>
					<script>
						$(function(){
							$('.chart').easyPieChart({
								easing: 'easeOutBounce',
								onStep: function(from, to, percent) {
									$(this.el).find('.percent').text(Math.round(percent));
								}
							});
							var chart = window.chart = $('.chart').data('easyPieChart');
							$('.js_update').on('click', function() {
								chart.update(Math.random()*200-100);
							});
						});
					</script>
				</div>
				
			</section>
		</div>
	</div>
	*/?>
	
	
	<div class="row">
		<div class="col-md-10">
			<section class="widget tiny-x2" style="height:29px;">
			<?/*	<button style="float:right;" onclick="go('<?=$this->url->get('user/edit&id='.$_GET['id']);?>');" class="btn btn-primary right">Wijzigen</button>*/?>
				<button style="float:right;" onclick="go('<?=$this->url->get('emailmessage/index');?>');" class="btn btn-primary right">Terug</button>
			</section>
		</div>
	</div>
</div>

