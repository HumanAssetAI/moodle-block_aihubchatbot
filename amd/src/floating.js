define(['block_aihubchatbot/appearance'], function(appearance) {
    'use strict';
    var registry = window.blockAihubchatbotFloatingRegistry || {instances: {}, order: [], listeners: false};
    window.blockAihubchatbotFloatingRegistry = registry;
    function storage(key, value) {
        if (!key) { return null; }
        try {
            if (value !== undefined) { localStorage.setItem(key, JSON.stringify(value)); return true; }
            return JSON.parse(localStorage.getItem(key));
        } catch (error) { return null; }
    }
    function desktop() { return window.innerWidth > 768 && !window.matchMedia('(pointer: coarse)').matches; }
    function defaults() {
        var w = Math.min(400, innerWidth - 16), h = Math.min(690, innerHeight - 112);
        return {x: Math.max(8, innerWidth - w - 24), y: Math.max(8, innerHeight - h - 96), w: w, h: h};
    }
    function bounded(rect) {
        var w = Math.min(Math.max(rect.w, Math.min(320, innerWidth - 16)), innerWidth - 16);
        var h = Math.min(Math.max(rect.h, Math.min(260, innerHeight - 16)), innerHeight - 16);
        return {x: Math.min(Math.max(8, rect.x), innerWidth - w - 8),
            y: Math.min(Math.max(8, rect.y), innerHeight - h - 8), w: w, h: h};
    }
    function validRect(rect) {
        return rect && ['x', 'y', 'w', 'h'].every(function(k) { return Number.isFinite(rect[k]); });
    }
    function layout(instance) {
        var rect = instance.expanded ? {x: 8, y: 8, w: innerWidth - 16, h: innerHeight - 16} :
            (desktop() ? bounded(instance.rect) : {x: 8, y: 8, w: innerWidth - 16, h: Math.max(160, innerHeight - 96)});
        Object.assign(instance.panel.style, {left: rect.x + 'px', top: rect.y + 'px', width: rect.w + 'px', height: rect.h + 'px'});
        instance.panel.classList.toggle('is-desktop-adjustable', desktop() && instance.config.allowDragResize !== false);
        instance.panel.classList.toggle('is-expanded', instance.expanded);
        instance.header.tabIndex = desktop() && instance.config.allowDragResize !== false ? 0 : -1;
        instance.expand.textContent = instance.expanded ? instance.labels.restore : instance.labels.expand;
        instance.expand.setAttribute('aria-pressed', String(instance.expanded));
    }
    function persist(instance) { storage(instance.geometryKey, instance.rect); }
    function visibility(instance) {
        instance.iframe.contentWindow.postMessage({type: 'aihub:embed:visibility', visible: instance.isOpen}, instance.config.origin);
    }
    function close(instance, focus) {
        if (!instance.isOpen) { return; }
        instance.isOpen = false;
        instance.panel.hidden = true;
        instance.panel.style.display = 'none';
        instance.panel.classList.remove('is-open');
        instance.button.classList.remove('is-open');
        instance.button.setAttribute('aria-expanded', 'false');
        instance.button.setAttribute('aria-label', instance.config.openLabel);
        visibility(instance);
        if (focus) { instance.button.focus(); }
    }
    function open(instance, automatic) {
        registry.order.forEach(function(key) { if (key !== instance.key) { close(registry.instances[key], false); } });
        storage(instance.seenKey, true);
        instance.isOpen = true;
        instance.panel.hidden = false;
        // Explicit visibility also overrides a stale/later legacy panel rule.
        instance.panel.style.display = 'flex';
        instance.panel.style.opacity = '1';
        instance.panel.classList.add('is-open');
        instance.button.classList.add('is-open');
        instance.button.setAttribute('aria-expanded', 'true');
        instance.button.setAttribute('aria-label', instance.config.closeLabel);
        layout(instance);
        visibility(instance);
        if (!automatic) { instance.minimize.focus(); }
    }
    function controls(instance, target, resizing) {
        var gesture = null;
        target.addEventListener('pointerdown', function(event) {
            if (!desktop() || instance.expanded || instance.config.allowDragResize === false || event.button !== 0 ||
                    event.target.closest('button') && event.target !== target) { return; }
            event.preventDefault();
            var rect = instance.panel.getBoundingClientRect();
            gesture = {id: event.pointerId, x: event.clientX, y: event.clientY,
                rect: {x: rect.x, y: rect.y, w: rect.width, h: rect.height}};
            target.setPointerCapture(event.pointerId);
            instance.panel.classList.add('is-adjusting');
        });
        target.addEventListener('pointermove', function(event) {
            if (!gesture || event.pointerId !== gesture.id) { return; }
            var dx = event.clientX - gesture.x, dy = event.clientY - gesture.y, r = gesture.rect;
            instance.rect = bounded(resizing ? {x: r.x, y: r.y, w: r.w + dx, h: r.h + dy} :
                {x: r.x + dx, y: r.y + dy, w: r.w, h: r.h});
            layout(instance);
        });
        function finish() {
            if (!gesture) { return; }
            gesture = null;
            instance.panel.classList.remove('is-adjusting');
            persist(instance);
        }
        target.addEventListener('pointerup', finish);
        target.addEventListener('pointercancel', finish);
        target.addEventListener('lostpointercapture', finish);
        target.addEventListener('keydown', function(event) {
            if (event.target !== target || !desktop() || instance.expanded || instance.config.allowDragResize === false ||
                    !['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'].includes(event.key)) { return; }
            event.preventDefault();
            var r = instance.panel.getBoundingClientRect(), step = event.shiftKey ? 40 : 10;
            var dx = event.key === 'ArrowLeft' ? -step : event.key === 'ArrowRight' ? step : 0;
            var dy = event.key === 'ArrowUp' ? -step : event.key === 'ArrowDown' ? step : 0;
            instance.rect = bounded(resizing ? {x: r.x, y: r.y, w: r.width + dx, h: r.height + dy} :
                {x: r.x + dx, y: r.y + dy, w: r.width, h: r.height});
            layout(instance); persist(instance);
        });
    }
    function create(config) {
        var key = String(config.instanceId).replace(/[^a-zA-Z0-9_-]/g, '_');
        if (registry.instances[key]) { return; }
        // PHP resolves every interface label through Moodle language strings.
        var labels = config.labels;
        var panel = document.createElement('div'), button = document.createElement('button');
        var header = document.createElement('div'), title = document.createElement('span'), iframe = document.createElement('iframe');
        var seenKey = config.courseKey ? 'block_aihubchatbot:seen:v1:' + config.courseKey : null;
        var geometryKey = config.courseKey ? 'block_aihubchatbot:geometry:v1:' + config.courseKey + ':' + key : null;
        var stored = storage(geometryKey);
        var instance = {key: key, config: config, labels: labels, panel: panel, button: button, iframe: iframe,
            header: header, isOpen: false, expanded: false, seenKey: seenKey, geometryKey: geometryKey, rect: validRect(stored) ? stored : defaults()};
        panel.id = 'block_aihubchatbot_floating_panel_' + key;
        panel.className = 'block_aihubchatbot_floating_panel';
        panel.hidden = true;
        panel.setAttribute('role', 'dialog');
        title.id = panel.id + '_title';
        title.textContent = config.title;
        title.className = 'block_aihubchatbot_window_title';
        panel.setAttribute('aria-labelledby', title.id);
        header.className = 'block_aihubchatbot_window_header';
        header.tabIndex = config.allowDragResize === false ? -1 : 0;
        header.setAttribute('aria-label', labels.move);
        header.appendChild(title);
        function action(label, className, callback) {
            var control = document.createElement('button');
            control.type = 'button'; control.textContent = label; control.title = label;
            control.className = className; control.addEventListener('click', callback);
            header.appendChild(control); return control;
        }
        instance.expand = action(labels.expand, 'block_aihubchatbot_window_expand', function() {
            instance.expanded = !instance.expanded; layout(instance);
        });
        instance.expand.hidden = config.allowExpand === false;
        var reset = action(labels.reset, 'block_aihubchatbot_window_reset', function() {
            instance.expanded = false; instance.rect = defaults(); layout(instance); persist(instance);
        });
        reset.textContent = '↺'; reset.setAttribute('aria-label', labels.reset);
        reset.hidden = config.allowDragResize === false;
        instance.minimize = action(labels.minimize, 'block_aihubchatbot_window_minimize', function() { close(instance, true); });
        iframe.className = 'block_aihubchatbot_floating_iframe';
        iframe.src = config.src; iframe.title = config.title;
        iframe.allow = config.allow || 'microphone; clipboard-write';
        iframe.referrerPolicy = 'strict-origin-when-cross-origin';
        iframe.addEventListener('load', function() { visibility(instance); });
        panel.appendChild(header); panel.appendChild(iframe);
        var resize = document.createElement('button');
        resize.type = 'button'; resize.className = 'block_aihubchatbot_window_resize';
        resize.textContent = '↘'; resize.title = labels.resize; resize.setAttribute('aria-label', labels.resize);
        panel.appendChild(resize);
        controls(instance, header, false); controls(instance, resize, true);
        button.type = 'button'; button.id = 'block_aihubchatbot_floating_launcher_' + key;
        button.className = 'block_aihubchatbot_floating_launcher';
        button.setAttribute('aria-controls', panel.id); button.setAttribute('aria-expanded', 'false');
        button.setAttribute('aria-label', config.openLabel);
        appearance.apply(button, config.appearance || {});
        if (config.showLauncherLabel !== false) {
            var label = document.createElement('span');
            label.className = 'block_aihubchatbot_launcher_label';
            label.textContent = config.launcherLabel;
            button.appendChild(label);
        }
        button.addEventListener('click', function() { if (instance.isOpen) { close(instance, true); } else { open(instance, false); } });
        document.body.appendChild(panel); document.body.appendChild(button);
        registry.instances[key] = instance; registry.order.push(key);
        registry.order.forEach(function(id, index) {
            registry.instances[id].button.style.setProperty('--aihubchatbot-stack-offset', (index * 72) + 'px');
        });
        layout(instance);
        // Fail closed if persistent storage is unavailable: no repeated automatic popup.
        if (config.autoOpenOnce === true && seenKey && storage(seenKey) !== true && storage(seenKey, true)) { open(instance, true); }
        if (!registry.listeners) {
            registry.listeners = true;
            window.addEventListener('message', function(event) {
                registry.order.forEach(function(id) {
                    var item = registry.instances[id];
                    if (event.source === item.iframe.contentWindow && event.origin === item.config.origin &&
                            event.data && event.data.type === 'aihub:embed:close') { close(item, true); }
                });
            });
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') { registry.order.forEach(function(id) { close(registry.instances[id], true); }); }
            });
            window.addEventListener('resize', function() {
                registry.order.forEach(function(id) { layout(registry.instances[id]); });
            });
        }
    }
    return {init: function(config) {
        if (!config || !config.src || !config.origin || !config.instanceId) { return; }
        if (document.body) { create(config); } else { document.addEventListener('DOMContentLoaded', function() { create(config); }, {once: true}); }
    }};
});
