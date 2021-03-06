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

function das_course_users($courseid) {
    global $DB, $PAGE;

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

    $userlimit = 50; // We'll just take the most recent 50 maximum.
    $users = $DB->get_records_sql($sql, $params, 0, $userlimit);
    if ($users) {
        foreach ($users as $user) {
            $users[$user->id]->fullname = fullname($user);
            $userpicture = new user_picture($user);
            $url = $userpicture->get_url($PAGE);
            $users[$user->id]->pictureurl = $url->out();
        }
    } else {
        $users = array();
    }
    return $users;
}


function das_missing_users($users, $lowboundary, $highboundary) {
    global $PAGE;
    $limitedusers = array();
    $now = time();
    foreach ($users as $user) {
        $days = ($now - $user->lastaccess) / 3600 / 24;
        if ($days >= $lowboundary && $days < $highboundary) {
                $user->days = floor($days);
                $userpicture = new user_picture($user);
                $url = $userpicture->get_url($PAGE);
                $user->pictureurl = $url->out();
                $limitedusers[] = $user;
        }
    }
    return $limitedusers;
}


function das_print_users($courseusers) {
    $beginofday = strtotime("midnight", time());
    foreach ($courseusers as $user) {
        if ($beginofday < $user->lastaccess) {
            ?><div class="das-item-default-header">
            <img class="das-user-small-image das-vertical-align" src="<?php echo $user->pictureurl;?>" alt="User-Image">
            <p class="das-vertical-align das-p-overflow"><?php echo "$user->firstname $user->lastname"; ?></p>
            <img class="das-message-icon" src="assets/img/msg.png" alt="Message-Image">
            </div><?php
        }
    }
}

function das_print_today_users($courseusers) {
    $beginofday = strtotime("midnight", time());
    foreach ($courseusers as $user) {
        if ($beginofday < $user->lastaccess) {
            ?><div class="das-item-default-header">
            <img class="das-user-small-image das-vertical-align" src="<?php echo $user->pictureurl;?>" alt="User-Image">
            <p class="das-vertical-align das-p-overflow"><?php echo "$user->firstname $user->lastname"; ?></p>
           <img class="das-message-icon" src="assets/img/comment-solid.png" alt="Message-Image">
            </div><?php
        }
    }
}


function das_print_missing_users($courseusers, $lowboundary, $highboundary=10000) {
    global $CFG;
    if (!($missingusers = das_missing_users($courseusers, $lowboundary, $highboundary))) {
        return;
    }
    ?><div class="das-missing-users-period">
    <p class="das-subtitle"><?php
    if ($highboundary < 10000) {
        echo "Entre $lowboundary e $highboundary dias";
    } else {
        echo "$lowboundary dias ou mais";
    }
    $color = 1;
    foreach ($missingusers as $user) {
        if ($color++ % 2) {
            ?> <div class="das-missing-user-color-grey"> <?php
        } else {
            ?> <div class="das-missing-user-color-white"> <?php
        }
        ?>
        <img class="das-user-small-image" src="<?php echo $user->pictureurl;?>" alt="User-Image">
        <p class="das-vertical-align das-p-overflow">
        <?php echo "$user->fullname";?>
        </p>
        <a href="<?php echo $CFG->wwwroot;?>/message?id=<?php echo $user->id;?>">
        <div class="das-missing-user-days-white">
        <div style="width: 18px;text-align: center;" n><?php echo "$user->days"?></div>
        </div>
        </div>
        </a>
        <?php
    }
    ?> </div>
    <?php
}


function das_print_second_column_window($courseusers, $activities, $type, $numberoftype) {
    ?><div id="<?php echo 'das-'.$type ?>">
    <p class="das-title"><?php echo get_string($type, 'block_das');?></p>
    <?php
    $counter = 0;
    $oldsection = '';
    foreach($activities as $activity){
        if($activity[$numberoftype]) {
            $oldsection = das_print_section($activity, $oldsection);
            $expansiveid = $type . ++$counter;
            das_print_student_list($courseusers, $activity[$type], $expansiveid,
                $activity['modid'], $activity['assign'], $activity[$numberoftype]);
        }
    }
    ?></div><?php
}


function das_activities($students/*$id_curso*/){
    global $DB;
    $course = 194;
    require('das_submission.php');

    foreach ($students as $tuple) {
        $inclause[] = $tuple->id;
    }
    list($insql, $inparams) = $DB->get_in_or_equal($inclause);
    $assign = $DB->get_record('modules', array('name' => 'assign'), 'id');
    $params = array_merge(array($assign->id, $course), $inparams);
    $sql = "SELECT a.id+(COALESCE(s.id,1)*1000000)as id, a.id as assignment, a.name as name, duedate, cutoffdate,
                s.userid, usr.firstname, usr.lastname, usr.email, s.timemodified as submissiontime, g.timemodified as gradetime,
                cs.section as sectionnumber, cs.name as sectionname, cm.id as modid
                FROM {assign} a
                LEFT JOIN {assign_submission} s on a.id = s.assignment AND s.status = 'submitted'
                LEFT JOIN {assign_grades} g on a.id = g.assignment AND s.status = 'submitted' AND g.userid = s.userid
                LEFT JOIN {user} usr ON usr.id = s.userid
                LEFT JOIN {course_modules} cm on cm.instance = a.id AND cm.module = ?
                LEFT JOIN {course_sections} cs on cs.id = cm.section
                WHERE a.course = ? and nosubmissions = 0 AND (s.userid IS NULL OR s.userid $insql)
                    AND cm.visible = 1
                ORDER BY sectionnumber, name, firstname";
     $result = $DB->get_records_sql($sql, $params);

    $submissions = new das_submission($course);
    $submissions->create_array($result,$students);
   return($submissions->get_array());
}


function das_print_student_list($courseusers, $students, $divid, $modid, $activityname, $number) {
    ?><div class="das-item-default-header">
        <a href="<?php echo $CFG->wwwroot;?>/mod/assign/view.php?id=<?php echo $modid;?>">
            <img class="das-activity-deliver-img das-vertical-align" src="assets/img/postlaranja.png" alt="activity-img">
        </a>
        <p class="das-vertical-align" onclick = "$('.<?php echo $divid ?>').toggle();">
            <?php echo $activityname; ?>
        </p>
        <div class="das-activity-number">
            <div style="">
                <p><?php echo $number;?></p>
            </div>
        </div>

    </div>
    <?php
    foreach($students as $student) {
                ?><div class="das-item-default-expansive <?php echo $divid; ?>" style="display: none">
                <img class="das-user-small-image das-vertical-align" src="<?php echo $courseusers[$student['userid']]->pictureurl;?>" alt="User-Image">
                <p class="das-vertical-align das-p-overflow"><?php echo $courseusers[$student['userid']]->fullname; ?></p>
                <a href="<?php echo $CFG->wwwroot;?>/message?id=<?php echo $student['userid']?>">
                    <img class="das-message-icon" src="assets/img/comment-solid.png" alt="Message-Image">
                </a>
                </div><?php
            }
}


function das_print_section($activity,$oldsection = '') {
    if($oldsection != $activity['sectionname']) {
        ?><p class="das-subtitle">Tópico <?php
        echo $activity['sectionname'];
        ?></p><?php
        return $activity['sectionname'];
    }
}
