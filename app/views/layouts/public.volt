
<?=$this->partial('layouts/header');?>


<nav id="sidebar" class="sidebar nav-collapse collapse" style="border-right:1px solid #ddd;">
    <ul id="side-nav" class="side-nav">
		<li>
            <a href="<?php echo  $this->url->get(); ?>"><i class="fa fa-home"></i> <span class="name">Dashboard</span></a>
        </li>
        <li class="panel">
            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#side-nav" href="#tables-collapse"><i class="fa fa-table"></i> <span class="name">Gebruikers</span></a>
            <ul id="tables-collapse" class="panel-collapse collapse">
                <li><a href="<?php echo  $this->url->get(); ?>user/">Overzicht</a></li>
                <li><a href="<?php echo  $this->url->get(); ?>user/new">Nieuw</a></li>
            </ul>
        </li>
		<?=$this->partial('layouts/menu');?>
                </ul>
        </li>
    </ul>
</nav>

<div id="general-content" class="">
	 {{ content() }}
</div>
