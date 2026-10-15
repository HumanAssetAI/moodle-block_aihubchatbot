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
 * AIHub Chatbot block definition.
 *
 * @package   block_aihubchatbot
 * @copyright 2026 Human Asset
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Renders one configured AIHub chatbot embed inside a Moodle course block.
 */
class block_aihubchatbot extends block_base {
    /**
     * Hide the title when the page is being viewed normally.
     *
     * @return bool
     */
    public function hide_header() {
        return !$this->page->user_is_editing();
    }

    /**
     * Restrict instance configuration to administrators and system Managers.
     *
     * @return bool
     */
    public function user_can_edit() {
        return \block_aihubchatbot\access::can_configure() && parent::user_can_edit();
    }

    /**
     * Restrict block editing controls, including positioning settings.
     *
     * @return bool
     */
    public function instance_can_be_edited() {
        return \block_aihubchatbot\access::can_configure() && parent::instance_can_be_edited();
    }

    /**
     * Restrict creation of new instances.
     *
     * @param moodle_page $page Target page.
     * @return bool
     */
    public function user_can_addto($page) {
        return \block_aihubchatbot\access::can_configure() && parent::user_can_addto($page);
    }

    /**
     * Check authorization again before writing instance configuration.
     *
     * @param stdClass $data Instance configuration.
     * @param bool $nolongerused Legacy argument.
     * @return void
     */
    public function instance_config_save($data, $nolongerused = false) {
        global $DB;
        \block_aihubchatbot\access::require_configuration_access();
        if (isset($data->launcherimage)) {
            $image = \block_aihubchatbot\launcher_image::draft_content((int) $data->launcherimage);
            if (($data->launchericon ?? 'chat') === 'image' && $image === null) {
                throw new moodle_exception('launcherimagerequired', 'block_aihubchatbot');
            }
            $transaction = $DB->start_delegated_transaction();
            \block_aihubchatbot\launcher_image::store($this->context->id, $image);
            unset($data->launcherimage);
            parent::instance_config_save($data, $nolongerused);
            $transaction->allow_commit();
            return;
        }
        parent::instance_config_save($data, $nolongerused);
    }

    /**
     * Remove the empty course block container for a working floating embed.
     *
     * @return array
     */
    public function html_attributes() {
        $attributes = parent::html_attributes();
        $content = $this->get_content();
        if (!$this->page->user_is_editing() &&
                strpos($content->text, 'block_aihubchatbot_floating_mount') !== false) {
            $attributes['class'] .= ' block_aihubchatbot_floating_only';
        }
        return $attributes;
    }

    /**
     * Initialise the block title.
     *
     * @return void
     */
    public function init() {
        $this->title = get_string('defaulttitle', 'block_aihubchatbot');
    }

    /**
     * Apply per-instance title after config is available.
     *
     * @return void
     */
    public function specialization() {
        if (!empty($this->config->title)) {
            $this->title = format_string($this->config->title);
        }
    }

    /**
     * Allow global plugin settings.
     *
     * @return bool
     */
    public function has_config() {
        return true;
    }

    /**
     * Allow multiple AIHub chatbot blocks in the same course.
     *
     * @return bool
     */
    public function instance_allow_multiple() {
        return true;
    }

    /**
     * Restrict the block to course pages.
     *
     * @return array
     */
    public function applicable_formats() {
        return [
            'all' => false,
            'course-view' => true,
        ];
    }

    /**
     * Build the block content.
     *
     * @return stdClass
     */
    public function get_content() {
        global $COURSE, $PAGE, $USER;

        if ($this->content !== null) {
            return $this->content;
        }

        $PAGE->requires->css('/blocks/aihubchatbot/styles.css');

        $this->content = new stdClass();
        $this->content->footer = '';
        if ($this->page->user_is_editing() && \block_aihubchatbot\access::can_configure()) {
            $this->content->footer = html_writer::link(
                new moodle_url('/blocks/aihubchatbot/globalsettings.php'),
                get_string('globalsettings', 'block_aihubchatbot')
            );
        }

        if (empty($this->config->embedcode)) {
            $message = get_string('missingembedcode', 'block_aihubchatbot');
            if (!empty($this->context) && has_capability('block/aihubchatbot:configure', $this->context)) {
                $message = get_string('missingembedcodeconfigure', 'block_aihubchatbot');
            }

            $this->content->text = html_writer::div($message, 'block_aihubchatbot_notice');
            return $this->content;
        }

        if (!isloggedin() || isguestuser()) {
            $this->content->text = html_writer::div(
                get_string('loginrequired', 'block_aihubchatbot'),
                'block_aihubchatbot_notice'
            );
            return $this->content;
        }

        if (!\block_aihubchatbot\embed_code::has_shared_secret()) {
            $this->content->text = html_writer::div(
                get_string('sharedsecretrequired', 'block_aihubchatbot'),
                'block_aihubchatbot_error'
            );
            return $this->content;
        }

        $height = $this->get_configured_height();
        $payload = \block_aihubchatbot\embed_code::build_context_payload($COURSE, $USER, $this->instance);

        if ($this->get_display_mode() === 'floating') {
            $iframe = \block_aihubchatbot\embed_code::prepare_iframe($this->config->embedcode, $payload);

            if (!empty($iframe['error'])) {
                $this->content->text = \block_aihubchatbot\embed_code::render($this->config->embedcode, $payload, $height);
                return $this->content;
            }

            $instanceid = !empty($this->instance->id) ? (string) $this->instance->id : '0';
            $safeinstanceid = clean_param($instanceid, PARAM_ALPHANUMEXT);

            $appearance = \block_aihubchatbot\appearance::configuration($this->config);
            if ($appearance['launcherIcon'] === 'image') {
                $appearance['launcherImageUrl'] = \block_aihubchatbot\launcher_image::url($this->context->id);
            }
            $PAGE->requires->js_call_amd('block_aihubchatbot/floating', 'init', [[
                'src' => $iframe['src'],
                'origin' => \block_aihubchatbot\embed_code::get_aihub_origin(),
                'instanceId' => $safeinstanceid,
                'title' => get_string('iframetitle', 'block_aihubchatbot'),
                'allow' => 'microphone; clipboard-write',
                'openLabel' => get_string('floatingopen', 'block_aihubchatbot'),
                'closeLabel' => get_string('floatingclose', 'block_aihubchatbot'),
                'appearance' => $appearance,
                'courseKey' => $payload['moodle_user_hash'] . ':' . (string) $COURSE->id,
                'autoOpenOnce' => !$this->page->user_is_editing() &&
                    (!isset($this->config->autoopenonce) || !empty($this->config->autoopenonce)),
                'showLauncherLabel' => !isset($this->config->showlauncherlabel) || !empty($this->config->showlauncherlabel),
                'launcherLabel' => !empty($this->config->launcherlabel) ? (string) $this->config->launcherlabel :
                    get_string('defaultlauncherlabel', 'block_aihubchatbot'),
                'allowDragResize' => !isset($this->config->allowdragresize) || !empty($this->config->allowdragresize),
                'allowExpand' => !isset($this->config->allowexpand) || !empty($this->config->allowexpand),
                'labels' => [
                    'minimize' => get_string('windowminimize', 'block_aihubchatbot'),
                    'expand' => get_string('windowexpand', 'block_aihubchatbot'),
                    'restore' => get_string('windowrestore', 'block_aihubchatbot'),
                    'reset' => get_string('windowreset', 'block_aihubchatbot'),
                    'move' => get_string('windowmove', 'block_aihubchatbot'),
                    'resize' => get_string('windowresize', 'block_aihubchatbot'),
                ],
            ]]);

            $this->content->text = html_writer::div('', 'block_aihubchatbot_floating_mount', [
                'id' => 'block_aihubchatbot_mount_' . $safeinstanceid,
                'aria-hidden' => 'true',
            ]);
            return $this->content;
        }

        $this->content->text = \block_aihubchatbot\embed_code::render($this->config->embedcode, $payload, $height);

        return $this->content;
    }

    /**
     * Read the configured display mode.
     *
     * @return string
     */
    private function get_display_mode(): string {
        $displaymode = !empty($this->config->displaymode) ? (string) $this->config->displaymode : 'floating';
        return $displaymode === 'floating' ? 'floating' : 'inline';
    }

    /**
     * Read and clamp the configured iframe height.
     *
     * @return int
     */
    private function get_configured_height(): int {
        $height = 0;

        if (!empty($this->config->height)) {
            $height = (int) $this->config->height;
        }

        if ($height <= 0) {
            $height = (int) get_config('block_aihubchatbot', 'defaultheight');
        }

        if ($height <= 0) {
            $height = 650;
        }

        return max(320, min($height, 1200));
    }
}
