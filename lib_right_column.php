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
global $DB;
$courseid = required_param('id', PARAM_INT);
$context = context_course::instance($courseid);
require_capability('block/analytics_graphs:viewpages', $context);
$courseusers = das_course_users($courseid);
$course = $DB->get_record("course", array("id" => $courseid), '*', MUST_EXIST);
$mintime = $course->startdate;
$maxtime = time();
require_once('../dedication_lib.php');
$dm = new block_dedication_manager($course, $mintime, $maxtime, 3600);
$rows = $dm->get_students_dedication($courseusers);
$totaldedication = 0;
$numberofstudents = 0;
foreach ($rows AS $student) {
	$totaldedication += $student->dedicationtime;
	$numberofstudents++;
}
$dedicationratio = $totaldedication / $numberofstudents;
$numberofweeks = ($maxtime - $mintime) / (7*24*3600);
$dedicationbyweek = $dedicationratio / $numberofweeks;
$dedicationbyweek = $dedicationbyweek / 3600;


<!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});



<!-- Draw pie graph -->
      google.charts.setOnLoadCallback(drawPie);

      function drawPie() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Ativo', <?php echo count(das_missing_users($courseusers, 0, 6));?>],
          ['Ausente', <?php echo count(das_missing_users($courseusers, 7, 29));?>],
          ['Abandono', <?php echo count(das_missing_users($courseusers, 30, 10000));?>]
        ]);
        var options = {
                       'width':330,
			slices: {
            			0: { color: 'green' },
            			1: { color: 'orange' },
            			2: { color: 'red' }
          		},
			legend: { position: 'top', alignment: 'center'},
			pieSliceText: 'percent'
		};

        var chart = new google.visualization.PieChart(document.getElementById('pie_div'));
        chart.draw(data, options);
      }
    </script>

    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawStuff);

<?php
	global $DB;
	$params = array('194');
	$sql = "SELECT cs.section section, count(*) a
		FROM {logstore_standard_log} log
		LEFT JOIN {course_modules} cm on cm.id = log.contextinstanceid
		LEFT JOIN {course_sections} cs on cs.id = cm.section
		WHERE component = 'mod_assign' AND courseid = '194' AND (action = 'viewed' OR action = 'submitted')
		GROUP BY cs.section";
	$result = $DB->get_records_sql($sql, $params);
?>
      function drawStuff() {
        var data = new google.visualization.arrayToDataTable([
          ['Tópico', 'Acessos'],
<?php
	$numberofsections = 0;
	foreach($result AS $tupla) {
		echo '["' . $tupla->section . '",' . $tupla->a . '],';
		$numberofsections++;
	}
	$height = 50*$numberofsections;
?> 
        ]);

        var options = {
          title: 'Chess opening moves',
          width: 300,
	  height: <?php echo $height;?>,
          legend: { position: 'none' },
          chart: { title: '',
                   subtitle: '' },
          bars: 'horizontal', // Required for Material Bar Charts.
          axes: {
            x: {
              0: { side: 'top', label: 'Acessos'} // Top x-axis.
            }
          },
          bar: { groupWidth: "90%" }
        };

        var chart = new google.charts.Bar(document.getElementById('top_x_div'));
        chart.draw(data, options);
      };
    </script>


    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawColumn);

      function drawColumn() {
	<?php
	$modinfo = get_fast_modinfo(194);
       	global $DB;
       	$params = array('194');
       	$sql = "SELECT cm.id id, count(*) a
               	FROM {logstore_standard_log} log
               	LEFT JOIN {course_modules} cm on cm.id = log.contextinstanceid
               	WHERE course = '194' AND component <> 'core' AND component <> 'mod_assign' AND action = 'viewed'
               	GROUP BY cm.id
               	ORDER BY a DESC";
       	$result = $DB->get_records_sql($sql, $params);
	foreach($result AS $tupla) {
		$resources[$tupla->id] = $tupla->a;
	}
	$reversed = array_reverse($resources,true);
	?>
        var data = new google.visualization.DataTable();
	data.addColumn('string', 'Recurso');
	data.addColumn('number', 'Acessos');
	data.addRows([
		<?php
		$maxcolumns = 10;
		foreach($resources AS $id => $value) {
			$cm = $modinfo->get_cm($id);
                	echo '["' . $cm->name . '",' . $value . '],';
			if(--$maxcolumns == 0) {
				break;
			}
		}
		?>
        ]);

        var options = {
          chart: {
            title: '',
            subtitle: '',
          },
	  hAxis: {textPosition: 'none'},
	  legend: { position: 'none' },
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('chart_column'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>

    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawReversed);

      function drawReversed() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Recurso');
        data.addColumn('number', 'Acessos');
	data.addColumn({type: 'string', role: 'style'});
        data.addRows([
                <?php
		$maxcolumns = 10; 
                foreach($reversed AS $id => $value) {
                        $cm = $modinfo->get_cm($id);
                        echo '["' . $cm->name . '",' . $value . ', "red"],';
			 if(--$maxcolumns == 0) {
                                break;
                        }
                }
                ?>
        ]);

        var options = {
          chart: {
            title: '',
            subtitle: '',
          },
          hAxis: {textPosition: 'none'},
          legend: { position: 'none' },
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('chart_reversed'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>



   <script type="text/javascript">
      google.charts.load('current', {'packages':['gauge']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Horas', <?php echo $dedicationbyweek ?>],
        ]);

        var options = {
          width: 250, height: 250,
          redFrom: 0, redTo: 2,
          yellowFrom:2, yellowTo: 6,
          greenFrom:6, greenTo: 20,
          majorTicks: ['0','5','10','15','20'],
	  minorTicks: 5,
	  max: 20
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_gauge'));

        chart.draw(data, options);

      }
    </script>


