<?php
include "config.php";
?>
<!DOCTYPE HTML>
<html land="pt-BR">
<head>
  <meta charset="UTF-8">
  
   <title>PHP Project ( DM104 )</title>
  <meta name="description" content="PHP Project ( DM104 )" />
  <meta name="robots" content="index, follow" />
   <meta name="author" content="Hudson Lima"/>
   <link rel="stylesheet" href="css/bootstrap.css" />
  <link rel="stylesheet" />
  <!--[if lt IE 9]>
      <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
   <![endif]-->
</head>
<body>

	<div class="container">

		<header class="masthead">
			<h1 class="muted top">PHP Project ( DM104 )</h1>
			
			<?php
			# CREATE
			if(isset($_POST['enviar'])){

				$nome  = $_POST['nome'];
				$email = $_POST['email'];
                $idade = $_POST['idade'];

				$sql  = 'INSERT INTO Cliente (Nome, Email, Idade) ';
				$sql .= 'VALUES (:nome, :email, :idade)';

				try {
					$create = $db->prepare($sql);
					$create->bindValue(':nome', $nome, PDO::PARAM_STR);
					$create->bindValue(':email', $email, PDO::PARAM_STR);
                    $create->bindValue(':idade', $idade, PDO::PARAM_STR);
					if($create->execute()){
						echo "<div class='alert alert-success'>
						<button type='button' class='close' data-dismiss='alert'>&times;</button>
						<strong>Inserido com sucesso!</strong>
						</div>";
					}
				} catch (PDOException $e) {
						echo "<div class='alert alert-error'>
						<button type='button' class='close' data-dismiss='alert'>&times;</button>
						<strong>Erro ao inserir dados!</strong>" . $e->getMessage() . "
						</div>";
				}

			}
			# UPDATE
			if(isset($_POST['atualizar'])){
				$id = (int)$_GET['id'];
				$nome = $_POST['nome'];
				$email = $_POST['email'];
                $idade = $_POST['idade'];

				$sqlUpdate = 'UPDATE Cliente SET Nome = ?, Email = ?, Idade = ? WHERE Id = ?';

				$dados = array($nome, $email, $idade, $id);

				try {
					$update = $db->prepare($sqlUpdate);
					if($update->execute($dados)){
						echo "<div class='alert alert-success'>
						<button type='button' class='close' data-dismiss='alert'>&times;</button>
						<strong>Atualizado com sucesso!</strong>
						</div>";
					}
				} catch (PDOException $e) {
					echo "<div class='alert alert-error'>
						<button type='button' class='close' data-dismiss='alert'>&times;</button>
						<strong>Erro ao atualizar dados!</strong>" . $e->getMessage() . "
						</div>";
				}
			}

			# DELETE
			if(isset($_GET['action']) && $_GET['action'] == 'delete'){
				$id = (int)$_GET['id'];

				$sqlDelete = 'DELETE FROM Cliente WHERE Id = :id';

				try {
					$delete = $db->prepare($sqlDelete);
					$delete->bindValue(':id', $id, PDO::PARAM_INT);
					if($delete->execute()){
						echo "<div class='alert alert-success'>
						<button type='button' class='close' data-dismiss='alert'>&times;</button>
						<strong>Deletado com sucesso!</strong>
						</div>";
					}
				} catch (PDOException $e) {
					echo "<div class='alert alert-error'>
						<button type='button' class='close' data-dismiss='alert'>&times;</button>
						<strong>Erro ao deletar dados!</strong>" . $e->getMessage() . "
						</div>";
				}
			}
			?>
		</header>

		<article>
			
			<section class="jumbotron">
				<?php
				if(isset($_GET['action']) && $_GET['action'] == 'update'){

					$id = (int)$_GET['id'];

					$sqlSelect = 'SELECT * FROM Cliente WHERE Id = :id';

					try {
						$select = $db->prepare($sqlSelect);
						$select->bindValue(':id', $id, PDO::PARAM_INT);
						$select->execute();
					} catch (PDOException $e) {
						echo $e->getMessage();
					}

					$result = $select->fetch(PDO::FETCH_OBJ);

				?>

				<ul class="breadcrumb">
					<li><a href="index.php">Página inicial <span class="divider"> /</span> </a></li>
					<li class="active">Atualizar</li>
				</ul>

				<form method="post" action="">
					<div class="input-prepend">
						<span class="add-on"><i class="icon-user"></i></span>
						<input type="text" name="nome" value="<?php echo $result->Nome; ?>" placeholder="Nome:" />
					</div>
					<div class="input-prepend">
						<span class="add-on"><i class="icon-envelope"></i></span>
						<input type="text" name="email" value="<?php echo $result->Email; ?>" placeholder="E-mail:" />
					</div>
                    <div class="input-prepend">
						<span class="add-on"><i class="icon-time"></i></span>
						<input type="text" name="idade" value="<?php echo $result->Idade; ?>" placeholder="Idade:" />
					</div>
					<br />
					<input type="submit" name="atualizar" class="btn btn-primary" value="Atualizar dados">					
				</form>

				<?php }else{ ?>

				<form method="post" action="">
					<div class="input-prepend">
						<span class="add-on"><i class="icon-user"></i></span>
						<input type="text" name="nome" placeholder="Nome:" />
					</div>
					<div class="input-prepend">
						<span class="add-on"><i class="icon-envelope"></i></span>
						<input type="text" name="email" placeholder="E-mail:" />
					</div>
					<div class="input-prepend">
						<span class="add-on"><i class="icon-time"></i></span>
						<input type="text" name="idade" placeholder="Idade:" />
					</div>
					<br />
					<input type="submit" name="enviar" class="btn btn-primary" value="Cadastrar Cliente">					
				</form>

				<?php } ?>

				<table class="table table-hover">
					
					<thead>
						<tr>
							<th>#</th>
							<th>Nome:</th>
							<th>E-mail:</th>
							<th>Idade</th>
							<th>Ações:</th>
						</tr>
					</thead>

					<tbody>
					<?php
					$sqlRead = 'SELECT * FROM Cliente limit 100';
					try {
						$read = $db->prepare($sqlRead);
						$read->execute();
					} catch (PDOException $e) {
						echo $e->getMessage();
					}
					while( $rs = $read->fetch(PDO::FETCH_OBJ) ){
					?>
						<tr>
							<td><?php echo $rs->Id; ?></td>
							<td><?php echo $rs->Nome; ?></td>
							<td><?php echo $rs->Email; ?></td>
							<td><?php echo $rs->Idade; ?></td>
							<td>
								<a href="index.php?action=update&id=<?php echo $rs->Id; ?>" class="btn"><i class="icon-pencil"></i></a>
								<a href="index.php?action=delete&id=<?php echo $rs->Id; ?>" class="btn" onclick="return confirm('Deseja deletar?');"><i class="icon-remove"></i></a>
							</td>
						</tr>
					<?php }	?>
					</tbody>

				</table>
				
			</section>

		</article>

	</div>

<script src="js/jQuery.js"></script>
<script src="js/bootstrap.js"></script>
</body>
</html>