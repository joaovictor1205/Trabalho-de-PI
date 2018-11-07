<?php

require "conexaoBanco.php";

function filtraEntrada($dado) 
{
	$dado = trim($dado);
	$dado = stripslashes($dado);
	$dado = htmlspecialchars($dado);

	return $dado;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $formProcSucesso = false;
	$msgErro = "";

    $id_funcionario = $nome = $data = $sexo = $estadoCivil = $cargo = $especialidade = $cpf = $rg = $outro = "";
    $cep = $tipoLogradouro = $logradouro = $numero = $complemento = $bairro = $cidade = $estado = "";


	$nome             = filtraEntrada($_POST["nome"]);     
	$data             = filtraEntrada($_POST["data"]);
	$sexo             = filtraEntrada($_POST["sexo"]);
    $estadoCivil      = filtraEntrada($_POST["estadoCivil"]);
    $cargo            = filtraEntrada($_POST["cargo"]);
    $especialidade    = filtraEntrada($_POST["especialidade"]);
    $cpf              = filtraEntrada($_POST["cpf"]);
    $rg               = filtraEntrada($_POST["rg"]);
    $outro            = filtraEntrada($_POST["outro"]);

	$cep              = filtraEntrada($_POST["cep"]);     
	$tipoLogradouro   = filtraEntrada($_POST["tipoLogradouro"]);
	$logradouro       = filtraEntrada($_POST["logradouro"]);
    $numero           = filtraEntrada($_POST["numero"]);
    $complemento      = filtraEntrada($_POST["complemento"]);
    $bairro           = filtraEntrada($_POST["bairro"]);
    $cidade           = filtraEntrada($_POST["cidade"]);
    $estado           = filtraEntrada($_POST["estado"]);

	try
	{    
        $conn = conectaAoMySQL();
        
        $conn->begin_transaction();

		$sql = "
		  INSERT INTO Funcionario (id_funcionario, nome, dataNascimento, sexo, estadoCivil, cargo, especialidade, cpf, rg, outro)
		  VALUES (NULL, '$nome', '$data', '$sexo', '$estadoCivil', '$cargo', '$especialidade', $cpf, $rg, '$outro')
        ";
        
		if (! $conn->query($sql))
            throw new Exception("Falha na inserção dos dados do Funcionario: " . $conn->error);

        // resgatar id do funcionario
        $id_endfuncionario = mysqli_insert_id($conn);

        $sql = "
            INSERT INTO EnderecoFunc (id, cep, tipoLogradouro, logradouro, numero, complemento, bairro, cidade, estado, nome)
            VALUES ($id_endfuncionario, $cep, '$tipoLogradouro', '$logradouro', $numero, '$complemento', '$bairro', '$cidade', '$estado', '$nome')
        ";
   
		if (! $conn->query($sql))
            throw new Exception("Falha na inserção dos dados do Endereço do Funcionario: " . $conn->error);

        $conn->commit();
        echo "Transacao executada com sucesso";
        $formProcSucesso = true;

    }

    //caso tenha ocorrido algum erro operacao nao e completada 
	catch (Exception $e)
	{
        $conn->rollback();
        echo "Ocorreu um erro na transacao: " . $e->getMessage();
	}
}

?>

<!DOCTYPE html>

<html>
<head>

    <meta charset="utf-8" />
    <title>Clinica - Cadastro de Funcionário</title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:100" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway:100" rel="stylesheet">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script src="js/cadastroFuncionario.js"></script>
    <link rel="stylesheet" href="css/cadastroFuncionario.css">

<script>
$(document).ready(function() {

   function limpa_formulário_cep() {
       // Limpa valores do formulário de cep.
       $("#logradouro").val("");
       $("#bairro").val("");
       $("#cidade").val("");
   }
   
   //Quando o campo cep perde o foco.
   $("#cep").blur(function() {

       //Nova variável "cep" somente com dígitos.
       var cep = $(this).val().replace(/\D/g, '');

       //Verifica se campo cep possui valor informado.
       if (cep != "") {

           //Expressão regular para validar o CEP.
           var validacep = /^[0-9]{8}$/;

           //Valida o formato do CEP.
           if(validacep.test(cep)) {

               //Preenche os campos com "..." enquanto consulta webservice.
               $("#logradouro").val("...");
               $("#bairro").val("...");
               $("#cidade").val("...");

               //Consulta o webservice viacep.com.br/
               $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                   if (!("erro" in dados)) {
                       //Atualiza os campos com os valores da consulta.
                       $("#logradouro").val(dados.logradouro);
                       $("#bairro").val(dados.bairro);
                       $("#cidade").val(dados.localidade);
                   } //end if.
                   else {
                       //CEP pesquisado não foi encontrado.
                       limpa_formulário_cep();
                       alert("CEP não encontrado.");
                   }
               });
           } //end if.
           else {
               //cep é inválido.
               limpa_formulário_cep();
               alert("Formato de CEP inválido.");
           }
       } //end if.
       else {
           //cep sem valor, limpa formulário.
           limpa_formulário_cep();
       }
   });
});
</script>

</head>

<body>
    
    <div id='home_page'>

    <div class="container">
        <nav id="header" class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="homePrivado.html">Área Restrita</a>
                </div>
                <ul class="nav navbar-nav">
                    <li class="active"><a href="cadastroFuncionario.php">Novo Funcionário</a></li>
                    <li><a href="listaFuncionario.html">Listar Funcionários</a></li>
                    <li><a href="listaContato.html">Listar Contatos</a></li>
                    <li><a href="listaAgendamento.html">Listar Agendamentos</a></li>
                    <a href="home.html"><button type="button" class="btn btn-default" id="voltar">Voltar</button></a>
                </ul>
            </div>
        </nav>
    </div>

    <div class="caixa">

            <h1>Para cadastrar um novo funcionário, insira as seguintes informações:</h1>

            <h2>Dados Pessoais</h2>

                <form class="form" name="formulario" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" onSubmit="return validaData()">

                    <fieldset>

                        <div class="form-group row">
                            <label class="col-sm-1" for="nome">Nome:</label>
                            <div class="col-sm-4">
                                <input class="form-control" type="text" id="nome" name="nome">
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-sm-1" for="data">Data de Nascimento:</label>
                            <div class="col-sm-2">
                                <input class="form-control" type="date" id="data" name="data">
                            </div>
                        </div>


                        <div class="form-check">
                            <label class="form-check-label col-sm-1" id="sexo" for="sexo">Sexo:</label>
                            <div>
                                <input class="form-check-input" type="radio" id="masculino" name="sexo" value="Masculino">Masculino
                                <input class="form-check-input" type="radio" id="feminino" name="sexo" value="Feminino">Feminino
                            </div>
                        </div><br>


                        <div class="form-group row">
                            <label class="col-sm-1" for="estadoCivil">Estado Civil:</label>
                            <div class="col-sm-2">
                                <select class="form-control" id="estadoCivil" name="estadoCivil">
                                    <option>Solteiro(a)</option>
                                    <option>Casado(a)</option>
                                    <option>Divorciado(a)</option>
                                    <option>Viúvo(a)</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-sm-1" for="cargo">Cargo:</label>
                            <div class="col-sm-2">
                                <select class="form-control" id="cargo" name="cargo">
                                    <option>Enfermeiro(a)</option>
                                    <option id='medico'>Médico(a)</option>
                                    <option>Secretário(a)</option>
                                    <option>Outro</option>
                                </select>
                            </div>
                        </div>

                        
                        <div id="especialidade" class="form-group row">
                            <label class="col-sm-1" for="especialidade">Especialidade:</label>
                            <div class="col-sm-2">
                                <select class="form-control" name="especialidade">
                                    <option>Cardiologista</option>
                                    <option>Otorrinologista</option>
                                    <option>Neurologista</option>
                                    <option>Cirurgião</option>
                                </select>
                            </div>
                        </div>

                    </fieldset>


                    <h2>Documentos</h2>

                    <fieldset>

                        <div class="form-group row">
                            <label class="col-sm-1" for="cpf">CPF:</label>
                            <div class="col-sm-2">
                                <input class="form-control" type="text" id="cpf" name="cpf">
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-sm-1" for="rg">RG:</label>
                            <div class="col-sm-2">
                                <input class="form-control" type="text" id="rg" name="rg">
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-sm-1" for="outro">Outro:</label>
                            <div class="col-sm-2">
                                <input class="form-control" type="text" id="outro" name="outro">
                            </div>
                        </div>

                    </fieldset>


                    <h2>Endereço</h2>

                    <fieldset>

                        <div class="form-group row">
                            <label class="col-sm-1" for="cep">CEP:</label>
                            <div class="col-sm-2">
                                <input class="form-control" type="text" id="cep" name="cep">
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-sm-1" for="tipoLogradouro">Tipo de Logradouro:</label>
                            <div class="col-sm-2">
                                <select class="form-control" id="tipoLogradouro" name="tipoLogradouro">
                                    <option>Rua</option>
                                    <option>Avenida</option>
                                    <option>Praça</option>
                                </select>
                            </div>
                        </div>
        
        
                        <div class="form-group row">
                            <label class="col-sm-1" for="logradouro">Logradouro:</label>
                            <div class="col-sm-2">
                                <input class="form-control" type="text" id="logradouro" name="logradouro">
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-sm-1" for="numero">Número:</label>
                            <div class="col-sm-2">
                                <input class="form-control" type="number" id="numero" name="numero">
                            </div>
                        </div>
        
        
                        <div class="form-group row">
                            <label class="col-sm-1" for="complemento">Complemento:</label>
                            <div class="col-sm-2">
                                <input class="form-control" type="text" id="complemento" name="complemento">
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-sm-1" for="bairro">Bairro:</label>
                            <div class="col-sm-2">
                                <input class="form-control" type="text" id="bairro" name="bairro">
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-sm-1" for="cidade">Cidade:</label>
                            <div class="col-sm-2">
                                <input class="form-control" type="text" id="cidade" name="cidade">
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-sm-1" for="estado">Estado:</label>
                            <div class="col-sm-2">
                                <select class="form-control" id="estado" name="estado">
                                    <option>MG</option>
                                    <option>SP</option>
                                    <option>RJ</option>
                                    <option>RS</option>
                                </select>
                            </div>
                        </div>

                    </fieldset>

                    <input type="submit" name="cadastrar" value="Cadastrar" id="cadastrar" class="btn btn-success">
                    <a href="homePrivado.html"><button type="button" name="voltar" id="voltar" class="btn btn-danger">Cancelar</button></a>

                </form>
        </div>

        <?php 
            if ($_SERVER["REQUEST_METHOD"] == "POST") {  
                if ($formProcSucesso == true)
                    echo "<h3 class='text-success'>Dados armazenados com sucesso!</h3>";
                else
                    echo "<h3 class='text-danger'>Cadastro não realizado: $msgErro</h3>";
            }
        ?>

    </div>

</body>

</html>