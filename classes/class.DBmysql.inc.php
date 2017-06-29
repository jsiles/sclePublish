<?php
class DBmysql {

private $debug;
private $row;

			var $BaseDatos;
			var $Servidor;
			var $Usuario;
			var $Clave;
			var $Conexion_ID = 0;
			var $Consulta_ID = 0;
			var $Errno = 0;
			var $Error = "";
			var $engine;

			function __construct($db=DATABASE, $host=DBHOST, $user=DBUSER, $pass=DBPASSWORD) {
			$this->BaseDatos = $db;
			$this->Servidor = $host;
			$this->Usuario = $user;
			$this->Clave = $pass;	
			$this->engine="sqlsrv";
			$this->connect();
	}

function connect($db="", $host="", $user="", $pass="")
	{
	if ($db != "") $this->BaseDatos = $db;
	if ($host != "") $this->Servidor = $host;
	if ($user != "") $this->Usuario = $user;
	if ($pass != "") $this->Clave = $pass;
try {
		//$this->Conexion_ID =new PDO($this->engine.":host=".$this->Servidor.";dbname=".$this->BaseDatos, $this->Usuario, $this->Clave);
		$this->Conexion_ID =new PDO($this->engine.":Server=".$this->Servidor.";Database=".$this->BaseDatos, $this->Usuario, $this->Clave);
 	} catch (PDOException $e) {
    echo "Failed to get DB handle: " . $e->getMessage() . "\n";
    exit;
  }
	return $this->Conexion_ID;
	}
 
/**
 * Initialize debbuger
 *
 * @param boolean $debug
 */
function debug($debug=true){
   $this->debug=$debug; 
}


/* Ejecuta un consulta */
function query($sql = "",$show=0){

		if ($show != 0)
			echo ""; //echo "sql=$sql";
		if ($sql == "")
			{
			$this->Error = "No ha especificado una consulta SQL";
			echo "<bn>ERROR". $this->Error;
			return 0;
			}
		//ejecutamos la consulta
		//
		try{
		$this->Consulta_ID = $this->Conexion_ID->query($sql);
		}
		catch(PDOException $e) {
    		echo "Failed to get DB handle: " . $e->getMessage() . "\n";
    		exit;
  		}
		return $this->Consulta_ID;
}

function next_record()
	{
		return (!$this->Consulta_ID)?null:$this->Consulta_ID->fetch();
	}
/*function numrows()	
{
   return (!$this->Consulta_ID)?0:$this->Consulta_ID->rowCount();

}*/
function numrows($_pagi_sql="")	
{
	if(strlen($_pagi_sql)>0)
	{
   		$_pagi_sql = strtoupper($_pagi_sql);
   		$_pagi_sqlConta = preg_replace("[(^SELECT.*FROM)]", "SELECT COUNT(*) FROM", $_pagi_sql );
   		$_pagi_sqlConta = preg_replace("[(ORDER BY.*)]", "", $_pagi_sqlConta);
                $_pagi_sqlConta = preg_replace("[(GROUP BY.*)]", "", $_pagi_sqlConta);
                $_pagi_sqlConta = strtolower($_pagi_sqlConta);
                //echo $_pagi_sqlConta;die;
   		$this->Consulta_ID = $this->Conexion_ID->query($_pagi_sqlConta); 
	}
   return ((!$this->Consulta_ID)||($this->Consulta_ID=='NULL'))?0:$this->Consulta_ID->fetch()[0];
   

}
} //fin de la Clase DBmysql
?>