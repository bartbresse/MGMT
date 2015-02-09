<?php 
/* standard variables */
if(!isset($entityid)){ die('ENTITYID IS REQUIRED'); }
?>
<script>
	/*custom logic*/
	$('#email').change(function(){
		val = $(this).val();
		$('#to').val(val);
	});
</script>
<header>
	<h4>Email</h4>
</header>
<div class="body">
	<div id="" class="form-horizontal label-left" novalidate="" data-parsley-priority-enabled="false">
		<input type="hidden" id="id" class="form-control" value="<? if(isset($_GET['id'])){ echo $_GET['id']; }else{ echo $entityid; } ?>" />
		<fieldset>
			<!-- NON VISIBLE REQUIRED -->
			<input type="hidden" class="email-control" id="from_name" value="HETWORKS MGMT" />
			<input type="hidden" class="email-control" id="from_email" value="noreply@hetworks.nl" />
			<input type="hidden" class="email-control" id="messageid" value="<?=$message->id;?>" />
			<!-- END NON VISIBLE REQUIRED -->
			<?// TODO hide under advanced settings ?>
			<div style="display:none;" class="control-group advanced-property" id="email-from_emailgroup">
				<label class="control-label" for="to">Van email
					<ul id="bccerror" class="parsley-errors-list"></ul>
				</label>
				<div class="controls form-group">
					<?php echo $form->render("email-from_email"); ?>
				</div>
			</div>
			<div style="display:none;" class="control-group advanced-property" id="email-from_namegroup">
				<label class="control-label" for="to">Van naam
					<ul id="bccerror" class="parsley-errors-list"></ul>
				</label>
				<div class="controls form-group">
					<?php echo $form->render("email-from_name"); ?>
				</div>
			</div>	
			<div style="display:none;" class="control-group advanced-property" id="email-messageid">
				<label for="to">Template
					<ul id="bccerror" class="parsley-errors-list"></ul>
				</label>
				<div class="controls form-group">
					<select onchange="load({ action:'<?=$this->url->get('emailmessage/load');?>',data:{ templateid:this.value },container:'.redactor_editor'});" class="form-control" id="email-messageid">
						<? foreach($emailmessages as $emailmessage){ ?>
						<option <? if($emailmessage->slug == 'standaardbericht'){ echo 'selected';} ?> value="<?=$emailmessage->id;?>"><?=$emailmessage->titel;?></option>
						<? } ?>
					</select>
				</div>
			</div>
			<div class="control-group" id="email-togroup">
				<label class="control-label" for="to">Aan
					<ul id="bccerror" class="parsley-errors-list"></ul>
				</label>
				<?	if($_GET['_url'])
					{
						
					}	?>
				<script>
					$('#email').blur(function(e){
						$('#email-to').append('<option selected="selected" value="'+$(this).val()+'">'+$(this).val()+'</option>');
						$('#email_to_chosen ul').prepend('<li class="search-choice"><span>'+$(this).val()+'</span><a class="search-choice-close" data-option-array-index="0"></a></li>');
					});
				</script>
				<div class="controls form-group">
					<p>Om een plaats te selecteren type eerst plaats: </p>
					<p>Om een team te selecteren type eerst team: </p>
					<p>Om een evenement te selecteren type eerst evenement: </p>
					<select multiple="" id="email-to" class="tag-multiple-select form-control"  data-placeholder="Zoek hier uw contacten..."  tabindex="2">
						<? foreach($users as $user)
						   { ?>						
						<option <? if($selecteduser == $user->id){ echo 'selected'; } ?> value="<?=$user->email;?>"><?=$user->email;?></option>
						<? } ?>	
						<? foreach($groups as $group)
						   { ?>		
						<option <? if(in_array($group,$selectedgroups)){ echo 'selected'; } ?> value="<?=$group;?>"><?=$group;?></option>	
						<? } ?>
					</select>
				</div>
			</div>
			<script type="text/javascript">
				 $(".tag-multiple-select").chosen({ width: '100%' });
			</script>
			<div class="control-group" id="email-subjectgroup">
				<label class="control-label" for="subject">Onderwerp*
					<ul id="subjecterror" class="parsley-errors-list"></ul>
				</label>
				<div class="controls form-group">
					<div>
						<?php echo $form->render("email-subject"); ?>
					</div>
				</div>
			</div>
			
			
			<div class="control-group" id="email-htmlgroup">
				<div class="controls" id="htmlcontainer" style="border:1px solid #ccc;">
				<?php
					$naam = '#naam#';			
					//verification link #activatielink#
					$code = substr(md5(uniqid(rand(), true)), 16, 16);
					
					?><input type="hidden" class="form-control" id="verification" value="<?=$code;?>" /><?
												
					$link = '<a href="'.BASEURL.'session/verification/?q='.$code.'&userid='.$entityid.'">'.BASEURL.'session/verification/?q='.$code.'&userid='.$entityid.'</a>';	
					$a = array('#naam#','#activatielink#','#berichten#','#evenementen#');
					$b = array($naam,$link);
					
					$message->html = str_replace($a,$b,$message->html);

					//wysiwyg settings
					$config = array('id' => 'email-html','class' => 'form-control');	
					
					if(isset($message->html)){ $config['content'] = $message->html; }
					
					$this->partial("file/wysiwyg"); 
				?> 	
				</div>
			</div>
			<?	$get = $this->request->getQuery();
				if($get['_url'] == '/user/new')
				{	?>
				<script>
				///	$('#htmlcontainer').css('width','460px');
					$('#htmlcontainer').css('margin-left','0px');
				</script>	
			<?  } ?>
			<?	$url = array_reverse(explode('/',trim($_GET['_url'],'/')));
				if($url[0] != 'new' || $_GET['_url'] == '/emailmessage/new'){ ?>
			<button class="btn btn-primary right" onclick="save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('emailmessage/send');?>',goto:'<?=$this->url->get('emailmessage/');?>'});" style="float:right;">Verzenden</button>
			<? } ?>
			<script>
				function opties(){ $('.advanced-property').toggle(); }
			</script>
			<?php 
			if($auth['clearance'] >= 900)
			{ ?>
				<div onclick="opties();">geavanceerde opties</div>
			<? } ?>
		</fieldset>    
	</div>		
</div>