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
class block_das extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_das');
    }
    // The PHP tag and the curly bracket for the class definition
    // will only be closed after there is another function added in the next section.
    public function get_content() {
        global $CFG;
        global $DB;

        $uselegacypixurl = false; // pix_url got deprecated in Moodle 3.3, leaving this just in case.

        $course = $this->page->course;
        $context = context_course::instance($course->id);
        $canview = has_capability('block/das:viewpages', $context);
        if (!$canview) {
            return;
        }
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        // $this->content->text = get_string('graphs', 'block_das');
        $this->content->text = "";
        $this->content->text .= "<li> <a href= {$CFG->wwwroot}/blocks/das/dashboard/index.php?id={$course->id} 
                          target=_blank>" . get_string('das', 'block_das') . "</a>";
      
        $this->content->footer = '<hr/>';
        return $this->content;
    }
}  // Here's the closing bracket for the class definition.
