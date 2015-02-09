<?=$this->partial("layouts/header");?> 
<nav id="sidebar" class="sidebar nav-collapse collapse" style="border-right:1px solid #ddd;">
    <ul id="side-nav" class="side-nav">
		<li>
            <a href="<?php echo  $this->url->get(); ?>"><i class="fa fa-home"></i> <span class="name">Dashboard</span></a>
        </li>
        <li class="panel">
            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#side-nav" href="#tables-collapse"><i class="fa fa-table"></i> <span class="name">Media</span></a>
            <ul id="tables-collapse" class="panel-collapse collapse">
                <li><a href="<?php echo  $this->url->get(); ?>media/">Overzicht</a></li>
                <li><a href="<?php echo  $this->url->get(); ?>media/new">Nieuw</a></li>
            </ul>
        </li>
        <li class="panel">
            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#side-nav" href="#thumb-collapse"><i class="fa fa-table"></i> <span class="name">Thumbs</span></a>
            <ul id="thumb-collapse" class="panel-collapse collapse">
                <li><a href="<?php echo  $this->url->get(); ?>thumb/">Overzicht</a></li>
                <li><a href="<?php echo  $this->url->get(); ?>thumb/new">Nieuw</a></li>
            </ul>
        </li>
	
	<?=$this->partial("layouts/menu");?> 
	</ul>
</nav>
<div id="general-content" class="">
	 {{ content() }}
</div>
