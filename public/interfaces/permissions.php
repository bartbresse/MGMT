
	/*
		PERMISSIONS MODULE STARTS HERE
	*/

	if(!isset($post)){ $post = $this->request->getPost(); } 
	if(!isset($form)){ $form = new Form(); }
	
	$rechten = array(
			'1000' => 'Hetworker',
			'900' => 'Beheerder',
			'500' => 'Editor',	
			'400' => 'Blogger',	
			'300' => 'Gebruiker',
			'10' => 'Bezoeker',	
			);
	
	$get = $this->session->get('auth');
	$keys = array_keys($rechten);
	foreach($keys as $key)
	{	if($get['clearance'] < $key)
		{
			array_push($allow,$rechten[$key]);	
		}
	}
	
	$form->add(new Text('permission-rechten',$allow,array('value' => '','id' => 'permission-rechten','class' => 'form-control')));
	
	//GET ACL RULES FOR THIS USER
	$acl = Acl::find('userid = "'.$id.'" AND entity = "category"');
	$rules = array();
	foreach($acl as $rule)
	{  array_push($rules,$rule->entityid); }
			
	$form->add(new Select('permission-beheerfunctie',Category::find(),array('using' => array('id','titel'),'value' => $rules,'multiple' => 'multiple','id' => 'permission-beheerfunctie','class' => 'form-control permission-rechten-permission-beheerfunctie')));		
		
	/*
		PERMISSIONS MODULE ENDS HERE
	*/
