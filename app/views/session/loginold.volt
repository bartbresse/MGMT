
<div class="single-widget-container">
    <section class="widget login-widget">
        <header class="text-align-center">
            <h4>Inloggen</h4>
        </header>
        <div class="body">
		
            <div class="no-margin">
                <fieldset>
                    <div id="emailgroup" class="form-group no-margin">
                        <label for="email" >Email</label>
                        <label style="color:red;" class="control-label" for="email">
							<ul></ul>
						</label>					
						<div class="input-group input-group-lg">
							<span class="input-group-addon">
								<i class="fa fa-user"></i>
							</span>
                            <input id="email"  type="email" class="form-control" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" >Wachtwoord</label>
                        <div class="input-group input-group-lg">
							<span class="input-group-addon">
								<i class="fa fa-lock"></i>
							</span>
                            <input id="password" type="password" class="form-control" placeholder="Wachtwoord">
                        </div>
                    </div>
                </fieldset>
                <div class="form-actions">
					<?
						if(isset($_GET['_url']) && isset($_GET['id']))
						{
							$url = ltrim($_GET['_url'],'/').'&id='.$_GET['id'];
						}else{$url = '';}
					?>
                    <button onclick="save({ class:'form-control',action:view.baseurl+'session/start',goto:'<?=$this->url->get().$url;?>'});" class="btn btn-block btn-lg btn-danger">
                        <span class="small-circle"><i class="fa fa-caret-right"></i></span>
                        <small>Inloggen</small>
                    </button>
                    <div class="forgot"><a class="forgot" href="#">Gebruikersnaam of wachtwoord vergeten?</a></div>
                </div>
				<script>
					$('#password').keypress(function (e) {
						if (e.which == 13) {
							
							$('.btn').click();
						  }
					});
				</script>
            </div>
        </div>
	<!--	<footer>
			<a>Login with google</a>
		</footer>-->
		
    </section>
</div>
