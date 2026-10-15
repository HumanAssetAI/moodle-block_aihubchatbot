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

/**
 * Include the block's processed launcher file in course backups.
 *
 * @package block_aihubchatbot
 * @copyright 2026 Human Asset
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_aihubchatbot_block_task extends backup_block_task {
    /** No additional backup settings. */
    protected function define_my_settings() {
    }
    /** Core handles configdata and the declared file areas. */
    protected function define_my_steps() {
    }
    /** @return array File areas included by core. */
    public function get_fileareas() {
        return ['launchericon'];
    }
    /** @return array No embedded file URLs are stored in configdata. */
    public function get_configdata_encoded_attributes() {
        return [];
    }
    /** @param string $content Content. @return string Unchanged content. */
    public static function encode_content_links($content) {
        return $content;
    }
}
