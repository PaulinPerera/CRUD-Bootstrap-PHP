<?php

$mysqli = new mysqli_driver();
$mysqli->report_mode = MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR;
//report_mode -> é um atributo;

function open_database()
{
	try {
		$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$conn->set_charset("utf8"); // metodo;
		return $conn;
	} catch (Exception $e) {
		throw new Exception("Erro ao conectar no banco de dados\n {$e->getMessage()}");
	}
}

function close_database($conn)
{
	try {
		$conn->close();
	} catch (Exception $e) {
		throw new Exception("Erro ao encerrar conexão com o banco de dados\n {$e->getMessage()}");
	}
}

/**
 *  Pesquisa um Registro pelo ID em uma Tabela
 */
function find($table = null, $id = null)
{
	$found = null;
	$database = null;

	try {
		$database = open_database();

		if ($id) {
			$id = (int) $id; // só aceita número: evita SQL injection pela URL
			$sql = "SELECT * FROM $table WHERE id = $id";
			$result = $database->query($sql);

			if ($result->num_rows > 0) {
				$found = $result->fetch_assoc();
			}

		} else {

			$sql = "SELECT * FROM $table";
			$result = $database->query($sql);

			if ($result->num_rows > 0) {
				$found = $result->fetch_all(MYSQLI_ASSOC);

				/* Metodo alternativo
				$found = [];
				while ($row = $result->fetch_assoc()) {
				  array_push($found, $row);
				} */
			}
		}
	} catch (Exception $e) {
		$_SESSION['message'] = $e->GetMessage();
		$_SESSION['type'] = 'danger';
	}

	if ($database) {
		close_database($database);
	}
	return $found;
}

/**
 *  Pesquisa Todos os Registros de uma Tabela
 */
function find_all($table)
{
	return find($table);
}

/**
 *  Pesquisa os Registros cujo campo contém o texto informado (LIKE),
 *  em ordem alfabética desse campo. Sem texto, traz todos os registros.
 */
function search($table = null, $column = null, $term = "")
{
	$found = null;
	$database = null;

	try {
		$database = open_database();

		// escapa % _ e \ para o texto digitado ser tratado como texto comum
		$like = "%" . addcslashes($term, "%_\\") . "%";

		// prepare + bind_param: o texto digitado nunca vira parte do SQL
		$stmt = $database->prepare("SELECT * FROM $table WHERE $column LIKE ? ORDER BY $column");
		$stmt->bind_param("s", $like);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows > 0) {
			$found = $result->fetch_all(MYSQLI_ASSOC);
		}
	} catch (Exception $e) {
		$_SESSION['message'] = $e->GetMessage();
		$_SESSION['type'] = 'danger';
	}

	if ($database) {
		close_database($database);
	}
	return $found;
}

/**
 *  Insere um registro no BD
 */
function save($table = null, $data = null)
{

	$database = open_database();

	$columns = null;
	$values = null;

	//print_r($data);

	foreach ($data as $key => $value) {
		$columns .= trim($key, "'") . ","; //.= -> concatena
		$values .= "'" . $database->real_escape_string($value) . "',";
		/*
			'$value' -> mesmo que o campo seja passado entre apostrofo, o bd interpreta corretamente caso o campo seja do tipo int ou outros
			real_escape_string -> protege apóstrofos e aspas digitados pelo usuário (ex.: na descrição)
		*/
	}

	// remove a ultima virgula
	$columns = rtrim($columns, ',');
	$values = rtrim($values, ',');

	$sql = "INSERT INTO $table ($columns) VALUES ($values);";

	try {
		$database->query($sql); // executa a query

		$_SESSION["message"] = "Registro cadastrado com sucesso.";
		$_SESSION["type"] = "success";

	} catch (Exception $e) {

		$_SESSION["message"] = "Nao foi possivel realizar a operacao.";
		$_SESSION["type"] = "danger";
	}

	close_database($database);
}

/**
 *  Atualiza um registro em uma tabela, por ID
 */
function update($table = null, $id = 0, $data = null)
{

	$database = open_database();

	$id = (int) $id; // só aceita número: evita SQL injection pela URL

	$items = null;

	foreach ($data as $key => $value) {
		$items .= trim($key, "'") . "='" . $database->real_escape_string($value) . "',";
	}

	// remove a ultima virgula
	$items = rtrim($items, ',');

	// $sql = "UPDATE " . $table;
	// $sql .= " SET $items";
	// $sql .= " WHERE id=" . $id . ";";

	$sql = "UPDATE $table SET $items WHERE id=$id;";

	try {
		$database->query($sql);

		$_SESSION["message"] = "Registro atualizado com sucesso.";
		$_SESSION["type"] = "success";

	} catch (Exception $e) {

		$_SESSION["message"] = "Nao foi possivel realizar a operacao.";
		$_SESSION["type"] = "danger";
	}

	close_database($database);
}

function remove( $table = null, $id = null ) {

  $database = open_database();
	
  try {
    if ($id) {

      $id = (int) $id; // só aceita número: evita SQL injection pela URL

      $sql = "DELETE FROM $table WHERE id = $id";
      $result = null; //$database->query($sql);

      if ($result = $database->query($sql)) {   	
        $_SESSION["message"] = "Registro Removido com Sucesso.";
        $_SESSION["type"] = "success";
      }
    }
  } catch (Exception $e) { 

    $_SESSION["message"] = "Não foi possível realizar a operação:<br>{$e->GetMessage()}";
    $_SESSION["type"] ="danger";
  }

  close_database($database);
}
?>
