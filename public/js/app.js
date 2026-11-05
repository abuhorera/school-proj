/**
 * Multi-School ERP Master Client Script
 * Ultra-resilient, zero external dependencies required
 */

// 1. Sidebar Treeview Accordion Toggle
function toggleMenu(event, menuId) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    const menu = document.getElementById(menuId);
    const arrow = document.getElementById(menuId + '-arrow');
    const parentBtn = document.getElementById(menuId + '-parent');
    if (!menu) return;

    const isHidden = menu.classList.contains('hidden');
    if (isHidden) {
        menu.classList.remove('hidden');
        if (arrow) {
            arrow.classList.add('rotate-90');
            arrow.classList.add('text-indigo-400');
        }
        if (parentBtn) {
            parentBtn.classList.add('bg-slate-800/80');
        }

        try {
            let openMenus = JSON.parse(localStorage.getItem('erp_open_menus') || '[]');
            if (!openMenus.includes(menuId)) {
                openMenus.push(menuId);
                localStorage.setItem('erp_open_menus', JSON.stringify(openMenus));
            }
        } catch(e) {}
    } else {
        menu.classList.add('hidden');
        if (arrow) {
            arrow.classList.remove('rotate-90');
            arrow.classList.remove('text-indigo-400');
        }
        if (parentBtn) {
            parentBtn.classList.remove('bg-slate-800/80');
        }

        try {
            let openMenus = JSON.parse(localStorage.getItem('erp_open_menus') || '[]');
            openMenus = openMenus.filter(id => id !== menuId);
            localStorage.setItem('erp_open_menus', JSON.stringify(openMenus));
        } catch(e) {}
    }
}

// 2. Instant Live Sidebar Search Filter
function filterSidebarMenu(query) {
    const q = (query || '').toLowerCase().trim();
    const items = document.querySelectorAll('.treeview-parent-item');

    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        const menuUl = item.querySelector('ul[id^="menu-"]');
        const arrow = item.querySelector('[id$="-arrow"]');

        if (!q) {
            item.style.display = '';
            return;
        }

        if (text.includes(q)) {
            item.style.display = '';
            if (menuUl) {
                menuUl.classList.remove('hidden');
                if (arrow) {
                    arrow.classList.add('rotate-90');
                    arrow.classList.add('text-indigo-400');
                }
            }
        } else {
            item.style.display = 'none';
        }
    });
}

// 3. Mobile Sidebar Toggle
function toggleSidebarCollapse() {
    const sidebar = document.getElementById('main-sidebar');
    if (sidebar) {
        sidebar.classList.toggle('hidden');
    }
}

// 4. Modal Open & Close Helpers
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

// 5. Generic Tab Switcher
function switchTab(containerSelector, activeTabId, activeClass = 'bg-blue-600 text-white', inactiveClass = 'bg-white text-slate-700 hover:bg-slate-50') {
    const container = document.querySelector(containerSelector);
    if (!container) return;

    // Toggle Tab Buttons
    container.querySelectorAll('[data-tab-target]').forEach(btn => {
        const targetId = btn.getAttribute('data-tab-target');
        if (targetId === activeTabId) {
            btn.className = btn.className.replace(inactiveClass, '').trim() + ' ' + activeClass;
        } else {
            btn.className = btn.className.replace(activeClass, '').trim() + ' ' + inactiveClass;
        }
    });

    // Toggle Content Panes
    document.querySelectorAll('[data-tab-content]').forEach(pane => {
        if (pane.id === activeTabId) {
            pane.classList.remove('hidden');
        } else {
            pane.classList.add('hidden');
        }
    });
}

// 6. Safe Chart Renderer Helper (prevents crashes if Canvas or Chart library is missing)
function safeCreateChart(canvasId, chartConfig) {
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js library is not loaded. Skipping chart on #' + canvasId);
        return null;
    }
    const canvas = document.getElementById(canvasId);
    if (!canvas) {
        return null;
    }
    const ctx = canvas.getContext('2d');
    if (!ctx) return null;

    try {
        return new Chart(ctx, chartConfig);
    } catch(err) {
        console.error('Error rendering chart #' + canvasId + ':', err);
        return null;
    }
}

// 7. Auto-run on DOM Ready
document.addEventListener('DOMContentLoaded', function() {
    // Restore remembered sidebar state
    try {
        let openMenus = JSON.parse(localStorage.getItem('erp_open_menus') || '[]');
        openMenus.forEach(menuId => {
            const menu = document.getElementById(menuId);
            const arrow = document.getElementById(menuId + '-arrow');
            const parentBtn = document.getElementById(menuId + '-parent');
            if (menu) {
                menu.classList.remove('hidden');
                if (arrow) {
                    arrow.classList.add('rotate-90');
                    arrow.classList.add('text-indigo-400');
                }
                if (parentBtn) {
                    parentBtn.classList.add('bg-slate-800/80');
                }
            }
        });
    } catch(e) {}

    // Auto expand active link's accordion branch
    document.querySelectorAll('.treeview-sublink.active-sublink').forEach(link => {
        const parentUl = link.closest('ul[id^="menu-"]');
        if (parentUl) {
            parentUl.classList.remove('hidden');
            const arrow = document.getElementById(parentUl.id + '-arrow');
            if (arrow) {
                arrow.classList.add('rotate-90');
                arrow.classList.add('text-indigo-400');
            }
            const parentBtn = document.getElementById(parentUl.id + '-parent');
            if (parentBtn) {
                parentBtn.classList.add('bg-slate-800/80');
            }
        }
    });

    // Close modals on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay:not(.hidden)').forEach(modal => {
                modal.classList.add('hidden');
            });
        }
    });
});

