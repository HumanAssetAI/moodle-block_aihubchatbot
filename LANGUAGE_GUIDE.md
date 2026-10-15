# Language customisation — AIHub Chatbot 1.3.3

Every plugin-owned interface message uses Moodle language strings: settings, options, help, validation errors, privacy descriptions, launcher text, window controls, tooltips and accessibility labels. Core controls such as Save changes use Moodle core strings. JavaScript receives translated labels from PHP; it contains no English UI fallbacks.

## Change wording on your site

Open Site administration > Language > Language customisation, select the language, open its language pack, and filter for block_aihubchatbot.php. Edit the desired strings and save changes to the language pack. Moodle permissions control this site administration tool; plugin configuration remains restricted to administrators and system-level Managers.

Useful identifiers: defaulttitle, iframetitle, defaultlauncherlabel, floatingopen, floatingclose, windowminimize, windowexpand, windowrestore, windowreset, windowmove, windowresize.

## Add a language to the plugin

Copy lang/en/block_aihubchatbot.php to lang/LANGUAGECODE/block_aihubchatbot.php (for example lang/el/block_aihubchatbot.php for Greek). Translate the right-hand values; keep the identifiers unchanged. Save as UTF-8, preserve PHP quoting and any placeholders, and purge Moodle caches after installing the updated files. Missing translations fall back to English. English and Greek (el) are included in this release.

Moodle selects the effective language, including course language settings. Reload the page after changing language.

## Default and custom text

Leave Block title and Launcher label empty to use language strings dynamically for each viewer. Existing saved values are preserved: clear a previously saved English title or label to use automatic translation. Custom labels remain literal administrator-authored text. Change defaulttitle/defaultlauncherlabel through language customisation for different text in each language.

The panel heading and iframe accessibility title now always use iframetitle. A title pasted inside an embed tag no longer overrides that language string.

The remote AIHub application's internal text is controlled by AIHub and is outside this Moodle plugin's language files.

## Upgrade and validation

Version 1.3.3 (2026101502) succeeds 1.3.2 and preserves its launcher visibility fix. Earlier releases and the separate multilingual preparation folder remain untouched. Install the ZIP matching your Moodle edition on a test site first. Verify two installed languages, course language overrides, site language customisations, blank/custom labels, and administrator/system Manager/teacher access. Local fixtures do not establish real Moodle integration compatibility.

References:
- https://moodledev.io/docs/5.3/apis/commonfiles
- https://docs.moodle.org/dev/String_API
