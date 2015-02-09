

	/*
		START CONTROLLER EMAIL INTERFACE
	*/
	if($new)
	{
		$emailmessagex = Emailmessage::findFirst('id = "'.$post['id'].'"'); 
		if(!isset($emailmessagex->id))
		{ 		 
			$emailmessage = new Emailmessage();
			if(strlen($post['id']) < 5){ $emailmessage->id = $this->uuid(); }else{ $emailmessage->id = $post['id']; }
		}
		else
		{
			$emailmessage = $emailmessagex;
		}
		
		//save standard columns 
		foreach ($emailmessage as $key => $value)
		{
			if(isset($post['email-'.$key]) && $key != 'id' && $key != 'fileid')
			{ 
				$emailmessage->$key = $post['email-'.$key]; 
			}				
		}
		
		$emailmessage->to = serialize($post['email-to']);
		
		$cc=0;	
		if(!isset($post['id']) || strlen($post['id']) < 10)
		{
			$messages = $emailmessage->validation();
			for($i=0;$i<count($messages);$i++)
			{
				$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
				array_push($status['messages'],$message);
				$cc++;
			}
		}	
		
		$emailmessage->userid = $this->user['id'];

		//EMAIL CALLS SEND HERE NOT SAVE
		if($emailmessage->send($post['email-to']) && $cc==0)
		{
			if($emailmessage->save())
			{
				$status['status'] = 'ok';	
			}
			else
			{
				foreach ($emailmessage->getMessages() as $message)
				{
					echo $message;
				} 
			}
		}
	}
	/*
		END CONTROLLER EMAIL INTERFACE
	*/