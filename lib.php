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
 * Serve processed launcher icons through Moodle's authenticated File API.
 *
 * @package block_aihubchatbot
 * @copyright 2026 Human Asset
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * File serving callback; viewing an icon does not require configuration rights.
 *
 * @param stdClass $course Course record.
 * @param stdClass $block Block instance record.
 * @param context $context Block context.
 * @param string $filearea Requested file area.
 * @param array $args Requested item ID and filename.
 * @param bool $forcedownload Force download flag.
 * @param array $options File serving options.
 * @return bool False for an invalid request.
 */
function block_aihubchatbot_pluginfile($course, $block, $context, $filearea, $args, $forcedownload, array $options = []) {
    if ($context->contextlevel !== CONTEXT_BLOCK || $block->blockname !== 'aihubchatbot' ||
            $filearea !== 'launchericon' || count($args) !== 2 || (string) $args[0] !== '0' ||
            $args[1] !== 'launcher.png' || !$context->get_course_context(false)) {
        return false;
    }
    require_course_login($course);
    if (isguestuser() || !has_capability('moodle/block:view', $context)) {
        return false;
    }
    $file = get_file_storage()->get_file($context->id, 'block_aihubchatbot', 'launchericon', 0, '/', 'launcher.png');
    if (!$file || $file->is_directory()) {
        return false;
    }
    \core\session\manager::write_close();
    send_stored_file($file, 0, 0, $forcedownload, $options);
}
