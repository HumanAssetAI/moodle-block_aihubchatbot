# Language customisation — AIHub Chatbot 1.3.3

Every plugin-owned interface message uses Moodle language strings: settings, options, help, validation errors, privacy descriptions, launcher text, window controls, tooltips and accessibility labels. Core controls such as Save changes use Moodle core strings. JavaScript receives translated labels from PHP; it contains no English UI fallbacks.

## Change wording on your site

Open Site administration > Language > Language customisation, select the language, open its language pack, and filter for block_aihubchatbot.php. Edit the desired strings and save changes to the language pack. Moodle permissions control this site administration tool; plugin configuration remains restricted to administrators and system-level Managers.

Useful identifiers: defaulttitle, iframetitle, defaultlauncherlabel, floatingopen, floatingclose, windowminimize, windowexpand, windowrestore, windowreset, windowmove, windowresize.

## Contribute translations through AMOS

This Marketplace package contains only lang/en/block_aihubchatbot.php. The existing Greek translation is preserved outside the plugin ZIP for submission through AMOS after plugin approval. It has not yet been submitted or accepted into the Greek language pack.

Once a translation is accepted and available, administrators can install or update the corresponding Moodle language pack. Site-specific wording can still be changed through Language customisation. Missing translations fall back to English.

Moodle selects the effective language, including course language settings. Reload the page after changing language.

## Default and custom text

Leave Block title and Launcher label empty to use language strings dynamically for each viewer. Existing saved values are preserved: clear a previously saved English title or label to use automatic translation. Custom labels remain literal administrator-authored text. Change defaulttitle/defaultlauncherlabel through language customisation for different text in each language.

The panel heading and iframe accessibility title now always use iframetitle. A title pasted inside an embed tag no longer overrides that language string.

The remote AIHub application's internal text is controlled by AIHub and is outside this Moodle plugin's language files.

## Upgrade and validation

Version 1.3.3 (2026101503) is an English-only packaging revision of build 2026101502 and preserves its launcher visibility fix. Earlier releases and the separate multilingual preparation folder remain untouched. Install the shared package on a test site first. After translations are available through language packs, verify those languages, course language overrides, site language customisations, blank/custom labels, and administrator/system Manager/teacher access. Local fixtures do not establish real Moodle integration compatibility.

References:
- https://moodledev.io/docs/5.3/apis/commonfiles
- https://docs.moodle.org/dev/String_API

- https://docs.moodle.org/en/AMOS_manual
