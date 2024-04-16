<?php

// $servername = "localhost";
// $username = "root";
// $password = "";
// $database = "chamados";

//     $conn = new mysqli($servername, $username, $password, $database);

//     if($conn->connect_error)
//     {
//         die("Conexão Falhou". $conn->connect_error);
//     }

require_once 'conexao.inc';
global $servidorBD, $usuarioBD, $senhaBD, $nome_banco;
define('DB_HOST', $servidorBD);
define('DB_USERNAME', $usuarioBD);
define('DB_PASSWORD', $senhaBD);
define('DB_NAME', $nome_banco);

class ConexaoBanco
{
	public function conectar()
	{

		$conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME) or die('Nao foi possivel conectar ');
		if ($conn)
		$acessobanco = mysqli_select_db($conn, DB_NAME) or die('Nao foi possivel conectar ');
		return $conn;

	}
}

    // $conteudo_arquivo = file_get_contents("caminho/do/seu/arquivo.csv");


?>