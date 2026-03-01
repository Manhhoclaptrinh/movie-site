// ===== LOGIN PAGE JAVASCRIPT =====

// Toggle password visibility
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeOpen = document.querySelector('.eye-open');
    const eyeClosed = document.querySelector('.eye-closed');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeOpen.style.display = 'none';
        eyeClosed.style.display = 'block';
    } else {
        passwordInput.type = 'password';
        eyeOpen.style.display = 'block';
        eyeClosed.style.display = 'none';
    }
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            
            // Basic validation
            if (username === '' || password === '') {
                e.preventDefault();
                showAlert('Vui lòng điền đầy đủ thông tin!', 'error');
                return false;
            }
            
            if (username.length < 3) {
                e.preventDefault();
                showAlert('Tên đăng nhập phải có ít nhất 3 ký tự!', 'error');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                showAlert('Mật khẩu phải có ít nhất 6 ký tự!', 'error');
                return false;
            }
            
            // Show loading state
            const submitBtn = loginForm.querySelector('.btn-login');
            submitBtn.innerHTML = '<span>Đang đăng nhập...</span>';
            submitBtn.disabled = true;
        });
    }
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.animation = 'fadeOut 0.5s ease';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
});

// Show custom alert
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
    
    const form = document.querySelector('form');
    form.parentNode.insertBefore(alert, form);
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        alert.style.animation = 'fadeOut 0.5s ease';
        setTimeout(() => alert.remove(), 500);
    }, 5000);
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + K to focus username
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        document.getElementById('username').focus();
    }
});

// Add fade out animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }
`;
document.head.appendChild(style);
