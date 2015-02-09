
			/*
				RELATIE MODULE START
			*/
			if(!isset($form))
			{
				$form = new Form();
			}

			echo 'entityid = "'.$category->id.'" AND clearance = 900';

			$beheerders = Acl::find('entityid = "'.$category->id.'" AND clearance = 900');
			$beheerderrules = array();
			foreach($beheerders as $beheerder)
			{
				array_push($beheerderrules,$beheerder->userid);
			}
			
			$leden = Acl::find('entityid = "'.$category->id.'" AND clearance = 400');
			$ledenrules = array();
			foreach($leden as $lid)
			{
				array_push($ledenrules,$lid->userid);
			}

			//print_r($ledenrules);

			$form->add(new Select('relaties_beheerder',User::find(), array('using' => array('id', 'email'),'value' => $beheerderrules,'id' => 'relaties_beheerder','class' => 'form-control relaties-beheerder-multiple-select','data-placeholder' => 'Kies een beheerder','multiple' => 'multiple')));
			$form->add(new Select('relaties_leden',User::find(), array('using' => array('id', 'email'),'value' => $ledenrules,'id' => 'relaties_leden','class' => 'form-control relaties-leden-multiple-select','data-placeholder' => 'Kies hier uw leden.','multiple' => 'multiple')));

			/*
				RELATIE MODULE EINDE
			*/
