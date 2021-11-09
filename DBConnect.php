<?php
class dbConnect
{

    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "sms";

    protected $conn;

    /* Function for opening connection */
    public function openConnection()
    {
      $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
      if ($this->conn->connect_error) {
        die("Connetion failed:" . $this->conn->connect_error);
      }
      // echo "Conneted successfully";
      return $this->conn;
  
    }

    /* Function for closing connection */
    public function closeConnection()
    {
        $this->conn = null;
    }
}
?>