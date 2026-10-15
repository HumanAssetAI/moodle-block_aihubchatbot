# AIHub Chatbot

AIHub Chatbot adds a course assistant to Moodle using a signed iframe connection to AIHub. It supports an inline block or a floating, labelled launcher.

## Release

Version **1.3.3**, build **2026101503**, stable maturity.

This edition requires Moodle 4.5 (2024100700) or later.

The minimum Moodle version is an installation requirement, not a claim that every later Moodle version and theme has been tested.

## Features

- Labelled floating launcher, with optional automatic opening once per user and course in each browser.
- Desktop dragging and resizing, keyboard controls, Expand / Restore and Minimize.
- Remembered window size and position, with a screen-fitting mobile layout.
- Built-in icons or uploaded PNG/JPG images, resized and centre-cropped; theme or custom colours.
- Block heading shown only in editing mode.
- Configuration restricted to site administrators and users assigned a Manager role at system level. Teachers cannot configure the plugin.
- Translatable interface strings, editable through Moodle language customisation.

## Service requirements and pricing

The plugin is free. Using the assistant requires a valid AIHub account and subscription supplied by Human Asset S.A. AIHub offers paid subscriptions; a limited free subscription is planned. Free-tier availability and limits must be confirmed with the provider. Installing this plugin does not create an AIHub account or subscription.

A valid HTTPS AIHub embed URL and shared signing secret are required. The AIHub service must verify the signed context. PHP GD with PNG/JPEG support is required when processing uploaded launcher images.

## Installation and upgrade

1. Install the package through Moodle's plugin installer, or put the repository contents in `blocks/aihubchatbot`.
2. Complete Moodle's plugin installation/upgrade and purge caches.
3. Configure the AIHub base URL, shared signing secret and default iframe height. Never commit the signing secret to a source repository.
4. In course editing mode, add the block and paste the course's AIHub iframe embed code or embed URL.
5. Choose the display mode, launcher appearance and window options.

The protected global settings page is linked from the block configuration. Configuration access is restricted to site administrators and system-level Managers.

## Languages

This Marketplace package includes English (`en`) only. The Greek translation is preserved separately for contribution through AMOS after approval; it is not yet available through AMOS. All plugin-owned interface wording uses Moodle language strings. See [LANGUAGE_GUIDE.md](LANGUAGE_GUIDE.md).

Leave Block title and Launcher label empty to use translated defaults. Existing custom text is preserved; clear saved English text if you want the translated default. The interface inside the remote AIHub iframe is controlled by AIHub separately.

## Data and browser storage

The signed context sent to AIHub includes a pseudonymous user hash, course ID and shortname, block instance ID, Moodle site URL, timestamp and signature. The plugin does not itself send the learner's name or email in this payload.

Browser localStorage remembers the course visit and window geometry using the pseudonymous user hash, course ID and block ID. It does not store conversation text or the signing secret. Clearing browser storage or changing the signing secret resets these preferences. When storage is unavailable, automatic opening is suppressed.

Uploaded launcher images are processed into square PNG files in Moodle's block file area. AIHub's own conversation processing, retention and service privacy terms are managed by the service provider.

## Support and attribution

- Publisher and copyright holder: **Human Asset S.A.**
- Developer: **Hatec S.A.**
- Support: **support@humanasset.com**

## Licence

Copyright 2026 Human Asset S.A. Distributed under the GNU General Public License, version 3 or (at your option) any later version. See [COPYING.txt](COPYING.txt).

## Validation

Local PHP, isolated authorization and browser fixture checks have been performed during development, including Greek interface labels. The publisher reports successful testing on Moodle 5; the exact Moodle/PHP/theme versions have not yet been recorded. This English-only packaging revision still needs an installation/upgrade smoke test. See [CHANGELOG.md](CHANGELOG.md).
