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

namespace block_aihubchatbot\form;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

/**
 * Global configuration form for administrators and system Managers.
 *
 * @package block_aihubchatbot
 * @copyright 2026 Human Asset
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class global_settings extends \moodleform {
    /**
     * Define the three global plugin settings.
     *
     * @return void
     */
    public function definition() {
        \block_aihubchatbot\access::require_configuration_access();
        $mform = $this->_form;
        foreach (['aihubbaseurl', 'sharedsecret', 'defaultheight'] as $name) {
            $type = $name === 'sharedsecret' ? 'passwordunmask' : 'text';
            $mform->addElement($type, $name, get_string('settings:' . $name, 'block_aihubchatbot'));
            $mform->setType($name, $name === 'defaultheight' ? PARAM_INT : PARAM_RAW_TRIMMED);
            $mform->addElement('static', $name . 'description', '',
                get_string('settings:' . $name . '_desc', 'block_aihubchatbot'));
        }
        $this->add_action_buttons(false, get_string('savechanges'));
    }

    /**
     * Validate global settings before saving them.
     *
     * @param array $data Submitted values.
     * @param array $files Submitted files.
     * @return array
     */
    public function validation($data, $files) {
        \block_aihubchatbot\access::require_configuration_access();
        $errors = parent::validation($data, $files);
        $url = $data['aihubbaseurl'];
        if (!filter_var($url, FILTER_VALIDATE_URL) || strtolower((string) parse_url($url, PHP_URL_SCHEME)) !== 'https'
                || parse_url($url, PHP_URL_USER) !== null || parse_url($url, PHP_URL_PASS) !== null) {
            $errors['aihubbaseurl'] = get_string('invalidbaseurl', 'block_aihubchatbot');
        }
        if ($data['defaultheight'] < 320 || $data['defaultheight'] > 1200) {
            $errors['defaultheight'] = get_string('invaliddefaultheight', 'block_aihubchatbot');
        }
        return $errors;
    }
}
