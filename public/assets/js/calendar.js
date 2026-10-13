/* Ob Anggen School - kalender akademik bulanan
   Data agenda dikirim dari CalendarController sebagai JSON (#oaCalendarData).
   Arahkan kursor ke tanggal bertanda untuk melihat detail, klik untuk mengunci. */
(function () {
    'use strict';

    var root = document.getElementById('oaCalendar');
    var source = document.getElementById('oaCalendarData');
    if (!root || !source) return;

    var data = JSON.parse(source.textContent);
    var MONTHS = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    var MONTHS_SHORT = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    var DAYS = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    var parts = (data.month || data.today.substring(0, 7)).split('-');
    var state = { y: parseInt(parts[0], 10), m: parseInt(parts[1], 10) - 1, cat: 'all', pinned: null };

    var grid = root.querySelector('[data-cal-grid]');
    var title = root.querySelector('[data-cal-title]');
    var pop = root.querySelector('[data-cal-pop]');
    var list = document.querySelector('[data-cal-list]');
    var listTitle = document.querySelector('[data-cal-list-title]');
    var canHover = window.matchMedia('(hover: hover)').matches;

    /* ---------- bantuan ---------- */
    function pad(n) { return (n < 10 ? '0' : '') + n; }
    function keyOf(d) { return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()); }
    function parseKey(k) { var p = k.split('-'); return new Date(+p[0], +p[1] - 1, +p[2]); }
    function longDate(k) { var d = parseKey(k); return DAYS[d.getDay()] + ', ' + d.getDate() + ' ' + MONTHS[d.getMonth()] + ' ' + d.getFullYear(); }
    function esc(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    function visibleEvents() {
        return data.events.filter(function (e) { return state.cat === 'all' || e.category === state.cat; });
    }
    function eventsOn(k) {
        return visibleEvents().filter(function (e) { return e.start <= k && e.end >= k; });
    }

    /* ---------- gambar kalender ---------- */
    function render() {
        var y = state.y, m = state.m;
        var first = new Date(y, m, 1);
        var offset = first.getDay();                       // minggu dimulai hari Minggu
        var daysInMonth = new Date(y, m + 1, 0).getDate();
        var total = Math.ceil((offset + daysInMonth) / 7) * 7;

        title.textContent = MONTHS[m] + ' ' + y;
        grid.innerHTML = '';
        hidePop(true);

        for (var i = 0; i < total; i++) {
            var d = new Date(y, m, 1 - offset + i);
            var k = keyOf(d);
            var evs = eventsOn(k);
            var cell = document.createElement('button');
            cell.type = 'button';
            cell.className = 'oa-cal__day';
            if (d.getMonth() !== m) cell.className += ' is-out';
            if (k === data.today) cell.className += ' is-today';
            if (d.getDay() === 0) cell.className += ' is-sunday';
            cell.dataset.date = k;
            cell.style.setProperty('--d', (i * 0.012).toFixed(3) + 's');

            var html = '<b>' + d.getDate() + '</b>';
            if (evs.length) {
                cell.className += ' has-events';
                cell.setAttribute('aria-label', longDate(k) + ': ' + evs.map(function (e) { return e.title; }).join(', '));
                evs.slice(0, 2).forEach(function (e) {
                    html += '<span class="oa-cal__pill" style="--cat:' + esc(e.color) + '">' + esc(e.title) + '</span>';
                });
                if (evs.length > 2) html += '<span class="oa-cal__more">+' + (evs.length - 2) + ' lainnya</span>';
            } else {
                cell.tabIndex = -1;
                cell.setAttribute('aria-label', longDate(k));
            }
            cell.innerHTML = html;
            grid.appendChild(cell);
        }

        updateSemester();
        updateLinks();
        renderList();
    }

    /* ---------- detail tanggal (popover) ---------- */
    function showPop(cell, pin) {
        var k = cell.dataset.date;
        var evs = eventsOn(k);
        if (!evs.length) return;

        var html = '<div class="oa-cal-pop__date"><span>' + esc(longDate(k)) + '</span>' +
            '<button type="button" class="oa-cal-pop__close" aria-label="Tutup"><i class="bi bi-x-lg"></i></button></div>';
        evs.forEach(function (e) {
            html += '<div class="oa-cal-pop__item" style="--cat:' + esc(e.color) + '">' +
                '<h4>' + esc(e.title) + '</h4>' +
                '<span class="oa-badge">' + esc(e.categoryLabel) + '</span> <span class="oa-badge oa-badge--grey">' + esc(e.level) + '</span>' +
                '<div class="oa-cal-pop__meta"><span><i class="bi bi-calendar3"></i> ' + esc(e.dateLabel) + '</span>' +
                (e.time ? '<span><i class="bi bi-clock"></i> ' + esc(e.time) + '</span>' : '') +
                (e.location ? '<span><i class="bi bi-geo-alt"></i> ' + esc(e.location) + '</span>' : '') + '</div>' +
                (e.description ? '<p>' + esc(e.description) + '</p>' : '') +
                '</div>';
        });
        pop.innerHTML = html;

        grid.querySelectorAll('.is-active').forEach(function (c) { c.classList.remove('is-active'); });
        cell.classList.add('is-active');
        state.pinned = pin ? k : null;

        // Posisi: di bawah tanggal, pindah ke atas bila tidak muat
        pop.classList.add('is-open');
        var r = cell.getBoundingClientRect();
        var box = root.getBoundingClientRect();
        var w = pop.offsetWidth, h = pop.offsetHeight;
        var left = Math.min(Math.max(8, r.left - box.left + r.width / 2 - w / 2), box.width - w - 8);
        var top = r.bottom - box.top + 8;
        if (top + h > box.height + 120 && r.top - box.top - h - 8 > 0) top = r.top - box.top - h - 8;
        pop.style.left = left + 'px';
        pop.style.top = top + 'px';
    }

    function hidePop(force) {
        if (state.pinned && !force) return;
        state.pinned = null;
        pop.classList.remove('is-open');
        grid.querySelectorAll('.is-active').forEach(function (c) { c.classList.remove('is-active'); });
    }

    grid.addEventListener('mouseover', function (e) {
        if (!canHover || state.pinned) return;
        var cell = e.target.closest('.oa-cal__day.has-events');
        if (cell) showPop(cell, false);
    });
    grid.addEventListener('mouseleave', function () { hidePop(false); });
    grid.addEventListener('focusin', function (e) {
        var cell = e.target.closest('.oa-cal__day.has-events');
        if (cell && !state.pinned) showPop(cell, false);
    });
    grid.addEventListener('click', function (e) {
        var cell = e.target.closest('.oa-cal__day.has-events');
        if (!cell) return;
        if (state.pinned === cell.dataset.date) { hidePop(true); return; }
        showPop(cell, true);
    });
    pop.addEventListener('click', function (e) {
        if (e.target.closest('.oa-cal-pop__close')) hidePop(true);
    });
    document.addEventListener('click', function (e) {
        if (state.pinned && !e.target.closest('#oaCalendar') && !e.target.closest('[data-cal-list]')) hidePop(true);
    });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') hidePop(true); });

    /* ---------- navigasi bulan & semester ---------- */
    function go(y, m) {
        var d = new Date(y, m, 1);
        state.y = d.getFullYear();
        state.m = d.getMonth();
        render();
    }
    root.querySelector('[data-cal-prev]').addEventListener('click', function () { go(state.y, state.m - 1); });
    root.querySelector('[data-cal-next]').addEventListener('click', function () { go(state.y, state.m + 1); });
    root.querySelector('[data-cal-today]').addEventListener('click', function () {
        var t = parseKey(data.today);
        go(t.getFullYear(), t.getMonth());
    });

    var semButtons = document.querySelectorAll('[data-sem]');
    semButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (btn.dataset.sem === 'ganjil') go(data.startYear, 6);       // Juli
            else go(data.startYear + 1, 0);                                // Januari
        });
    });
    function updateSemester() {
        var odd = state.y === data.startYear && state.m >= 6;
        var even = state.y === data.startYear + 1 && state.m <= 5;
        semButtons.forEach(function (btn) {
            btn.classList.toggle('active', (btn.dataset.sem === 'ganjil' && odd) || (btn.dataset.sem === 'genap' && even));
        });
    }

    // Tab jenjang & alamat halaman ikut membawa bulan yang sedang dibuka
    function updateLinks() {
        var month = state.y + '-' + pad(state.m + 1);
        document.querySelectorAll('[data-level-link]').forEach(function (a) {
            var url = new URL(a.href, window.location.href);
            url.searchParams.set('bulan', month);
            a.href = url.toString();
        });
        if (window.history && history.replaceState) {
            var current = new URL(window.location.href);
            current.searchParams.set('bulan', month);
            history.replaceState(null, '', current.toString());
        }
    }

    /* ---------- filter kategori ---------- */
    document.querySelectorAll('[data-cat]').forEach(function (chip) {
        chip.addEventListener('click', function () {
            state.cat = chip.dataset.cat;
            document.querySelectorAll('[data-cat]').forEach(function (c) { c.classList.toggle('active', c === chip); });
            render();
        });
    });

    /* ---------- daftar agenda bulan ini ---------- */
    function renderList() {
        if (!list) return;
        var start = state.y + '-' + pad(state.m + 1) + '-01';
        var end = keyOf(new Date(state.y, state.m + 1, 0));
        var items = visibleEvents().filter(function (e) { return e.start <= end && e.end >= start; });

        if (listTitle) listTitle.textContent = 'Agenda ' + MONTHS[state.m] + ' ' + state.y + ' (' + items.length + ')';
        if (!items.length) {
            list.innerHTML = '<li class="text-muted-oa small justify-content-center" style="cursor:default">Tidak ada agenda di bulan ini.</li>';
            return;
        }

        list.innerHTML = items.map(function (e) {
            var focus = e.start < start ? start : e.start;
            var d = parseKey(e.start);
            return '<li data-focus="' + focus + '" style="--cat:' + esc(e.color) + '">' +
                '<span class="oa-date"><strong>' + d.getDate() + '</strong><small>' + MONTHS_SHORT[d.getMonth()] + '</small></span>' +
                '<span class="min-w-0 flex-fill"><strong class="text-truncate">' + esc(e.title) + '</strong>' +
                '<small>' + esc(e.dateLabel) + (e.time ? ' · ' + esc(e.time) : '') + ' · ' + esc(e.level) + '</small></span>' +
                '<span class="oa-badge flex-none" style="background:color-mix(in srgb,' + esc(e.color) + ' 14%,#fff);color:' + esc(e.color) + '">' + esc(e.categoryLabel) + '</span>' +
                '</li>';
        }).join('');
    }

    if (list) {
        list.addEventListener('click', function (e) {
            var li = e.target.closest('li[data-focus]');
            if (!li) return;
            var cell = grid.querySelector('[data-date="' + li.dataset.focus + '"]');
            if (cell) {
                root.scrollIntoView({ behavior: 'smooth', block: 'start' });
                setTimeout(function () { showPop(cell, true); }, 350);
            }
        });
    }

    /* ---------- unduh ---------- */
    function download(name, content, type) {
        var blob = new Blob([content], { type: type });
        var a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = name;
        document.body.appendChild(a);
        a.click();
        setTimeout(function () { URL.revokeObjectURL(a.href); a.remove(); }, 500);
    }

    function fileBase() {
        return 'kalender-akademik-' + (data.level || 'semua') + '-' + String(data.year).replace('/', '-');
    }

    function icsText(s) {
        return String(s || '').replace(/\\/g, '\\\\').replace(/;/g, '\\;').replace(/,/g, '\\,').replace(/\r?\n/g, '\\n');
    }

    function toIcs() {
        var now = new Date();
        var stamp = now.getUTCFullYear() + pad(now.getUTCMonth() + 1) + pad(now.getUTCDate()) + 'T' + pad(now.getUTCHours()) + pad(now.getUTCMinutes()) + '00Z';
        var lines = ['BEGIN:VCALENDAR', 'VERSION:2.0', 'PRODID:-//' + icsText(data.schoolName) + '//Kalender Akademik//ID', 'CALSCALE:GREGORIAN',
            'X-WR-CALNAME:' + icsText('Kalender Akademik ' + (data.level ? data.level.toUpperCase() : '') + ' ' + data.year)];
        visibleEvents().forEach(function (e) {
            var endNext = parseKey(e.end);
            endNext.setDate(endNext.getDate() + 1);
            lines.push('BEGIN:VEVENT',
                'UID:agenda-' + e.id + '@' + window.location.hostname,
                'DTSTAMP:' + stamp,
                'DTSTART;VALUE=DATE:' + e.start.replace(/-/g, ''),
                'DTEND;VALUE=DATE:' + keyOf(endNext).replace(/-/g, ''),
                'SUMMARY:' + icsText(e.title),
                'DESCRIPTION:' + icsText([e.categoryLabel, e.level, e.time, e.description].filter(Boolean).join(' - ')),
                'LOCATION:' + icsText(e.location),
                'END:VEVENT');
        });
        lines.push('END:VCALENDAR');
        return lines.join('\r\n');
    }

    function toCsv() {
        var rows = [['Tanggal mulai', 'Tanggal selesai', 'Agenda', 'Kategori', 'Jenjang', 'Waktu', 'Tempat', 'Keterangan']];
        visibleEvents().forEach(function (e) {
            rows.push([e.start, e.end, e.title, e.categoryLabel, e.level, e.time || '', e.location || '', e.description || '']);
        });
        return '\uFEFF' + rows.map(function (r) {
            return r.map(function (v) { return '"' + String(v).replace(/"/g, '""') + '"'; }).join(';');
        }).join('\r\n');
    }

    document.querySelectorAll('[data-download]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var type = btn.dataset.download;
            if (type === 'ics') download(fileBase() + '.ics', toIcs(), 'text/calendar;charset=utf-8');
            else if (type === 'csv') download(fileBase() + '.csv', toCsv(), 'text/csv;charset=utf-8');
            else window.print();
        });
    });

    window.addEventListener('resize', function () { hidePop(true); });

    render();
})();