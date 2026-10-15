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

namespace block_aihubchatbot;

defined('MOODLE_INTERNAL') || die();

/**
 * Configuration authorization shared by every entry point.
 *
 * @package block_aihubchatbot
 * @copyright 2026 Human Asset
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class access {
    /**
     * Allow site administrators or a Manager archetype assigned at system level.
     *
     * Course/category Managers and delegated teacher capabilities do not qualify.
     *
     * @return bool
     */
    public static function can_configure(): bool {
        global $USER;

        if (!isloggedin() || isguestuser()) {
            return false;
        }
        if (is_siteadmin()) {
            return true;
        }
        $managerroles = get_archetype_roles('manager');
        foreach (get_user_roles(\context_system::instance(), $USER->id, false) as $role) {
            if (isset($managerroles[$role->roleid])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Reject unauthorized form access or writes.
     *
     * @return void
     */
    public static function require_configuration_access(): void {
        if (!self::can_configure()) {
            throw new \required_capability_exception(
                \context_system::instance(), 'block/aihubchatbot:configure', 'nopermissions', ''
            );
        }
    }
}
