/* Ob Anggen School - skrip dashboard (dipakai ketiga role) */
(function () {
    'use strict';

    var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------------------------------------------------------------
       Semua modal dipindah langsung ke <body> sebelum dipakai.
       Modal yang berada di dalam elemen beranimasi / ber-transform akan
       "terkunci" di bawah lapisan gelap (backdrop) sehingga tidak bisa diklik.
       --------------------------------------------------------------- */
    document.querySelectorAll('.modal').forEach(function (modal) {
        if (modal.parentElement !== document.body) document.body.appendChild(modal);
    });

    /* ---------------------------------------------------------------
       Efek masuk: menu sidebar, kartu, statistik, dan daftar muncul
       berurutan setiap kali halaman dashboard dibuka
       --------------------------------------------------------------- */
    if (!reduceMotion) {
        document.querySelectorAll('.dash-nav > *').forEach(function (el, i) {
            el.style.setProperty('--d', Math.min(i * 0.025, 0.5).toFixed(3) + 's');
        });

        var targets = document.querySelectorAll(
            '.dash-content > .dash-pagehead, .dash-content > .alert, .dash-hero, .dash-stat, .pipeline__item, ' +
            '.dash-content .dash-card, .dash-content .block-item, .dash-content .photo-grid__item, .dash-content .dash-filter'
        );
        var order = 0;
        targets.forEach(function (el) {
            if (el.closest('.modal') || el.parentElement.closest('.dash-anim, .media-panel')) return;
            el.classList.add('dash-anim');
            el.style.setProperty('--d', Math.min(order * 0.045, 0.6).toFixed(3) + 's');
            order++;
            // Lepas animasi setelah selesai agar dropdown/modal di dalamnya tidak terganggu
            el.addEventListener('animationend', function () {
                el.classList.remove('dash-anim');
                el.style.removeProperty('--d');
            }, { once: true });
        });

        document.querySelectorAll('.mini-chart__bar span').forEach(function (bar, i) {
            bar.style.setProperty('--d', (0.2 + i * 0.03).toFixed(2) + 's');
        });
    }

    /* ---------------------------------------------------------------
       Sidebar di layar kecil, tooltip, toast, konfirmasi hapus
       --------------------------------------------------------------- */
    document.querySelectorAll('[data-sidebar-toggle]').forEach(function (btn) {
        btn.addEventListener('click', function () { document.body.classList.toggle('sidebar-open'); });
    });

    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) { new bootstrap.Tooltip(el); });
    document.querySelectorAll('.toast').forEach(function (el) { new bootstrap.Toast(el).show(); });

    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (form.hasAttribute('data-confirm') && !window.confirm(form.getAttribute('data-confirm'))) {
            e.preventDefault();
        }
    });

    /* ---------------------------------------------------------------
       Editor teks (Summernote)
       --------------------------------------------------------------- */
    // Tombol perataan paragraf yang langsung terlihat di toolbar (kiri, tengah, kanan, kiri-kanan)
    function alignButton(command, icon, label) {
        return function (context) {
            return jQuery.summernote.ui.button({
                className: 'note-btn-align',
                contents: '<i class="' + icon + '"></i>',
                tooltip: label,
                click: function () { context.invoke('editor.' + command); }
            }).render();
        };
    }

    // Dijalankan setelah DOMContentLoaded agar plugin jQuery milik Bootstrap (tooltip, dll.) sudah terdaftar
    function initEditors() {
        if (!window.jQuery || !jQuery.fn.summernote) return;

        // Link otomatis Summernote kadang galat saat mengetik spasi setelah Tebal/Coret/Warna; abaikan dengan aman
        var autoLink = jQuery.summernote.options.modules && jQuery.summernote.options.modules.autoLink;
        if (autoLink && autoLink.prototype.replace && !autoLink.prototype.oaSafe) {
            var replaceLink = autoLink.prototype.replace;
            autoLink.prototype.replace = function () {
                try { return replaceLink.apply(this, arguments); } catch (err) { this.lastWordRange = null; }
            };
            autoLink.prototype.oaSafe = true;
        }
        var langId = jQuery.summernote.lang && jQuery.summernote.lang['id-ID'];
        if (langId && langId.color) langId.color.cpSelect = 'Pilih warna lain';

        jQuery('.js-editor').each(function () {
            var $area = jQuery(this);
            $area.summernote({
                lang: 'id-ID',
                height: parseInt(this.getAttribute('data-height') || '200', 10),
                buttons: {
                    alignLeft: alignButton('justifyLeft', 'note-icon-align-left', 'Rata kiri'),
                    alignCenter: alignButton('justifyCenter', 'note-icon-align-center', 'Rata tengah'),
                    alignRight: alignButton('justifyRight', 'note-icon-align-right', 'Rata kanan'),
                    alignJustify: alignButton('justifyFull', 'note-icon-align-justify', 'Rata kiri-kanan')
                },
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol']],
                    ['align', ['alignLeft', 'alignCenter', 'alignRight', 'alignJustify']],
                    ['insert', ['link', 'table', 'hr']],
                    ['history', ['undo', 'redo']],
                    ['view', ['codeview']]
                ],
                styleTags: [
                    { title: 'Paragraf biasa', tag: 'p', value: 'p' },
                    { title: 'Judul besar', tag: 'h2', value: 'h2' },
                    { title: 'Judul sedang', tag: 'h3', value: 'h3' },
                    { title: 'Judul kecil', tag: 'h4', value: 'h4' },
                    { title: 'Kutipan', tag: 'blockquote', value: 'blockquote' }
                ]
            });

            // Summernote memakai atribut Bootstrap 4 (data-toggle). Ubah ke Bootstrap 5
            // agar menu Gaya, Warna, dan Tabel bisa dibuka.
            var editor = $area.next('.note-editor')[0];
            if (!editor) return;
            // Bootstrap 5 tidak mengizinkan tooltip dan dropdown di tombol yang sama: pakai keterangan biasa (title).
            editor.querySelectorAll('[data-toggle="dropdown"]').forEach(function (btn) {
                var label = btn.getAttribute('aria-label') || '';
                var tip = window.bootstrap && bootstrap.Tooltip.getInstance(btn);
                if (tip) tip.dispose();
                jQuery(btn).off('click');
                btn.removeAttribute('data-toggle');
                btn.removeAttribute('data-bs-original-title');
                btn.setAttribute('data-bs-toggle', 'dropdown');
                btn.setAttribute('aria-expanded', 'false');
                if (label) btn.setAttribute('title', label);
            });
        });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initEditors);
    else initEditors();

    // Menu Gaya / Warna / Tabel di editor langsung tertutup setelah dipilih atau saat klik di tempat lain
    document.addEventListener('click', function (e) {
        if (!window.bootstrap) return;
        document.querySelectorAll('.note-toolbar [data-bs-toggle="dropdown"].show').forEach(function (btn) {
            if (btn.contains(e.target) || (e.target.closest && e.target.closest('.note-color-select'))) return;
            bootstrap.Dropdown.getOrCreateInstance(btn).hide();
        });
    }, true);

    // Sebelum formulir dikirim: isi editor (termasuk mode Kode HTML) disalin ke kolom aslinya
    document.addEventListener('submit', function (e) {
        if (!window.jQuery || !jQuery.fn.summernote) return;
        jQuery(e.target).find('.js-editor').each(function () {
            var $area = jQuery(this);
            if (!$area.next('.note-editor').length) return;
            if ($area.summernote('codeview.isActivated')) $area.summernote('codeview.deactivate');
            this.value = $area.summernote('code');
        });
    }, true);

    /* ---------------------------------------------------------------
       Video otomatis diputar saat kursor diarahkan (tanpa suara)
       --------------------------------------------------------------- */
    function ytCommand(iframe, func) {
        if (iframe && iframe.contentWindow) {
            iframe.contentWindow.postMessage(JSON.stringify({ event: 'command', func: func, args: [] }), '*');
        }
    }
    document.querySelectorAll('[data-hover-video]').forEach(function (box) {
        var iframe = box.querySelector('iframe');
        var video = box.querySelector('video');
        if (iframe) {
            iframe.addEventListener('load', function () {
                iframe.contentWindow.postMessage(JSON.stringify({ event: 'listening', id: 1, channel: 'widget' }), '*');
            });
        }
        if (!window.matchMedia('(hover: hover)').matches) return;
        function play() {
            box.classList.add('is-playing');
            if (video) { video.muted = true; var p = video.play(); if (p && p.catch) p.catch(function () {}); }
            else { ytCommand(iframe, 'mute'); ytCommand(iframe, 'playVideo'); }
        }
        function pause() {
            box.classList.remove('is-playing');
            if (video) video.pause(); else ytCommand(iframe, 'pauseVideo');
        }
        box.addEventListener('mouseenter', play);
        box.addEventListener('mouseleave', pause);
        if (iframe) { iframe.addEventListener('mouseenter', play); iframe.addEventListener('mouseleave', pause); }
    });

    /* ---------------------------------------------------------------
       Pilihan latar: Foto / Video / YouTube / Slide foto
       (Studio > header halaman, dan Pengaturan Website > Halaman Login)
       --------------------------------------------------------------- */
    function youtubeId(url) {
        var m = String(url || '').match(/(?:youtu\.be\/|youtube(?:-nocookie)?\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/|live\/))([\w-]{11})/);
        return m ? m[1] : null;
    }

    document.querySelectorAll('[data-media-form]').forEach(function (form) {
        // Dipakai Studio (name="banner_type") dan Pengaturan > Halaman Login (data-media-type)
        var radios = form.querySelectorAll('[name="banner_type"], [data-media-type]');
        var panels = form.querySelectorAll('[data-media-panel]');

        function show() {
            var checked = form.querySelector('[name="banner_type"]:checked, [data-media-type]:checked');
            var type = checked ? checked.value : 'image';
            panels.forEach(function (panel) {
                var on = panel.getAttribute('data-media-panel') === type;
                panel.classList.toggle('is-active', on);
                panel.querySelectorAll('video').forEach(function (v) {
                    if (on && !v.classList.contains('d-none')) { var p = v.play(); if (p && p.catch) p.catch(function () {}); }
                    else v.pause();
                });
            });
        }
        radios.forEach(function (r) { r.addEventListener('change', show); });
        show();

        // Pratinjau video lokal sebelum diunggah
        form.querySelectorAll('[data-video-input]').forEach(function (input) {
            input.addEventListener('change', function () {
                var video = document.getElementById(input.getAttribute('data-video-input'));
                var empty = form.querySelector('[data-video-empty]');
                if (!video || !input.files || !input.files[0]) return;
                video.src = URL.createObjectURL(input.files[0]);
                video.classList.remove('d-none');
                if (empty) empty.classList.add('d-none');
                var p = video.play(); if (p && p.catch) p.catch(function () {});
            });
        });

        // Pratinjau thumbnail YouTube saat link ditempel
        form.querySelectorAll('[data-yt-input]').forEach(function (input) {
            var img = document.getElementById(input.getAttribute('data-yt-input'));
            var play = form.querySelector('[data-yt-play]');
            var empty = form.querySelector('[data-yt-empty]');
            input.addEventListener('input', function () {
                var id = youtubeId(input.value);
                input.classList.toggle('is-invalid', input.value !== '' && !id);
                if (id) img.src = 'https://img.youtube.com/vi/' + id + '/hqdefault.jpg';
                img.classList.toggle('d-none', !id);
                if (play) play.classList.toggle('d-none', !id);
                if (empty) empty.classList.toggle('d-none', !!id);
            });
        });

        // Pratinjau foto slide baru
        form.querySelectorAll('[data-slides-input]').forEach(function (input) {
            var box = document.getElementById(input.getAttribute('data-slides-input'));
            input.addEventListener('change', function () {
                if (!box) return;
                box.innerHTML = '';
                Array.prototype.slice.call(input.files || []).forEach(function (file, i) {
                    var item = document.createElement('div');
                    item.className = 'photo-grid__item';
                    item.innerHTML = '<img alt=""><span class="photo-grid__badge badge text-bg-success">Baru ' + (i + 1) + '</span>';
                    item.querySelector('img').src = URL.createObjectURL(file);
                    box.appendChild(item);
                });
            });
        });
    });

    /* ---------------------------------------------------------------
       Pratinjau gambar, jumlah file, ikon
       --------------------------------------------------------------- */
    document.querySelectorAll('input[type="file"][data-preview]').forEach(function (input) {
        input.addEventListener('change', function () {
            var img = document.getElementById(input.getAttribute('data-preview'));
            if (img && input.files && input.files[0]) {
                img.src = URL.createObjectURL(input.files[0]);
                img.classList.remove('d-none');
            }
        });
    });

    /* ---------------------------------------------------------------
       Nama & ukuran file yang dipilih (video, dokumen)
       --------------------------------------------------------------- */
    function sizeText(bytes) {
        if (bytes >= 1073741824) return (bytes / 1073741824).toFixed(2).replace('.', ',') + ' GB';
        if (bytes >= 1048576) return (bytes / 1048576).toFixed(1).replace('.', ',') + ' MB';
        return Math.max(1, Math.round(bytes / 1024)) + ' KB';
    }

    document.querySelectorAll('input[type="file"][data-file-label]').forEach(function (input) {
        input.addEventListener('change', function () {
            var label = document.getElementById(input.getAttribute('data-file-label'));
            var file = input.files && input.files[0];
            if (!label) return;
            label.textContent = '';
            if (file) {
                var icon = document.createElement('i');
                icon.className = 'bi bi-paperclip';
                label.appendChild(icon);
                label.appendChild(document.createTextNode(' ' + file.name + ' · ' + sizeText(file.size) + ' — siap diunggah'));
            }
            label.classList.toggle('d-none', !file);
        });
    });

    /* ---------------------------------------------------------------
       Unggah bertahap (tanpa batas ukuran)
       Setiap file yang dipilih dikirim per potongan kecil ke server sebelum
       formulir disimpan, jadi foto, video, PDF, dan file lain sebesar apa pun
       tetap bisa diunggah walau batas php.ini kecil. Progres tampil di layar.
       --------------------------------------------------------------- */
    var uploadUrl = (document.querySelector('meta[name="upload-url"]') || {}).content;
    var chunkSize = parseInt((document.querySelector('meta[name="upload-chunk-size"]') || {}).content, 10) || 2 * 1048576;
    var uploading = false;

    function uploadPanel() {
        var panel = document.getElementById('uploadPanel');
        if (panel) return panel;
        panel = document.createElement('div');
        panel.id = 'uploadPanel';
        panel.className = 'upload-panel';
        panel.setAttribute('role', 'dialog');
        panel.setAttribute('aria-modal', 'true');
        panel.setAttribute('aria-labelledby', 'uploadPanelTitle');
        panel.innerHTML =
            '<div class="upload-panel__box">' +
            '  <div class="upload-panel__head">' +
            '    <span class="upload-panel__icon"><i class="bi bi-cloud-arrow-up"></i></span>' +
            '    <div class="min-w-0"><strong id="uploadPanelTitle">Mengunggah file</strong><small data-up-step></small></div>' +
            '  </div>' +
            '  <div class="upload-panel__file" data-up-name></div>' +
            '  <div class="progress upload-panel__bar" role="progressbar" aria-label="Progres unggah" aria-valuemin="0" aria-valuemax="100">' +
            '    <div class="progress-bar" data-up-bar style="width:0%"></div>' +
            '  </div>' +
            '  <div class="upload-panel__meta"><span data-up-bytes></span><span data-up-percent></span></div>' +
            '  <div class="upload-panel__note" data-up-note>Jangan tutup atau pindah halaman sampai unggahan selesai.</div>' +
            '  <div class="upload-panel__actions"><button type="button" class="btn btn-light btn-sm" data-up-cancel>Batalkan</button></div>' +
            '</div>';
        document.body.appendChild(panel);
        return panel;
    }

    function uploadId() {
        var rand = '';
        var bytes = new Uint8Array(12);
        (window.crypto || window.msCrypto).getRandomValues(bytes);
        bytes.forEach(function (b) { rand += ('0' + b.toString(16)).slice(-2); });
        return Date.now().toString(36) + '-' + rand;
    }

    function chunkedName(name) {
        // "photos[]" → "_chunked[photos][]", "image" → "_chunked[image]"
        return '_chunked[' + name.replace(/^([^\[]+)/, '$1]');
    }

    function sendChunk(job, file, offset, blob) {
        return new Promise(function (resolve, reject) {
            var data = new FormData();
            data.append('upload_id', job.id);
            data.append('name', file.name);
            data.append('size', file.size);
            data.append('offset', offset);
            data.append('chunk', blob, 'potongan');

            var xhr = new XMLHttpRequest();
            job.xhr = xhr;
            xhr.open('POST', uploadUrl);
            xhr.setRequestHeader('X-CSRF-TOKEN', csrf);
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.upload.onprogress = function (e) { if (e.lengthComputable) job.onChunkProgress(e.loaded); };
            xhr.onload = function () {
                var body = {};
                try { body = JSON.parse(xhr.responseText); } catch (err) { body = {}; }
                if (xhr.status >= 200 && xhr.status < 300) resolve(body);
                else reject({ status: xhr.status, body: body });
            };
            xhr.onerror = function () { reject({ status: 0, body: {} }); };
            xhr.onabort = function () { reject({ status: -1, body: {} }); };
            xhr.send(data);
        });
    }

    function uploadFile(job, file, report) {
        job.id = uploadId();
        var offset = 0;
        var size = chunkSize;
        var tries = 0;

        function next() {
            if (job.cancelled) return Promise.reject({ status: -1, body: {} });
            var blob = file.slice(offset, Math.min(offset + size, file.size));
            job.onChunkProgress = function (loaded) { report(offset + Math.min(loaded, blob.size)); };

            return sendChunk(job, file, offset, blob).then(function (res) {
                tries = 0;
                offset = res.received || (offset + blob.size);
                report(offset);
                return res.done ? res.token : next();
            }, function (err) {
                if (err.status === -1) throw err;
                // Potongan terlalu besar untuk server (mis. batas nginx): perkecil lalu ulangi
                if (err.status === 413 && size > 262144) { size = Math.max(262144, Math.floor(size / 2)); return next(); }
                // Server meminta melanjutkan dari posisi tertentu
                if (err.status === 409 && typeof err.body.received === 'number') { offset = err.body.received; return next(); }
                // Koneksi putus / server sibuk: coba lagi beberapa kali
                if ((err.status === 0 || err.status >= 500) && err.status !== 507 && tries < 4) {
                    tries++;
                    return new Promise(function (r) { setTimeout(r, 1000 * tries); }).then(next);
                }
                throw err;
            });
        }

        return file.size === 0
            ? Promise.reject({ status: 422, body: { message: 'File "' + file.name + '" kosong (0 byte).' } })
            : next();
    }

    function startChunkedUpload(form, inputs, submitter) {
        var panel = uploadPanel();
        var el = function (sel) { return panel.querySelector(sel); };
        var items = [];
        inputs.forEach(function (input) {
            Array.prototype.forEach.call(input.files, function (file) { items.push({ input: input, file: file }); });
        });
        var total = items.reduce(function (sum, it) { return sum + it.file.size; }, 0);
        var done = 0;
        var started = Date.now();
        var job = { cancelled: false, xhr: null };
        var buttons = form.querySelectorAll('button[type="submit"], button:not([type])');

        uploading = true;
        buttons.forEach(function (b) { b.disabled = true; });
        panel.classList.remove('is-error');
        el('[data-up-note]').textContent = 'Jangan tutup atau pindah halaman sampai unggahan selesai.';
        el('[data-up-cancel]').textContent = 'Batalkan';
        panel.classList.add('is-open');

        function render(index, sent) {
            var current = done + sent;
            var pct = total ? Math.min(100, Math.floor(current / total * 100)) : 100;
            var secs = (Date.now() - started) / 1000;
            var speed = secs > 1 ? current / secs : 0;
            var left = speed ? Math.round((total - current) / speed) : 0;
            el('[data-up-step]').textContent = 'File ' + (index + 1) + ' dari ' + items.length;
            el('[data-up-name]').textContent = items[index].file.name;
            el('[data-up-bar]').style.width = pct + '%';
            panel.querySelector('.upload-panel__bar').setAttribute('aria-valuenow', pct);
            el('[data-up-bytes]').textContent = sizeText(current) + ' dari ' + sizeText(total);
            el('[data-up-percent]').textContent = pct + '%' + (left > 3 ? ' · sisa ± ' + (left >= 90 ? Math.round(left / 60) + ' menit' : left + ' detik') : '');
        }

        function finish() {
            uploading = false;
            buttons.forEach(function (b) { b.disabled = false; });
        }

        function fail(err) {
            finish();
            if (job.cancelled) { panel.classList.remove('is-open'); return; }
            var msg = (err.body && err.body.message) ||
                (err.status === 419 ? 'Sesi login sudah berakhir. Muat ulang halaman lalu coba lagi.' :
                 err.status === 0 ? 'Koneksi terputus. Periksa internet lalu tekan Simpan lagi.' :
                 'Unggahan gagal (kode ' + err.status + '). Coba lagi.');
            panel.classList.add('is-error');
            el('[data-up-note]').textContent = msg;
            el('[data-up-cancel]').textContent = 'Tutup';
        }

        el('[data-up-cancel]').onclick = function () {
            if (uploading) {
                job.cancelled = true;
                if (job.xhr) job.xhr.abort();
            }
            panel.classList.remove('is-open');
        };

        var chain = Promise.resolve();
        var tokens = [];
        items.forEach(function (it, index) {
            chain = chain.then(function () {
                render(index, 0);
                return uploadFile(job, it.file, function (sent) { render(index, sent); }).then(function (token) {
                    done += it.file.size;
                    tokens.push({ input: it.input, token: token });
                });
            });
        });

        chain.then(function () {
            el('[data-up-bar]').style.width = '100%';
            el('[data-up-percent]').textContent = '100%';
            el('[data-up-note]').textContent = 'Unggahan selesai. Menyimpan...';

            // File diganti token unggahan; isian file asli dimatikan agar tidak ikut terkirim
            tokens.forEach(function (t) {
                var hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = chunkedName(t.input.name);
                hidden.value = t.token;
                hidden.setAttribute('data-chunk-token', '');
                form.appendChild(hidden);
            });
            inputs.forEach(function (input) { input.disabled = true; input.setAttribute('data-chunk-off', ''); });

            finish();
            form.setAttribute('data-chunk-done', '');
            if (form.requestSubmit) {
                form.requestSubmit(submitter && submitter.form === form ? submitter : undefined);
            } else {
                if (submitter && submitter.name) {
                    var s = document.createElement('input');
                    s.type = 'hidden'; s.name = submitter.name; s.value = submitter.value;
                    form.appendChild(s);
                }
                form.submit();
            }
        }, fail);
    }

    if (uploadUrl) {
        document.addEventListener('submit', function (e) {
            var form = e.target;
            if (e.defaultPrevented || form.hasAttribute('data-chunk-done') || (form.method || '').toLowerCase() !== 'post') return;
            if (uploading) { e.preventDefault(); return; }

            var inputs = Array.prototype.filter.call(form.querySelectorAll('input[type="file"]'), function (input) {
                return input.name && !input.disabled && input.files && input.files.length;
            });
            if (!inputs.length) return;

            e.preventDefault();
            startChunkedUpload(form, inputs, e.submitter);
        });

        window.addEventListener('beforeunload', function (e) {
            if (uploading) { e.preventDefault(); e.returnValue = ''; }
        });

        // Kembali dengan tombol Back: pulihkan formulir agar file bisa dipilih & diunggah lagi
        window.addEventListener('pageshow', function () {
            document.querySelectorAll('[data-chunk-token]').forEach(function (el) { el.remove(); });
            document.querySelectorAll('[data-chunk-off]').forEach(function (el) { el.disabled = false; el.removeAttribute('data-chunk-off'); });
            document.querySelectorAll('form[data-chunk-done]').forEach(function (f) { f.removeAttribute('data-chunk-done'); });
            var panel = document.getElementById('uploadPanel');
            if (panel) panel.classList.remove('is-open');
        });
    }

    document.querySelectorAll('input[type="file"][multiple][data-count-label]').forEach(function (input) {
        input.addEventListener('change', function () {
            var label = document.getElementById(input.getAttribute('data-count-label'));
            if (label) label.textContent = input.files.length ? input.files.length + ' file dipilih' : '';
        });
    });

    document.querySelectorAll('[data-icon-input]').forEach(function (input) {
        var preview = document.getElementById(input.getAttribute('data-icon-input'));
        var update = function () { if (preview) preview.className = 'bi ' + (input.value || 'bi-question-square'); };
        input.addEventListener('input', update);
        update();
        document.querySelectorAll('[data-icon-pick="' + input.id + '"]').forEach(function (btn) {
            btn.addEventListener('click', function () { input.value = btn.getAttribute('data-icon'); update(); });
        });
    });

    /* ---------------------------------------------------------------
       Simpan urutan hasil seret (Studio)
       --------------------------------------------------------------- */
    function postJson(url, data) {
        return fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify(data)
        }).then(function (res) {
            return res.json().then(function (json) {
                if (!res.ok) throw new Error(json.message || 'Gagal menyimpan urutan.');
                return json;
            });
        });
    }

    function flash(message, type) {
        var box = document.getElementById('dashFlash');
        if (!box) return;
        box.innerHTML = '<div class="toast align-items-center border-0 text-bg-' + (type || 'success') + '" role="alert" data-bs-delay="2500"><div class="d-flex"><div class="toast-body">' + message + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>';
        new bootstrap.Toast(box.querySelector('.toast')).show();
    }

    var tree = document.getElementById('studioTree');
    if (tree && window.Sortable) {
        var saveTree = function () {
            var items = [];
            tree.querySelectorAll(':scope > .tree-item').forEach(function (root) {
                items.push({ id: +root.dataset.id, parent_id: null });
                root.querySelectorAll(':scope > .tree-children > .tree-item').forEach(function (child) {
                    items.push({ id: +child.dataset.id, parent_id: +root.dataset.id });
                });
            });
            postJson(tree.dataset.reorderUrl, { items: items })
                .then(function (r) { flash(r.message); })
                .catch(function (err) { flash(err.message, 'danger'); setTimeout(function () { location.reload(); }, 1200); });
        };

        new Sortable(tree, { handle: '.tree-handle', animation: 150, group: 'roots', onEnd: saveTree });
        tree.querySelectorAll('.tree-children').forEach(function (list) {
            new Sortable(list, { handle: '.tree-handle', animation: 150, group: 'children', onEnd: saveTree });
        });
    }

    var blocks = document.getElementById('blockList');
    if (blocks && window.Sortable) {
        new Sortable(blocks, {
            handle: '.tree-handle', animation: 150,
            onEnd: function () {
                var ids = Array.prototype.map.call(blocks.querySelectorAll('.block-item'), function (el) { return +el.dataset.id; });
                postJson(blocks.dataset.reorderUrl, { ids: ids }).then(function (r) { flash(r.message); }).catch(function (err) { flash(err.message, 'danger'); });
            }
        });
    }

    if (location.hash.indexOf('#blok-') === 0) {
        var target = document.querySelector('.block-item[data-anchor="' + location.hash.substring(1) + '"]');
        if (target) { target.classList.add('is-target'); target.scrollIntoView({ block: 'center' }); }
    }

    /* ---------------------------------------------------------------
       Studio: pilihan "filter" (param) mengikuti jenis isi yang dipilih
       --------------------------------------------------------------- */
    function bindTypeParam(scope) {
        var paramMap = JSON.parse(scope.getAttribute('data-params') || '{}');
        var rootOnly = JSON.parse(scope.getAttribute('data-root-only') || '[]');
        var typeInputs = scope.querySelectorAll('[name="type"]');
        var paramWrap = scope.querySelector('[data-param-wrap]');
        var paramSelect = scope.querySelector('[name="param"]');
        var urlWrap = scope.querySelector('[data-url-wrap]');
        var parentSelect = scope.querySelector('[name="parent_id"]');
        var accessPub = scope.querySelector('[name="access_publikasi"]');
        var accessReg = scope.querySelector('[name="access_pendaftaran"]');
        var suggest = { berita: 'pub', galeri: 'pub', kelulusan: 'pub', kalender: 'pub', pendaftaran: 'reg', cek_status: 'reg' };

        function currentType() {
            var checked = scope.querySelector('[name="type"]:checked') || scope.querySelector('select[name="type"]');
            return checked ? checked.value : '';
        }

        function refresh(fromUser) {
            var type = currentType();
            var opts = paramMap[type] || {};
            var keys = Object.keys(opts);
            if (paramWrap && paramSelect) {
                var keep = paramSelect.getAttribute('data-value') || '';
                paramSelect.innerHTML = '';
                keys.forEach(function (k) {
                    var o = document.createElement('option');
                    o.value = k; o.textContent = opts[k];
                    if (k === keep) o.selected = true;
                    paramSelect.appendChild(o);
                });
                paramWrap.classList.toggle('d-none', keys.length === 0);
                paramSelect.disabled = keys.length === 0;
            }
            if (urlWrap) urlWrap.classList.toggle('d-none', type !== 'tautan');
            if (fromUser && accessPub && accessReg) {
                accessPub.checked = suggest[type] === 'pub';
                accessReg.checked = suggest[type] === 'reg';
            }
        }

        function refreshParent() {
            if (!parentSelect) return;
            var hasParent = parentSelect.value !== '';
            typeInputs.forEach(function (input) {
                if (input.type === 'radio' && rootOnly.indexOf(input.value) !== -1) {
                    input.disabled = hasParent;
                    if (hasParent && input.checked) {
                        input.checked = false;
                        var first = scope.querySelector('[name="type"][value="halaman"]');
                        if (first) first.checked = true;
                    }
                }
            });
            refresh(true);
        }

        typeInputs.forEach(function (input) { input.addEventListener('change', function () { refresh(true); }); });
        if (parentSelect) parentSelect.addEventListener('change', refreshParent);
        refresh(false);
        if (parentSelect && parentSelect.value !== '') refreshParent();
        return { setParent: function (id) { if (parentSelect) { parentSelect.value = id || ''; refreshParent(); } } };
    }

    document.querySelectorAll('[data-type-param]').forEach(function (scope) {
        var api = bindTypeParam(scope);
        var modal = scope.closest('.modal');
        if (modal) {
            modal.addEventListener('show.bs.modal', function (e) {
                var trigger = e.relatedTarget;
                if (trigger) api.setParent(trigger.getAttribute('data-parent'));
                setTimeout(function () { var t = modal.querySelector('[name="title"]'); if (t) t.focus(); }, 300);
            });
        }
    });

    var autoModal = document.querySelector('[data-auto-open]');
    if (autoModal) new bootstrap.Modal(autoModal).show();

    /* ---------------------------------------------------------------
       Warna & isian formulir pendaftaran
       --------------------------------------------------------------- */
    document.querySelectorAll('[data-color-sync]').forEach(function (picker) {
        var text = document.getElementById(picker.getAttribute('data-color-sync'));
        if (!text) return;
        picker.addEventListener('input', function () { text.value = picker.value; });
        text.addEventListener('input', function () { if (/^#[0-9a-f]{6}$/i.test(text.value)) picker.value = text.value; });
    });

    document.querySelectorAll('[data-field-form]').forEach(function (form) {
        var select = form.querySelector('[name="type"]');
        var options = form.querySelector('[data-options-wrap]');
        var update = function () { options.classList.toggle('d-none', ['select', 'radio'].indexOf(select.value) === -1); };
        select.addEventListener('change', update);
        update();
    });

    /* ---------------------------------------------------------------
       Tampilan blok: pratinjau lebar, susunan dengan blok sebelumnya,
       posisi, gaya, dan perataan
       --------------------------------------------------------------- */
    document.querySelectorAll('[data-block-layout]').forEach(function (card) {
        var form = card.closest('form');
        var box = card.querySelector('[data-lp-block]');
        if (!form || !box) return;
        var cell = card.querySelector('[data-lp-cell]');
        var prevCell = card.querySelector('[data-lp-prev-cell]');
        var line1 = card.querySelector('[data-lp-line="1"]');
        var line2 = card.querySelector('[data-lp-line="2"]');
        var titleText = card.querySelector('[data-lp-title]');
        var placement = card.querySelector('[data-lp-placement]');
        var titleInput = form.querySelector('[name="title"]');
        var justify = { left: 'flex-start', center: 'center', right: 'flex-end' };

        function value(name) {
            var el = form.querySelector('[name="' + name + '"]:checked') || form.querySelector('select[name="' + name + '"]');
            return el ? el.value : '';
        }
        // Lebar & letak satu blok di pratinjau (12 kolom)
        function put(el, width, place) {
            el.style.width = (width / 12 * 100) + '%';
            el.style.marginLeft = place === 'center' || place === 'right' ? 'auto' : '';
            el.style.marginRight = place === 'center' ? 'auto' : '';
        }
        function update() {
            var width = parseInt(value('width') || '12', 10);
            var place = width === 12 ? 'left' : (value('placement') || 'left');
            var pairing = prevCell ? (value('pairing') || 'auto') : 'auto';
            var paired = pairing === 'right' || pairing === 'left';
            var align = value('align') || 'left';
            var title = value('title_align');

            box.setAttribute('data-style', value('style') || 'plain');
            box.setAttribute('data-align', align);
            box.setAttribute('data-title', !title || title === 'ikut' ? (align === 'justify' ? 'left' : align) : title);
            if (titleText) titleText.textContent = (titleInput && titleInput.value.trim()) || 'Judul blok';

            line1.style.justifyContent = '';
            line2.hidden = true;
            if (!prevCell) {
                put(cell, width, place);
            } else {
                var prevWidth = parseInt(prevCell.getAttribute('data-w'), 10) || 12;
                var prevPlace = prevWidth === 12 ? 'left' : prevCell.getAttribute('data-place');
                if (paired) {
                    // Bersebelahan: satu baris; bila tidak muat, keduanya sama lebar
                    var equal = prevWidth + width > 12;
                    if (pairing === 'left') line1.append(cell, prevCell); else line1.append(prevCell, cell);
                    put(prevCell, equal ? 6 : prevWidth, 'left');
                    put(cell, equal ? 6 : width, 'left');
                    line1.style.justifyContent = justify[prevPlace] || '';
                } else {
                    line1.append(prevCell);
                    put(prevCell, prevWidth, prevPlace);
                    var beside = pairing === 'auto' && prevPlace === 'left' && place === 'left' && prevWidth + width <= 12;
                    if (beside) { line1.append(cell); } else { line2.append(cell); line2.hidden = false; }
                    put(cell, width, place);
                }
            }

            card.querySelectorAll('[data-pair-hint]').forEach(function (hint) {
                hint.hidden = hint.getAttribute('data-pair-hint') !== pairing;
            });
            if (placement) {
                placement.classList.toggle('is-off', width === 12 || paired);
                placement.classList.toggle('is-full', width === 12 && !paired);
                placement.classList.toggle('is-pair', paired);
            }
        }

        card.addEventListener('change', update);
        if (titleInput) titleInput.addEventListener('input', update);
        update();
    });
})();
