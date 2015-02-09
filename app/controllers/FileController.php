<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Select;


class FileController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Phalcon\Tag::setTitle('Files');
        parent::initialize();
    }

    public function indexAction()
    {
		
    }
    
    public function wysiwygAction()
    {

    }

    /*MULTIPLE FILE UPLOAD*/
    public function multipleuploadAction()
    {
        $this->view->disable();
        $filearray = array();
        $status = array('status' => 'false');	
        if ($this->request->hasFiles() == true) 
        {
                foreach ($this->request->getUploadedFiles() as $file) 
                {
                    $mgmtfile = new MgmtFile\MgmtImage($file->getName());
                    if(!in_array($mgmtfile->getPath(),$filearray))
                    {
                        $dbfile = new File();
                        $dbfile->id = $this->uuid();	
                        $dbfile->path = $mgmtfile->getPath();
                        $dbfile->type = $file->getRealType();
                        $dbfile->creationdate = new Phalcon\Db\RawValue('now()');
                        $dbfile->lastedit =  new Phalcon\Db\RawValue('now()'); 
                        if($dbfile->save())
                        {	
                                $status = array('status' => 'ok','id' => $dbfile->id,'path' => $mgmtfile->getPath(),'type' => $file->getRealType());

                                $mgmtfile->store($file);
                                $mgmtfile->generateThumbs(MgmtThumb::find());
                        }
                        else
                        {
                                foreach ($dbfile->getMessages() as $message)
                                {
                                        echo $message;
                                }
                        }

                        /*
                        $post = $this->request->getPost();
                        $timestamp = time();
                        $id = $this->uuid();

                        $fn = explode('.',$file->getName());
                        $f = substr(preg_replace("/[^a-zA-Z]/", "",$fn[0]),0,15);
                        $fnr = array_reverse($fn);
                        $name = $f.'.'.$fnr[0];

                        $path = '../../uploads/'. $timestamp .$name;	
                        $type = $file->getRealType();
                        $fileid = $this->uuid();

                        $dbf = new File();
                        $dbf->id = $fileid;	
                        $dbf->path = $path;
                        $dbf->entityid = $post['entityid'];
                        $dbf->type = 'file';
                        $dbf->creationdate = new Phalcon\Db\RawValue('now()');
                        $dbf->lastedit =  new Phalcon\Db\RawValue('now()'); 

                        $file->moveTo('../../uploads/' . $timestamp . $name);
                        if(isset($_POST['thumb']) && $_POST['thumb'] == 'true')
                        {
                                $dbf->thumb = '../../uploads/thumb/'.$path;
                                $this->createthumb($timestamp . $name);
                        }

                        if($dbf->save())
                        { }
                        else
                        {
                                foreach ($dbf->getMessages() as $message)
                                {
                                        //echo $message;
                                }
                        }

                        $path = '../uploads/' . $timestamp . $name;
                        $fid = array('path' => $path,'type' => $type,'id' => $fileid);
                        echo json_encode($fid);
                        */
                        array_push($filearray,$mgmtfile->getPath());
                    }
                }
            }	
            echo json_encode($status);
	}
	
	public function deletefileAction()
	{
		$this->view->disable();
		$status = array('status' => 'false');	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
		
			if(strlen($post['id']) > 5)
			{
				$file = File::findFirst('id = "'.$post['id'].'"');
				if($file->delete())
				{
                                    $status['status'] = 'ok';
				}
			}
		}
		echo json_encode($status);			
	}
	
	/*END MULTIPLE FILE UPLOAD*/
	
    private function resizeimage($path,$name,$height,$width)
    {
		$image = new Phalcon\Image\Adapter\GD($path);
		
		if($image->getWidth() > ($width-100))
		{
			$image->resize(($width-100),($width-100));
			if(!$image->save()){ return true; }
		
			
		}
		
		if($image->getHeight() > ($height-200))
		{
			$image->resize(($height-200),($height-200));
			if(!$image->save()){ return true; }
		
			
		}
		
		$h = $image->getHeight();
		$w = $image->getWidth();
		return array('h' => $h,'w' => $w);	
    }
    

    public function deleteAction()
    {
        $this->deletepictureAction();
    }
    
    public function deletepictureAction()
    {
            $this->view->disable();
            $status = array('status' => 'false');	
            if ($this->request->isPost()) 
            {
                    $post = $this->request->getPost();
                    if(strlen($post['id']) > 5)
                    {
                            $file = File::findFirst('id = "'.$post['id'].'"');
                            if($file->delete())
                            {
                                    $status['status'] = 'ok';
                            }
                    }
            }
            echo json_encode($status);
    }
	
    public function addAction()
    {
		$this->view->disable();
		$status = array('status' => 'false');
		
		// Check if the user has uploaded files
        if ($this->request->hasFiles() == true) 
		{
            // Print the real file names and sizes
        foreach ($this->request->getUploadedFiles() as $file) 
        {
                $mgmtfile = new MgmtFile\MgmtImage($file->getName());

                $dbfile = new File();
                $dbfile->id = $this->uuid();	
                $dbfile->path = $mgmtfile->getPath();
                $dbfile->type = $file->getRealType();
                $dbfile->creationdate = new Phalcon\Db\RawValue('now()');
                $dbfile->lastedit =  new Phalcon\Db\RawValue('now()'); 
                if($dbfile->save())
                {	
                        $status = array('status' => 'ok','id' => $dbfile->id,'path' => $mgmtfile->getPath(),'type' => $file->getRealType());

                        $mgmtfile->store($file);
                        $mgmtfile->generateThumbs(MgmtThumb::find());
                }
                else
                {
                        foreach ($dbfile->getMessages() as $message)
                        {
                                echo $message;
                        }
                }

                /*
                $post = $this->request->getPost();
                $timestamp = time();
                $id = $this->uuid();

                $fn = explode('.',$file->getName());
                $f = substr (preg_replace("/[^a-zA-Z]/", "",$fn[0]),0,15);

                $fnr = array_reverse($fn);

                $name = $f.'.'.$fnr[0];

                $path = '../../uploads/'. $timestamp .$name;	
                $type = $file->getRealType();

                $dbf = new File();
                $dbf->id = $id;	
                $dbf->path = $path;

                $dbf->type = $type;

                $dbf->creationdate = new Phalcon\Db\RawValue('now()');
                $dbf->lastedit =  new Phalcon\Db\RawValue('now()'); 

                $file->moveTo('../../uploads/' . $timestamp . $name);


                if(isset($_POST['thumb']) && $_POST['thumb'] == 'true')
                {	
                        $dbf->thumb = '../../uploads/thumb/'.$path;
                        $this->createthumb($timestamp . $name);
                }

                if($_POST['resize'] == 'false')
                {
                        $dimensions = array();
                        $image = new Phalcon\Image\Adapter\GD('../../uploads/' . $timestamp . $name);
                        $dimensions['h'] = $image->getHeight();
                        $dimensions['w'] = $image->getWidth();
                        if(!$image->save()){ die('image save failed'); }
                }
                else
                {
                        $dimensions = $this->resizeimage('../../uploads/' . $timestamp . $name ,$timestamp . $name,$post['screenheight'],$post['screenwidth']);		
                }

                if($dbf->save())
                {	}
                else
                {
                        foreach ($dbf->getMessages() as $message)
                        {
                                echo $message;
                        }
                }

                $path = '../uploads/' . $timestamp . $name;
                $fid = array('id' => $id,'path' => $path,'type' => $type,'h' => $dimensions['h'],'w' => $dimensions['w']);
                echo json_encode($fid);
                */

            }		
            echo json_encode($status);
        }	
    }
	
	private function createthumb($path,$name,$x,$y)
	{
		$path2 = $path.$x.'/'.$y.'/';
		$name2 = $name;
		
		$tree = new MgmtFile\MgmtFileTree();
		$tree->createFolder($path2);
		
		copy($path.$name,$path2.$name2);
		
		if($y == $x)
		{
			$nx = $x;
			$resize = $nx;
		}
		
		if($y > $x)
		{
			$nx = ($y*$y)/$x;
			$resize = $nx;
		}
		if($x > $y)
		{
			$ny = ($x*$x)/$y;
			$resize = $ny;
		}
		
		$image = new Phalcon\Image\Adapter\Imagick($path2.$name2);
		$image->resize(intval($resize),intval($resize));
		$image->save($path2.$name2);
		
		//offset
		$offsetx = ($image->getWidth() - $x) / 2;	
		if($offsetx < 0){ $offset = 0; }
		
		$offsety = ($image->getheight() - $y) / 2;	
		if($offsety < 0){ $offset = 0; }
		
		$image2 = new Phalcon\Image\Adapter\Imagick($path2.$name2);
		$image2->crop(255,400,$offsetx,0);
		$image2->save($path2.$name2);
		
	
	
		/*	if($x > $y)
		{
			$largestval = $x;
			$offsetx = ($x - $y) / 2;
			$offsety = 0;
		}
		else
		{
			$largestval = $y;
			$offsety = ($y - $x) / 2;
			$offsetx = 0;
		}*/
		
		//$image->resize(intval($largestval),intval($largestval));
		//$image->crop(intval($x),intval($y),intval($offsetx),intval($offsety));
		
		
		/*
		//thumb size 200 x 200
		copy('../../uploads/'.$path,'../../uploads/thumb/'.$path);	
		$image = new Phalcon\Image\Adapter\Imagick('../../uploads/thumb/'.$path);
		$image->resize(200, 200);
		$image->save('../../uploads/thumb/'.$path);*/
	}

	public function cropAction()
	{
		$this->view->disable();
		$status = $this->status;	
		if($this->request->isPost()) 
		{
			$post = $this->request->getPost();
			$filename = array_reverse(explode('/',$post['path']));
			$path = array_reverse(explode('../',$post['path']));
			
			$path = explode($filename[0],$path[0]);
			$mgmtfile = new MgmtFile\MgmtImage($filename[0],'../../'.$path[0]);
				
			$dbfile = new File();
			$dbfile->id = $this->uuid();	
			$dbfile->path = $mgmtfile->getPath();
			$dbfile->type = 'image/jpeg'; 
			$dbfile->creationdate = new Phalcon\Db\RawValue('now()');
			$dbfile->lastedit =  new Phalcon\Db\RawValue('now()'); 
			
			if($dbfile->save())
			{	
				$path = $mgmtfile->crop($post['x'],$post['y'],$post['w'],$post['h']);
				$status = array('status' => 'ok','id' => $dbfile->id,'path' => $path,'type' => $mgmtfile->type);
			}
			else
			{
				foreach ($dbfile->getMessages() as $message)
				{
					echo $message;
				}
			}
		}
		echo json_encode($status);
	}
	
    public function addcropAction()
    {
		$this->view->disable();
		
		$status = array('status' => 'false');	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();

			$w = $post['w'.$post['num']];
			$h = $post['h'.$post['num']];
			$x = $post['x'.$post['num']];
			$y = $post['y'.$post['num']];
	
		//	$image = new Phalcon\Image\Adapter\GD($post['path']);	
		//	$image->crop($w,$h,$x,$y);
	
			$image = new Phalcon\Image\Adapter\Imagick('../'.$post['path']);
			//crop moet minder zijn als image, anders cropt de lib from center 
			$x = ($x - 2);
			$y = ($y - 2);
			
			//echo 'x'.$x.',y:'.$y.',h'.$h.',w:'.$w;
			
			$image->crop($w,$h,$x,$y);
			if($image->save()) 
			{
				$status['w'] = $w;
				$status['h'] = $h;
				$status['x'] = $x;
				$status['y'] = $y;	
				$status['status'] = 'ok';
				$status['path'] = $post['path'];		
			}
			
			if(isset($_POST['thumb']) && $_POST['thumb'] == 'true')
			{
				$path = str_replace('../uploads/','../uploads/thumb/',$post['path']);
				$image = new Phalcon\Image\Adapter\Imagick($path);
				//crop moet minder zijn als image, anders cropt de lib from center 
				$x = ($x - 2);
				$y = ($y - 2);
				$image->crop($w,$h,$x,$y);
				$image->resize(200, 200);
				
					
				$this->createthumb($timestamp . $name);
				
				if($image->save()) 
				{ $status['status'] = 'ok';	}
			}
		}
		echo json_encode($status);
    }


    public function uploadAction()
    {
	
    }
    public function uploadedAction()
    {
	
    }	

    public function newAction()
    {
	
    }
	
	public function removeimageAction()
	{
		$this->view->disable();
		$status = array('status' => 'false');	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
			
			$id = $post['id'];
			
			$controller = ucfirst($post['controller']);
				
			$entity = $controller::findFirst('fileid = "'.$id.'"');
			
			if($entity->delete())
			{
				//echo $id;
				$image = File::findFirst('id = "'.$id.'"');
				if(unlink($image->path))
				{
					if($image->delete())
					{
						$status['status'] = 'ok';
					}
				}
			}
		}
		echo json_encode($status);	
	}
}

?>

