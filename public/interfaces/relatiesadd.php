

/* SAVE THE CATEGORY RELATIONSHIPS START */

if(isset($post['relaties_beheerder']))
{
	$beheerders = $post['relaties_beheerder'];
	foreach($beheerders as $beheerder)
	{
		$acl = new Acl();
		$acl->id = $this->uuid();
		$acl->entity = 'category';
		$acl->userid = $beheerder;
		$acl->clearance = 900;
		$acl->entityid = $post['id'];
		if($acl->save())
		{	}
		else
		{
			foreach ($acl->getMessages() as $message)
			{
					echo 'ACL1:'.$message;
			}
		}		
	}
}

if(isset($post['relaties_leden']))
{
	$leden = $post['relaties_leden'];
	foreach($leden as $lid)
	{
		$acl = new Acl();
		$acl->id = $this->uuid();
		$acl->entity = 'category';
		$acl->userid = $lid;
		$acl->clearance = 400;
		$acl->entityid = $post['id'];
		if($acl->save())
		{	}
		else
		{
			foreach ($acl->getMessages() as $message)
			{
					echo 'ACL2:'.$message;
			}
		}		
	}
}


/* SAVE THE CATEGORY RELATIONSHIPS END */