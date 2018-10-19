i<?php
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
            if ($assignmentid == 0) { // First time in loop.
                $this->statistics[$counter]['assign'] = $tuple->name;
                $this->statistics[$counter]['duedate'] = $tuple->duedate;
                                $this->statistics[$counter]['cutoffdate'] = $tuple->cutoffdate;
                if (isset($tuple->userid)) { // If a student submitted.
                    if ($tuple->duedate >= $tuple->timecreated || $tuple->duedate == 0) { // In the right time.
                        $this->statistics[$counter]['in_time_submissions'][] = array('userid'  => $tuple->userid,
                            'nome'  => $tuple->firstname." ".$tuple->lastname,
                            'email'  => $tuple->email, 'timecreated'  => $tuple->timecreated);
                        $numberofintimesubmissions++;
                    } else { // Late.
                        $this->statistics[$counter]['latesubmissions'][] = array('userid'  => $tuple->userid,
                            'nome'  => $tuple->firstname." ".$tuple->lastname, 'email'  => $tuple->email,
                            'timecreated'  => $tuple->timecreated);
                        $numberoflatesubmissions++;
                    }
                }
                $assignmentid = $tuple->assignment;
            } else { // Not first time in loop.
                if ($assignmentid == $tuple->assignment and $tuple->userid) { // Same task -> add student.
                    if ($tuple->duedate >= $tuple->timecreated || $tuple->duedate == 0) { // Right time.
                        $this->statistics[$counter]['in_time_submissions'][] = array('userid'  => $tuple->userid,
                            'nome'  => $tuple->firstname." ".$tuple->lastname,
                            'email'  => $tuple->email, 'timecreated'  => $tuple->timecreated);
                        $numberofintimesubmissions++;
                    } else { // Late.
                        $this->statistics[$counter]['latesubmissions'][] = array('userid'  => $tuple->userid,
                            'nome'  => $tuple->firstname." ".$tuple->lastname,
                            'email'  => $tuple->email, 'timecreated'  => $tuple->timecreated);
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
                                dassubtract_student_arrays($arrayofstudents,
                                    $this->statistics[$counter]['in_time_submissions']),
                                $this->statistics[$counter]['latesubmissions']);
                        }
                    }
                    $counter++;
Last login: Wed Oct 17 12:19:50 on ttys000
Marcelos-MacBook-Air:~ marcelo$ ssh moodle.poa.ifrs.edu.br
marcelo@moodle.poa.ifrs.edu.br's password:
Welcome to Ubuntu 14.04.5 LTS (GNU/Linux 3.16.0-77-generic x86_64)

 * Documentation:  https://help.ubuntu.com/
New release '16.04.5 LTS' available.
Run 'do-release-upgrade' to upgrade to it.

Last login: Fri Oct 19 08:20:03 2018 from 189.6.243.46
marcelo@moodle:~$ ssh -l schmitt atom.poa.ifrs.edu.br
schmitt@atom.poa.ifrs.edu.br's password:

marcelo@moodle:~$ ssh moodle.inf.poa.ifrs.edu.br
marcelo@moodle.inf.poa.ifrs.edu.br's password:
Welcome to Ubuntu 16.04.1 LTS (GNU/Linux 4.4.0-81-generic i686)

 * Documentation:  https://help.ubuntu.com
 * Management:     https://landscape.canonical.com
 * Support:        https://ubuntu.com/advantage

126 pacotes podem ser atualizados.
7 atualizações são atualizações de segurança.

New release '18.04.1 LTS' available.
Run 'do-release-upgrade' to upgrade to it.


Last login: Fri Oct 19 08:11:56 2018 from 200.132.50.8
marcelo@moodle2:~$ sudo su
[sudo] password for marcelo:
root@moodle2:/home/marcelo# cd /var/www/moodle/blocks/das
root@moodle2:/var/www/moodle/blocks/das# ls
block_das.php  classes  dashboard  das_submission.php  db  lang  lib.php  LICENSE.md  README.md  version.php
root@moodle2:/var/www/moodle/blocks/das# vi lib.php











root@moodle2:/var/www/moodle/blocks/das# !pi
ping penta.ufrgs.br
PING penta.ufrgs.br (143.54.1.20) 56(84) bytes of data.
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=1 ttl=249 time=1.73 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=2 ttl=249 time=1.61 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=3 ttl=249 time=14.3 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=4 ttl=249 time=1.92 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=5 ttl=249 time=2.25 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=6 ttl=249 time=6.95 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=7 ttl=249 time=1.66 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=8 ttl=249 time=1.77 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=9 ttl=249 time=2.17 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=10 ttl=249 time=1.55 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=11 ttl=249 time=1.65 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=12 ttl=249 time=1.32 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=13 ttl=249 time=1.65 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=14 ttl=249 time=1.81 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=15 ttl=249 time=3.61 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=16 ttl=249 time=15.1 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=17 ttl=249 time=1.39 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=18 ttl=249 time=1.57 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=19 ttl=249 time=1.65 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=20 ttl=249 time=1.76 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=21 ttl=249 time=1.29 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=22 ttl=249 time=1.79 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=23 ttl=249 time=1.39 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=24 ttl=249 time=11.4 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=25 ttl=249 time=3.68 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=26 ttl=249 time=2.33 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=27 ttl=249 time=1.78 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=28 ttl=249 time=1.53 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=29 ttl=249 time=1.37 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=30 ttl=249 time=1.27 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=31 ttl=249 time=1.59 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=32 ttl=249 time=1.39 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=33 ttl=249 time=1.52 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=34 ttl=249 time=6.42 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=35 ttl=249 time=1.73 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=36 ttl=249 time=1.29 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=37 ttl=249 time=1.84 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=38 ttl=249 time=2.55 ms
^C
--- penta.ufrgs.br ping statistics ---
38 packets transmitted, 38 received, 0% packet loss, time 37058ms
rtt min/avg/max/mdev = 1.273/2.997/15.162/3.373 ms
root@moodle2:/var/www/moodle/blocks/das# ls
block_das.php  classes  dashboard  das_submission.php  db  lang  lib.php  LICENSE.md  README.md  version.php
root@moodle2:/var/www/moodle/blocks/das# vi dashboard/index.php
root@moodle2:/var/www/moodle/blocks/das# vi dashboard/index.php
root@moodle2:/var/www/moodle/blocks/das# !pi
ping penta.ufrgs.br
PING penta.ufrgs.br (143.54.1.20) 56(84) bytes of data.
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=1 ttl=249 time=2.03 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=2 ttl=249 time=5.94 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=3 ttl=249 time=1.64 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=4 ttl=249 time=1.75 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=5 ttl=249 time=1.65 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=6 ttl=249 time=2.00 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=7 ttl=249 time=1.39 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=8 ttl=249 time=7.26 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=9 ttl=249 time=3.96 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=10 ttl=249 time=1.70 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=11 ttl=249 time=1.63 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=12 ttl=249 time=4.08 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=13 ttl=249 time=1.65 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=14 ttl=249 time=1.88 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=15 ttl=249 time=1.31 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=16 ttl=249 time=2.18 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=17 ttl=249 time=1.44 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=18 ttl=249 time=7.35 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=19 ttl=249 time=1.50 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=20 ttl=249 time=1.61 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=21 ttl=249 time=1.53 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=22 ttl=249 time=4.31 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=23 ttl=249 time=1.81 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=24 ttl=249 time=1.96 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=25 ttl=249 time=1.92 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=26 ttl=249 time=1.46 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=27 ttl=249 time=1.60 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=28 ttl=249 time=5.15 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=29 ttl=249 time=5.23 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=30 ttl=249 time=4.33 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=31 ttl=249 time=34.2 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=32 ttl=249 time=1.64 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=33 ttl=249 time=1.90 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=34 ttl=249 time=3.43 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=35 ttl=249 time=1.44 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=36 ttl=249 time=1.75 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=37 ttl=249 time=1.37 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=38 ttl=249 time=1.95 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=39 ttl=249 time=1.66 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=40 ttl=249 time=1.75 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=41 ttl=249 time=2.99 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=42 ttl=249 time=1.62 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=43 ttl=249 time=6.35 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=44 ttl=249 time=13.1 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=45 ttl=249 time=1.58 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=46 ttl=249 time=1.37 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=47 ttl=249 time=3.73 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=48 ttl=249 time=1.87 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=49 ttl=249 time=1.38 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=50 ttl=249 time=1.25 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=51 ttl=249 time=4.01 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=52 ttl=249 time=1.61 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=53 ttl=249 time=2.00 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=54 ttl=249 time=1.62 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=55 ttl=249 time=1.73 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=56 ttl=249 time=4.38 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=57 ttl=249 time=1.86 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=58 ttl=249 time=3.64 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=59 ttl=249 time=4.21 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=60 ttl=249 time=3.14 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=61 ttl=249 time=15.0 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=62 ttl=249 time=1.46 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=63 ttl=249 time=13.8 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=64 ttl=249 time=1.77 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=65 ttl=249 time=1.87 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=66 ttl=249 time=1.78 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=67 ttl=249 time=11.0 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=68 ttl=249 time=1.75 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=69 ttl=249 time=1.36 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=70 ttl=249 time=1.90 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=71 ttl=249 time=1.56 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=72 ttl=249 time=1.64 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=73 ttl=249 time=1.76 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=74 ttl=249 time=1.28 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=75 ttl=249 time=1.90 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=76 ttl=249 time=1.89 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=77 ttl=249 time=1.54 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=78 ttl=249 time=2.19 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=79 ttl=249 time=65.7 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=80 ttl=249 time=7.59 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=81 ttl=249 time=1.33 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=82 ttl=249 time=1.65 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=83 ttl=249 time=4.02 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=84 ttl=249 time=1.68 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=85 ttl=249 time=1.42 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=86 ttl=249 time=1.76 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=87 ttl=249 time=9.92 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=88 ttl=249 time=4.10 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=89 ttl=249 time=1.51 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=90 ttl=249 time=1.79 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=91 ttl=249 time=1.51 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=92 ttl=249 time=1.63 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=93 ttl=249 time=1.68 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=94 ttl=249 time=1.96 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=95 ttl=249 time=1.53 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=96 ttl=249 time=1.52 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=97 ttl=249 time=1.65 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=98 ttl=249 time=1.91 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=99 ttl=249 time=1.38 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=100 ttl=249 time=1.76 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=101 ttl=249 time=1.88 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=102 ttl=249 time=1.65 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=103 ttl=249 time=1.64 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=104 ttl=249 time=1.66 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=105 ttl=249 time=1.29 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=106 ttl=249 time=1.52 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=107 ttl=249 time=6.96 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=108 ttl=249 time=2.41 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=109 ttl=249 time=1.35 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=110 ttl=249 time=1.75 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=111 ttl=249 time=1.78 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=112 ttl=249 time=1.51 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=113 ttl=249 time=1.67 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=114 ttl=249 time=5.01 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=115 ttl=249 time=1.37 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=116 ttl=249 time=1.40 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=117 ttl=249 time=1.78 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=118 ttl=249 time=1.53 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=119 ttl=249 time=1.78 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=120 ttl=249 time=21.8 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=121 ttl=249 time=1.44 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=122 ttl=249 time=1.28 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=123 ttl=249 time=1.91 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=124 ttl=249 time=1.54 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=125 ttl=249 time=1.37 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=126 ttl=249 time=1.65 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=127 ttl=249 time=1.65 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=128 ttl=249 time=1.53 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=129 ttl=249 time=1.91 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=130 ttl=249 time=1.38 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=131 ttl=249 time=1.39 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=132 ttl=249 time=1.64 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=133 ttl=249 time=1.77 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=134 ttl=249 time=1.76 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=135 ttl=249 time=1.64 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=136 ttl=249 time=1.99 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=137 ttl=249 time=40.7 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=138 ttl=249 time=1.43 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=139 ttl=249 time=6.33 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=140 ttl=249 time=1.87 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=141 ttl=249 time=1.78 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=142 ttl=249 time=2.02 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=143 ttl=249 time=18.6 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=144 ttl=249 time=1.97 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=145 ttl=249 time=2.40 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=146 ttl=249 time=1.55 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=147 ttl=249 time=2.36 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=148 ttl=249 time=1.77 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=149 ttl=249 time=5.99 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=150 ttl=249 time=1.86 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=151 ttl=249 time=1.67 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=152 ttl=249 time=1.48 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=153 ttl=249 time=1.40 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=154 ttl=249 time=1.69 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=155 ttl=249 time=1.59 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=156 ttl=249 time=1.41 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=157 ttl=249 time=1.90 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=158 ttl=249 time=4.50 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=159 ttl=249 time=2.07 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=160 ttl=249 time=1.65 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=161 ttl=249 time=3.78 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=162 ttl=249 time=4.87 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=163 ttl=249 time=1.73 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=164 ttl=249 time=2.04 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=165 ttl=249 time=2.11 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=166 ttl=249 time=1.53 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=167 ttl=249 time=2.05 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=168 ttl=249 time=1.51 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=169 ttl=249 time=1.28 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=170 ttl=249 time=1.73 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=171 ttl=249 time=1.78 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=172 ttl=249 time=1.97 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=173 ttl=249 time=1.65 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=174 ttl=249 time=1.59 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=175 ttl=249 time=1.61 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=176 ttl=249 time=1.89 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=177 ttl=249 time=1.76 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=178 ttl=249 time=1.61 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=179 ttl=249 time=5.11 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=180 ttl=249 time=4.00 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=181 ttl=249 time=1.78 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=182 ttl=249 time=1.78 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=183 ttl=249 time=1.68 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=184 ttl=249 time=1.70 ms
^C
--- penta.ufrgs.br ping statistics ---
184 packets transmitted, 184 received, 0% packet loss, time 183275ms
rtt min/avg/max/mdev = 1.255/3.444/65.782/6.509 ms
root@moodle2:/var/www/moodle/blocks/das# git pull
remote: Enumerating objects: 11, done.
remote: Counting objects: 100% (11/11), done.
remote: Compressing objects: 100% (1/1), done.
remote: Total 6 (delta 5), reused 6 (delta 5), pack-reused 0
Unpacking objects: 100% (6/6), done.
From https://github.com/marceloschmitt/moodle-block_das
   7ffa17c..3a8b229  0.2        -> origin/0.2
error: Your local changes to the following files would be overwritten by merge:
	dashboard/index.php
	lib.php
Please, commit your changes or stash them before you can merge.
Aborting
root@moodle2:/var/www/moodle/blocks/das# rm lib.php
root@moodle2:/var/www/moodle/blocks/das# rm dashboard/index.php
root@moodle2:/var/www/moodle/blocks/das# git pull
Auto-merging dashboard/index.php
Merge made by the 'recursive' strategy.
 dashboard/index.php | 41 +++--------------------------------------
 lib.php             | 79 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-----------
 version.php         |  2 +-
 3 files changed, 72 insertions(+), 50 deletions(-)
root@moodle2:/var/www/moodle/blocks/das# git pull
Already up-to-date.
root@moodle2:/var/www/moodle/blocks/das# !pi
ping penta.ufrgs.br
PING penta.ufrgs.br (143.54.1.20) 56(84) bytes of data.
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=1 ttl=249 time=1.47 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=2 ttl=249 time=1.52 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=3 ttl=249 time=1.63 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=4 ttl=249 time=1.62 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=5 ttl=249 time=1.76 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=6 ttl=249 time=5.57 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=7 ttl=249 time=12.6 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=8 ttl=249 time=1.97 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=9 ttl=249 time=2.12 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=10 ttl=249 time=1.38 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=11 ttl=249 time=1.89 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=12 ttl=249 time=1.63 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=13 ttl=249 time=1.40 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=14 ttl=249 time=1.51 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=15 ttl=249 time=1.64 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=16 ttl=249 time=2.12 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=17 ttl=249 time=3.06 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=18 ttl=249 time=1.81 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=19 ttl=249 time=6.38 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=20 ttl=249 time=1.42 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=21 ttl=249 time=1.62 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=22 ttl=249 time=1.52 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=23 ttl=249 time=4.68 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=24 ttl=249 time=11.2 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=25 ttl=249 time=1.86 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=26 ttl=249 time=1.51 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=27 ttl=249 time=2.68 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=28 ttl=249 time=1.65 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=29 ttl=249 time=1.91 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=30 ttl=249 time=8.00 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=31 ttl=249 time=1.40 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=32 ttl=249 time=2.00 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=33 ttl=249 time=1.75 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=34 ttl=249 time=1.78 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=35 ttl=249 time=1.29 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=36 ttl=249 time=1.73 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=37 ttl=249 time=3.78 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=38 ttl=249 time=2.07 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=39 ttl=249 time=4.59 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=40 ttl=249 time=1.58 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=41 ttl=249 time=1.90 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=42 ttl=249 time=1.49 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=43 ttl=249 time=1.67 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=44 ttl=249 time=1.67 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=45 ttl=249 time=1.89 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=46 ttl=249 time=1.85 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=47 ttl=249 time=2.00 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=48 ttl=249 time=1.73 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=49 ttl=249 time=1.39 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=50 ttl=249 time=1.50 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=51 ttl=249 time=4.19 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=52 ttl=249 time=1.67 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=53 ttl=249 time=1.63 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=54 ttl=249 time=4.13 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=55 ttl=249 time=28.3 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=56 ttl=249 time=2.17 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=57 ttl=249 time=1.85 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=58 ttl=249 time=1.97 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=59 ttl=249 time=1.71 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=60 ttl=249 time=1.72 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=61 ttl=249 time=1.91 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=62 ttl=249 time=2.53 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=63 ttl=249 time=1.85 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=64 ttl=249 time=10.0 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=65 ttl=249 time=1.40 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=66 ttl=249 time=1.78 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=67 ttl=249 time=1.40 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=68 ttl=249 time=2.19 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=69 ttl=249 time=1.71 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=70 ttl=249 time=1.75 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=71 ttl=249 time=1.41 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=72 ttl=249 time=1.76 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=73 ttl=249 time=1.65 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=74 ttl=249 time=4.31 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=75 ttl=249 time=1.60 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=76 ttl=249 time=1.57 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=77 ttl=249 time=1.54 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=78 ttl=249 time=1.72 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=79 ttl=249 time=2.05 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=80 ttl=249 time=4.95 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=81 ttl=249 time=1.38 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=82 ttl=249 time=1.54 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=83 ttl=249 time=4.88 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=84 ttl=249 time=2.60 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=85 ttl=249 time=1.50 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=86 ttl=249 time=1.93 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=87 ttl=249 time=1.49 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=88 ttl=249 time=1.76 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=89 ttl=249 time=1.36 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=90 ttl=249 time=2.26 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=91 ttl=249 time=2.40 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=92 ttl=249 time=3.93 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=93 ttl=249 time=1.45 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=94 ttl=249 time=7.87 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=95 ttl=249 time=11.7 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=96 ttl=249 time=2.84 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=97 ttl=249 time=2.00 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=98 ttl=249 time=1.40 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=99 ttl=249 time=1.52 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=100 ttl=249 time=6.24 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=101 ttl=249 time=3.84 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=102 ttl=249 time=19.6 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=103 ttl=249 time=1.51 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=104 ttl=249 time=1.63 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=105 ttl=249 time=5.18 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=106 ttl=249 time=3.19 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=107 ttl=249 time=9.65 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=108 ttl=249 time=5.67 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=109 ttl=249 time=1.52 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=110 ttl=249 time=1.76 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=111 ttl=249 time=1.54 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=112 ttl=249 time=1.62 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=113 ttl=249 time=1.47 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=114 ttl=249 time=1.95 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=115 ttl=249 time=1.65 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=116 ttl=249 time=10.2 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=117 ttl=249 time=1.68 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=118 ttl=249 time=3.08 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=119 ttl=249 time=5.04 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=120 ttl=249 time=1.86 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=121 ttl=249 time=1.42 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=122 ttl=249 time=4.35 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=123 ttl=249 time=1.40 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=124 ttl=249 time=2.00 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=125 ttl=249 time=1.60 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=126 ttl=249 time=2.48 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=127 ttl=249 time=3.68 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=128 ttl=249 time=7.39 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=129 ttl=249 time=1.48 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=130 ttl=249 time=12.5 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=131 ttl=249 time=1.67 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=132 ttl=249 time=1.72 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=133 ttl=249 time=1.50 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=134 ttl=249 time=2.29 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=135 ttl=249 time=1.34 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=136 ttl=249 time=1.66 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=137 ttl=249 time=1.74 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=138 ttl=249 time=2.82 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=139 ttl=249 time=1.58 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=140 ttl=249 time=1.48 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=141 ttl=249 time=1.49 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=142 ttl=249 time=1.36 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=143 ttl=249 time=1.46 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=144 ttl=249 time=1.76 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=145 ttl=249 time=1.52 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=146 ttl=249 time=1.51 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=147 ttl=249 time=1.52 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=148 ttl=249 time=1.65 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=149 ttl=249 time=1.52 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=150 ttl=249 time=2.90 ms
64 bytes from penta.ufrgs.br (143.54.1.20): icmp_seq=151 ttl=249 time=1.76 ms
^C
--- penta.ufrgs.br ping statistics ---
151 packets transmitted, 151 received, 0% packet loss, time 150237ms
rtt min/avg/max/mdev = 1.295/2.979/28.346/3.384 ms
root@moodle2:/var/www/moodle/blocks/das# !tail
tail -f /var/log/php_error.log
* line 98 of /blocks/das/das_submission.php: Error thrown
* line 205 of /blocks/das/lib.php: call to das_submission->create_array()
* line 382 of /blocks/das/dashboard/index.php: call to das_activities()

[19-Oct-2018 10:59:54 America/Sao_Paulo] Default exception handler: Exceção - Call to undefined function das_graphs_subtract_student_arrays() Debug:
Error code: generalexceptionmessage
* line 98 of /blocks/das/das_submission.php: Error thrown
* line 198 of /blocks/das/lib.php: call to das_submission->create_array()
* line 382 of /blocks/das/dashboard/index.php: call to das_activities()





^C
root@moodle2:/var/www/moodle/blocks/das# vi das_submission.php
root@moodle2:/var/www/moodle/blocks/das# vi das_submission.php

                                das_subtract_student_arrays(
                                dassubtract_student_arrays($arrayofstudents,
                                    $this->statistics[$counter]['in_time_submissions']),
                                $this->statistics[$counter]['latesubmissions']);
                        }
                    }
                    $counter++;
                    $numberofintimesubmissions = 0;
                    $numberoflatesubmissions = 0;
                    $this->statistics[$counter]['assign'] = $tuple->name;
                    $this->statistics[$counter]['duedate'] = $tuple->duedate;
                    $this->statistics[$counter]['cutoffdate'] = $tuple->cutoffdate;
                    $assignmentid = $tuple->assignment;
                    if ($tuple->userid) { // If a user has submitted
                        if ($tuple->duedate >= $tuple->timecreated || $tuple->duedate == 0) { // Right time.
                            $this->statistics[$counter]['in_time_submissions'][] = array('userid'  => $tuple->userid,
                                'nome' => $tuple->firstname." ".$tuple->lastname,
                                'email' => $tuple->email, 'timecreated'  => $tuple->timecreated);
                            $numberofintimesubmissions = 1;
                        } else { // Late.
                            $this->statistics[$counter]['latesubmissions'][] = array('userid'  => $tuple->userid,
                                'nome'  => $tuple->firstname." ".$tuple->lastname,
                                'email'  => $tuple->email, 'timecreated'  => $tuple->timecreated);
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
        foreach ($this->statistics as $tuple) {            $arrayofassignments[] = $tuple['assign'];
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
