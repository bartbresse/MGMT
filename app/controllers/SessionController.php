<?php

use Phalcon\Tag as Tag;

class SessionController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Tag::setTitle('Sign Up/Sign In');
        parent::initialize();
    }

    public function indexAction()
    {
      
    }
	public function loginAction()
	{
		$user = User::findFirst();
		print_r($user);
	}

	public function verification()
	{

	}
	
	public function emailAction()
	{
		
	}
	
	public function forgotAction() 
	{
		
	}

    public function registerAction()
    {
		$this->view->disable();
        $request = $this->request;
        if ($request->isPost()) {

            $name = $request->getPost('name', array('string', 'striptags'));
            $username = $request->getPost('username', 'alphanum');
            $email = $request->getPost('email', 'email');
            $password = $request->getPost('password');
            $repeatPassword = $this->request->getPost('repeatPassword');

            if ($password != $repeatPassword) {
                $this->flash->error('Passwords are diferent');
                return false;
            }

            $user = new Users();
            $user->username = $username;
            $user->password = sha1($password);
            $user->name = $name;
            $user->email = $email;
            $user->created_at = new Phalcon\Db\RawValue('now()');
            $user->active = 'Y';
            if ($user->save() == false) 
			{
                foreach ($user->getMessages() as $message) 
				{
                    $this->flash->error((string) $message);
                }
            } 
			else 
			{
                Tag::setDefault('email', '');
                Tag::setDefault('password', '');
                $this->flash->success('Thanks for sign-up, please log-in to start generating invoices');
               // return $this->forward('session/index');
            }
        }
    }

    /**
     * Register authenticated user into session data
     *
     * @param Users $user
     */
    private function _registerSession($user)
    {
        $this->session->set('auth', array(
            'id' => $user->id,
			'clearance' => $user->clearance 
        ));
    }

	
    /**
     * This actions receive the input from the login form
     *
     */
    public function startAction()
    {
		$this->view->disable();
		$status = array('status' => 'no','messages' => array());
		if ($this->request->isPost()) 
		{
			$email = $this->request->getPost('email','email');
			$password = $this->request->getPost('password',array('string', 'striptags'));
			$user = User::findFirst("email='".$email."'"); 		
			
			$acl = Acl::find('userid = "'.$user->id.'" AND clearance > 200 ');	

			if(count($acl) > 0 || $user->clearance > 300)
			{
				if ($user) 
				{		
					if ($this->security->checkHash($password, $user->password) && $user->status == 1)
					{	
						$this->_registerSession($user);	
						$status = array('status' => 'ok');
					}
					else
					{
						$message['email'] = 'U heeft niet het juiste password ingevoerd.';
						array_push($status['messages'],$message);
					}
				}
			}
			else
			{
				$message['email'] = 'U heeft geen permissies';
				array_push($status['messages'],$message);
			}
		}
		echo json_encode($status);
	}
	
	public function googleAction()
	{
	
		$client_id = '402112839929-18up9acfmaauj2u7v1e06ihomu5rgfhd.apps.googleusercontent.com';
		$client_secret = 'ifumpfN9RV1Bd87-7RdkO0Gz';
		$redirect_uri = 'http://www.dev12-hetworks.nl/MGMTx/backend/session/google';	

		$client = new Google_Client();
		$client->setClientId($client_id);
		$client->setClientSecret($client_secret);
		$client->setRedirectUri($redirect_uri);
		$client->setScopes('email');
		
		//$google_oauthV2 = new Google_Oauth2Service($client);
		//$google_oauthV2 = new Google_Userinfo($client);
	
		/************************************************
		  If we're logging out we just need to clear our
		  local access token in this case
		 ************************************************/
		if (isset($_REQUEST['logout'])) {
		  unset($_SESSION['access_token']);
		}

		/************************************************
		  If we have a code back from the OAuth 2.0 flow,
		  we need to exchange that with the authenticate()
		  function. We store the resultant access token
		  bundle in the session, and redirect to ourself.
		 ************************************************/
		if (isset($_GET['code'])) {
		  $client->authenticate($_GET['code']);
		  $_SESSION['access_token'] = $client->getAccessToken();
		 // $session->set('google',array('access_token' => $client->getAccessToken()));
		  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
		  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
		}

		/************************************************
		  If we have an access token, we can make
		  requests, else we generate an authentication URL.
		 ************************************************/

		if (isset($_SESSION['access_token']) && $_SESSION['access_token']) 
		{ $client->setAccessToken($_SESSION['access_token']); } 
		else 
		{ $authUrl = $client->createAuthUrl(); }
		
		/************************************************
		  If we're signed in we can go ahead and retrieve
		  the ID token, which is part of the bundle of
		  data that is exchange in the authenticate step
		  - we only need to do a network call if we have
		  to retrieve the Google certificate to verify it,
		  and that can be cached.
		 ************************************************/
		 
		if($client->getAccessToken())
		{
			$_SESSION['access_token'] = $client->getAccessToken();
			$token_data = $client->verifyIdToken()->getAttributes();
				
			//For logged in user, get details from google using access token
		/*	$user                 = $google_oauthV2->userinfo->get();
			$user_id              = $user['id'];
			$user_name            = filter_var($user['name'], FILTER_SANITIZE_SPECIAL_CHARS);
			$email                = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
			$profile_url          = filter_var($user['link'], FILTER_VALIDATE_URL);
			$profile_image_url    = filter_var($user['picture'], FILTER_VALIDATE_URL);
			$personMarkup         = "$email<div><img src='$profile_image_url?sz=50'></div>";*/
			
			$_SESSION['token']    = $client->getAccessToken();
		}

		if ($client_id == '<YOUR_CLIENT_ID>' || $client_secret == '<YOUR_CLIENT_SECRET>' || $redirect_uri == '<YOUR_REDIRECT_URI>')
		{
			echo missingClientSecretsWarning();
		}
		
		?>
		<div class="box">
		  <div class="request">
			<?php if (isset($authUrl)): ?>
			  <a class='login' href='<?php echo $authUrl; ?>'>Connect Me!</a>
			<?php else: ?>
			  <a class='logout' href='?logout'>Logout</a>
			<?php endif ?>
		  </div>

		  <?php if (isset($token_data)): ?>
			<div class="data">
			  <?php var_dump($token_data); ?>
			</div>
		  <?php endif ?>
		</div>
		<?php
	}

    /**
     * Finishes the active session redirecting to the index
     *
     * @return unknown
     */
    public function endAction()
    {
        $this->session->remove('auth');
        $this->flash->success('Goodbye!');
        return $this->forward('index/index');
    }


}
