document.addEventListener('DOMContentLoaded', function () {
    // Schování flash zprávy po 4 sekundách
    const zpravy = document.querySelectorAll('.zprava');
    zpravy.forEach(function (z) {
        setTimeout(function () {
            z.style.transition = 'opacity 0.5s';
            z.style.opacity = '0';
            setTimeout(function () { z.style.display = 'none'; }, 500);
        }, 4000);
    });

    // Aktivní odkaz v sidebaru - zvýraznění
    const aktualniSoubor = window.location.pathname.split('/').pop();
    document.querySelectorAll('.sidebar-nav a').forEach(function (odkaz) {
        const href = odkaz.getAttribute('href');
        if (href === aktualniSoubor) {
            odkaz.classList.add('aktivni');
        } else {
            odkaz.classList.remove('aktivni');
        }
    });

    // Potvrzení před mazáním (záchrana pro starší prohlížeče kde onclick nefunguje)
    document.querySelectorAll('[data-confirm]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            if (!confirm(el.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });

    // Animace kapacita barů
    document.querySelectorAll('.kapacita-vyplneni').forEach(function (bar) {
        const cilova_sirka = bar.style.width;
        bar.style.width = '0';
        setTimeout(function () {
            bar.style.transition = 'width 0.6s ease';
            bar.style.width = cilova_sirka;
        }, 100);
    });
});
