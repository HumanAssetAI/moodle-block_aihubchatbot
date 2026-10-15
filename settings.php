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
 * Global settings for the AIHub Chatbot block.
 *
 * @package   block_aihubchatbot
 * @copyright 2026 Human Asset
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if (!\block_aihubchatbot\access::can_configure()) {
    $settings = null;
} else if ($hassiteconfig) {
    $settings->add(new admin_setting_configtext(
        'block_aihubchatbot/aihubbaseurl',
        get_string('settings:aihubbaseurl', 'block_aihubchatbot'),
        get_string('settings:aihubbaseurl_desc', 'block_aihubchatbot'),
        'https://aihub.humanasset.com',
        PARAM_URL
    ));

    $settings->add(new admin_setting_configpasswordunmask(
        'block_aihubchatbot/sharedsecret',
        get_string('settings:sharedsecret', 'block_aihubchatbot'),
        get_string('settings:sharedsecret_desc', 'block_aihubchatbot'),
        ''
    ));

    $settings->add(new admin_setting_configtext(
        'block_aihubchatbot/defaultheight',
        get_string('settings:defaultheight', 'block_aihubchatbot'),
        get_string('settings:defaultheight_desc', 'block_aihubchatbot'),
        650,
        PARAM_INT
    ));
}
