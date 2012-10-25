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
 * Main block file
 *
 * @package    block
 * @subpackage related_sections
 * @copyright  2012 University of London Computer Centre
 * @author     Ian Wild {@link http://moodle.org/user/view.php?id=325899}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * This class lists the 'Programmes', 'Courses' and 'Units' related to this course
 *
 * @copyright 2012 Ian Wild
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_related_sections extends block_base {

    /**
     * Standard init function, sets block title and version number
     *
     * @return void
     */
    public function init() {
        $this->title = get_string('relatedsections', 'block_related_sections');
    }

    /**
     * Standard specialization function
     *
     * @return void
     */
    public function specialization() {
        $this->title = get_string('relatedsections', 'block_related_sections');
    }

    /**
     * Standard get content function returns $this->content containing the block HTML etc
     *
     * @return stdClass
     */
    public function get_content() {

        global $CFG, $USER, $PAGE, $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }
        if (empty($this->instance)) {
            return null;
        }

        // Load userdefined title and make sure it's never empty.
        if (empty($this->config->title)) {
            $this->title = get_string('relatedsections', 'block_related_sections');
        } else {
            $this->title = $this->config->title;
        }

        $this->content = new stdClass();

        $this->content->text = '';
        $this->content->footer = '';
        if (isloggedin() && !isguestuser()) {   // Show the block.
            $this->content = new stdClass();

            // TODO: add capability check here?

            $renderer = $this->page->get_renderer('block_related_sections');

            $parentcontext = get_context_instance_by_id($this->instance->parentcontextid);
            $courseid = get_courseid_from_context($parentcontext);
            $this->content->text = $renderer->related_sections($courseid);
            $this->content->footer = '';

        }
        return $this->content;
    }

    /**
     * Standard function - does the block allow configuration for specific instances of itself
     * rather than sitewide?
     *
     * @return bool false
     */
    public function instance_allow_config() {
        return false;
    }
}
