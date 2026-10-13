/* Ob Anggen School - skrip website publik */
(function () {
    'use strict';

    var header = document.getElementById('oaHeader');
    var backTop = document.getElementById('oaBackTop');
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------------------------------------------------------------
       Navbar mengecil & tombol kembali ke atas
       --------------------------------------------------------------- */
    function onScroll() {
        var y = window.scrollY || document.documentElement.scrollTop;
        if (header) header.classList.toggle('is-scrolled', y > 40);
        if (backTop) backTop.classList.toggle('is-visible', y > 500);
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    if (backTop) {
        backTop.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    function setNavHeight() {
        if (header) document.documentElement.style.setProperty('--oa-nav-h', header.offsetHeight + 'px');
    }
    window.addEventListener('resize', setNavHeight);
    setNavHeight();

    /* ---------------------------------------------------------------
       Navbar selalu rapi berapa pun jumlah menunya (layar lebar):
       1. Bila menu tidak muat, tagline di bawah nama sekolah disembunyikan dulu.
       2. Bila masih tidak muat, menu dari ujung kanan dipindah ke dropdown "Lainnya".
       Logo sekolah & tombol menonjol (mis. Pendaftaran) tidak pernah tergeser.
       Di layar kecil semua menu tampil di panel samping seperti biasa.
       --------------------------------------------------------------- */
    var navbar = document.querySelector('.oa-navbar');
    var navMain = document.querySelector('[data-nav-main]');
    var desktop = window.matchMedia('(min-width: 1200px)');

    if (navbar && navMain) {
        var navItems = Array.prototype.slice.call(navMain.querySelectorAll(':scope > [data-nav-index]'));
        var navMore = navMain.querySelector('[data-nav-more]');
        var moreLink = navMore ? navMore.querySelector('.nav-link') : null;
        var moreEntries = navMore ? Array.prototype.slice.call(navMore.querySelectorAll('[data-more]')) : [];

        var contentWidth = function () {
            var gap = parseFloat(getComputedStyle(navMain).columnGap) || 0;
            var total = 0, count = 0;
            Array.prototype.forEach.call(navMain.children, function (li) {
                if (li.hidden || li.offsetParent === null) return;
                total += li.getBoundingClientRect().width;
                count++;
            });
            return total + Math.max(0, count - 1) * gap;
        };
        var fits = function () { return contentWidth() <= navMain.clientWidth + 0.5; };

        var fitNav = function () {
            // kembalikan ke keadaan awal
            navbar.classList.remove('is-compact');
            navItems.forEach(function (li) { li.classList.remove('is-overflow'); });
            moreEntries.forEach(function (li) { li.hidden = true; });
            if (navMore) navMore.hidden = true;
            if (moreLink) moreLink.classList.remove('active');

            if (!desktop.matches || fits()) return;

            navbar.classList.add('is-compact');
            if (fits() || !navMore) return;

            navMore.hidden = false;
            var hidden = [];
            for (var i = navItems.length - 1; i > 0 && !fits(); i--) {   // menu pertama (Beranda) selalu tampil
                navItems[i].classList.add('is-overflow');
                hidden.push(navItems[i].getAttribute('data-nav-index'));
            }

            moreEntries.forEach(function (li) {
                li.hidden = hidden.indexOf(li.getAttribute('data-more')) === -1;
            });
            // tandai "Lainnya" aktif bila halaman yang sedang dibuka ada di dalamnya
            if (moreLink && navMore.querySelector('[data-more]:not([hidden]) .active')) moreLink.classList.add('active');
        };

        var fitTimer = null;
        var scheduleFit = function () {
            if (fitTimer) cancelAnimationFrame(fitTimer);
            fitTimer = requestAnimationFrame(function () { fitNav(); setNavHeight(); });
        };

        fitNav();
        window.addEventListener('resize', scheduleFit);
        if (desktop.addEventListener) desktop.addEventListener('change', scheduleFit);
        if (document.fonts && document.fonts.ready) document.fonts.ready.then(scheduleFit);   // lebar teks berubah setelah font termuat
        window.addEventListener('load', scheduleFit);
    }

    // Dropdown di dekat tepi kanan layar dibuka ke arah kiri agar tidak terpotong
    function placeDropdown(li) {
        var menu = li.querySelector(':scope > .dropdown-menu');
        if (!menu || !desktop.matches) return;
        menu.classList.remove('is-flip');
        requestAnimationFrame(function () {
            var rect = menu.getBoundingClientRect();
            if (rect.width && rect.right > window.innerWidth - 8) menu.classList.add('is-flip');
        });
    }
    document.querySelectorAll('.oa-navbar .dropdown').forEach(function (li) {
        li.addEventListener('mouseenter', function () { placeDropdown(li); });
        li.addEventListener('shown.bs.dropdown', function () { placeDropdown(li); });
    });

    /* ---------------------------------------------------------------
       Efek masuk: setiap konten muncul berurutan saat halaman dibuka
       dan saat digulir. Elemen umum ditandai otomatis, jadi view
       tidak perlu menulis data-reveal satu per satu.
       --------------------------------------------------------------- */
    var autoTargets = [
        '#main .oa-sechead', '#main .oa-card', '#main .oa-panel', '#main .oa-widget', '#main .oa-feature',
        '#main .oa-testi', '#main .oa-quicklink', '#main .oa-tile', '#main .oa-form-section', '#main .oa-empty',
        '#main .oa-filterbar', '#main .oa-chips', '#main .oa-gallery > a', '#main .oa-block', '#main .oa-video',
        '#main .oa-videofeature', '#main .oa-cal-toolbar', '#main .oa-cal', '#main .oa-result', '#main .oa-ticket',
        '#main .pagination', '#main .oa-welcome__img', '#main .alert'
    ].join(',');

    function revealBasis(el) {
        // Kartu biasanya dibungkus kolom grid, urutan dihitung dari kolomnya
        var parent = el.parentElement;
        if (parent && /(^|\s)col(-|\s|$)/.test(parent.className) && parent.children.length === 1) return parent;
        return el;
    }

    function prepareReveal() {
        document.querySelectorAll(autoTargets).forEach(function (el) {
            if (el.closest('.oa-hero, .oa-pagehead, .modal, .dropdown-menu')) return;
            if (el.parentElement && el.parentElement.closest('[data-reveal]')) return;   // hindari animasi bertumpuk
            if (!el.hasAttribute('data-reveal')) {
                el.setAttribute('data-reveal', el.matches('.oa-gallery > a, .oa-tile, .oa-video') ? 'zoom' : '');
            }
        });

        document.querySelectorAll('[data-reveal]').forEach(function (el) {
            if (el.style.getPropertyValue('--d')) return;
            var basis = revealBasis(el);
            var siblings = basis.parentElement ? Array.prototype.slice.call(basis.parentElement.children) : [basis];
            var index = Math.max(0, siblings.indexOf(basis));
            el.style.setProperty('--d', ((index % 6) * 0.07).toFixed(2) + 's');
        });
    }

    function finishReveal(el) {
        // Setelah animasi selesai, atribut dilepas agar efek hover kartu kembali normal
        el.addEventListener('animationend', function done(e) {
            if (e.target !== el) return;
            el.removeEventListener('animationend', done);
            el.removeAttribute('data-reveal');
            el.classList.remove('is-visible');
            el.style.removeProperty('--d');
        });
    }

    var html = document.documentElement;
    if (!reduceMotion && 'IntersectionObserver' in window) {
        prepareReveal();
        html.classList.add('js-reveal');
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    finishReveal(entry.target);
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.05, rootMargin: '0px 0px -30px 0px' });
        document.querySelectorAll('[data-reveal]').forEach(function (el) { io.observe(el); });
    }
    html.classList.remove('oa-anim-pending');

    /* ---------------------------------------------------------------
       Angka statistik berjalan dari 0
       --------------------------------------------------------------- */
    document.querySelectorAll('[data-count]').forEach(function (el) {
        var target = parseInt(el.getAttribute('data-count'), 10) || 0;
        if (reduceMotion) { el.textContent = target.toLocaleString('id-ID'); return; }
        var start = null;
        function step(ts) {
            if (!start) start = ts;
            var p = Math.min((ts - start) / 1200, 1);
            el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3))).toLocaleString('id-ID');
            if (p < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    });

    /* ---------------------------------------------------------------
       Video otomatis diputar saat kursor diarahkan ke video
       (YouTube dikendalikan lewat postMessage, video tanpa suara
       karena browser melarang autoplay bersuara)
       --------------------------------------------------------------- */
    function ytCommand(iframe, func) {
        if (iframe && iframe.contentWindow) {
            iframe.contentWindow.postMessage(JSON.stringify({ event: 'command', func: func, args: [] }), '*');
        }
    }

    document.querySelectorAll('[data-hover-video]').forEach(function (box) {
        var iframe = box.querySelector('iframe');
        var video = box.querySelector('video');
        var hint = box.querySelector('.oa-video__hint');
        var canHover = window.matchMedia('(hover: hover)').matches;

        if (iframe) {
            iframe.addEventListener('load', function () {
                // "salam" agar YouTube menerima perintah dari halaman ini
                iframe.contentWindow.postMessage(JSON.stringify({ event: 'listening', id: 1, channel: 'widget' }), '*');
                if (box.classList.contains('is-playing')) ytCommand(iframe, 'playVideo');
            });
        }

        if (!canHover) {
            if (hint) hint.innerHTML = '<i class="bi bi-hand-index"></i> Ketuk untuk memutar';
            return;
        }

        function play() {
            box.classList.add('is-playing');
            if (video) {
                video.muted = true;
                var p = video.play();
                if (p && p.catch) p.catch(function () {});
            } else {
                ytCommand(iframe, 'mute');
                ytCommand(iframe, 'playVideo');
            }
        }

        function pause() {
            box.classList.remove('is-playing');
            if (video) video.pause();
            else ytCommand(iframe, 'pauseVideo');
        }

        box.addEventListener('mouseenter', play);
        box.addEventListener('mouseleave', pause);
        if (iframe) {
            iframe.addEventListener('mouseenter', play);
            iframe.addEventListener('mouseleave', pause);
        }
    });

    /* ---------------------------------------------------------------
       Latar header bergerak (header halaman & hero Beranda)
       - Video lokal: diputar otomatis tanpa suara, berulang
       - YouTube: muncul halus setelah video siap
       - Slide foto: berganti otomatis setiap 5 detik
       Semua dijeda saat header tidak terlihat di layar agar hemat baterai.
       --------------------------------------------------------------- */
    document.querySelectorAll('[data-headmedia]').forEach(function (box) {
        var video = box.querySelector('video');
        var iframe = box.querySelector('iframe');
        var slides = box.querySelectorAll('.oa-headmedia__slide');
        var dots = box.querySelectorAll('.oa-headmedia__dots span');
        var visible = true, index = 0, timer = null;

        if (video) {
            video.muted = true;
            if (reduceMotion) video.removeAttribute('autoplay');
            else { var p = video.play(); if (p && p.catch) p.catch(function () {}); }
        }

        if (iframe) {
            iframe.addEventListener('load', function () { box.classList.add('is-ready'); });
            setTimeout(function () { box.classList.add('is-ready'); }, 2500);
        }

        function go(next) {
            slides[index].classList.remove('is-active');
            if (dots[index]) dots[index].classList.remove('is-active');
            index = next % slides.length;
            // mulai ulang animasi zoom pelan pada slide yang aktif
            slides[index].style.animation = 'none';
            void slides[index].offsetWidth;
            slides[index].style.animation = '';
            slides[index].classList.add('is-active');
            if (dots[index]) dots[index].classList.add('is-active');
        }
        function start() {
            if (slides.length > 1 && !reduceMotion && !timer) timer = setInterval(function () { if (visible) go(index + 1); }, 5000);
        }
        start();

        if ('IntersectionObserver' in window) {
            new IntersectionObserver(function (entries) {
                visible = entries[0].isIntersecting;
                if (video && !reduceMotion) {
                    if (visible) { var q = video.play(); if (q && q.catch) q.catch(function () {}); }
                    else video.pause();
                }
            }).observe(box);
        }
    });

    /* ---------------------------------------------------------------
       Lightbox foto, tooltip & toast Bootstrap
       --------------------------------------------------------------- */
    if (window.GLightbox) {
        GLightbox({ selector: '.glightbox', touchNavigation: true, loop: true, autoplayVideos: true });
    }

    if (window.bootstrap) {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) { new bootstrap.Tooltip(el); });
        document.querySelectorAll('.toast').forEach(function (el) { new bootstrap.Toast(el).show(); });
    }

    /* ---------------------------------------------------------------
       Video di galeri: diputar di jendela pemutar (tanpa meninggalkan halaman)
       --------------------------------------------------------------- */
    var videoModal = null;
    document.addEventListener('click', function (e) {
        var link = e.target.closest('[data-video-modal]');
        if (!link || !window.bootstrap) return;
        e.preventDefault();
        if (!videoModal) {
            var wrap = document.createElement('div');
            wrap.className = 'modal fade oa-videomodal';
            wrap.tabIndex = -1;
            wrap.setAttribute('aria-label', 'Pemutar video');
            wrap.innerHTML = '<div class="modal-dialog modal-dialog-centered modal-xl"><div class="modal-content">'
                + '<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>'
                + '<video controls playsinline></video></div></div>';
            document.body.appendChild(wrap);
            videoModal = { el: wrap, video: wrap.querySelector('video'), modal: new bootstrap.Modal(wrap) };
            wrap.addEventListener('shown.bs.modal', function () { var p = videoModal.video.play(); if (p && p.catch) p.catch(function () {}); });
            wrap.addEventListener('hidden.bs.modal', function () { videoModal.video.pause(); videoModal.video.removeAttribute('src'); videoModal.video.load(); });
        }
        videoModal.video.src = link.getAttribute('href');
        videoModal.modal.show();
    });

    /* ---------------------------------------------------------------
       Pembaca PDF (Year Book, brosur, laporan) memakai pdf.js:
       halaman per halaman, mode buku 2 halaman, perbesar, daftar halaman,
       layar penuh, geser (HP), dan unduh.
       Bila pdf.js gagal dimuat, dipakai pembaca PDF bawaan browser.
       --------------------------------------------------------------- */
    var pdfLibLoading = null;
    var openReader = null;
    var ZOOMS = [0.5, 0.75, 1, 1.25, 1.5, 2, 2.5, 3];

    function loadPdfLib(cfg) {
        if (window.pdfjsLib) return Promise.resolve(window.pdfjsLib);
        if (pdfLibLoading) return pdfLibLoading;
        pdfLibLoading = new Promise(function (resolve, reject) {
            var script = document.createElement('script');
            var timer = setTimeout(function () { reject(new Error('timeout')); }, 20000);
            script.src = cfg.lib;
            script.async = true;
            script.onload = function () {
                clearTimeout(timer);
                if (!window.pdfjsLib) { reject(new Error('pdf.js tidak tersedia')); return; }
                window.pdfjsLib.GlobalWorkerOptions.workerSrc = cfg.worker;
                resolve(window.pdfjsLib);
            };
            script.onerror = function () { clearTimeout(timer); reject(new Error('pdf.js gagal dimuat')); };
            document.head.appendChild(script);
        });
        return pdfLibLoading;
    }

    function PdfReader(box) {
        var self = this;
        this.box = box;
        this.url = box.getAttribute('data-pdf');
        this.title = box.getAttribute('data-title') || 'Dokumen PDF';
        this.cfg = {
            lib: box.getAttribute('data-pdfjs-lib'), worker: box.getAttribute('data-pdfjs-worker'),
            cmaps: box.getAttribute('data-pdfjs-cmaps'), fonts: box.getAttribute('data-pdfjs-fonts')
        };
        this.isCard = box.classList.contains('oa-doc--card');
        this.reader = box.querySelector('.oa-doc__reader');
        this.view = box.querySelector('[data-pdf-view]');
        this.sheet = box.querySelector('[data-pdf-sheet]');
        this.status = box.querySelector('[data-pdf-status]');
        this.thumbs = box.querySelector('[data-pdf-thumbs]');
        this.pageInput = box.querySelector('[data-pdf-page]');
        this.page = 1;
        this.total = 0;
        this.zoom = 1;
        this.spread = false;
        this.pdf = null;
        this.loading = null;
        this.token = 0;
        this.expanded = false;

        // Tombol di kartu/kepala dokumen dan di pembaca (pembaca dipindah ke <body> saat layar penuh)
        var onClick = function (e) {
            var btn = e.target.closest('[data-pdf-act]');
            if (btn) { e.preventDefault(); self.action(btn.getAttribute('data-pdf-act')); }
        };
        this.reader.addEventListener('click', onClick);
        box.addEventListener('click', function (e) { if (!self.reader.contains(e.target)) onClick(e); });

        this.pageInput.addEventListener('focus', function () { self.pageInput.select(); });
        this.pageInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); self.go(parseInt(self.pageInput.value, 10) || self.page); self.view.focus({ preventScroll: true }); }
        });
        this.pageInput.addEventListener('change', function () { self.go(parseInt(self.pageInput.value, 10) || self.page); });

        this.view.addEventListener('keydown', function (e) { self.key(e); });

        // Geser kiri/kanan di layar sentuh untuk pindah halaman
        var startX = null, startY = null;
        this.view.addEventListener('pointerdown', function (e) { startX = e.clientX; startY = e.clientY; });
        this.view.addEventListener('pointerup', function (e) {
            if (startX === null || self.zoom > 1) { startX = null; return; }
            var dx = e.clientX - startX, dy = e.clientY - startY;
            startX = null;
            if (Math.abs(dx) > 55 && Math.abs(dy) < 70) self.action(dx < 0 ? 'next' : 'prev');
        });

        // Ukuran halaman menyesuaikan saat layar/jendela berubah
        var resizeTimer;
        var onResize = function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () { if (self.pdf && !self.reader.hidden) self.render(); }, 150);
        };
        if ('ResizeObserver' in window) new ResizeObserver(onResize).observe(this.view);
        else window.addEventListener('resize', onResize);

        // Dokumen baru dimuat saat mendekati layar, agar halaman tetap ringan
        var start = function () {
            if (!self.isCard) self.load();
            else if (box.querySelector('[data-pdf-cover]')) self.cover();
        };
        if ('IntersectionObserver' in window) {
            var io = new IntersectionObserver(function (entries) {
                if (entries.some(function (en) { return en.isIntersecting; })) { io.disconnect(); start(); }
            }, { rootMargin: '300px 0px' });
            io.observe(box);
        } else {
            start();
        }
    }

    PdfReader.prototype.setStatus = function (state, text) {
        if (!state) { this.status.hidden = true; return; }
        this.status.hidden = false;
        this.status.innerHTML = state === 'loading'
            ? '<span><span class="spinner-border spinner-border-sm me-2"></span>' + (text || 'Memuat dokumen...') + '</span>'
            : '<span><i class="bi bi-exclamation-circle d-block fs-3 mb-2"></i>' + (text || 'Dokumen tidak bisa ditampilkan.')
              + '<br><a href="' + this.url + '" target="_blank" rel="noopener" class="btn btn-sm btn-light mt-3"><i class="bi bi-box-arrow-up-right me-1"></i> Buka PDF</a></span>';
    };

    PdfReader.prototype.open = function () {
        var self = this;
        if (this.loading) return this.loading;
        this.loading = loadPdfLib(this.cfg).then(function (lib) {
            return lib.getDocument({
                url: self.url, cMapUrl: self.cfg.cmaps, cMapPacked: true, standardFontDataUrl: self.cfg.fonts,
                disableAutoFetch: true, disableStream: true
            }).promise.then(function (pdf) {
                self.pdf = pdf;
                self.total = pdf.numPages;
                self.box.querySelectorAll('[data-pdf-pages]').forEach(function (el) { el.textContent = pdf.numPages + ' halaman'; });
                self.reader.querySelector('[data-pdf-total]').textContent = pdf.numPages;
                self.box.classList.add('is-ready');
                return pdf;
            }, function (err) {
                self.setStatus('error', err && err.name === 'PasswordException'
                    ? 'Dokumen ini dikunci kata sandi, jadi tidak bisa ditampilkan di sini.'
                    : 'Dokumen tidak bisa ditampilkan. Coba buka atau unduh dokumennya.');
                throw err;
            });
        }, function (err) {
            self.useNative();
            throw err;
        });
        return this.loading;
    };

    PdfReader.prototype.load = function () {
        var self = this;
        this.setStatus('loading');
        return this.open().then(function () { return self.render(); }).catch(function () {});
    };

    // Sampul kartu dokumen dari halaman pertama PDF
    PdfReader.prototype.cover = function () {
        var self = this;
        var canvas = this.box.querySelector('[data-pdf-cover]');
        this.open().then(function (pdf) { return pdf.getPage(1); }).then(function (page) {
            var width = canvas.parentElement.clientWidth || 160;
            var ratio = Math.min(window.devicePixelRatio || 1, 2);
            var base = page.getViewport({ scale: 1 });
            var vp = page.getViewport({ scale: width / base.width });
            canvas.width = Math.floor(vp.width * ratio);
            canvas.height = Math.floor(vp.height * ratio);
            return page.render({ canvasContext: canvas.getContext('2d'), viewport: vp, transform: ratio !== 1 ? [ratio, 0, 0, ratio, 0, 0] : null }).promise;
        }).then(function () {
            canvas.parentElement.classList.add('is-rendered');
        }).catch(function () {});
        return self;
    };

    // pdf.js tidak tersedia: pakai pembaca PDF bawaan browser
    PdfReader.prototype.useNative = function () {
        if (this.native) return;
        this.native = true;
        this.box.classList.add('is-native');
        this.reader.classList.add('is-native');
        var frame = document.createElement('iframe');
        frame.className = 'oa-doc__native';
        frame.src = this.url + '#view=FitH';
        frame.title = this.title;
        this.sheet.innerHTML = '';
        this.sheet.appendChild(frame);
        this.setStatus(false);
    };

    // Halaman yang tampil: satu halaman, atau dua halaman (mode buku, sampul tetap sendiri)
    PdfReader.prototype.pagesFor = function (p) {
        if (!this.spread || p <= 1) return [p];
        var left = p % 2 === 0 ? p : p - 1;
        return left + 1 <= this.total ? [left, left + 1] : [left];
    };

    PdfReader.prototype.go = function (p) {
        if (!this.total) return;
        this.page = Math.max(1, Math.min(this.total, p));
        this.render();
    };

    PdfReader.prototype.action = function (act) {
        var shown = this.pagesFor(this.page);
        switch (act) {
            case 'expand': this.expand(); break;
            case 'close': this.close(); break;
            case 'next': if (shown[shown.length - 1] < this.total) this.go(shown[shown.length - 1] + 1); break;
            case 'prev': if (shown[0] > 1) this.go(shown[0] - 1); break;
            case 'zoom-in': this.setZoom(1); break;
            case 'zoom-out': this.setZoom(-1); break;
            case 'spread':
                this.spread = !this.spread;
                this.userSpread = true;
                this.autoSpread = false;
                this.render();
                break;
            case 'thumbs': this.toggleThumbs(); break;
        }
    };

    PdfReader.prototype.setZoom = function (dir) {
        var i = ZOOMS.indexOf(this.zoom);
        var next = ZOOMS[Math.max(0, Math.min(ZOOMS.length - 1, i + dir))];
        if (next !== this.zoom) { this.zoom = next; this.render(); }
    };

    PdfReader.prototype.key = function (e) {
        if (e.target === this.pageInput) return;
        var map = { ArrowRight: 'next', PageDown: 'next', ArrowLeft: 'prev', PageUp: 'prev', '+': 'zoom-in', '=': 'zoom-in', '-': 'zoom-out' };
        if (map[e.key]) { e.preventDefault(); this.action(map[e.key]); }
        else if (e.key === 'Home') { e.preventDefault(); this.go(1); }
        else if (e.key === 'End') { e.preventDefault(); this.go(this.total); }
        else if (e.key === 'Escape' && this.expanded) { e.preventDefault(); this.close(); }
    };

    PdfReader.prototype.updateUi = function (nums) {
        var r = this.reader;
        this.pageInput.value = nums.length > 1 ? nums[0] + '-' + nums[1] : nums[0];
        this.pageInput.style.width = (nums.length > 1 ? 4.6 : 3.4) + 'em';
        r.querySelectorAll('[data-pdf-act="prev"]').forEach(function (b) { b.disabled = nums[0] <= 1; });
        var last = nums[nums.length - 1];
        var total = this.total;
        r.querySelectorAll('[data-pdf-act="next"]').forEach(function (b) { b.disabled = last >= total; });
        r.querySelector('[data-pdf-zoom]').textContent = Math.round(this.zoom * 100) + '%';
        r.querySelector('[data-pdf-act="zoom-out"]').disabled = this.zoom <= ZOOMS[0];
        r.querySelector('[data-pdf-act="zoom-in"]').disabled = this.zoom >= ZOOMS[ZOOMS.length - 1];
        r.querySelector('[data-pdf-act="spread"]').setAttribute('aria-pressed', this.spread ? 'true' : 'false');
        if (this.thumbList) {
            this.thumbList.forEach(function (t, i) { t.classList.toggle('is-active', nums.indexOf(i + 1) !== -1); });
        }
    };

    PdfReader.prototype.render = function () {
        var self = this;
        if (!this.pdf || this.native) return Promise.resolve();
        var token = ++this.token;
        var nums = this.pagesFor(this.page);
        this.updateUi(nums);

        return Promise.all(nums.map(function (n) { return self.pdf.getPage(n); })).then(function (pages) {
            if (token !== self.token) return null;
            var availW = self.view.clientWidth - 24;
            var availH = self.view.clientHeight - 24;
            if (availW <= 0 || availH <= 0) return null;

            var base = pages.map(function (p) { return p.getViewport({ scale: 1 }); });
            var totalW = base.reduce(function (sum, v) { return sum + v.width; }, 0);
            var maxH = Math.max.apply(null, base.map(function (v) { return v.height; }));
            var scale = Math.min(availW / totalW, availH / maxH) * self.zoom;
            var ratio = Math.min(window.devicePixelRatio || 1, 2);
            var canvases = [];

            var jobs = pages.map(function (page, i) {
                var vp = page.getViewport({ scale: scale });
                var canvas = document.createElement('canvas');
                canvas.width = Math.floor(vp.width * ratio);
                canvas.height = Math.floor(vp.height * ratio);
                canvas.style.width = Math.floor(vp.width) + 'px';
                canvas.style.height = Math.floor(vp.height) + 'px';
                canvas.className = 'oa-doc__page' + (pages.length > 1 ? (i === 0 ? ' is-left' : ' is-right') : '');
                canvases.push(canvas);
                return page.render({
                    canvasContext: canvas.getContext('2d'), viewport: vp,
                    transform: ratio !== 1 ? [ratio, 0, 0, ratio, 0, 0] : null
                }).promise;
            });

            return Promise.all(jobs).then(function () {
                if (token !== self.token) return;
                self.sheet.innerHTML = '';
                canvases.forEach(function (c) { self.sheet.appendChild(c); });
                self.sheet.classList.toggle('is-spread', canvases.length > 1);
                self.view.classList.toggle('is-zoomed', self.zoom > 1);
                self.view.scrollTop = 0;
                self.view.scrollLeft = Math.max(0, (self.view.scrollWidth - self.view.clientWidth) / 2);
                self.setStatus(false);
            });
        }).catch(function () {
            if (token === self.token) self.setStatus('error', 'Halaman ini tidak bisa ditampilkan.');
        });
    };

    // Daftar halaman (gambar kecil) untuk lompat cepat, digambar saat terlihat
    PdfReader.prototype.toggleThumbs = function () {
        var self = this;
        if (!this.pdf) return;
        var show = this.thumbs.hidden;
        this.thumbs.hidden = !show;
        this.reader.querySelector('[data-pdf-act="thumbs"]').setAttribute('aria-pressed', show ? 'true' : 'false');
        if (show && !this.thumbList) {
            this.thumbList = [];
            var io = 'IntersectionObserver' in window ? new IntersectionObserver(function (entries) {
                entries.forEach(function (en) {
                    if (!en.isIntersecting) return;
                    io.unobserve(en.target);
                    self.drawThumb(en.target);
                });
            }, { root: this.thumbs, rootMargin: '200px 0px' }) : null;

            for (var i = 1; i <= this.total; i++) {
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'oa-doc__thumb';
                btn.setAttribute('data-page', i);
                btn.setAttribute('aria-label', 'Halaman ' + i);
                btn.innerHTML = '<canvas></canvas><span>' + i + '</span>';
                btn.addEventListener('click', function () {
                    self.go(parseInt(this.getAttribute('data-page'), 10));
                    if (window.matchMedia('(max-width: 767.98px)').matches) self.toggleThumbs();
                });
                this.thumbs.appendChild(btn);
                this.thumbList.push(btn);
                if (io) io.observe(btn); else this.drawThumb(btn);
            }
            this.updateUi(this.pagesFor(this.page));
        }
        if (show) {
            var active = this.thumbs.querySelector('.is-active');
            if (active) active.scrollIntoView({ block: 'nearest' });
        }
        setTimeout(function () { self.render(); }, 30);
    };

    PdfReader.prototype.drawThumb = function (btn) {
        var canvas = btn.querySelector('canvas');
        this.pdf.getPage(parseInt(btn.getAttribute('data-page'), 10)).then(function (page) {
            var base = page.getViewport({ scale: 1 });
            var vp = page.getViewport({ scale: 200 / base.width });
            canvas.width = Math.floor(vp.width);
            canvas.height = Math.floor(vp.height);
            return page.render({ canvasContext: canvas.getContext('2d'), viewport: vp }).promise;
        }).catch(function () {});
    };

    // Layar penuh: pembaca dipindah ke <body> agar menutupi seluruh layar
    PdfReader.prototype.expand = function () {
        var self = this;
        if (this.expanded) return;
        if (openReader) openReader.close();
        this.expanded = true;
        openReader = this;
        this.placeholder = document.createComment('pembaca-pdf');
        this.reader.parentNode.insertBefore(this.placeholder, this.reader);
        document.body.appendChild(this.reader);
        this.reader.hidden = false;
        this.reader.classList.add('is-expanded');
        document.documentElement.classList.add('oa-doc-open');
        try { history.pushState({ oaDoc: true }, ''); this.pushed = true; } catch (e) { this.pushed = false; }

        if (this.native) { this.view.focus({ preventScroll: true }); return; }
        this.setStatus(this.pdf ? false : 'loading');
        this.open().then(function () {
            // Layar lebar: tampil seperti buku (2 halaman) kecuali pengunjung sudah memilih sendiri
            if (!self.userSpread && window.innerWidth >= 1100 && self.total > 2) { self.spread = true; self.autoSpread = true; }
            return self.render();
        }).then(function () { self.view.focus({ preventScroll: true }); }).catch(function () {});
    };

    PdfReader.prototype.close = function (fromHistory) {
        if (!this.expanded) return;
        this.expanded = false;
        openReader = null;
        this.reader.classList.remove('is-expanded');
        this.placeholder.parentNode.insertBefore(this.reader, this.placeholder);
        this.placeholder.parentNode.removeChild(this.placeholder);
        document.documentElement.classList.remove('oa-doc-open');
        if (this.isCard) this.reader.hidden = true;
        if (this.autoSpread) { this.spread = false; this.autoSpread = false; }
        if (this.pushed) {
            this.pushed = false;
            if (!fromHistory) history.back();
        }
        if (!this.isCard) this.render();
    };

    window.addEventListener('popstate', function () { if (openReader) openReader.close(true); });
    document.addEventListener('keydown', function (e) { if (openReader && !openReader.view.contains(e.target)) openReader.key(e); });

    document.querySelectorAll('[data-pdf]').forEach(function (box) { new PdfReader(box); });

    /* ---------------------------------------------------------------
       Salin teks, nama file unggahan, cegah kirim ganda
       --------------------------------------------------------------- */
    document.querySelectorAll('[data-copy]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            navigator.clipboard.writeText(btn.getAttribute('data-copy')).then(function () {
                var old = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check2"></i> Tersalin';
                setTimeout(function () { btn.innerHTML = old; }, 1600);
            });
        });
    });

    document.querySelectorAll('input[type="file"][data-filename]').forEach(function (input) {
        input.addEventListener('change', function () {
            var target = document.getElementById(input.getAttribute('data-filename'));
            if (target && input.files.length) {
                var size = (input.files[0].size / 1024 / 1024).toFixed(2);
                target.textContent = input.files[0].name + ' (' + size + ' MB)';
            }
        });
    });

    document.querySelectorAll('form[data-once]').forEach(function (form) {
        form.addEventListener('submit', function () {
            var btn = form.querySelector('[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mengirim...';
            }
        });
    });

    /* ---------------------------------------------------------------
       Bilah pencarian & filter: pilihan langsung diterapkan saat diganti,
       isian kosong dan urutan bawaan tidak ikut ditulis di alamat halaman
       --------------------------------------------------------------- */
    document.querySelectorAll('.oa-listbar__form').forEach(function (form) {
        form.querySelectorAll('select[data-autosubmit]').forEach(function (select) {
            select.addEventListener('change', function () {
                if (form.requestSubmit) { form.requestSubmit(); } else { form.submit(); }
            });
        });
        form.addEventListener('submit', function () {
            form.querySelectorAll('input[name], select[name]').forEach(function (field) {
                var isDefaultSort = field.name === 'urut' && field.selectedIndex === 0;
                if (field.value === '' || isDefaultSort) { field.disabled = true; }
            });
        });
    });
    // Saat kembali dengan tombol "Back", isian yang tadi dinonaktifkan dipulihkan
    window.addEventListener('pageshow', function () {
        document.querySelectorAll('.oa-listbar__form [disabled]').forEach(function (field) { field.disabled = false; });
    });
})();
