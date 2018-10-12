<!DOCTYPE html>
<?php 
require_once("../../../config.php");
include '../lib.php'; ?>
<html lang="pt">

<head>
    <link rel="stylesheet" type="text/css" href="assets/css/normalize.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/animate.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dash Fluxo de Caixas</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


    <script>
        $(function() {

            var menu = Array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
            var blockNames = Array("das-activity-deliver", "das-preventive-notice", "das-on-time", "das-out-of-time", "das-remail", "das-online-users-box", "das-missing-users-box", "das-status-class", "das-acess-activity", "das-resources", "das-permanence-course");
            var columns = Array(1,1,1,1);

            function reorganizeSkeleton() {
                var cont = 0;
                var someTag;

                if (menu[0] + menu[1] + menu[2] + menu[3] + menu[4] == 0) {
                    columns[1] = 0;
                } else {
                    columns[1] = 1;
                }

                if (menu[5] + menu[6] == 0) {
                    columns[2] = 0;
                } else {
                    columns[2] = 1;
                }

                for (var i = 0; i <= 12; i++) {
                    cont = cont + menu[i];
                    someTag = "das-item-menu" + i;
                    if (menu[i] == 0) {
                        document.getElementById(someTag).style.backgroundColor = '#999999';
                        document.getElementById(blockNames[i]).style.display = 'none';
                    } else {
                        document.getElementById(someTag).style.backgroundColor = '#ff6600';
                        document.getElementById(blockNames[i]).style.display = 'block';
                    }
                }
            }

            function clickMenu(event) {
                var data = event.data;

                if (data.menu[data.i] == 0) {
                    data.menu[data.i] = 1;
                } else {
                    data.menu[data.i] = 0;
                }
                reorganizeSkeleton();
            }

            for (var i = 0; i <= 12; i++) {
                var someTag = "a.das-item-menu" + i;
                $(someTag).on('click', {
                    menu,
                    i
                }, clickMenu);
            }

            $("a.das-check-buttom").on('click', function() {
                for (var i = 0; i <= 12; i++) {
                    menu[i] = 1;
                }
                reorganizeSkeleton();
            });

            $("a.das-uncheck-buttom").on('click', function() {
                for (var i = 0; i <= 12; i++) {
                    menu[i] = 0;
                }
                reorganizeSkeleton();
            });
            
            $("#das-col-menu").mouseover(function() {
                document.getElementById("das-col-menu").style.width = '25%';
                document.getElementById("das-check-text").style.display = 'block';
                document.getElementById("das-uncheck-text").style.display = 'block';
                document.getElementById("das-check").style.width = '20px';
                document.getElementById("das-check").style.height = '20px';
                document.getElementById("das-uncheck").style.width = '20px';
                document.getElementById("das-uncheck").style.height = '20px';
                if (columns[2] == 1){
                    document.getElementById("das-user-column-1").style.width = '25%';
                    document.getElementById("das-user-column-1").style.left = '25%';
                    document.getElementById("das-user-column-2").style.display = 'block';
                    $("#das-user-column-2").addClass('animated fadeIn das-delay');
                }
                else{
                    document.getElementById("das-user-column-1").style.width = '50%';
                    document.getElementById("das-user-column-1").style.left = '25%';
                    document.getElementById("das-user-column-2").style.display = 'none';
                    $("#das-user-column-2").removeClass('animated fadeIn das-delay');
                }
                if (columns[1] == 1){
                    document.getElementById("das-user-column-2").style.width = '25%';                
                    document.getElementById("das-user-column-2").style.left = '50%';
                    document.getElementById("das-user-column-1").style.display = 'block';
                    $("#das-user-column-1").addClass('animated fadeIn das-delay');
                }
                else{
                    document.getElementById("das-user-column-2").style.width = '50%';
                    document.getElementById("das-user-column-2").style.left = '25%';
                    document.getElementById("das-user-column-1").style.display = 'none';
                    $("#das-user-column-1").removeClass('animated fadeIn das-delay');
                }
                for (var i = 0; i <= 12; i++) {
                    var someTag = "das-name-menu" + i;
                    $("#"+someTag).addClass('animated fadeIn');
                    document.getElementById(someTag).style.display = 'block';
                }
                
            });
            
            $("#das-col-menu").mouseout(function() {
                document.getElementById("das-col-menu").style.width = '5%';
                document.getElementById("das-check-text").style.display = 'none';
                document.getElementById("das-uncheck-text").style.display = 'none';
                document.getElementById("das-check").style.width = '50px';
                document.getElementById("das-check").style.height = '50px';
                document.getElementById("das-uncheck").style.width = '50px';
                document.getElementById("das-uncheck").style.height = '50px';
                if (columns[2] == 1){
                    document.getElementById("das-user-column-1").style.width = '35%';
                    document.getElementById("das-user-column-1").style.left = '5%';
                    document.getElementById("das-user-column-2").style.display = 'block';
                }
                else{
                    document.getElementById("das-user-column-1").style.width = '70%';
                    document.getElementById("das-user-column-1").style.left = '5%';
                    document.getElementById("das-user-column-2").style.display = 'none';
                }
                if (columns[1] == 1){
                    document.getElementById("das-user-column-2").style.width = '35%';                
                    document.getElementById("das-user-column-2").style.left = '40%';
                    document.getElementById("das-user-column-1").style.display = 'block';
                }
                else{
                    document.getElementById("das-user-column-2").style.width = '70%';                
                    document.getElementById("das-user-column-2").style.left = '5%';
                    document.getElementById("das-user-column-1").style.display = 'none';
                }
                for (var i = 0; i <= 12; i++) {
                    var someTag = "das-name-menu" + i;
                    document.getElementById(someTag).style.display = 'none';
                    $("#"+someTag).removeClass('animated fadeIn');
                }
            });               
        });

    </script>

</head>

<body>

    <div class="row" style="margin-left: 0; margin-right: 0;">
        <div id="das-col-menu">
            <ul id="das-menu-vertical">
                <a class="das-item-menu0" href="#">
                    <li id="das-item-menu0"><img src="assets/img/tempo.png" alt="Imagem de item do menu" class="das-image-menu">
                        <p id="das-name-menu0">Postagens Fora do Prazo</p>
                    </li>
                </a>
                <a class="das-item-menu1" href="#">
                    <li id="das-item-menu1"><img src="assets/img/repostagem.png" alt="Imagem de item do menu" class="das-image-menu">
                        <p id="das-name-menu1">Repostagem da atividade</p>
                    </li>
                </a>
                <a class="das-item-menu2" href="#">
                    <li id="das-item-menu2"><img src="assets/img/postagensadiantadas.png" alt="Imagem de item do menu" class="das-image-menu">
                        <p id="das-name-menu2">Postagens Antecipadas</p>
                    </li>
                </a>
                <a class="das-item-menu3" href="#">
                    <li id="das-item-menu3"><img src="assets/img/svisopreventivo.png" alt="Imagem de item do menu" class="das-image-menu">
                        <p id="das-name-menu3">Aviso Preventivo</p>
                    </li>
                </a>
                <a class="das-item-menu4" href="#">
                    <li id="das-item-menu4"><img src="assets/img/post.png" alt="Imagem de item do menu" class="das-image-menu">
                        <p id="das-name-menu4">Postagem no Prazo</p>
                    </li>
                </a>
                <a class="das-item-menu5" href="#">
                    <li id="das-item-menu5"><img src="assets/img/globo.png" alt="Imagem de item do menu" class="das-image-menu">
                        <p id="das-name-menu5">Ativos no Dia</p>
                    </li>
                </a>
                <a class="das-item-menu6" href="#">
                    <li id="das-item-menu6"><img src="assets/img/evasão.png" alt="Imagem de item do menu" class="das-image-menu">
                        <p id="das-name-menu6">Relatório de Evasão</p>
                    </li>
                </a>
                <a class="das-item-menu7" href="#">
                    <li id="das-item-menu7"><img src="assets/img/status.png" alt="Imagem de item do menu" class="das-image-menu">
                        <p id="das-name-menu7">Status da Turma</p>
                    </li>
                </a>
                <a class="das-item-menu8" href="#">
                    <li id="das-item-menu8"><img src="assets/img/topicos.png" alt="Imagem de item do menu" class="das-image-menu">
                        <p id="das-name-menu8">Acesso por Tópico</p>
                    </li>
                </a>
                <a class="das-item-menu9" href="#">
                    <li id="das-item-menu9"><img src="assets/img/top.png" alt="Imagem de item do menu" class="das-image-menu">
                        <p id="das-name-menu9">Top 10 Recursos</p>
                    </li>
                </a>
                <a class="das-item-menu10" href="#">
                    <li id="das-item-menu10"><img src="assets/img/bottom.png" alt="Imagem de item do menu" class="das-image-menu">
                        <p id="das-name-menu10">Bottom 10 Recursos</p>
                    </li>
                </a>
                <a class="das-item-menu11" href="#">
                    <li id="das-item-menu11"><img src="assets/img/tempo.png" alt="Imagem de item do menu" class="das-image-menu">
                        <p id="das-name-menu11">Tempo Médio no Curso</p>
                    </li>
                </a>
                <a class="das-item-menu12" href="#">
                    <li id="das-item-menu12"><img src="assets/img/report.png" alt="Imagem de item do menu" class="das-image-menu">
                        <p id="das-name-menu12">Relatório Geral das Atividades</p>
                    </li>
                </a>
            </ul>

            <div class="das-select-buttons">
                <div class="das-select-buttons-division">
                    <a href="#" class="das-check-buttom">
                        <div id="das-check" alt="Select All"></div>
                        <p id="das-check-text">Selecionar Tudo</p>
                    </a>
                </div>
                <div class="das-select-buttons-division">
                    <a href="#" class="das-uncheck-buttom">
                        <div id="das-uncheck" alt="Deselect All"></div>
                        <p id="das-uncheck-text">Deselecionar Tudo</p>
                    </a>
                </div>
            </div>
        </div>



        <div id="das-user-column-1">
            <div id="das-activity-deliver">
                <img class="das-gear" src="assets/img/engrenagemcinza.png" alt="das-gear-img">
                <p class="das-title">Atividades Entregues </p>

                <p class="das-subtitle">Educação</p>

                <div class="das-item-default">
                    <div class="das-item-default-header">
                        <img class="das-activity-deliver-img das-vertical-align" src="assets/img/postlaranja.png" alt="activity-img">
                        <p class="das-vertical-align das-p-overflow">Atividades Texto Online</p>
                        <div class="das-activity-number">
                            <div style="">
                                <p>5</p>
                            </div>
                        </div>
                    </div>
                    <div class="das-item-default-expansive">
                        <img class="das-user-small-image das-vertical-align" src="assets/img/rosto1.jpg" alt="User-Image">
                        <p class="das-vertical-align das-p-overflow">Anita Raquel da Silva</p>
                        <img class="das-message-icon" src="assets/img/msg.png" alt="Message-Image">
                    </div>                    
                </div>

                <div class="das-item-default-header">
                    <img class="das-activity-deliver-img das-vertical-align" src="assets/img/postlaranja.png" alt="activity-img">
                    <p class="das-vertical-align das-p-overflow">Integração de Mídias da Educação</p>
                    <div class="das-activity-number">
                        <div style="">
                            <p>8</p>
                        </div>
                    </div>
                </div>

                <div class="das-item-default-header">
                    <img class="das-activity-deliver-img das-vertical-align" src="assets/img/postlaranja.png" alt="activity-img">
                    <p class="das-vertical-align das-p-overflow">Fórum Geral - Atualizado</p>
                    <div class="das-activity-number">
                        <div style="">
                            <p>1</p>
                        </div>
                    </div>
                </div>

                <p class="das-subtitle"> Integração de Mídias na Educação</p>

                <div class="das-item-default-header">
                    <img class="das-activity-deliver-img das-vertical-align" src="assets/img/postlaranja.png" alt="activity-img">
                    <p class="das-vertical-align das-p-overflow">Atividade 1</p>
                    <div class="das-activity-number">
                        <div style="">
                            <p>4</p>
                        </div>
                    </div>
                </div>

                <div class="das-item-default-header">
                    <img class="das-activity-deliver-img das-vertical-align" src="assets/img/postlaranja.png" alt="activity-img">
                    <p class="das-vertical-align das-p-overflow">Atividade 2</p>
                    <div class="das-activity-number">
                        <div style="">
                            <p>3</p>
                        </div>
                    </div>
                </div>

                <div class="das-item-default-header">
                    <img class="das-activity-deliver-img das-vertical-align" src="assets/img/postlaranja.png" alt="activity-img">
                    <p class="das-vertical-align das-p-overflow">Atividade 3</p>
                    <div class="das-activity-number">
                        <div style="">
                            <p>7</p>
                        </div>
                    </div>
                </div>
            </div>


            <div id="das-preventive-notice">
                <img class="das-gear" src="assets/img/engrenagemcinza.png" alt="das-gear-img">
                <p class="das-title">Aviso Preventivo</p>

                <p class="das-subtitle">Educação</p>

                <div class="das-item-default-header">
                    <img class="das-activity-deliver-img das-vertical-align" src="assets/img/postlaranja.png" alt="activity-img">
                    <p class="das-vertical-align das-p-overflow">Atividade Texto Online</p>
                    <div class="das-activity-number">
                        <div style="">
                            <p>5</p>
                        </div>
                    </div>
                </div>

                <p class="das-subtitle"> Interação Mídias da Educação</p>

                <div class="das-item-default-header">
                    <img class="das-activity-deliver-img das-vertical-align" src="assets/img/postlaranja.png" alt="activity-img">
                    <p class="das-vertical-align das-p-overflow">Atividade 1</p>
                    <div class="das-activity-number">
                        <div style="">
                            <p>4</p>
                        </div>
                    </div>
                </div>

                <div class="das-item-default-header">
                    <img class="das-activity-deliver-img das-vertical-align" src="assets/img/postlaranja.png" alt="activity-img">
                    <p class="das-vertical-align das-p-overflow">Atividade 2</p>
                    <div class="das-activity-number">
                        <div style="">
                            <p>9</p>
                        </div>
                    </div>
                </div>
                
                <div class="das-item-default-header">
                    <img class="das-activity-deliver-img das-vertical-align" src="assets/img/postlaranja.png" alt="activity-img">
                    <p class="das-vertical-align das-p-overflow">Atividade 3</p>
                    <div class="das-activity-number">
                        <div style="">
                            <p>1</p>
                        </div>
                    </div>
                </div>
                
                <div class="das-expansive-more">
                    <p class="das-subtitle">mais...</p>
                </div>
            </div>


            <div id="das-on-time">

                <p class="das-title"> Dentro do Prazo</p>

                <p class="das-subtitle">Integração Mídias da Educação</p>

                <div class="das-item-default-header">
                    <img class="das-activity-deliver-img das-vertical-align" src="assets/img/postlaranja.png" alt="activity-img">
                    <p class="das-vertical-align das-p-overflow">Atividade 1</p>
                    <div class="das-activity-number">
                        <div style="">
                            <p>1</p>
                        </div>
                    </div>
                </div>

            </div>

            <div id="das-out-of-time">
                <p class="das-title">Fora do Prazo</p>
                <p class="das-subtitle"> Educação</p>
                <div class="das-item-default-header">
                    <img class="das-activity-deliver-img das-vertical-align" src="assets/img/postlaranja.png" alt="activity-img">
                    <p class="das-vertical-align das-p-overflow">Atividade Texto Online</p>
                    <div class="das-activity-number">
                        <div style="">
                            <p>4</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="das-remail">
                <p class="das-title"> Reenvio de Atividade</p>
                <p class="das-subtitle">Educação</p>
                <div class="das-item-default-header">
                    <img class="das-activity-deliver-img das-vertical-align" src="assets/img/postlaranja.png" alt="activity-img">
                    <p class="das-vertical-align das-p-overflow">Atividade 1</p>
                    <div class="das-activity-number">
                        <div style="">
                            <p>1</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div id="das-user-column-2">
            <div id="das-online-users-box">
                <p class="das-title">Usuários Online</p>
                <!-- Alterar String!!! -->

                <!-- Loop com resultado da query para usuários online -->
                
                <?php
                $onlineusers = usuarios_online();
                foreach($onlineusers AS $user) {
                ?>

                <div class="das-item-default-header">
                    <img class="das-user-small-image das-vertical-align" src="assets/img/rosto1.jpg" alt="User-Image">
                    <p class="das-vertical-align das-p-overflow"><?php echo "$user->firstname $user->lastname"; ?></p>
                    <img class="das-message-icon" src="assets/img/msg.png" alt="Message-Image">
                </div>
                <?php
                }
                ?>

            
                <div class="das-item-default-header">
                    <img class="das-user-small-image das-vertical-align" src="assets/img/rosto2.jpg" alt="User-Image">
                    <p class="das-vertical-align das-p-overflow">Givaldo Batista Medeiros</p>
                    <img class="das-message-icon" src="assets/img/msg.png" alt="Message-Image">
                </div>
                
                <div class="das-box-buttons">

                </div>
            </div>

            <div id="das-missing-users-box">
                <img class="das-gear" src="assets/img/engrenagemcinza.png" alt="das-gear-img">
                <p class="das-title">Usuários Ausentes</p>
                <!-- Alterar String!!! -->

                <!-- Aqui já fazer a query para verificar quais das DIVs são úteis -->
                
                <?php printmissingusers(0, 6);?>
                 <!-- Modelo para as outras consultas de ausências -->
                 <div class="das-missing-users-period">
                    <p class="das-subtitle">Entre 3 e 6 dia
                    <?php
                    $das_missing_users = missingusers($onlineusers, 0, 6);
                    $color = 1;
                    foreach($das_missing_users As $user){
                        if($color++ % 2) {
                           ?> <div class="das-missing-user-color-grey"> <?php
                        } 
                        else {
                           ?> <div class="das-missing-user-color-white"> <?php 
                        }       
                        ?>
                                
                       <img class="das-user-small-image" src="assets/img/rosto1.jpg" alt="User-Image">
                       <p class="das-vertical-align das-p-overflow"><?php echo "$user->firstname $user->lastname"?></p>
                        <div class="das-missing-user-days-white">
                            <div style="width: 18px;text-align: center;" n><?php echo "$user->days"?></div>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </div>
                <!-- Fim do modelo -->
                
                <div class="das-missing-users-period">
                    <p class="das-subtitle">Entre 7 e 10 dias</p>
                    <?php
                    $das_missing_users = missingusers($onlineusers, 2, 6);
                    foreach($das_missing_users As $users){
                    ?>
                    <div class="das-missing-user-color-white">
                       <img class="das-user-small-image" src="assets/img/rosto2.jpg" alt="User-Image">
                       <p class="das-vertical-align das-p-overflow"><?php echo "$user->firstname"?></p>
                        <div class="das-missing-user-days-grey">
                            <div style="width: 18px;text-align: center;" n>7</div>
                        </div>
                    </div>
                    
                    <div class="das-missing-user-color-grey">
                       <img class="das-user-small-image" src="assets/img/rosto3.jpg" alt="User-Image">
                       <p class="das-vertical-align das-p-overflow"><?php echo "$user->firstname"?></p>
                        <div class="das-missing-user-days-white">
                            <div style="width: 18px;text-align: center;">10</div>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </div>
                
                <div class="das-missing-users-period">
                    <p class="das-subtitle">Entre 11 e 60 dias</p>
                    <?php
                    $das_missing_users = missingusers($onlineusers, 2, 6);
                    foreach($das_missing_users As $users){
                    ?>
                    <div class="das-missing-user-color-white">
                       <img class="das-user-small-image" src="assets/img/rosto4.jpg" alt="User-Image">
                       <p class="das-vertical-align das-p-overflow"><?php echo "$user->firstname"?></p>
                        <div class="das-missing-user-days-grey">
                            <div style="width: 18px;text-align: center;">15</div>
                        </div>
                    </div>

                    <div class="das-missing-user-color-grey">
                       <img class="das-user-small-image" src="assets/img/rosto5.jpg" alt="User-Image">
                       <p class="das-vertical-align das-p-overflow"><?php echo "$user->firstname"?></p>
                        <div class="das-missing-user-days-white">
                            <div style="width: 18px;text-align: center;">15</div>
                        </div>
                    </div>
                    <?php
                    }   
                    ?>
                </div>
                <div class="das-missing-users-period">
                    <p class="das-subtitle">Mais de 60 dias</p>
                    <?php
                    $das_missing_users = missingusers($onlineusers, 2, 6);
                    foreach($das_missing_users As $users){
                    ?>
                    <div class="das-missing-user-color-white">
                       <img class="das-user-small-image" src="assets/img/rosto6.jpg" alt="User-Image">
                       <p class="das-vertical-align das-p-overflow"><?php echo "$user->firstname"?></p>
                        <div class="das-missing-user-days-grey">
                            <div style="width: 18px;text-align: center;">65</div>
                        </div>
                    </div>

                    <div class="das-missing-user-color-grey">
                        <img class="das-user-small-image" src="assets/img/rosto7.jpg" alt="User-Image">
                        <p class="das-vertical-align das-p-overflow"><?php echo "$user->firstname"?></p>
                        <div class="das-missing-user-days-white">
                            <div style="width: 18px;text-align: center;">85</div>
                        </div>
                    </div>
                    <?php
                    }   
                    ?>
                </div>

                <div class="das-box-buttons">

                </div>
            </div>
        </div>
        <div id="das-user-column-3">
            <div id="das-status-class">
                <img class="das-gear" src="assets/img/engrenagemcinza.png" alt="das-gear-img">
                <p class="das-title"> Status da Turma</p>
                <img class="das-status-class-graphic" src="assets/img/acesso-alunos.png" alt="status-class">
            </div>

            <div id="das-acess-activity">
                <p class="das-title"> Acesso por Atividade</p>
                <img class="das-acess-activity-graphic" src="assets/img/interacao-por-topico-na-turma.png" alt="acess-activity">
            </div>

            <div id="das-resources">
                <p class="das-title"> Recursos</p>
                <p class="das-top-bottom">Top 10 x Bottom 10</p>
                <img class="das-resources-graphic" src="assets/img/recursos.png" alt="resources">

            </div>

            <div id="das-permanence-course">
                <p class="das-title"> Tempo Médio no Curso </p>
                <img class="das-permanence-course-graphic" src="assets/img/permanencia-no-curso.png" alt="permanence-course">
            </div>
        </div>
    </div>
</body>
</html>

<?php
function printmissingusers($lowboundary, $highboundary) {
   ?>
   <div class="das-missing-users-period">
   <p class="das-subtitle">Entre 3 e 6 dia
   <?php
   $das_missing_users = missingusers($onlineusers, $lowboundary, $highboundary);
   $color = 1;
   foreach($das_missing_users As $user){
       if($color++ % 2) {
           ?> <div class="das-missing-user-color-grey"> <?php
       } 
       else {
           ?> <div class="das-missing-user-color-white"> <?php 
       }       
       ?>                     
       <img class="das-user-small-image" src="assets/img/rosto1.jpg" alt="User-Image">
       <p class="das-vertical-align das-p-overflow"><?php echo "$user->firstname $user->lastname"?></p>
       <div class="das-missing-user-days-white">
       <div style="width: 18px;text-align: center;" n><?php echo "$user->days"?></div>
       </div>
       </div>
       <?php
       }
       ?>
       </div>
    <?php           
    }
}
?>
