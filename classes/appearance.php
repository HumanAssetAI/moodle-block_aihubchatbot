<?php
// This file is part of Moodle - http://moodle.org/
// Licensed under the GNU GPL v3 or later: http://www.gnu.org/copyleft/gpl.html

namespace block_aihubchatbot;

defined('MOODLE_INTERNAL') || die();

/**
 * Validated launcher appearance settings.
 * @package block_aihubchatbot
 * @copyright 2026 Human Asset
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class appearance {
    /** @var array Allowed icons from Moodle's bundled Font Awesome library. */
    public const ICONS = [
        'chat' => 'fa-comment',
        'robot' => 'fa-robot',
        'education' => 'fa-graduation-cap',
        'help' => 'fa-circle-question',
        'idea' => 'fa-lightbulb',
    ];

    /** Check a six-digit hexadecimal colour without accepting CSS expressions. */
    public static function valid_color($value): bool {
        return is_string($value) && preg_match('/^#[0-9a-fA-F]{6}$/D', $value) === 1;
    }

    /** Return safe appearance settings, including defaults for existing blocks. */
    public static function configuration($config): array {
        $icon = $config->launchericon ?? 'chat';
        $color = $config->launchercolor ?? '';
        return [
            'useThemeColor' => !isset($config->usethemecolor) || !empty($config->usethemecolor),
            'launcherColor' => self::valid_color($color) ? strtolower($color) : '#155eef',
            'launcherIcon' => is_string($icon) && (isset(self::ICONS[$icon]) || $icon === 'image') ? $icon : 'chat',
        ];
    }
}
