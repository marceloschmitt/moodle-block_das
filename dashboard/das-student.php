<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Das - Student</title>
	<link rel="stylesheet" type="text/css" href="assets/css/normalize.css">
    <link rel="stylesheet" type="text/css" href="assets/css/das-style-student.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/animate.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/das-general-report.css">
</head>
<body>
	<div class="das-students-status-information">
	<div class="das-student-information">
		<p class="das-title-class">Atividades Texto Online</p>
		
		<div id="das-image-message">
			<img id="das-img-user" src="assets/img/rosto6.jpg">
			<div id="das-student-text-message">
			<p class="das-title">João Alfredo da Silva</p>
			<button>Mensagem</button>
			</div>
		</div>
	</div>

	<div class="dasStudentChart">
		<p class="das-subtitle">Hits de hoje em Atividades de Texto Online por João Alfredo da Silva</p>
		<div style="width: 600px; display: block; height: 300px; margin: 0px auto;">
        <canvas id="dasStudentChart" style="width: 100%;"></canvas>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    <script>
        let dasStudentChart = document.getElementById('dasStudentChart').getContext('2d');

        let chart = new Chart(dasStudentChart, {
            type: 'bar',

            data: {
                labels: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'],

                datasets: [{
                    label: 'Hits',
                    data: [0,0,0,0,0,0,0,0,0,0,2,0,0,0,0,0,0,0,0,0,0,0,0],
                    backgroundColor: "rgba(0, 2, 252)",
                    borderColor: "#0000ff"
                }]
            }

        });
    </script>
	</div>

	<div class="das-student-activities">
		<table id="das-student-activities-table" border="1" width="60%%">
			<tr class="das-color-orange">
				<td><p><b>Hora</b></p></td>
				<td><p><b>Nome Completo</b></p></td>
				<td><p><b>Usuário Afetado</b></p></td>
				<td><p><b>Contexto do Evento</b></p></td>
				<td><p><b>Componente</b></p></td>
				<td><p><b>Nome do Evento</b></p></td>
				<td><p><b>Descrição</b></p></td>
				<td><p><b>Origem</b></p></td>
				<td><p><b>Endereço IP</b></p></td>
			</tr>
			
			<tr>
				<td><p>12 de Mar, 10:35</p></td>
				<td><p>Nome do Aluno</p></td>
				<td><p>-</p></td>
				<td><p>Arquivo: Leitura: Unidade I Aspectos Teóricos Conceituais</p></td>
				<td><p>Arquivo</p></td>
				<td><p>Módulo do Curso Visualizado</p></td>
				<td><p>The user with id "7449" viewed the 'resource' activity with course module id '64228'.</p></td>
				<td><p>Web</p></td>
				<td><p>187.4.13.180</p></td>
			</tr>

			<tr class="das-color-ccc">
				<td><p>12 de Mar, 10:35</p></td>
				<td><p>Nome do Aluno</p></td>
				<td><p>-</p></td>
				<td><p>Curso: Novo Hamburgo T4</p></td>
				<td><p>Sistema</p></td>
				<td><p>Curso Visto</p></td>
				<td><p>The user with id "7449" viewed the course with id '412'.</p></td>
				<td><p>Web</p></td>
				<td><p>187.4.13.180</p></td>
			</tr>
		</table>

		
	</div>

	<div class="das-student-download">
		<p style="margin-right: 2em;">Baixar todos os dados como tabela</p>
		<select id="das-select-download">
			<option>Valores Separados por Vírgula(.csv)</option>
			<option>Microsoft Excel(.xlsx)</option>
			<option>Tabela HTML</option>
			<option>Javascript Object Notation(.json)</option>
			<option>Open Document (.ods)</option>
			</select>
		<button id="das-download">Download</button>
	</div>
</div>	
</body>
</html>
