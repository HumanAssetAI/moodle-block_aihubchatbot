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
 * Validates, crops and stores a per-block launcher image.
 *
 * @package block_aihubchatbot
 * @copyright 2026 Human Asset
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class launcher_image {
    /** @var int Output resolution, displayed at 26 pixels for sharp high-density icons. */
    public const SIZE = 52;
    /** @var int Maximum upload size. */
    public const MAX_BYTES = 5242880;

    /**
     * Filemanager options shared by the edit form and draft preparation.
     *
     * @return array
     */
    public static function options(): array {
        global $CFG;
        require_once($CFG->dirroot . '/repository/lib.php');
        return ['subdirs' => false, 'maxfiles' => 1, 'maxbytes' => self::MAX_BYTES,
            'accepted_types' => ['.png', '.jpg', '.jpeg'], 'return_types' => \FILE_INTERNAL];
    }

    /**
     * Decode a real PNG/JPEG and produce a transparent square PNG.
     *
     * Sampling the centred source square directly is equivalent to scaling its
     * smaller dimension to SIZE and then cropping the scaled longer dimension.
     * It avoids allocating a very wide/tall intermediate bitmap.
     *
     * @param string $content Uploaded bytes.
     * @return string Re-encoded PNG bytes without original metadata.
     */
    public static function crop(string $content): string {
        $info = @getimagesizefromstring($content);
        if (strlen($content) > self::MAX_BYTES || !$info ||
                !in_array($info[2], [IMAGETYPE_PNG, IMAGETYPE_JPEG], true) ||
                $info[0] < 1 || $info[1] < 1 || $info[0] > 4096 || $info[1] > 4096 ||
                $info[0] * $info[1] > 4194304) {
            throw new \moodle_exception('invalidlauncherimage', 'block_aihubchatbot');
        }
        if (!function_exists('imagecreatefromstring')) {
            throw new \moodle_exception('launchergdrequired', 'block_aihubchatbot');
        }
        $source = @imagecreatefromstring($content);
        if (!$source) {
            throw new \moodle_exception('invalidlauncherimage', 'block_aihubchatbot');
        }
        $target = imagecreatetruecolor(self::SIZE, self::SIZE);
        try {
            imagealphablending($target, false);
            imagesavealpha($target, true);
            imagefill($target, 0, 0, imagecolorallocatealpha($target, 0, 0, 0, 127));
            $side = min($info[0], $info[1]);
            $left = (int) floor(($info[0] - $side) / 2);
            $top = (int) floor(($info[1] - $side) / 2);
            imagecopyresampled($target, $source, 0, 0, $left, $top, self::SIZE, self::SIZE, $side, $side);
            ob_start();
            try {
                imagepng($target);
                return ob_get_contents();
            } finally {
                ob_end_clean();
            }
        } finally {
            unset($source, $target);
        }
    }

    /**
     * Read only the current user's draft area, rejecting multiple uploads.
     *
     * @param int $draftid Draft filemanager item ID.
     * @return string|null Processed PNG or null for an empty selection.
     */
    public static function draft_content(int $draftid): ?string {
        global $USER;
        $files = get_file_storage()->get_area_files(
            \context_user::instance($USER->id)->id, 'user', 'draft', $draftid, 'id', false
        );
        if (count($files) > 1) {
            throw new \moodle_exception('invalidlauncherimage', 'block_aihubchatbot');
        }
        if (!$files) {
            return null;
        }
        $file = reset($files);
        if ($file->get_filesize() > self::MAX_BYTES) {
            throw new \moodle_exception('invalidlauncherimage', 'block_aihubchatbot');
        }
        return self::crop($file->get_content());
    }

    /**
     * Replace the processed image after validating it; never store the original.
     *
     * @param int $contextid Block context ID.
     * @param string|null $content Processed PNG, or null to remove the image.
     * @return void
     */
    public static function store(int $contextid, ?string $content): void {
        access::require_configuration_access();
        $fs = get_file_storage();
        $fs->delete_area_files($contextid, 'block_aihubchatbot', 'launchericon', 0);
        if ($content !== null) {
            $fs->create_file_from_string([
                'contextid' => $contextid, 'component' => 'block_aihubchatbot', 'filearea' => 'launchericon',
                'itemid' => 0, 'filepath' => '/', 'filename' => 'launcher.png', 'mimetype' => 'image/png',
                'userid' => 0,
            ], $content);
        }
    }

    /**
     * Get an authenticated Moodle file URL with a content revision.
     *
     * @param int $contextid Block context ID.
     * @return string Empty when no image is stored.
     */
    public static function url(int $contextid): string {
        $file = get_file_storage()->get_file($contextid, 'block_aihubchatbot', 'launchericon', 0, '/', 'launcher.png');
        if (!$file) {
            return '';
        }
        $url = \moodle_url::make_pluginfile_url($contextid, 'block_aihubchatbot', 'launchericon', 0, '/', 'launcher.png');
        $url->param('rev', $file->get_contenthash());
        return $url->out(false);
    }
}
