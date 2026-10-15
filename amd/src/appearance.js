define([], function() {
    'use strict';

    var icons = {
        chat: 'fa-comment', robot: 'fa-robot', education: 'fa-graduation-cap',
        help: 'fa-circle-question', idea: 'fa-lightbulb'
    };

    function themeColor() {
        // Probe the actual primary button: supports both Bootstrap 4 and 5 themes.
        var probe = document.createElement('button');
        probe.type = 'button';
        probe.className = 'btn btn-primary';
        probe.tabIndex = -1;
        probe.setAttribute('aria-hidden', 'true');
        probe.style.cssText = 'position:absolute;visibility:hidden;pointer-events:none;';
        document.body.appendChild(probe);
        var color = getComputedStyle(probe).backgroundColor;
        if (!color || color === 'transparent' || color === 'rgba(0, 0, 0, 0)') {
            probe.style.backgroundColor = 'var(--bs-primary, var(--primary, #155eef))';
            color = getComputedStyle(probe).backgroundColor;
        }
        probe.remove();
        return color || '#155eef';
    }

    function foreground(color) {
        // Canvas normalizes CSS colours, including modern colour syntax, to sRGB.
        var canvas = document.createElement('canvas');
        canvas.width = canvas.height = 1;
        var context = canvas.getContext('2d');
        context.fillStyle = '#155eef';
        context.fillStyle = color;
        context.fillRect(0, 0, 1, 1);
        var pixel = context.getImageData(0, 0, 1, 1).data;
        var rgb = Array.from(pixel).slice(0, 3).map(function(value) {
            value /= 255;
            return value <= 0.04045 ? value / 12.92 : Math.pow((value + 0.055) / 1.055, 2.4);
        });
        var luminance = 0.2126 * rgb[0] + 0.7152 * rgb[1] + 0.0722 * rgb[2];
        return luminance > 0.179 ? '#000000' : '#ffffff';
    }

    function apply(button, settings) {
        var color = settings.useThemeColor === false && /^#[0-9a-f]{6}$/i.test(settings.launcherColor) ?
            settings.launcherColor : themeColor();
        button.style.backgroundColor = color;
        button.style.color = foreground(color);
        if (settings.launcherIcon === 'image' && settings.launcherImageUrl) {
            var image = document.createElement('img');
            image.className = 'block_aihubchatbot_launcher_image';
            image.alt = '';
            image.setAttribute('aria-hidden', 'true');
            image.width = image.height = 26;
            image.addEventListener('error', function() {
                image.remove();
                var fallback = document.createElement('i');
                fallback.className = 'block_aihubchatbot_launcher_icon fa fa-solid fa-comment';
                fallback.setAttribute('aria-hidden', 'true');
                button.appendChild(fallback);
            }, {once: true});
            image.src = settings.launcherImageUrl;
            button.appendChild(image);
            return;
        }
        var icon = document.createElement('i');
        var selected = Object.prototype.hasOwnProperty.call(icons, settings.launcherIcon) ? settings.launcherIcon : 'chat';
        icon.className = 'block_aihubchatbot_launcher_icon fa fa-solid ' + icons[selected];
        icon.setAttribute('aria-hidden', 'true');
        button.appendChild(icon);
    }

    function initForm() {
        var input = document.querySelector('[name="config_launchercolor"]');
        if (input) {
            input.type = 'color';
            input.classList.add('block_aihubchatbot_colorpicker');
        }
    }

    return {apply: apply, initForm: initForm};
});
