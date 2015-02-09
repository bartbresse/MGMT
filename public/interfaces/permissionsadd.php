

	/*
		PERMISSION ADD START
	*/
	
	if(!isset($post)){ $post = $this->request->getPost(); } 

	$acl = Acl::find('userid = "'.$user->id.'" AND entity = "category"');
	foreach($acl as $a)
	{
		$a->delete();
	}
	
	$beheerfuncties = $post['permission-beheerfunctie'];
	foreach($beheerfuncties as $functie)
	{
		$acl = new Acl();
		$acl->entity = 'category';
		//$acl->entityid = ;
	}
	
	if(isset($post['permission-rechten']))
	{
		$user->clearance = $post['permission-rechten'];
	}
	
	/*
		PERMISSION ADD END
	*/
	