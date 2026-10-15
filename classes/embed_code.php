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
 * Sanitises AIHub embed code and injects Moodle context values.
 *
 * @package   block_aihubchatbot
 * @copyright 2026 Human Asset
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class embed_code {
    /**
     * Check whether the signing secret required for secure embeds is configured.
     *
     * @return bool
     */
    public static function has_shared_secret(): bool {
        return self::get_shared_secret() !== '';
    }

    /**
     * Build the Moodle context fields sent to AIHub.
     *
     * @param \stdClass $course Moodle course record.
     * @param \stdClass $user Moodle user record.
     * @param \stdClass|null $blockinstance Moodle block instance record.
     * @return array
     */
    public static function build_context_payload(\stdClass $course, \stdClass $user, ?\stdClass $blockinstance): array {
        global $CFG;

        $secret = self::get_shared_secret();

        $payload = [
            'moodle_user_hash' => hash_hmac('sha256', $CFG->wwwroot . ':' . (string) $user->id, $secret),
            'moodle_course_id' => (string) $course->id,
            'moodle_course_shortname' => isset($course->shortname) ? (string) $course->shortname : '',
            'moodle_block_instance_id' => !empty($blockinstance->id) ? (string) $blockinstance->id : '',
            'moodle_site_url' => (string) $CFG->wwwroot,
            'moodle_context_timestamp' => (string) time(),
        ];

        $payload['moodle_context_signature'] = self::sign_payload($payload, $secret);

        return $payload;
    }

    /**
     * Prepare a signed iframe URL from a pasted AIHub embed code or plain URL.
     *
     * @param string $embedcode AIHub embed code copied into the block settings.
     * @param array $payload Moodle context fields to pass to AIHub.
     * @return array Signed iframe data, or an error identifier in the error key.
     */
    public static function prepare_iframe(string $embedcode, array $payload): array {
        $embedcode = trim($embedcode);

        if ($embedcode === '') {
            return ['error' => 'invalidembedcode'];
        }

        $tag = self::extract_first_tag($embedcode);
        $attributes = [];
        $src = '';

        if ($tag === null) {
            if (!self::looks_like_url($embedcode)) {
                return ['error' => 'invalidembedcode'];
            }

            $src = $embedcode;
        } else {
            [$tagname, $taghtml] = $tag;
            if ($tagname !== 'iframe') {
                return ['error' => 'scriptembeddisabled'];
            }

            $attributes = self::extract_attributes($taghtml);
            $src = $attributes['src'] ?? '';
        }

        if (!self::is_allowed_src($src)) {
            return ['error' => 'untrustedembedcode'];
        }

        return [
            'src' => self::append_query_params($src, $payload),
            'attributes' => $attributes,
        ];
    }

    /**
     * Get the configured AIHub origin for strict postMessage targeting.
     *
     * @return string
     */
    public static function get_aihub_origin(): string {
        $configuredbase = self::get_configured_base_url();
        $host = parse_url($configuredbase, PHP_URL_HOST);
        $port = parse_url($configuredbase, PHP_URL_PORT);

        if (!$host) {
            $host = 'aihub.humanasset.com';
            $port = null;
        }

        return 'https://' . strtolower($host) . ($port ? ':' . $port : '');
    }

    /**
     * Render validated AIHub embed HTML.
     *
     * @param string $embedcode AIHub embed code copied into the block settings.
     * @param array $payload Moodle context fields to pass to AIHub.
     * @param int $height Iframe height.
     * @return string
     */
    public static function render(string $embedcode, array $payload, int $height): string {
        $iframe = self::prepare_iframe($embedcode, $payload);

        if (!empty($iframe['error'])) {
            return self::error($iframe['error']);
        }

        return self::render_iframe($iframe['src'], $iframe['attributes'], $height);
    }

    /**
     * Sign context fields with the shared secret configured by the Moodle admin.
     *
     * @param array $payload Context payload.
     * @param string $secret Shared secret.
     * @return string
     */
    private static function sign_payload(array $payload, string $secret): string {
        ksort($payload);
        $canonical = http_build_query($payload, '', '&', PHP_QUERY_RFC3986);
        return hash_hmac('sha256', $canonical, $secret);
    }

    /**
     * Extract the first iframe or script tag.
     *
     * @param string $html User supplied embed code.
     * @return array|null
     */
    private static function extract_first_tag(string $html): ?array {
        if (!preg_match('/<(iframe|script)\b[^>]*>/i', $html, $matches)) {
            return null;
        }

        return [strtolower($matches[1]), $matches[0]];
    }

    /**
     * Extract attributes from one tag.
     *
     * @param string $taghtml Opening HTML tag.
     * @return array
     */
    private static function extract_attributes(string $taghtml): array {
        $attributes = [];

        preg_match_all(
            '/([a-zA-Z_:][-a-zA-Z0-9_:.]*)(?:\s*=\s*(?:"([^"]*)"|\'([^\']*)\'|([^\s"\'=<>`]+)))?/',
            $taghtml,
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $match) {
            $name = strtolower($match[1]);
            if (in_array($name, ['iframe', 'script'], true)) {
                continue;
            }

            $value = '';
            if (array_key_exists(2, $match) && $match[2] !== '') {
                $value = $match[2];
            } else if (array_key_exists(3, $match) && $match[3] !== '') {
                $value = $match[3];
            } else if (array_key_exists(4, $match) && $match[4] !== '') {
                $value = $match[4];
            }

            $attributes[$name] = $value;
        }

        return $attributes;
    }

    /**
     * Render an iframe embed with Moodle context query parameters.
     *
     * @param string $src Signed iframe URL.
     * @param array $originalattributes Attributes from the pasted embed.
     * @param int $height Iframe height.
     * @return string
     */
    private static function render_iframe(string $src, array $originalattributes, int $height): string {
        if (!self::is_allowed_src($src)) {
            return self::error('untrustedembedcode');
        }

        $attributes = [
            'src' => $src,
            'class' => 'block_aihubchatbot_iframe',
            'title' => get_string('iframetitle', 'block_aihubchatbot'),
            'width' => '100%',
            'height' => (string) $height,
            'loading' => 'eager',
            'allow' => 'microphone; clipboard-write',
            'referrerpolicy' => 'strict-origin-when-cross-origin',
        ];

        return \html_writer::div(\html_writer::tag('iframe', '', $attributes), 'block_aihubchatbot_embed');
    }

    /**
     * Append Moodle context as URL query parameters.
     *
     * @param string $url URL to extend.
     * @param array $params Query parameters.
     * @return string
     */
    private static function append_query_params(string $url, array $params): string {
        $fragment = '';
        $hashposition = strpos($url, '#');

        if ($hashposition !== false) {
            $fragment = substr($url, $hashposition);
            $url = substr($url, 0, $hashposition);
        }

        $separator = strpos($url, '?') === false ? '?' : '&';

        return $url . $separator . http_build_query($params, '', '&', PHP_QUERY_RFC3986) . $fragment;
    }

    /**
     * Check whether a src URL belongs to the configured AIHub host.
     *
     * @param string $src URL to validate.
     * @return bool
     */
    private static function is_allowed_src(string $src): bool {
        if (!self::looks_like_url($src)) {
            return false;
        }

        $configuredbase = self::get_configured_base_url();
        $allowedhost = parse_url($configuredbase, PHP_URL_HOST);
        $srchost = parse_url($src, PHP_URL_HOST);
        $scheme = parse_url($src, PHP_URL_SCHEME);
        $path = parse_url($src, PHP_URL_PATH);

        if (!$allowedhost || !$srchost || !$scheme || !$path) {
            return false;
        }

        return strtolower($scheme) === 'https'
            && strcasecmp($allowedhost, $srchost) === 0
            && ($path === '/embed' || strpos($path, '/embed/') === 0);
    }

    /**
     * Read the configured AIHub base URL.
     *
     * @return string
     */
    private static function get_configured_base_url(): string {
        $configuredbase = get_config('block_aihubchatbot', 'aihubbaseurl');
        if (!is_string($configuredbase) || trim($configuredbase) === '') {
            return 'https://aihub.humanasset.com';
        }

        return trim($configuredbase);
    }

    /**
     * Read the shared signing secret without accidental surrounding whitespace.
     *
     * @return string
     */
    private static function get_shared_secret(): string {
        $secret = get_config('block_aihubchatbot', 'sharedsecret');
        if (!is_string($secret)) {
            return '';
        }

        return trim($secret);
    }

    /**
     * Lightweight URL check before parsing.
     *
     * @param string $value Value to check.
     * @return bool
     */
    private static function looks_like_url(string $value): bool {
        return (bool) filter_var(trim($value), FILTER_VALIDATE_URL);
    }

    /**
     * Render an error message in the block.
     *
     * @param string $identifier Language string identifier.
     * @return string
     */
    private static function error(string $identifier): string {
        return \html_writer::div(get_string($identifier, 'block_aihubchatbot'), 'block_aihubchatbot_error');
    }
}
