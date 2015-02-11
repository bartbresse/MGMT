<?=$this->partial("layouts/header");?> 
<nav id="sidebar" class="sidebar nav-collapse collapse" style="border-right:1px solid #ddd;">
    <ul id="side-nav" class="side-nav">
        <li>
            <a href="<?php echo  $this->url->get(); ?>"><i class="fa fa-home"></i> <span class="name">Dashboard</span></a>
        </li>
        <li class="panel">
            <a data-parent="#side-nav" href="<?php echo  $this->url->get('mail/inbox'); ?>"><i class="fa fa-table"></i> <span class="name">Inbox</span></a>
        </li>
        <li class="panel">
            <a data-parent="#side-nav" href="<?php echo  $this->url->get('mail/spam'); ?>"><i class="fa fa-table"></i> <span class="name">Spam</span></a>
        </li>
        <li class="panel">
            <a data-parent="#side-nav" href="<?php echo  $this->url->get('mail/concept'); ?>"><i class="fa fa-table"></i> <span class="name">Concept</span></a>
        </li>
        <li class="panel">
            <a data-parent="#side-nav" href="<?php echo  $this->url->get('mail/sent'); ?>"><i class="fa fa-table"></i> <span class="name">Sent</span></a>
        </li>
        <li class="panel">
            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#side-nav" href="#tables-collapse"><i class="fa fa-table"></i> <span class="name">Email</span></a>
            <ul id="tables-collapse" class="panel-collapse collapse">
                <li><a href="<?php echo  $this->url->get(); ?>mail/">Overzicht</a></li>
                <li><a href="<?php echo  $this->url->get(); ?>mail/new">Nieuw</a></li>
            </ul>
        </li>
        <?=$this->partial("layouts/menu");?> 
    </ul>
</nav>
<div id="general-content" class="">
	 {{ content() }}
</div>
