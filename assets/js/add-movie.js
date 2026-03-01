// ===== ADD MOVIE JAVASCRIPT =====

// Preview image before upload
function previewImage(input) {
    const preview = document.getElementById('preview');
    const placeholder = document.querySelector('.upload-placeholder');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Drag and drop functionality
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('poster');
    
    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });
    
    // Highlight drop area when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, () => {
            uploadArea.classList.add('drag-over');
        }, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, () => {
            uploadArea.classList.remove('drag-over');
        }, false);
    });
    
    // Handle dropped files
    uploadArea.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            fileInput.files = files;
            previewImage(fileInput);
        }
    }, false);
    
    // Form validation
    const form = document.getElementById('addMovieForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                return false;
            }
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>Đang thêm phim...</span>';
        });
    }
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

// Validate form
function validateForm() {
    const title = document.getElementById('title').value.trim();
    const category = document.getElementById('category_id').value;
    const fileInput = document.getElementById('poster');
    
    if (title === '') {
        showAlert('Vui lòng nhập tên phim!', 'error');
        document.getElementById('title').focus();
        return false;
    }
    
    if (category === '') {
        showAlert('Vui lòng chọn thể loại!', 'error');
        document.getElementById('category_id').focus();
        return false;
    }
    
    // Validate file size (max 5MB)
    if (fileInput.files.length > 0) {
        const fileSize = fileInput.files[0].size / 1024 / 1024; // in MB
        if (fileSize > 5) {
            showAlert('Kích thước ảnh không được vượt quá 5MB!', 'error');
            return false;
        }
        
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (!allowedTypes.includes(fileInput.files[0].type)) {
            showAlert('Chỉ chấp nhận file ảnh (JPG, PNG, WEBP)!', 'error');
            return false;
        }
    }
    
    return true;
}

// Reset form
function resetForm() {
    const preview = document.getElementById('preview');
    const placeholder = document.querySelector('.upload-placeholder');
    
    if (preview && placeholder) {
        preview.style.display = 'none';
        placeholder.style.display = 'block';
    }
    
    // Reset form validation
    const form = document.getElementById('addMovieForm');
    if (form) {
        form.reset();
    }
}

// Show alert message
function showAlert(message, type = 'error') {
    // Remove existing alerts
    const existingAlert = document.querySelector('.alert');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    // Create new alert
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.innerHTML = `
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
            <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM13 17H11V15H13V17ZM13 13H11V7H13V13Z" fill="currentColor"/>
        </svg>
        <span>${message}</span>
    `;
    
    // Insert alert before form
    const formContainer = document.querySelector('.form-container');
    formContainer.insertBefore(alert, formContainer.firstChild);
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        alert.style.animation = 'slideUp 0.3s ease';
        setTimeout(() => alert.remove(), 300);
    }, 5000);
}

// Auto-generate slug (optional feature)
document.getElementById('title')?.addEventListener('input', function() {
    // This could be used to auto-generate URL slugs if needed
    const slug = this.value
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/đ/g, 'd')
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim();
    
    // You can store this slug in a hidden field if needed
    console.log('Generated slug:', slug);
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + S to submit form
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        document.getElementById('addMovieForm').dispatchEvent(new Event('submit'));
    }
    
    // Ctrl/Cmd + R to reset form
    if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
        e.preventDefault();
        resetForm();
    }
});
