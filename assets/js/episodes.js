// ===== EPISODES JAVASCRIPT =====

document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('addEpisodeForm');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateEpisodeForm()) {
                e.preventDefault();
                return false;
            }
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>Đang thêm tập...</span>';
        });
    }
    
    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.animation = 'slideUp 0.3s ease';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
    
    // Video URL validation helper
    const videoUrlInput = document.getElementById('video_url');
    if (videoUrlInput) {
        videoUrlInput.addEventListener('input', function() {
            validateVideoUrl(this.value);
        });
    }
});

// Validate episode form
function validateEpisodeForm() {
    const episodeNumber = document.getElementById('episode_number').value;
    const videoUrl = document.getElementById('video_url').value;
    
    // Validate episode number
    if (episodeNumber === '' || episodeNumber < 1) {
        showAlert('Vui lòng nhập số tập hợp lệ!', 'error');
        document.getElementById('episode_number').focus();
        return false;
    }
    
    // Validate video URL
    if (videoUrl === '') {
        showAlert('Vui lòng nhập link video!', 'error');
        document.getElementById('video_url').focus();
        return false;
    }
    
    if (!isValidUrl(videoUrl)) {
        showAlert('Link video không hợp lệ! Vui lòng nhập URL đầy đủ.', 'error');
        document.getElementById('video_url').focus();
        return false;
    }
    
    return true;
}

// Check if URL is valid
function isValidUrl(url) {
    try {
        new URL(url);
        return true;
    } catch (e) {
        return false;
    }
}

// Validate and show video URL type
function validateVideoUrl(url) {
    const urlInput = document.getElementById('video_url');
    
    if (!url) return;
    
    // Remove existing badges
    const existingBadge = urlInput.parentElement.querySelector('.url-type-badge');
    if (existingBadge) existingBadge.remove();
    
    if (!isValidUrl(url)) return;
    
    // Detect video platform
    let platform = 'Direct Link';
    let color = '#6366f1';
    
    if (url.includes('youtube.com') || url.includes('youtu.be')) {
        platform = 'YouTube';
        color = '#ff0000';
    } else if (url.includes('vimeo.com')) {
        platform = 'Vimeo';
        color = '#1ab7ea';
    } else if (url.includes('drive.google.com')) {
        platform = 'Google Drive';
        color = '#4285f4';
    } else if (url.includes('dailymotion.com')) {
        platform = 'Dailymotion';
        color = '#0066dc';
    }
    
    // Create badge
    const badge = document.createElement('span');
    badge.className = 'url-type-badge';
    badge.textContent = platform;
    badge.style.cssText = `
        display: inline-block;
        margin-top: 8px;
        padding: 4px 12px;
        background: ${color};
        color: white;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    `;
    
    urlInput.parentElement.appendChild(badge);
}

// Show alert message
function showAlert(message, type = 'error') {
    const existingAlert = document.querySelector('.alert');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.innerHTML = `
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
            <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM13 17H11V15H13V17ZM13 13H11V7H13V13Z" fill="currentColor"/>
        </svg>
        <span>${message}</span>
    `;
    
    const container = document.querySelector('.admin-container');
    const header = document.querySelector('.header');
    container.insertBefore(alert, header.nextSibling);
    
    setTimeout(() => {
        alert.style.animation = 'slideUp 0.3s ease';
        setTimeout(() => alert.remove(), 300);
    }, 5000);
}

// Auto-increment episode number
const episodeInput = document.getElementById('episode_number');
if (episodeInput) {
    // Get the highest episode number from the table
    const episodeBadges = document.querySelectorAll('.episode-badge');
    if (episodeBadges.length > 0) {
        let maxEpisode = 0;
        episodeBadges.forEach(badge => {
            const episodeNum = parseInt(badge.textContent.replace('Tập ', ''));
            if (episodeNum > maxEpisode) {
                maxEpisode = episodeNum;
            }
        });
        episodeInput.value = maxEpisode + 1;
    }
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + N to focus on episode number input
    if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
        e.preventDefault();
        document.getElementById('episode_number').focus();
    }
    
    // Ctrl/Cmd + S to submit form
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        const form = document.getElementById('addEpisodeForm');
        if (form) {
            form.dispatchEvent(new Event('submit'));
        }
    }
});

// Confirm before delete
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function(e) {
        if (!confirm('Bạn có chắc muốn xóa tập phim này?')) {
            e.preventDefault();
            return false;
        }
    });
});

// Add animation styles
const style = document.createElement('style');
style.textContent = `
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
    
    .url-type-badge {
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);
