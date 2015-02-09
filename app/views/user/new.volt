
<div class="wrap">
	<div class="content container">
		<div class="row">
		    <div class="col-md-12">
	<h2 class="page-title"><? if(isset($_GET['id'])){ echo 'Wijzig'; }else{echo 'Nieuw';} ?> gebruiker <!--<small>Out of the box form</small>--></h2>
		    </div>
		</div>
		<div class="row">
		    <div class="col-md-7">
		        <section class="widget">
		            <header>
		             <!--   <h4><i class="fa fa-user"></i> Account Profile <small>Create new or edit existing user</small></h4>-->
					</header>
					<div class="body">
						<div id="user-form" class="form-horizontal label-left" data-parsley-priority-enabled="false" novalidate="">
							<input type="hidden" id="id" class="form-control" value="<? if(isset($_GET['id'])){ echo $_GET['id']; }else{ echo $entityid; } ?>" />
							<fieldset>
								<div class="control-group" id="firstnamegroup">
									<label class="control-label" for="firstname">Voornaam
										<ul id="firstnameerror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("firstname"); ?></div>
									</div>
								</div>
								<div class="control-group" id="insertiongroup">
									<label class="control-label" for="insertion">Tussenvoegsel
										<ul id="insertionerror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("insertion"); ?></div>
									</div>
								</div>
								<div class="control-group" id="lastnamegroup">
									<label class="control-label" for="lastname">Achternaam
										<ul id="lastnameerror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("lastname"); ?></div>
									</div>
								</div>
								<div class="control-group" id="emailgroup">
									<label class="control-label" for="email">Email*
										<ul id="emailerror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("email"); ?></div>
									</div>
								</div>
								<div class="control-group" id="passwordgroup">
									<label class="control-label" for="password">Wachtwoord*
										<ul id="passworderror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("password"); ?></div>
									</div>
								</div>
								<div class="control-group" id="passwordgroup">
									<label class="control-label" for="password">Herhaal wachtwoord*
										<ul id="passworderror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("password2"); ?></div>
									</div>
								</div>
								<div class="control-group" id="postcodegroup">
									<label class="control-label" for="postcode">Postcode
										<ul id="postcodeerror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("postcode"); ?></div>
									</div>
								</div>
								<div class="control-group" id="citygroup">
									<label class="control-label" for="city">Plaats
										<ul id="cityerror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("city"); ?></div>
									</div>
								</div>
								<div class="control-group" id="streetgroup">
									<label class="control-label" for="street">Straat
										<ul id="streeterror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("street"); ?></div>
									</div>
								</div>
								<div class="control-group" id="streetnumbergroup">
									<label class="control-label" for="streetnumber">Huisnummer
										<ul id="streetnumbererror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("streetnumber"); ?></div>
									</div>
								</div>
								<div class="control-group" id="telephonegroup">
									<label class="control-label" for="telephone">Telefoon
										<ul id="telephoneerror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("telephone"); ?></div>
									</div>
								</div>
								<div class="control-group" id="mobilegroup">
									<label class="control-label" for="mobile">Mobiel
										<ul id="mobileerror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("mobile"); ?></div>
									</div>
								</div>
								<div class="control-group" id="fileidgroup">							
									<label class="control-label" for="fileid">Foto
										<ul id="fileiderror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls control-group">
										<div><?php echo $form->render("gebruikerfoto"); ?></div>						
									</div>
								</div>
								
								<?/*	<div style="margin-left:15px;">
									<?	//crop images settings
										$config = array('slot' => 0,'x' => 210,'y' => 210,'cx' => 200,'cy' => 200,'id' => 23,'crop' => 'true');	?>
									<?php $this->partial("file/singleupload"); ?> 	
										</div>*/ ?>
								
								
								<?/*
								<div class="control-group" id="verificationgroup">
									<label class="control-label" for="verification">Verification
										<ul id="verificationerror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("verification"); ?></div>
									</div>
								</div>*/?>
						
						</fieldset>                       
						<div class="form-actions" class="pull-right">
							<button type="submit" onclick="save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('user/add');?>',goto:'<?=$this->url->get('user/index');?>'});" class="btn btn-primary">Opslaan</button>
							<button type="submit" onclick="save({ class:'form-control',action:'<?=$this->url->get('user/add');?>',goto:'<?=$this->url->get('user/new');?>'});" class="btn btn-primary">Opslaan + Nieuw</button>
							<button type="button" onclick="go('<?=$this->url->get('user/index');?>');" class="btn btn-default">Cancel</button>
						</div>
					</div>
				</div>
			</section>
			</div>
			<div class="col-md-5">
				<section class="widget">		
					<script>widget.addWidget({action:'<?=$this->url->get('emailwidget/clean');?>',id:'emailwidget-control',data:{field:'userid',value:'<? if(isset($_GET['id'])){echo $_GET['id']; }?>'}});</script>
					<div id="emailwidget-control">
						loading form 
					</div>
				</section>
			</div>
			<div class="col-md-5">
				<section class="widget">		
					<script>widget.addWidget({action:'<?=$this->url->get('permissionswidget/clean');?>',id:'permissions-control',data:{field:'userid',value:'<? if(isset($_GET['id'])){echo $_GET['id']; }?>'}});</script>
					<div id="permissions-control">
						loading form 
					</div>
				</section>
			</div>
		</div>
	</div>		
</div>



