<?php 
// session_start inicia a sessão
session_start();
$login = $_POST['login'];
$senha = $_POST['senha'];
require "conexaoBanco.php";
  
$conn = conectaAoMySQL();
$select = mysqli_select_db("server") or die("Sem acesso ao DB");
 
$result = mysqli_query("SELECT * FROM `USUARIO` 
WHERE `NOME` = '$login' AND `SENHA`= '$senha'");
if(mysqli_num_rows ($result) > 0 )
{
$_SESSION['login'] = $login;
$_SESSION['senha'] = $senha;
header('location:homePrivado.html');
}
else{
  unset ($_SESSION['login']);
  unset ($_SESSION['senha']);
  header('location:home.html');
   
  }
?>