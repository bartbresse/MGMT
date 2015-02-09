<?php	
namespace MgmtFile;

use \Phalcon\Mvc\User\Component;
use \Phalcon\Image\Adapter\Imagick;


class MgmtThumb extends \Phalcon\Mvc\User\Component
{	
	public $tree;
	
	public function __construct()
	{
	}
	
	public function set($basepath)
	{
		return $path.$name;
	}
	
	public function generate($path,$name,$x,$y)
	{
		$path2 = $path.$x.'/'.$y.'/';
		$name2 = $name;

		if(!is_file($path2.$name2))
		{
			$tree = new MgmtFileTree();
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
			
			$image = new \Phalcon\Image\Adapter\Imagick($path2.$name2);
			$image->resize(intval($resize),intval($resize));
			$image->save($path2.$name2);
			
			//offset
			$offsetx = ($image->getWidth() - $x) / 2;	
			if($offsetx < 0){ $offset = 0; }
			
			$offsety = ($image->getheight() - $y) / 2;	
			if($offsety < 0){ $offset = 0; }
			
			$image2 = new \Phalcon\Image\Adapter\Imagick($path2.$name2);
			$image2->crop($x,$y,$offsetx,0);
			$image2->save($path2.$name2);
		}
	}
}
?>