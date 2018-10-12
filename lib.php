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

function das_course_users() {

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
        $params['courseid'] = $courseid;
 
        $userfields = \user_picture::fields('u', array('username'));
        $timeaccess    = ", ul.timeaccess AS lastaccess";
        $groupby = "GROUP BY $userfields";

            // Course level - show only enrolled users for now.
            // TODO: add a new capability for viewing of all users (guests+enrolled+viewing).
            $context = context_course::instance($courseid);

            list($esqljoin, $eparams) = get_enrolled_sql($context);
            $params = array_merge($params, $eparams);
             $sql = "SELECT $userfields $timeaccess
                      FROM {user_lastaccess} ul, {user} u
                      JOIN ($esqljoin) euj ON euj.id = u.id
                     WHERE
                           u.id = ul.userid
                           AND ul.courseid = :courseid
                           AND u.deleted = 0
                  $groupby
                  ORDER BY lastaccess DESC";

        
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
 return $users;
}

 
function das_missing_users($users, $lowboundary, $highboundary){
        $limitedusers = array();
        $now = time();
        foreach($users AS $user) {
                $days = ($now - $user->lastaccess) / 3600 / 24;
                if($days >= $lowboundary && $days < $highboundary) {
                        $user->days = floor($days);
                        $limitedusers[] = $user;
                }
        }
        return $limitedusers;
}


function das_print_today_users($courseusers) {
   $beginOfDay = strtotime("midnight", time());
   foreach($courseusers AS $user) {
       if($beginOfDay < $user->lastaccess) {
           ?>
           <div class="das-item-default-header">
           <img class="das-user-small-image das-vertical-align" src="assets/img/rosto1.jpg" alt="User-Image">
           <p class="das-vertical-align das-p-overflow"><?php echo "$user->firstname $user->lastname"; ?></p>
           <img class="das-message-icon" src="assets/img/msg.png" alt="Message-Image">
           </div>
           <?php
       }
   }
}             


function das_print_missing_users($courseusers, $lowboundary, $highboundary=10000) {
   if(!($missingusers = das_missing_users($courseusers, $lowboundary, $highboundary))) {
       return;
   }
   ?>
   <div class="das-missing-users-period">
   <p class="das-subtitle">
   <?php 
   if($highboundary < 10000) {
       echo "Entre $lowboundary e $highboundary dias";
   }
   else {
       echo "$lowboundary dias ou mais";
   }
   $color = 1;
   foreach($missingusers As $user) {
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
