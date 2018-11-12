<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Caixa de Mensagem - DAS</title>
	<link rel="stylesheet" type="text/css" href="assets/css/normalize.css">
    <link rel="stylesheet" type="text/css" href="assets/css/das-style-message.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/animate.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>	
		<div class="das-header">
			<img src="assets/img/message-icon.png" id="das-message-icon">
			<p class="das-subtitle-message" style="margin: auto;">Nome do Curso ></p>
			<p class="das-subtitle-message" style="margin: auto;">Nome do Bloco ></p>
			<p class="das-subtitle-message" style="margin: auto;">Nome do Tópico ></p>
			<p class="das-subtitle-message" style="margin: auto;">Nome da Atividade</p>			
		</div>

		<div class="das-text-area">
			<p class="das-text" style="margin: 1em 1em 1em 1em;">Assunto:</p>
			<textarea rows="1" cols="100" id="das-area"></textarea>
			<p class="das-text" style="margin: 1em 1em 1em 1em;">Mensagem:</p>
		<textarea rows="10" cols="100" placeholder="Digite sua mensagem" wrap="hard" id="das-text-message" ></textarea>
		</div>

	<div class="das-checkbox-users">
		<div class="das-check">
			<p> <input type="checkbox" name="User1" value="user1"> Nome do Aluno 1</p>
			<p> <input type="checkbox" name="User2" value="user2"> Nome do Aluno 2</p>
			<p> <input type="checkbox" name="User3" value="user3"> Nome do Aluno 3</p>
			<p> <input type="checkbox" name="User4" value="user4"> Nome do Aluno 4</p>
			<p> <input type="checkbox" name="User5" value="user5"> Nome do Aluno 5</p>
			<p> <input type="checkbox" name="User5" value="user5"> Nome do Aluno 6</p>
			<p> <input type="checkbox" name="User5" value="user5"> Nome do Aluno 7</p>
			<p> <input type="checkbox" name="User5" value="user5"> Nome do Aluno 8</p>
			<p> <input type="checkbox" name="User5" value="user5"> Nome do Aluno 9</p>
			<p> <input type="checkbox" name="User5" value="user5"> Nome do Aluno 10</p>
		</div>
	
					
        <p id="das-check-text">Selecionar Tudo</p>
        <p id="das-uncheck-text">Deselecionar Tudo</p>
        <a href="#" class="das-button-send">Enviar</a>
	
	</div>
</body>
</html>
