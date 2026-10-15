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

namespace block_aihubchatbot\privacy;

use core_privacy\local\metadata\collection;

defined('MOODLE_INTERNAL') || die();

/**
 * Privacy metadata for the AIHub Chatbot block.
 *
 * The block does not create custom Moodle database tables, but it exports Moodle
 * context fields to the configured AIHub embed endpoint.
 *
 * @package   block_aihubchatbot
 * @copyright 2026 Human Asset
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements \core_privacy\local\metadata\provider {
    /**
     * Describe external user data sent to AIHub.
     *
     * @param collection $collection Privacy metadata collection.
     * @return collection
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_subsystem_link('core_files', [], 'privacy:metadata:launcherfiles');
        $collection->add_external_location_link('aihub', [
            'moodle_user_hash' => 'privacy:metadata:aihub:moodle_user_hash',
            'moodle_course_id' => 'privacy:metadata:aihub:moodle_course_id',
            'moodle_course_shortname' => 'privacy:metadata:aihub:moodle_course_shortname',
            'moodle_block_instance_id' => 'privacy:metadata:aihub:moodle_block_instance_id',
            'moodle_site_url' => 'privacy:metadata:aihub:moodle_site_url',
            'moodle_context_timestamp' => 'privacy:metadata:aihub:moodle_context_timestamp',
            'moodle_context_signature' => 'privacy:metadata:aihub:moodle_context_signature',
        ], 'privacy:metadata:aihub');

        return $collection;
    }
}
