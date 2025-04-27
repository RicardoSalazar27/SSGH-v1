document.addEventListener('DOMContentLoaded', function () {
    const currentUrl = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
        // Ignorar enlaces que son solo anclas (tabs)
        if (link.hash && link.href.replace(link.hash, '') === window.location.href.replace(window.location.hash, '')) {
            // console.log('Ignorado (tab):', link);
            return;
        }

        if (link.pathname === currentUrl) {
            link.classList.add('active');

            const navItem = link.closest('.nav-item');
            if (navItem) {
                navItem.classList.add('menu-open');
            }

            const treeview = link.closest('.nav-treeview');
            if (treeview) {
                const parentItem = treeview.closest('.nav-item');
                if (parentItem) {
                    parentItem.classList.add('menu-open');
                    const parentLink = parentItem.querySelector('a.nav-link');
                    if (parentLink) {
                        parentLink.classList.add('active');
                    }
                }

                treeview.style.display = 'block';
            }
        }
    });
});
