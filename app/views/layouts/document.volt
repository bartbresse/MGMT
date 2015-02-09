<?=$this->partial("layouts/header");?> 
<nav id="sidebar" class="sidebar nav-collapse collapse" style="border-right:1px solid #ddd;">
    <ul id="side-nav" class="side-nav">
        <li>
            <a href="<?php echo  $this->url->get(); ?>"><i class="fa fa-home"></i> <span class="name">Dashboard</span></a>
        </li>
	<?=$this->partial("layouts/menu");?> 
    </ul>
</nav>
<div id="general-content" class="">
    {{ content() }}
</div>
