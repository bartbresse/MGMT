<?=$this->partial("layouts/header");?>
<nav id="sidebar" class="sidebar nav-collapse collapse">
    <ul id="side-nav" class="side-nav">
        <li >
            <a href="<?php echo  $this->url->get(); ?>"><i class="fa fa-home"></i> <span class="name">Dashboard</span></a>
        </li>
		<?/*
		<li class="panel">
            <a class="accordion-toggle collapsed" data-toggle="collapse"
               data-parent="#side-nav" href="#backup-collapse"><i class="fa fa-table"></i> <span class="name">Backup</span></a>
				<ul id="backup-collapse" class="panel-collapse collapse">
					<li><a href="<?php echo  $this->url->get('settings/backupstatus'); ?>">Status</a></li>
				</ul>
        </li>*/?>
		<li class="panel">
            <a class="accordion-toggle collapsed" data-toggle="collapse"
               data-parent="#side-nav" href="#entities-collapse"><i class="fa fa-table"></i> <span class="name">Entiteiten</span></a>
			<ul id="entities-collapse" class="panel-collapse collapse">
				<li><a href="<?php echo  $this->url->get('entity'); ?>">Overzicht</a></li>  
				<li><a href="<?php echo  $this->url->get('entity/generatemodel'); ?>">Nieuw model</a></li>  
				<li><a href="<?php echo  $this->url->get('entity/generatefile'); ?>">Nieuw bestand</a></li>   			 				
				<li><a href="<?php echo  $this->url->get('entity/new'); ?>">Nieuw</a></li>
			</ul>
        </li>
		
		<?/*
		<li class="panel">
            <a class="accordion-toggle collapsed" data-toggle="collapse"
               data-parent="#side-nav" href="#emailsettings-collapse"><i class="fa fa-table"></i> <span class="name">Email settings</span></a>
			<ul id="emailsettings-collapse" class="panel-collapse collapse">
				<li><a href="<?php echo  $this->url->get('emailsettings'); ?>">Overzicht</a></li>           
				<li><a href="<?php echo  $this->url->get('emailsettings/new'); ?>">Nieuw</a></li>
			</ul>
        </li> 
		<li class="panel">
            <a class="accordion-toggle collapsed" data-toggle="collapse"
               data-parent="#side-nav" href="#entities-collapse"><i class="fa fa-table"></i> <span class="name">Entiteiten old</span></a>
				<ul id="entities-collapse" class="panel-collapse collapse">
					<li><a href="<?php echo  $this->url->get('settings'); ?>">Overzicht</a></li>           
					<li><a href="<?php echo  $this->url->get('settings/entity'); ?>">Manage</a></li>                
					<li><a href="<?php echo  $this->url->get('settings/newentity'); ?>">Nieuw</a></li>
					<li><a href="<?php echo  $this->url->get('settings/newentityjs'); ?>">Nieuw js</a></li>
					<li><a href="<?php echo  $this->url->get('settings/checklist'); ?>">Checklist</a></li>
					<li><a href="<?php echo  $this->url->get('settings/generatecontroller'); ?>">Generate controller</a></li>
				</ul>
        </li>
		<li class="panel">
            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#side-nav" href="#website-collapse">
				<i class="fa fa-table"></i><span class="name">Website</span>
			</a>
            <ul id="website-collapse" class="panel-collapse collapse">
                <li><a href="<?php echo  $this->url->get('settings/page'); ?>">pagina's</a></li>
                <li><a href="<?php echo  $this->url->get('settings/checklistsite'); ?>">checklist</a></li>
				<!--<li><a href="<?php echo  $this->url->get('settings/nieuwepagina'); ?>">Nieuw</a></li>-->
            </ul>
        </li>
		<li class="panel">
            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#side-nav" href="#permissions-collapse">
				<i class="fa fa-table"></i><span class="name">Permissies</span>
			</a>
            <ul id="permissions-collapse" class="panel-collapse collapse">
                <li><a href="<?php echo  $this->url->get('settings/permissions'); ?>">Overzicht</a></li>
            </ul>
        </li>
		<li class="panel">
            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#side-nav" href="#multilang-collapse">
				<i class="fa fa-table"></i><span class="name">Multilang</span>
			</a>
            <ul id="multilang-collapse" class="panel-collapse collapse">
                <li><a href="<?php echo  $this->url->get('settings/multilang'); ?>">Overzicht</a></li>
            </ul>
        </li>
		<li class="panel">
            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#side-nav" href="#email-collapse">
				<i class="fa fa-table"></i><span class="name">Email</span>
			</a>
            <ul id="email-collapse" class="panel-collapse collapse">
			    <li><a href="<?=$this->url->get('settings/client'); ?>">Client</a></li> 
                <li><a href="<?=$this->url->get('settings/emailtemplates'); ?>">Templates</a></li> 
                <li><a href="<?=$this->url->get('settings/standardmessages'); ?>">Standaard berichten</a></li>				
            </ul>
        </li>	*/?>	
		
		<?=$this->partial("layouts/menu");?> 
    </ul>
</nav>


<div id="general-content" class="">
    {{ content() }}
</div>




