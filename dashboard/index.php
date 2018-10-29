<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
require_once("../../../config.php");
require('../lib.php');
$courseid = required_param('id', PARAM_INT);
require_login($courseid);
$context = context_course::instance($courseid);
require_capability('block/analytics_graphs:viewpages', $context);
$courseusers = das_course_users($courseid);
?>
<!DOCTYPE html>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script>
        $(function() {
            var menu = Array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
            var blockNames = Array("das-activity-deliver", "das-preventive-notice", "das-on-time", "das-out-of-time", "das-remail",
                                    "das-online-users-box", "das-missing-users-box", "das-status-class", "das-acess-activity",
                                    "das-resources", "das-permanence-course");
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
    $(document).ready(function(){
                $(".das-activity-number").click(function(){
                $(".das-item-default-expansive").toggle();
                });

                $("p.das-p-overflow").click(function(){
                $(".das-item-default-expansive").toggle();
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
                    <li id="das-item-menu1"><img src="assets/img/repostagem.png" alt="Imagem de item do menu"
                        class="das-image-menu">
                        <p id="das-name-menu1">Repostagem da atividade</p>
                    </li>
                </a>
                <a class="das-item-menu2" href="#">
                    <li id="das-item-menu2"><img src="assets/img/postagensadiantadas.png" alt="Imagem de item do menu"
                        class="das-image-menu">
                        <p id="das-name-menu2">Postagens Antecipadas</p>
                    </li>
                </a>
                <a class="das-item-menu3" href="#">
                    <li id="das-item-menu3"><img src="assets/img/svisopreventivo.png" alt="Imagem de item do menu"
                        class="das-image-menu">
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
                <p class="das-title">Atividades Entregues </p>
                <p class="das-subtitle"><b>Educação</b></a>
                <div class="das-item-default">
                    <div class="das-item-default-header">
                        <img class="das-activity-deliver-img das-vertical-align" src="assets/img/postlaranja.png"
                            alt="activity-img">
                        <p class="das-vertical-align das-p-overflow">Atividades Texto Online</p>
                        <div class="das-activity-number">
                            <div style="">
                                <p>5</p>
                            </div>
                        </div>
                    </div>

                   <div class="das-item-default-expansive " style="display: none">
                        <img class="das-user-small-image das-vertical-align" src="assets/img/rosto1.jpg" alt="User-Image">
                        <p class="das-vertical-align das-p-overflow">Anita Raquel da Silva</p>
                        <img class="das-message-icon" src="assets/img/msg.png" alt="Message-Image">
                    </div>
                    <div class="das-item-default-expansive " style="display: none">
                        <img class="das-user-small-image das-vertical-align" src="assets/img/rosto1.jpg" alt="User-Image">
                        <p class="das-vertical-align das-p-overflow">Anita Raquel da Silva</p>
                        <img class="das-message-icon" src="assets/img/msg.png" alt="Message-Image">
                    </div>
                    <div class="das-item-default-expansive " style="display: none">
                        <img class="das-user-small-image das-vertical-align" src="assets/img/rosto1.jpg" alt="User-Image">
                        <p class="das-vertical-align das-p-overflow">Anita Raquel da Silva</p>
                        <img class="das-message-icon" src="assets/img/msg.png" alt="Message-Image">
                    </div>
                </div>
                
                <div class="das-item-default">
                    <div class="das-item-default-header">
                        <img class="das-activity-deliver-img das-vertical-align" src="assets/img/postlaranja.png" alt="activity-img">
                        <p class="das-vertical-align das-p-overflow">Integração de Mídias da Educação</p>
                        <div class="das-activity-number">
                            <div style="">
                                <p>8</p>
                            </div>
                        </div>
                    </div>
                    <div class="das-item-default-expansive " style="display: none">
                        <img class="das-user-small-image das-vertical-align" src="assets/img/rosto1.jpg" alt="User-Image">
                        <p class="das-vertical-align das-p-overflow">Anita Raquel da Silva</p>
                        <img class="das-message-icon" src="assets/img/msg.png" alt="Message-Image">
                    </div>
                    <div class="das-item-default-expansive " style="display: none">
                        <img class="das-user-small-image das-vertical-align" src="assets/img/rosto1.jpg" alt="User-Image">
                        <p class="das-vertical-align das-p-overflow">Anita Raquel da Silva</p>
                        <img class="das-message-icon" src="assets/img/msg.png" alt="Message-Image">
                    </div>
                    <div class="das-item-default-expansive " style="display: none">
                        <img class="das-user-small-image das-vertical-align" src="assets/img/rosto1.jpg" alt="User-Image">
                        <p class="das-vertical-align das-p-overflow">Anita Raquel da Silva</p>
                        <img class="das-message-icon" src="assets/img/msg.png" alt="Message-Image">
                    </div>
                </div>
            </div>
 

            <div id="das-preventive-notice">
                <p class="das-title">Aviso Preventivo</p>
                <p class="das-subtitle">Educação<a href="das-message.php"><img src="assets/img/email.jpeg" alt="img-activity-email" style="height: 20px;width: 20px;position: absolute;right: 12px;"></a></p>
                <div class="das-item-default-header">
                    <img class="das-activity-deliver-img das-vertical-align" src="assets/img/postlaranja.png" alt="activity-img">
                    <p class="das-vertical-align das-p-overflow">Atividade Texto Online</p>
                    <div class="das-activity-number">
                        <div style="">
                            <p>5</p>
                        </div>
                    </div>
                </div>
                <p class="das-subtitle"> Interação Mídias da Educação<a href="das-message.php"><img src="assets/img/email.jpeg" alt="img-activity-email" style="height: 20px;width: 20px;position: absolute;right: 12px;"></a></p>
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
        </div>


<?php
    $activities = das_activities($courseusers);
            das_print_delivered_assigns($courseusers);
    das_print_ontime_assign($activities);
?>

            <div id="das-remail">
                <p class="das-title"> Reenvio de Atividade</p>
                <p class="das-subtitle">Educação<a href="das-message.php"><img src="assets/img/email.jpeg" alt="img-activity-email" style="height: 20px;width: 20px;position: absolute;right: 12px;"></a>
</p>
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
                <p class="das-title"><?php echo get_string('todayusers', 'block_das');?></p>
                <?php
                das_print_today_users($courseusers);
                ?>
                <div class="das-box-buttons">
                </div>
            </div>
            <div id="das-missing-users-box">
                <p class="das-title"><?php echo get_string('missingusers', 'block_das');?></p>
                <?php
                das_print_missing_users($courseusers, 0, 6);
                das_print_missing_users($courseusers, 0, 10);
                das_print_missing_users($courseusers, 0, 60);
                das_print_missing_users($courseusers, 60, 10000);
                ?>
                <div class="das-box-buttons">
                </div>
            </div>
        </div>


        <div id="das-user-column-3">
            <div id="das-status-class">
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
