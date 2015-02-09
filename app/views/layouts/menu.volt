	<?	
			$auth = $this->session->get('auth');
			foreach($tables as $table)
			{
				if($table->entityseperator == 1)
				{
					?>
					<li class="panel">
                                            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#side-nav" href="#<?=$table->slug;?>-collapse">
                                                <span class="name"><?=ucfirst($table->alias);?></span>
                                            </a>
					</li> 
					<?
				}
				else
				{
					//	if($table->clearance < $auth['clearance'])
					//	{  
					?> 
					<li class="panel">
						<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#side-nav" href="#<?=$table->slug;?>-collapse">
                                                    <i class="fa fa-table"></i> 
                                                    <span class="name"><?=  ucfirst($table->alias);?></span>
						</a>
						<ul id="<?=$table->slug;?>-collapse" class="panel-collapse collapse">
                                                    <li><?php echo $this->tag->linkTo(array($table->slug."/index", "Overzicht")); ?></li>
                                                    <li><?php echo $this->tag->linkTo(array($table->slug."/new", $table->newtext)); ?></li>
						</ul>
					</li> 
					<?
					//	}
				}
			}
		?>