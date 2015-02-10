<header class="navbar navbar-default">
	<form style="margin-left:200px;" class="navbar-form navbar-left" role="search">
            <div class="form-group">
                <input style="width:200px;" type="text" autocomplete="off" id="mainsearch" class="form-control" placeholder="Search">
                <div id="searchresults" style="min-width:200px;position:absolute;background-color:#fff;border:1px solid #ccc;z-index:99999;">

                </div>    
            </div>
        </form>
	<ul class="nav navbar-right navbar-nav" style="margin-right:20px;margin-top:0px;font-size:25px;">
                <?/*
                <li class="hidden-xs">
                    <a href="<?php echo  $this->url->get('documentation'); ?>" id="settings" title="Email" data-toggle="popover" data-placement="bottom">
                        <i class="fa fa-question"></i>
                    </a>
		</li>
                */?>
		<li class="hidden-xs">
                    <a href="<?php echo  $this->url->get('mail'); ?>" id="settings" title="Email" data-toggle="popover" data-placement="bottom">
                        <i class="fa fa-envelope"></i>
                    </a>
		</li>
		<?  foreach($modules as $module)
                    { if(strlen($module->icon) > 0){?>
                <li class="hidden-xs">
                    <a href="<?php echo  $this->url->get(strtolower($module->titel)); ?>" id="settings" title="<?=$module->titel;?>" data-toggle="popover" data-placement="bottom">
                        <i class="fa <?=$module->icon;?>"></i>
                    </a>
                </li>                        
                    <?  }} ?>
                <li class="hidden-xs">
			<a href="<?php echo  $this->url->get('media'); ?>" id="settings" title="Media" data-toggle="popover" data-placement="bottom">
				<i class="fa fa-picture-o"></i>
			</a>
		</li>
		<li class="hidden-xs">
			<a href="<?php echo  $this->url->get('settings'); ?>" id="settings" title="Settings" data-toggle="popover" data-placement="bottom">
				<i class="fa fa-cog"></i>
			</a>
		</li>
		<li class="hidden-xs dropdown">
			<a href="#" title="Account" id="account"
			   class="dropdown-toggle"
			   data-toggle="dropdown">
				<i class="fa fa-user"></i>
			</a>
			<ul id="account-menu" class="dropdown-menu account" role="menu">
				<li role="presentation" class="account-picture">
					
				   <?	echo $user->firstname.' '.$user->insertion.' '.$user->lastname;		?>
				</li>
				<?/*
				<li role="presentation">
					<a href="<?=$this->url->get('user/view?id='.$session['id']);?>" class="link">
						<i class="fa fa-user"></i>
						Profile
					</a>
				</li>*/?>
			</ul>
		</li>
		<li class="hidden-xs"><a href="<?=$this->url->get('session/end');?>"><i class="fa fa-sign-out"></i></a></li>
	</ul>
</header>
