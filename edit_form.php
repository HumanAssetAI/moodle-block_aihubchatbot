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
 * Instance settings form for the AIHub Chatbot block.
 *
 * @package   block_aihubchatbot
 * @copyright 2026 Human Asset
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Defines per-course block instance settings.
 */
class block_aihubchatbot_edit_form extends block_edit_form {
    /**
     * Add fields to the block configuration form.
     *
     * @param MoodleQuickForm $mform Moodle form instance.
     * @return void
     */
    protected function specific_definition($mform) {
        global $PAGE;
        \block_aihubchatbot\access::require_configuration_access();
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
        $mform->addElement('static', 'globalsettingslink', '', html_writer::link(
            new moodle_url('/blocks/aihubchatbot/globalsettings.php'),
            get_string('globalsettings', 'block_aihubchatbot')
        ));

        $mform->addElement('text', 'config_title', get_string('configtitle', 'block_aihubchatbot'));
        $mform->setType('config_title', PARAM_TEXT);
        $mform->setDefault('config_title', '');
        $mform->addHelpButton('config_title', 'configtitle', 'block_aihubchatbot');

        $mform->addElement(
            'textarea',
            'config_embedcode',
            get_string('configembedcode', 'block_aihubchatbot'),
            ['rows' => 8, 'cols' => 70]
        );
        $mform->setType('config_embedcode', PARAM_RAW_TRIMMED);
        $mform->addHelpButton('config_embedcode', 'configembedcode', 'block_aihubchatbot');
        $mform->addRule('config_embedcode', get_string('required'), 'required', null, 'client');

        $mform->addElement('select', 'config_displaymode', get_string('configdisplaymode', 'block_aihubchatbot'), [
            'inline' => get_string('displaymodeinline', 'block_aihubchatbot'),
            'floating' => get_string('displaymodefloating', 'block_aihubchatbot'),
        ]);
        $mform->setType('config_displaymode', PARAM_ALPHA);
        $mform->setDefault('config_displaymode', 'floating');
        $mform->addHelpButton('config_displaymode', 'configdisplaymode', 'block_aihubchatbot');
        foreach (['showlauncherlabel', 'autoopenonce', 'allowdragresize', 'allowexpand'] as $setting) {
            $mform->addElement('advcheckbox', 'config_' . $setting, get_string($setting, 'block_aihubchatbot'));
            $mform->setDefault('config_' . $setting, 1);
            $mform->disabledIf('config_' . $setting, 'config_displaymode', 'eq', 'inline');
        }
        $mform->addElement('text', 'config_launcherlabel', get_string('launcherlabel', 'block_aihubchatbot'),
            ['maxlength' => 80]);
        $mform->setType('config_launcherlabel', PARAM_TEXT);
        $mform->setDefault('config_launcherlabel', '');
        $mform->addHelpButton('config_launcherlabel', 'launcherlabel', 'block_aihubchatbot');
        $mform->addHelpButton('config_autoopenonce', 'autoopenonce', 'block_aihubchatbot');

        $mform->addElement('advcheckbox', 'config_usethemecolor',
            get_string('usethemecolor', 'block_aihubchatbot'));
        $mform->setDefault('config_usethemecolor', 1);
        $mform->addElement('text', 'config_launchercolor', get_string('launchercolor', 'block_aihubchatbot'),
            ['maxlength' => 7, 'size' => 8]);
        $mform->setType('config_launchercolor', PARAM_RAW_TRIMMED);
        $mform->setDefault('config_launchercolor', '#155eef');
        $mform->disabledIf('config_launchercolor', 'config_usethemecolor', 'checked');

        $icons = [];
        foreach (\block_aihubchatbot\appearance::ICONS as $key => $icon) {
            $label = html_writer::tag('i', '', ['class' => 'fa fa-solid ' . $icon, 'aria-hidden' => 'true']) .
                ' ' . get_string('launchericon_' . $key, 'block_aihubchatbot');
            $icons[] = $mform->createElement('radio', 'config_launchericon', '', $label, $key);
        }
        $icons[] = $mform->createElement('radio', 'config_launchericon', '',
            get_string('launchericon_image', 'block_aihubchatbot'), 'image');
        $mform->addGroup($icons, 'launchericons', get_string('launchericon', 'block_aihubchatbot'), ' ', false);
        $mform->setType('config_launchericon', PARAM_ALPHA);
        $mform->setDefault('config_launchericon', 'chat');
        $mform->addElement('filemanager', 'config_launcherimage',
            get_string('launcherimage', 'block_aihubchatbot'), null, \block_aihubchatbot\launcher_image::options());
        $mform->addHelpButton('config_launcherimage', 'launcherimage', 'block_aihubchatbot');
        $PAGE->requires->js_call_amd('block_aihubchatbot/appearance', 'initForm');

        $defaultheight = (int) get_config('block_aihubchatbot', 'defaultheight');
        if ($defaultheight <= 0) {
            $defaultheight = 650;
        }

        $mform->addElement('text', 'config_height', get_string('configheight', 'block_aihubchatbot'));
        $mform->setType('config_height', PARAM_INT);
        $mform->setDefault('config_height', $defaultheight);
        $mform->addHelpButton('config_height', 'configheight', 'block_aihubchatbot');

        $mform->addElement('static', 'config_contextnotice', '', get_string('contextnotice', 'block_aihubchatbot'));
    }

    /** Validate custom appearance values before saving the block configuration. */
    public function validation($data, $files) {
        \block_aihubchatbot\access::require_configuration_access();
        $errors = parent::validation($data, $files);
        if (empty($data['config_usethemecolor']) &&
                !\block_aihubchatbot\appearance::valid_color($data['config_launchercolor'] ?? '')) {
            $errors['config_launchercolor'] = get_string('invalidlaunchercolor', 'block_aihubchatbot');
        }
        if (($data['config_launchericon'] ?? 'chat') !== 'image' &&
                !isset(\block_aihubchatbot\appearance::ICONS[$data['config_launchericon'] ?? 'chat'])) {
            $errors['launchericons'] = get_string('invalidlaunchericon', 'block_aihubchatbot');
        }
        try {
            $image = \block_aihubchatbot\launcher_image::draft_content((int) ($data['config_launcherimage'] ?? 0));
            if (($data['config_launchericon'] ?? 'chat') === 'image' && $image === null) {
                $errors['config_launcherimage'] = get_string('launcherimagerequired', 'block_aihubchatbot');
            }
        } catch (moodle_exception $exception) {
            $errors['config_launcherimage'] = $exception->getMessage();
        }
        return $errors;
    }

    /**
     * Prepare the stored image for Moodle's draft filemanager when editing.
     *
     * @param stdClass $defaults Form defaults.
     * @return void
     */
    public function set_data($defaults) {
        \block_aihubchatbot\access::require_configuration_access();
        $draftid = file_get_submitted_draft_itemid('config_launcherimage');
        file_prepare_draft_area($draftid, $this->block->context->id, 'block_aihubchatbot',
            'launchericon', 0, \block_aihubchatbot\launcher_image::options());
        $defaults->config_launcherimage = $draftid;
        parent::set_data($defaults);
    }
}
