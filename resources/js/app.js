import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    // ---- Dark mode toggle ----
    const themeBtn = document.getElementById('theme-toggle');

    if (themeBtn) {
        themeBtn.addEventListener('click', async () => {
            const isDark = document.documentElement.classList.toggle('dark');
            const theme = isDark ? 'dark' : 'light';

            localStorage.setItem('theme', theme);

            try {
                await fetch(themeBtn.dataset.url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ theme }),
                });
            } catch (error) {
                console.error('Could not save theme:', error);
            }
        });
    }

    // ---- Mobile sidebar toggle ----
    const sidebarBtn = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');

    if (sidebarBtn && sidebar) {
        sidebarBtn.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
        });
    }
});