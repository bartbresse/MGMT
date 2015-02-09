<?php	
namespace MgmtFile;

/**
 *  Wrapper handles secure ftp traffic to update the system with a filezilla like messages
 */
class MgmtFTP
{
    private $connection;
    private $status = false;
    private $trace = array();
    public  $isconnected = false;
    public  $issuccesfull = false;

    public function __construct($host,$user,$password,$port = 21)
    {
        $this->connect($host,$user,$password,$port);
    }
    

    /**
     * 
     * @param type $host host url 
     * @param type $user
     * @param type $password
     */
    private function connect($host,$user,$password,$port)
    {
        // set up basic connection
        $this->connection = ftp_connect($host); 

        // login with username and password
        $result = ftp_login($this->connection,$user,$password); 
        
        if ((!$this->connection) || (!$result)) 
        { 
            $this->setStatus("FTP connection has failed! Attempted to connect to $host for user $user");
        } 
        else 
        {
            $this->isconnected = true;
            $this->setStatus("Connected to $host, for user $user");
        }
        
         ftp_pasv($this->connection, false);
    }
    
    /**
     * 
     * @return Mgmt ftp status
     */
    public function getStatus() 
    {
        return $this->status;
    }
    
    /**
     * sets status and add status to $trace
     * 
     * @param type $status
     */
    protected function setStatus($status) 
    {
        $this->status = $status;
        array_push($this->trace,$status);
    }
    
    /**
     * stores file on host system
     * 
     * @param $path path where the file needs to go.
     * @param $file encoded file string
     */
    public function putFile($path,$file)
    {
        // upload the file
                 
        $upload = ftp_put($this->connection, $path, $file, FTP_ASCII);
        
           // if (ftp_put($conn_id, $remote_file, $file, FTP_ASCII))
        
        if(!$upload)
        {
            $this->setStatus("File transfer failed: $path!");
        }
        else
        {
            $this->setStatus("File transfer successful, transferred $path");
        }
    }

    /**
     * 
     * @param type $directory
     * @return boolean
     */
    public function makeDir($directory)
    {
        // *** If creating a directory is successful...
        if (ftp_mkdir($this->connection, $directory)) {

            $this->setStatus('Directory "' . $directory . '" created successfully');
            return true;

        } else {

            // *** ...Else, FAIL.
            $this->setStatus('Failed creating directory "' . $directory . '"');
            return false;
        }
    }
    
    /**
     * an open ftp connection ALWAYS needs to be closed after al transaction. 
     */
    public function close()
    {
        ftp_close($this->connection);
    }
    
}