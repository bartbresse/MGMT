<?php

/* 
 * Copyright (c) 2015, Bart Bresse ,SONDER All rights reserved.
 * This software is licensed under the NEW BSD LICENSE
 * 
 */
namespace MgmtFile;

class MgmtCSV
{
    public function __construct()
    {
        
    }
    
    public function generate($entities,$columns,$entityname)
    {
        $string = '';
        //table headers
        $cc=0;
        $keys = array();
        $row = array();		

        $row[0] = array();
        foreach($entities->items[0] as $key => $value)
        {
            if(in_array($key,$columns))
            { 
                array_push($keys,$key);
                array_push($row[0],$key);
            }
        }

        $string .= '\n';
        $cc=1;

        foreach($entities->items as $entity)
        {
                $row[$cc] = array();
                foreach($keys as $key)
                {				
                        array_push($row[$cc],$entity->$key);					
                }
                $cc++;
        }

        $filename = '../../uploads/exports/'.$entityname.date('Y-m-d').'.csv';			
        if($f = fopen($filename, 'w'))
        {
                fwrite($f,$string);
                foreach ($row as $fields) 
                {
                        fputcsv($f,$fields,';');
                }
                fclose($f);
        }
        return 'http://'.$_SERVER['HTTP_HOST'].'/MGMTx/uploads/exports/'.$entityname.date('Y-m-d').'.csv';
    }
}

