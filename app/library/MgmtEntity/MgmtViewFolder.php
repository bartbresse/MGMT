<?php
namespace MgmtEntity;

class MgmtViewFolder extends MgmtFile
{
	public function toFile()
	{
		$foldername = strtolower(str_replace('_','',ucfirst($this->entity->name)));		
		if (!file_exists('../app/views/'.$foldername)) 
		{
			mkdir('../app/views/'.$foldername, 0777, true);
		}
	}
	
	public function delete()
	{
            $foldername = strtolower(str_replace('_','',$this->entity->name));		
            if(file_exists('../app/views/'.$foldername)) 
            {
                /**
                 * delete all files in folder
                 */
                $files = glob('../app/views/'.$foldername.'/*'); // get all file names
                if(count($files) > 0 && is_array($files))
                {
                    foreach($files as $file){ // iterate files
                      if(is_file($file))
                        unlink($file); // delete file
                    }    
                }
                
                rmdir('../app/views/'.$foldername);
            }
	}
}


?>