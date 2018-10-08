<!DOCTYPE html>
<html lang="">
	<head>
		<meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    
	    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>	    
	    <link rel="stylesheet" type="text/css" href="assets/css/config.css">
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<title>Das Config</title>

	</head>


	<body>
		
		<div id="das-config">

			<p class="das-title"><i id="one" style="font-size:20px" class="fa">&#xf105;</i> Envio do Relatório de Atividades e ações da turma</p>

			
			<div id="das-config-preventine-notice">
				
				
				<div id="das-config-preventine-notice-selects">
					
					<div id="das-config-period">
						<p class="das-subtitle">Periodicidade do envio do Relatório</p>
						<select id="das-config-period-select">
							<option value="das-choice">Escolher...</option>
							<option value="das-one-days">1 dia</option>
							<option value="das-two-days">2 dias</option>
							<option value="das-three-days">3 dias</option>
							<option value="das-four-days">4 dias</option>
							<option value="das-five-days">5 dias</option>
							<option value="das-six-days">6 dias</option>
							<option value="das-one-week">1 semana</option>
							<option value="das-two-weeks">2 semanas</option>
						</select>
				</div>
				<div id="das-config-period">
					
					<p class="das-subtitle">Permite envios a partir de</p>
						<select id="das-config-send-day">
							<option value="das-zero">00</option>
							<option value="das-one">01</option>
							<option value="das-two">02</option>
							<option value="">03</option>
							<option value="">04</option>
							<option value="">05</option>
							<option value="">06</option>
							<option value="">07</option>
							<option value="">08</option>
							<option value="">09</option>
							<option value="">10</option>
							<option value="">11</option>
							<option value="">12</option>
							<option value="">13</option>
							<option value="">14</option>
							<option value="">15</option>
							<option value="">16</option>
							<option value="">17</option>
							<option value="">18</option>
							<option value="">19</option>
							<option value="">20</option>
							<option value="">21</option>
							<option value="">22</option>
							<option value="">23</option>
							<option value="">24</option>
							<option value="">25</option>
							<option value="">26</option>
							<option value="">27</option>
							<option value="">28</option>
							<option value="">29</option>
							<option value="">30</option>
							<option value="">31</option>
						</select>


						<select id="das-config-send-min">
							<option value="das-select">00</option>
							<option value="das-select-five">05</option>
							<option value="das-select-ten">10</option>
							<option value="das-select-fifteen">15</option>
							<option value="das-select-twenty">20</option>
							<option value="das-select-twenty-five">25</option>
							<option value="das-six-days">30</option>
							<option value="das-one-week">35</option>
							<option value="das-select-fourty">40</option>
							<option value="das-select-fourty-five">45</option>
							<option value="das-select-fifty">50</option>
							<option value="das-select-fifty-five">55</option>
						</select>
				</div>
			</div>
		</div>

		<script>
			
			$(document).ready(function(){
   				$("i#one").click(function(){
       				 $("div#das-config-preventine-notice-selects").toggle(1000);
    			});
			});	

			
		</script>



			<p class="das-title"><i id="two" style="font-size:20px" class="fa">&#xf105;</i> Avisos de prevenção para a entrega das atividades</p>

			<div id="das-config-notice">
				
				
				<div id="das-config-on-time">
					<p class="das-subtitle">Período de aviso antes do prazo final da atividade</p>
					<select id="das-select-on-time-days">
						<option value="das-choice">Escolher...</option>
							<option value="das-one-days">1 dia</option>
							<option value="das-two-days">2 dias</option>
							<option value="das-three-days">3 dias</option>
							<option value="das-four-days">4 dias</option>
							<option value="das-five-days">5 dias</option>
							<option value="das-six-days">6 dias</option>
							<option value="das-one-week">1 semana</option>
							<option value="das-two-weeks">2 semanas</option>
					</select>
				</div>
			</div>

			<script>
			$(document).ready(function(){
   				$("i#two").click(function(){
       				 $("div#das-config-on-time").toggle(1000);
    			});
			});
		</script>


			<p class="das-title"><i id="three" style="font-size:20px" class="fa">&#xf105;</i> Intervalos dos alunos ausentes na turma</p>
			<div id="das-config-missing-users">
				
				<div id="das-config-missing-users-selects">

				<div id="das-first-interval">

					<p class="das-subtitle">Primeiro intervalo</p>
					<div id="das-select-missing-users">
						<select id="das-select-missing-users1">
							<option value="">Escolher...</option>
							<option value="">1 dia</option>
							<option value="">3 dias</option>
							<option value="">4 dias</option>
							<option value="">5 dias</option>
							<option value="">6 dias</option>
							<option value="">1 semana</option>
							<option value="">2 semanas</option>
						</select>
					</div>
				</div>
				

				<div id="das-second-interval">

					<p class="das-subtitle">Segundo intervalo</p>
					<div id="das-select-missing-users">
						<select id="das-select-missing-users2">
							<option value="">Escolher...</option>
							<option value="">1 dia</option>
							<option value="">3 dias</option>
							<option value="">4 dias</option>
							<option value="">5 dias</option>
							<option value="">6 dias</option>
							<option value="">1 semana</option>
							<option value="">2 semanas</option>
						</select>
					</div>
				</div>

				<div id="das-thrid-interval">
					<p class="das-subtitle">Terceiro Intervalo</p>
					<div id="das-select-missing-users">
						<select id="das-select-missing-users3">
							<option value="">Escolher...</option>
							<option value="">1 dia</option>
							<option value="">3 dias</option>
							<option value="">4 dias</option>
							<option value="">5 dias</option>
							<option value="">6 dias</option>
							<option value="">1 semana</option>
							<option value="">2 semanas</option>
						</select>
					</div>
				</div>

				<div id="das-fourth-interval">
						<p class="das-subtitle">Quarto Intervalo</p>
					<div id="das-select-missing-users">
						<select id="das-select-missing-users4">
							<option value="">Escolher...</option>
							<option value="">1 dia</option>
							<option value="">3 dias</option>
							<option value="">4 dias</option>
							<option value="">5 dias</option>
							<option value="">6 dias</option>
							<option value="">1 semana</option>
							<option value="">2 semanas</option>
						</select>
					</div>
				</div>
			</div>
		</div>

		<script>
			$(document).ready(function(){
   				$("i#three").click(function(){
       				 $("div#das-config-missing-users-selects").toggle(1000);
    			});
			});
		</script>

			<p class="das-title"><i id="four" style="font-size:20px" class="fa rotate">&#xf105;</i> Status dos alunos na turma</p>
			<div id="das-config-status-class">
				
			<div id="das-interval-active">
				<p class="das-subtitle">Intervalo dos ativos</p>
				<select id="das-select-interval-active">
					<option value="">Escolher...</option>
							<option value="">1 dia</option>
							<option value="">3 dias</option>
							<option value="">4 dias</option>
							<option value="">5 dias</option>
							<option value="">6 dias</option>
							<option value="">1 semana</option>
							<option value="">2 semanas</option>	

				</select>

			</div>


			</div>
			<script>
			$(document).ready(function(){
   				$("i#four").click(function(){
       				 $("div#das-interval-active").toggle(1000);
    			});
			});
		</script>
	</div>
	</body>
</html>
