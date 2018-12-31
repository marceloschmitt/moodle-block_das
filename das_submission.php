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
defined('MOODLE_INTERNAL') || die();
class das_submission {
    private $context;
    private $course;
    private $statistics;
    public function __construct($course) {
        $this->course = $course;
        // Control access.
       // require_login($course);
        $this->context = context_course::instance($course);
        require_capability('block/das:viewpages', $this->context);
        $courseparams = get_course($course);
    }
    public function get_course() {
        return $this->course;
    }
    public function get_statistics() {
        return $this->statistics;
    }
    public function create_array($result, $students) {
        if (empty($result)) {
            exit;
        }
        $numberofstudents = count($students);
        if ($numberofstudents == 0) {
            echo(get_string('no_students', 'block_analytics_graphs'));
            exit;
        }
        foreach ($students as $tuple) {
            $arrayofstudents[] = array('userid' => $tuple->id ,
                'nome' => $tuple->firstname.' '.$tuple->lastname, 'email' => $tuple->email);
        }
        $counter = 0;
        $numberofintimesubmissions = 0;
        $numberoflatesubmissions = 0;
        $assignmentid = 0;
        foreach ($result as $tuple) {
            $temparray = array(
                        'userid' => $tuple->userid,
                        'nome' => $tuple->firstname." ".$tuple->lastname,
                        'email' => $tuple->email,
                        'submissiontime' => $tuple->submissiontime,
                        'gradetime' => $tuple->gradetime);
            if ($assignmentid == 0) { // First time in loop.
                $this->statistics[$counter]['assign'] = $tuple->name;
                if($tuple->sectionname) { //Section name.
                    $this->statistics[$counter]['sectionname'] = $tuple->sectionname;
                } else {
                    $this->statistics[$counter]['sectionname'] = $tuple->sectionnumber;
                }
                $this->statistics[$counter]['duedate'] = $tuple->duedate;
                $this->statistics[$counter]['cutoffdate'] = $tuple->cutoffdate;
                if (isset($tuple->userid)) { // If a student submitted.
                    if ($tuple->duedate >= $tuple->submissiontime || $tuple->duedate == 0) { // In the right time.
                        $this->statistics[$counter]['in_time_submissions'][] = $temparray;
                        $numberofintimesubmissions++;
                    } else { // Late.
                        $this->statistics[$counter]['latesubmissions'][] = $temparray;
                        $numberoflatesubmissions++;
                    }
                }
                $assignmentid = $tuple->assignment;
            } else { // Not first time in loop.
                if ($assignmentid == $tuple->assignment and $tuple->userid) { // Same task -> add student.
                    if ($tuple->duedate >= $tuple->submissiontime || $tuple->duedate == 0) { // Right time.
                        $this->statistics[$counter]['in_time_submissions'][] = $temparray;
                        $numberofintimesubmissions++;
                    } else { // Late.
                        $this->statistics[$counter]['latesubmissions'][] = $temparray;
                        $numberoflatesubmissions++;
                    }
                }
                if ($assignmentid != $tuple->assignment) { // Another task -> finish previous and start.
                    $this->statistics[$counter]['numberofintimesubmissions'] = $numberofintimesubmissions;
                    $this->statistics[$counter]['numberoflatesubmissions'] = $numberoflatesubmissions;
                    $this->statistics[$counter]['numberofnosubmissions'] =
                            $numberofstudents - $numberofintimesubmissions - $numberoflatesubmissions;
                    if ($this->statistics[$counter]['numberofnosubmissions'] > 0) {
                        if ($this->statistics[$counter]['numberofnosubmissions'] == $numberofstudents) {
                            $this->statistics[$counter]['no_submissions'] = $arrayofstudents;
                        } else if ($numberoflatesubmissions == 0) {
                            $this->statistics[$counter]['no_submissions'] =
                                das_subtract_student_arrays($arrayofstudents,
                                $this->statistics[$counter]['in_time_submissions']);
                        } else if ($numberofintimesubmissions == 0) {
                            $this->statistics[$counter]['no_submissions'] =
                                das_subtract_student_arrays($arrayofstudents,
                                $this->statistics[$counter]['latesubmissions']);
                        } else {
                            $this->statistics[$counter]['no_submissions'] =
                                das_subtract_student_arrays(
                                das_subtract_student_arrays($arrayofstudents,
                                    $this->statistics[$counter]['in_time_submissions']),
                                $this->statistics[$counter]['latesubmissions']);
                        }
                    }
                    $counter++;
                    $numberofintimesubmissions = 0;
                    $numberoflatesubmissions = 0;
                    $this->statistics[$counter]['assign'] = $tuple->name;
                    if($tuple->sectionname) {
                        $this->statistics[$counter]['sectionname'] = $tuple->sectionname;
                    } else {
                        $this->statistics[$counter]['sectionname'] = $tuple->sectionnumber;
                    }
                    $this->statistics[$counter]['duedate'] = $tuple->duedate;
                    $this->statistics[$counter]['cutoffdate'] = $tuple->cutoffdate;
                    $assignmentid = $tuple->assignment;
                    if ($tuple->userid) { // If a user has submitted
                         if ($tuple->duedate >= $tuple->submissiontime || $tuple->duedate == 0) { // Right time.
                            $this->statistics[$counter]['in_time_submissions'][] = $temparray;
                            $numberofintimesubmissions = 1;
                        } else { // Late.
                            $this->statistics[$counter]['latesubmissions'][] = $temparray;
                            $numberoflatesubmissions = 1;
                        }
                    }
                }
            }
        }
        // Finishing of last access.

        $this->statistics[$counter]['numberofintimesubmissions'] = $numberofintimesubmissions;
        $this->statistics[$counter]['numberoflatesubmissions'] = $numberoflatesubmissions;
        $this->statistics[$counter]['numberofnosubmissions'] = $numberofstudents - $numberofintimesubmissions -
            $numberoflatesubmissions;
        if ($this->statistics[$counter]['numberofnosubmissions'] > 0) {
            if ($this->statistics[$counter]['numberofnosubmissions'] == $numberofstudents) {
                $this->statistics[$counter]['no_submissions'] = $arrayofstudents;
            } else if ($numberoflatesubmissions == 0) {
                $this->statistics[$counter]['no_submissions'] =
                    das_subtract_student_arrays($arrayofstudents,
                    $this->statistics[$counter]['in_time_submissions']);
            } else if ($numberofintimesubmissions == 0) {
                $this->statistics[$counter]['no_submissions'] =
                    das_subtract_student_arrays($arrayofstudents,
                    $this->statistics[$counter]['latesubmissions']);
            } else {
                $this->statistics[$counter]['no_submissions'] =
                    das_subtract_student_arrays(
                    das_subtract_student_arrays($arrayofstudents,
                        $this->statistics[$counter]['in_time_submissions']),
                    $this->statistics[$counter]['latesubmissions']);
            }
        }
        foreach ($this->statistics as $tuple) {
            $arrayofassignments[] = $tuple['assign'];
            $arrayofintimesubmissions[] = $tuple['numberofintimesubmissions'];
            $arrayoflatesubmissions[] = $tuple['numberoflatesubmissions'];
            $arrayofnosubmissions[] = $tuple['numberofnosubmissions'];
            $arrayofduedates[] = $tuple['duedate'];
            $arrayofcutoffdates[] = $tuple['cutoffdate']; // For future use.
        }
        return($this->statistics);
    }

    function get_array() {
        return $this->statistics;
    }

}

function das_subtract_student_arrays($estudantes, $acessaram) {
    $resultado = array();
    foreach ($estudantes as $estudante) {
        $encontrou = false;
        foreach ($acessaram as $acessou) {
            if ($estudante['userid'] == $acessou ['userid']) {
                $encontrou = true;
                break;
            }
        }
        if (!$encontrou) {
            $resultado[] = $estudante;
        }
    }
    return $resultado;
}
