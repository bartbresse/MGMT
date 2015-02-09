<?php

class Sitemapcontroller extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		
	}

	public $controllers;

	private function getsitemaps($entities)
	{
		$controller = $this->controllers;
		$str =  '';
		foreach($controllers as $controller)
		{
			foreach($controller->Controllerview as $view)
			{
				if($view->struct == 1 && count($view->Entity) > 0)
				{
					//push to a new structure sitemap
					$cc=0;
					foreach($view->Entity as $entity)
					{						
						if($cc == 0)
						{
							$str .= '
								public function '.$entity.'Action() 
								{
									$response = $this->responseheader();
									$sitemap = $this->sitemapheader();
									$urlset = $sitemap->createElement(\'urlset\');
									$urlset->setAttribute(\'xmlns\', \'http://www.sitemaps.org/schemas/sitemap/0.9\');
									
									$results = '.$ucfirst($entity).'::find();
									foreach ($results as $result)
									{
										if(strlen($result->slug) > 3)
										{
											$url = $sitemap->createElement(\'url\');
											$url->appendChild($sitemap->createElement(\'loc\', \'http://www.beautyspotten.nl/\'.$result->geturl().$result->slug.\'/\'));
											$url->appendChild($sitemap->createElement(\'priority\', \'0.4\'));				
											$url->appendChild($sitemap->createElement(\'changefreq\', \'weekly\'));
											$lastmod = isset($result->lastmod) ? date(\'Y-m-d\', strtotime($result->lastmod)) : $this->lastmod;
											$url->appendChild($sitemap->createElement(\'lastmod\', $lastmod));
											$urlset->appendChild($url);
										}
									}
									$sitemap->appendChild($urlset);
									$response->setContent($sitemap->saveXML());
									return $response;	
								}
								';
						}
						$cc++;
					}	
				}
			}
		}
		return $str;
	}
	
	public function getsitemapentities()
	{
		$controller = $this->controllers;
		$str =  '$entities = array(';
		foreach($controllers as $controller)
		{
			foreach($controller->Controllerview as $view)
			{
				if($view->struct == 1 && count($view->Entity) > 0)
				{
					$cc=0;
					foreach($view->Entity as $entity)
					{						
						if($cc == 0)
						{
							$entity = Entity::findFirst('title = "'.$entity.'"');
							if($cc2 > 0){ $str .= ',';}
							$str .= '"'.$entity->id.'"';
						}
						$cc++;
					}
				}
			}
		}
		$str .= ');';
		return $str;
	}

	public function tofile()
	{
		//#menuitems#
		$contents =	file_get_contents(BASEURL.'backend/templates/sitemapcontroller.rsi');
		$a = array('#sitemapentities#','#url#','#sitemaps#');
		$b = array($this->getsitemapentities(),$this->geturl(),$this->getsitemaps());
		$model = str_replace($a,$b,$contents);
		$file = '../app/views/layouts/main.volt';
		if(strlen($model) > 10){ 
			file_put_contents($file,$model); 
			//anders geen groep edit /np++ edit mogelijk wegens te weinig rechten
			chmod($file,0777); 
		}else{ return false; }
	}  

    public function columnMap(){ return array(); }
}
