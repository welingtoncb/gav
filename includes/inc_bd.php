<?php
	class db
	{
		var $Local; 
		var $Usuario; 
		var $Senha; 
		var $Banco; 
		var $Tipo; 
		var $StringConexao; 
		var $Identificador;
		var $Resultado;
		var $Registro;
		var $CodigoInserido;
		
		//Atributos relativos a erro
		var $Erro;
		var $CodigoErro;
		var $DescricaoErro;
	
		function Conecta() //FUNวรO QUE CONECTA AO BANCO DE DADOS
		{
			switch($this->Tipo)
			{
				case "MYSQL" : 
					$this->Erro = false;
					$this->Identificador = mysqli_connect($this->Local,$this->Usuario,$this->Senha,true);
					if ($this->Identificador == false)
					{
						$this->Erro = true;
						$this->CodigoErro = mysqli_errno();
						$this->DescricaoErro = mysqli_error();
					}
					mysqli_select_db($this->Banco);
					break;
				
				case "MSSQL" : 
					$this->Erro = false;
					$this->Identificador = mssql_connect($this->Local,$this->Usuario,$this->Senha);
					if ($this->Identificador == false)
					{
						$this->Erro = true;
						$this->CodigoErro = 1;
						$this->DescricaoErro = mssql_get_last_message();
					}
					mssql_select_db($this->Banco);
					break;
			}
		}
		
		function Desconecta() //FUNวรO QUE DESCONECTA AO BANCO DE DADOS
		{			
			switch($this->Tipo)
			{
				case "MYSQL" : 
					mysqli_close($this->Identificador);
					break;
					
				case "MSSQL" : 
					mssql_close($this->Identificador);
					break;
			}
		}
		
		function AcessaBanco($sNomeBanco) //FUNวรO QUE CONECTA AO BANCO DE DADOS
		{			
			switch($this->Tipo)
			{
				case "MYSQL" :					
					$this->Banco = $sNomeBanco;
					mysqli_select_db($sNomeBanco,$this->Identificador);
					break;
				
				case "MSSQL" :					
					$this->Banco = $sNomeBanco;
					mssql_select_db($sNomeBanco,$this->Identificador);
					break;
			}
			return true;
		}
		
		function Consulta($sQuery)
		{
			switch($this->Tipo)
			{
				case "MYSQL" : 
					$this->Erro = false;
					$this->Resultado = mysqli_query($sQuery, $this->Identificador);
					
					if($this->Resultado == false)
					{
						$this->Erro = true;
						$this->CodigoErro = mysqli_errno();
						$this->DescricaoErro = mysqli_error();
					}
					return $this->Resultado;
					break;
					
				case "MSSQL" : 
					$this->Erro = false;
					$this->Resultado = mssql_query($sQuery, $this->Identificador);
					if ($this->Resultado == false)
					{
						$this->Erro = true;
						$this->CodigoErro = 1;
						$this->DescricaoErro = mssql_get_last_message();
					}
					return $this->Resultado;
					break;
			}
		}	
		
		function BeginTransaction()
		{
			switch($this->Tipo)
				{
				 case "MYSQL" : 
					$this->Resultado = mysqli_query("BEGIN", $this->Identificador);
					if ($this->Resultado == false)
					{
						$this->Erro = true;
						$this->CodigoErro = mysqli_errno();
						$this->DescricaoErro = mysqli_error();
					}
					return $this->Resultado;
					break;
					
				case "MSSQL" : 
					$this->Resultado = mssql_query("BEGIN TRANSACTION", $this->Identificador);
					if ($this->Resultado == false)
					{
						$this->Erro = true;
						$this->CodigoErro = 1;
						$this->DescricaoErro = mssql_get_last_message();
					}
					return $this->Resultado;
					break;
				}
		}

		function CommitTransaction()
		{
			switch($this->Tipo)
			{
				case "MYSQL" : 
					$this->Resultado = mysqli_query("COMMIT", $this->Identificador);
					if ($this->Resultado == false)
					{
						$this->Erro = true;
						$this->CodigoErro = mysqli_errno();
						$this->DescricaoErro = mysqli_error();
					}
					break;
					
				case "MSSQL" : 
					$this->Resultado = mssql_query("COMMIT", $this->Identificador);
					if ($this->Resultado == false)
					{
						$this->Erro = true;
						$this->CodigoErro = 1;
						$this->DescricaoErro = mssql_get_last_message();
					}
					return $this->Resultado;
					break;
			}
		}

		function RollbackTransaction()
		{
			switch($this->Tipo)
			{
				case "MYSQL" : 
				$this->Resultado = mysqli_query("ROLLBACK", $this->Identificador);
				if ($this->Resultado == false)
					{
						$this->Erro = true;
						$this->CodigoErro = mysql_errno();
						$this->DescricaoErro = mysql_error();
					}
				break;
				
				case "MSSQL" : 
					$this->Resultado = mssql_query("ROLLBACK", $this->Identificador);
					if ($this->Resultado == false)
					{
						$this->Erro = true;
						$this->CodigoErro = 1;
						$this->DescricaoErro = mssql_get_last_message();
					}
					return $this->Resultado;
					break;
			}
		}
		
		function TotalRegistros($objResultado)
		{
			switch($this->Tipo)
			{
				case "MYSQL" : 
					return mysqli_num_rows($objResultado);
					break;
				
				case "MSSQL" : 
					return mssql_num_rows($objResultado);
					break;
			}
		}	
		
		function CodigoInserido()
		{
			switch($this->Tipo)
			{
				case "MYSQL" : 
					return mysqli_insert_id($this->Identificador);
					break;
					
				case "MSSQL" : 
					//NรO IMPLEMENTADO
					return 0;
					break;
			}
		}
		
		function EOF($objResultado)
		{
			switch($this->Tipo)
			{
				case "MYSQL" : 
					if ($this->Registro = mysqli_fetch_array($objResultado))					
						{return false;}
					else
						{return true;}
					break;
					
				case "MSSQL" : 
					if ($this->Registro = mssql_fetch_array($objResultado))					
						{return false;}
					else
						{return true;}
					break;
			}
		}
		
		function Posiciona($objResultado, $nRegistro)
		{
			switch($this->Tipo)
			{
				case "MYSQL" : 
					if (!mysqli_data_seek($objResultado, $nRegistro)) 					 
						{return false;}
					else
						{return true;}
					break;
					
				case "MSSQL" : 
					if (!mssql_data_seek($objResultado, $nRegistro)) 				
						{return false;}
					else
						{return true;}
					break;
			}
		}
			
	}
?>