// ===== DASHBOARD JAVASCRIPT =====

document.addEventListener('DOMContentLoaded', function() {
    // Animate stats on page load
    animateStats();
    
    // Handle sidebar toggle on mobile
    handleSidebarToggle();
    
    // Auto refresh stats every 30 seconds
    setInterval(refreshStats, 30000);
});

// Animate stat numbers
function animateStats() {
    const statValues = document.querySelectorAll('.stat-value');
    
    statValues.forEach(stat => {
        const target = parseInt(stat.textContent.replace(/,/g, ''));
        const duration = 1500; // 1.5 seconds
        const step = target / (duration / 16); // 60fps
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            stat.textContent = formatNumber(Math.floor(current));
        }, 16);
    });
}

// Format number with commas
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Handle sidebar toggle for mobile
function handleSidebarToggle() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    
    // Add mobile menu button if not exists
    if (window.innerWidth <= 768 && !document.querySelector('.mobile-menu-btn')) {
        const menuBtn = document.createElement('button');
        menuBtn.className = 'mobile-menu-btn';
        menuBtn.innerHTML = `
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M3 18H21V16H3V18ZM3 13H21V11H3V13ZM3 6V8H21V6H3Z" fill="currentColor"/>
            </svg>
        `;
        
        const topbar = document.querySelector('.topbar');
        topbar.insertBefore(menuBtn, topbar.firstChild);
        
        menuBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            mainContent.classList.toggle('sidebar-active');
        });
    }
}

// Refresh stats (optional - would need AJAX endpoint)
function refreshStats() {
    // This would make an AJAX call to get updated stats
    // For now, just a placeholder
    console.log('Stats refreshed at ' + new Date().toLocaleTimeString());
}

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Add loading state to buttons
document.querySelectorAll('.btn').forEach(btn => {
    if (btn.getAttribute('href') && !btn.getAttribute('target')) {
        btn.addEventListener('click', function(e) {
            if (!this.classList.contains('btn-small')) {
                this.style.opacity = '0.6';
                this.style.pointerEvents = 'none';
            }
        });
    }
});

// Auto-hide notifications
const alerts = document.querySelectorAll('.alert');
alerts.forEach(alert => {
    setTimeout(() => {
        alert.style.animation = 'slideUp 0.3s ease';
        setTimeout(() => alert.remove(), 300);
    }, 5000);
});

// Add CSS for mobile menu button
const style = document.createElement('style');
style.textContent = `
    .mobile-menu-btn {
        display: none;
        background: none;
        border: none;
        color: var(--text-color);
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
        transition: background 0.2s;
    }
    
    .mobile-menu-btn:hover {
        background: var(--bg-color);
    }
    
    @media (max-width: 768px) {
        .mobile-menu-btn {
            display: block;
        }
        
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s;
        }
        
        .sidebar.active {
            transform: translateX(0);
        }
    }
    
    @keyframes slideUp {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-20px);
        }
    }
`;
document.head.appendChild(style);

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + D to go to dashboard
    if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
        e.preventDefault();
        window.location.href = 'dashboard.php';
    }
    
    // Ctrl/Cmd + M to go to movies
    if ((e.ctrlKey || e.metaKey) && e.key === 'm') {
        e.preventDefault();
        window.location.href = '../movies.php';
    }
});

