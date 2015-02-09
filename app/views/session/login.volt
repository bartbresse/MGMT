
<div class="single-widget-container">
    <section class="login-widget widget" style="border:none;">
       
        <div class="body">
            <div class="">
                <fieldset>
                    <div id="emailgroup" class="form-group">
						<div class="input-group-lg">
                            <input id="email"  type="email" class="form-control" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group-lg">
                            <input id="password" type="password" class="form-control" placeholder="Wachtwoord">
                        </div>
                    </div>
                </fieldset>
                <div class="">
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
					<div>&nbsp;</div>
                    <div class="forgot"><a class="forgot" style="color:white;" href="#">Gebruikersnaam of wachtwoord vergeten?</a></div>
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
