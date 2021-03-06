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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.0/css/all.css" integrity="sha384-aOkxzJ5uQz7WBObEZcHvV5JvRW3TUc2rNPA7pe3AwnsUohiw1Vj2Rgx2KSOkF5+h" crossorigin="anonymous">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dash Fluxo de Caixas</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <?php include("../lib_right_column.php"); ?>
    <script>
        $(function() {
            var menu = Array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
            var blockNames = Array("das-alerts", "das-resubmissions", "das-intimesubmissions", "das-latesubmissions", "das-nosubmissions",
                                    "das-online-users-box", "das-missing-users-box", "das-status-class", "das-acess-activity", "das-resources",
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
    /*$(document).ready(function(){
                $(".das-activity-number").click(function(){
                $(".das-item-default-expansive").toggle();
                });

                $("p.das-p-overflow").click(function(){
                $(".das-item-default-expansive").toggle();
                });
     });*/

    </script>
</head>
<body>
    <div class="row" style="margin-left: 0; margin-right: 0;">
        <div id="das-col-menu">
            <ul id="das-menu-vertical">
                <a class="das-item-menu0" href="#">
                    <li id="das-item-menu0"><img src="assets/img/tempo.png" alt="Imagem de item do menu" class="das-image-menu">
                        <p id="das-name-menu0">Aviso Preventivo</p>
                    </li>
                </a>
                <a class="das-item-menu1" href="#">
                    <li id="das-item-menu1"><img src="assets/img/repostagem.png" alt="Imagem de item do menu"
                        class="das-image-menu">
                        <p id="das-name-menu1">Aviso de Reenvio</p>
                    </li>
                </a>
                <a class="das-item-menu2" href="#">
                    <li id="das-item-menu2"><img src="assets/img/postagensadiantadas.png" alt="Imagem de item do menu"
                        class="das-image-menu">
                        <p id="das-name-menu2">Entregue no Prazo</p>
                    </li>
                </a>
                <a class="das-item-menu3" href="#">
                    <li id="das-item-menu3"><img src="assets/img/svisopreventivo.png" alt="Imagem de item do menu"
                        class="das-image-menu">
                        <p id="das-name-menu3">Entregue Fora do Prazo</p>
                    </li>
                </a>
                <a class="das-item-menu4" href="#">
                    <li id="das-item-menu4"><img src="assets/img/post.png" alt="Imagem de item do menu" class="das-image-menu">
                        <p id="das-name-menu4">Não Entregue</p>
                    </li>
                </a>
                <a class="das-item-menu5" href="#">
                    <li id="das-item-menu5"><img src="assets/img/globo.png" alt="Imagem de item do menu" class="das-image-menu">
                        <p id="das-name-menu5">Usuários Ativos no Dia</p>
                    </li>
                </a>
                <a class="das-item-menu6" href="#">
                    <li id="das-item-menu6"><img src="assets/img/evasão.png" alt="Imagem de item do menu" class="das-image-menu">
                        <p id="das-name-menu6">Usuários Ausentes</p>
                    </li>
                </a>
                <a class="das-item-menu7" href="#">
                    <li id="das-item-menu7"><img src="assets/img/status.png" alt="Imagem de item do menu" class="das-image-menu">
                        <p id="das-name-menu7">Status da Turma</p>
                    </li>
                </a>
                <a class="das-item-menu8" href="#">
                    <li id="das-item-menu8"><img src="assets/img/topicos.png" alt="Imagem de item do menu" class="das-image-menu">
                        <p id="das-name-menu8">Acesso por Atividade</p>
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
                        <p id="das-name-menu11">Tempo Médio por semana</p>
                    </li>
                </a>
                <a class="das-item-menu12" href="das-general-report.php">
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

    <?php
    $activities = das_activities($courseusers);

    das_print_second_column_window($courseusers, $activities,'alerts','numberofalerts');
    das_print_second_column_window($courseusers, $activities,'resubmissions','numberofresubmissions');
    das_print_second_column_window($courseusers, $activities,'intimesubmissions','numberofintimesubmissions');
    das_print_second_column_window($courseusers, $activities,'latesubmissions','numberoflatesubmissions');
    das_print_second_column_window($courseusers, $activities,'nosubmissions','numberofnosubmissions');

    ?>



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

                $blockrecord = $DB->get_record('block_instances', array('blockname' => 'das',
                    'parentcontextid' => $context->id), '*', MUST_EXIST);
                $block = block_instance('das', $blockrecord);

                das_print_missing_users($courseusers, $block->config->beginoffirstgap, $block->config->beginofsecondgap - 1);
                das_print_missing_users($courseusers, $block->config->beginofsecondgap, $block->config->beginofthirdgap - 1);
                das_print_missing_users($courseusers, $block->config->beginofthirdgap, $block->config->beginofforthgap - 1);
                das_print_missing_users($courseusers, $block->config->beginofforthgap, 10000);
                ?>
                <div class="das-box-buttons">
                </div>
            </div>
        </div>


        <div id="das-user-column-3">
            <div id="das-status-class">
                <p class="das-title"> Status da Turma</p>
    		<div id="pie_div"></div>
            </div>
            <div id="das-acess-activity">
                <p class="das-title"> Acesso por Atividade</p>
		<div id="top_x_div"></div>
            </div>
            <div id="das-resources">
                <p class="das-title"> Recursos</p>
                <p class="das-top-bottom">Top 10 x Bottom 10</p>
		<div id="chart_column"></div>
		<div id="chart_reversed"></div>
            </div>
            <div id="das-permanence-course">
                <p class="das-title"> Tempo Médio por Semana </p>
		<div id="chart_gauge"></div>
            </div>
        </div>
    </div>
</body>
</html>
