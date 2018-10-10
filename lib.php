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
defined('MOODLE_INTERNAL') || die();

function usuarios_online() {

/**
 * This code comes fom online users block
 * This block needs to be reworked.
 * The new roles system does away with the concepts of rigid student and
 * teacher roles.
 */
        global $CFG, $DB;
      
        $courseid = 194;
        $timetoshowusers = 300; //Seconds default, make it configurable
        $now = time();
        $params = array();
        $params['now'] = $now;
        $timefrom = 100 * floor(($now - $timetoshowusers) / 100); // Round to nearest 100 seconds for better query cache.
        $params['timefrom'] = $timefrom;
 
            // Course level - show only enrolled users for now.
            // TODO: add a new capability for viewing of all users (guests+enrolled+viewing).
            $context = context_course::instance($courseid);

            list($esqljoin, $eparams) = get_enrolled_sql($context);
            $params = array_merge($params, $eparams);
 
            $sql = "SELECT $userfields $timeaccess
                      FROM {user_lastaccess} ul $groupmembers, {user} u
                      JOIN ($esqljoin) euj ON euj.id = u.id
                     WHERE ul.timeaccess > $timefrom
                           AND u.id = ul.userid
                           AND ul.courseid = $courseid
                           AND ul.timeaccess <= $now
                           AND u.deleted = 0
                  ORDER BY lastaccess DESC";
             $params['courseid'] = $courseid;
        
        //Calculate minutes
        $minutes  = floor($timetoshowusers/60);
        $periodminutes = get_string('periodnminutes', 'block_online_users', $minutes);
        
        $userlimit = 50; // We'll just take the most recent 50 maximum.
        $users = $DB->get_records_sql($sql, $params, 0, $userlimit);
        if ($users) {
            foreach ($users as $user) {
                $users[$user->id]->fullname = fullname($user);
            }
        } else {
            $users = array();
        }
      
        echo $users;
 return array('AAnita', 'MMarcelo', 'PPatricia'); 
}
