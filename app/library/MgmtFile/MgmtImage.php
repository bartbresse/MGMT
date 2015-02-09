<?php	
namespace MgmtFile;

class MgmtImage
{	
	public $thumbsizes;
	public $file;
	public $path;
	public $name;
	public $tree;
	public $type;
	
	public function __construct($name,$path = false)
	{	
            $this->tree = new MgmtFileTree();
            $folder = $this->tree->createFolder('../../uploads/'.date('Y/m').'/'.time().'/');
            if(!$path)
            {
                    $this->path = '../../uploads/'.date('Y/m').'/'.time().'/';	
            }
            else
            {
                    $this->path = $path;
            }
            $this->name = $name;
	}

	public function getPath()
	{
		return $this->path.$this->name;
	}
	
	public function generateThumbs($thumbsizes)
	{
            foreach($thumbsizes as $thumbsize)
            {
                $thumb = new MgmtThumb();
                $thumb->generate($this->path,$this->name,$thumbsize->x,$thumbsize->y);
            }
	}
	
	public function crop($x,$y,$w,$h)
	{
		$image = new \Phalcon\Image\Adapter\Imagick($this->path.$this->name);
		$this->type = $image->getType();
		//crop moet minder zijn als image, anders cropt de lib from center 
		$x = ($x - 2);
		$y = ($y - 2);
		$image->crop($w,$h,$x,$y);

		$path = $this->tree->createFolder('../../uploads/'.date('Y/m').'/'.time().'/'.intval($w).'/'.intval($h).'/');
		if(!$image->save($path.$this->name)) 
		{ die('CROP FAILED'); }
		else
		{ return $path.$this->name; }
	}
	
	public function store($file)
	{
		$this->file = $file;
		$this->type = $file->getRealType();
		
		$this->file->moveTo($this->path.$this->name);
	}
	
	public function update()
	{
	
	}
}
?>