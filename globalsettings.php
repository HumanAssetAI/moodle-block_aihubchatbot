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
 * Protected global settings page, also accessible to system Managers.
 *
 * @package block_aihubchatbot
 * @copyright 2026 Human Asset
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/../../config.php');
require_login();
\block_aihubchatbot\access::require_configuration_access();

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/blocks/aihubchatbot/globalsettings.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('globalsettings', 'block_aihubchatbot'));
$PAGE->set_heading(get_string('globalsettings', 'block_aihubchatbot'));

$form = new \block_aihubchatbot\form\global_settings();
$defaults = ['aihubbaseurl' => 'https://aihub.humanasset.com', 'sharedsecret' => '', 'defaultheight' => 650];
$values = new stdClass();
foreach ($defaults as $name => $default) {
    $stored = get_config('block_aihubchatbot', $name);
    $values->$name = $stored === false ? $default : $stored;
}
$form->set_data($values);

if ($data = $form->get_data()) {
    require_sesskey();
    \block_aihubchatbot\access::require_configuration_access();
    foreach ($defaults as $name => $default) {
        set_config($name, $data->$name, 'block_aihubchatbot');
    }
    redirect($PAGE->url, get_string('changessaved'), null, \core\output\notification::NOTIFY_SUCCESS);
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('globalsettings', 'block_aihubchatbot'));
$form->display();
echo $OUTPUT->footer();
