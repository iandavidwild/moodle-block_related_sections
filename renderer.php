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


/**
 * Print related sections
 *
 * @package    block_related_sections
 * @copyright  2012 University of London Computer Centre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

class block_related_sections_renderer extends plugin_renderer_base {
    public $courses;

    /**
     * Prints out the related sections
     * @return string
     */
    public function related_sections($courseid) {
        global $DB, $CFG;

        $text = '';

        if (class_exists('ual_mis')) {
            $mis = new ual_mis();

            $this_course = $DB->get_record('course', array('id' => $courseid), 'shortname');
            $related_sections = $this->courses = $mis->get_related_courses($this_course->shortname);

            $left_col = html_writer::tag('h4', get_string('programmes_and_courses', 'block_related_sections', array('id' => 'programmes_courses')));

            if(!empty($related_sections)) {
                if(!empty($related_sections[ual_mis::PROGRAMMES_INDEX])) {
                    $programmes = $related_sections[ual_mis::PROGRAMMES_INDEX];
                    // Display programmes and courses...
                    foreach($programmes as $programme) {
                        // Get link to Moodle course from shortname...
                        $moodle_course = $DB->get_record('course', array('shortname' => $programme), 'id');
                        $left_col .= html_writer::link($CFG->wwwroot.'course/view.php?id='.$moodle_course->id, $programme);
                        $left_col .= html_writer::empty_tag('br');
                    }
                } else {
                    $left_col .= get_string('no_programmes', 'block_related_sections');
                }

                if(!empty($related_sections[ual_mis::COURSES_INDEX])) {
                    $courses = $related_sections[ual_mis::COURSES_INDEX];
                    // Display courses
                    foreach($courses as $course) {
                        $moodle_course = $DB->get_record('course', array('shortname' => $course), 'id');
                        $left_col .= html_writer::link($CFG->wwwroot.'course/view.php?id='.$moodle_course->id, $course);
                        $left_col .= html_writer::empty_tag('br');
                    }
                } else {
                    $left_col .= get_string('no_courses', 'block_related_sections');
                }

                // Put list of programmes and courses in a DIV and make it float to the left
                $text = html_writer::tag('div', $left_col, array('id' => 'left_col', 'style' => 'float: left;'));

                // Display units...
                $right_col = html_writer::tag('h4', get_string('units', 'block_related_sections', array('id' => 'units')));
                if(!empty($related_sections[ual_mis::UNITS_INDEX])) {
                    $units = $related_sections[ual_mis::UNITS_INDEX];
                    // Display units
                    foreach($units as $course => $unit) {
                        $moodle_course = $DB->get_record('course', array('shortname' => $unit), 'id');
                        $right_col .= html_writer::link($CFG->wwwroot.'course/view.php?id='.$moodle_course->id, $course.' - '.$unit);
                        $right_col .= html_writer::empty_tag('br');
                    }
                } else {
                    $right_col .= get_string('no_units', 'block_related_sections');
                }

                // Put list of units in a DIV and make it float to the left, too
                $text .= html_writer::tag('div', $right_col, array('id' => 'right_col', 'style' => 'float: left; padding-left: 20px'));

            }
        }
        // TODO warn if local plugin 'ual_api' is not installed.

        return $text;
    }

}


