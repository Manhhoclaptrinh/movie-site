// Hàm áp dụng theme
function applyTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('movieTheme', theme);
    
    // Cập nhật active state cho button
    document.querySelectorAll('.theme-btn').forEach(btn => {
        btn.classList.toggle('active', btn.getAttribute('data-theme') === theme);
    });
    
    console.log('✅ Theme đã đổi sang:', theme);
}

// Thêm theme switcher vào trang
function addThemeSwitcher() {
    // Kiểm tra nếu đã có rồi thì không thêm nữa
    if (document.querySelector('.theme-switcher')) return;
    
    const switcher = document.createElement('div');
    switcher.className = 'theme-switcher';
    switcher.innerHTML = `
        <div class="theme-switcher-title">Chọn màu</div>
        <div class="theme-buttons">
            <button class="theme-btn" data-theme="blue" title="Xanh dương"></button>
            <button class="theme-btn" data-theme="pink" title="Hồng"></button>
            <button class="theme-btn" data-theme="yellow" title="Vàng"></button>
            <button class="theme-btn" data-theme="red" title="Đỏ"></button>
            <button class="theme-btn" data-theme="green" title="Xanh lá"></button>
            <button class="theme-btn" data-theme="purple" title="Tím"></button>
            <button class="theme-btn" data-theme="orange" title="Cam"></button>
            <button class="theme-btn" data-theme="cyan" title="Xanh ngọc"></button>
        </div>
    `;
    
    document.body.appendChild(switcher);
    
    // Thêm event listeners
    document.querySelectorAll('.theme-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const theme = btn.getAttribute('data-theme');
            applyTheme(theme);
        });
    });
}

// Phát hiện theme từ URL poster (phương pháp đơn giản hơn)
function detectThemeFromPosterURL() {
    const poster = document.querySelector('.poster img');
    if (!poster || !poster.src) {
        console.log('⚠️ Không tìm thấy poster, dùng theme mặc định');
        return 'blue';
    }
    
    const posterURL = poster.src.toLowerCase();
    
    // Phát hiện theme dựa trên tên file hoặc path
    if (posterURL.includes('pink') || posterURL.includes('hong')) return 'pink';
    if (posterURL.includes('yellow') || posterURL.includes('vang')) return 'yellow';
    if (posterURL.includes('red') || posterURL.includes('do')) return 'red';
    if (posterURL.includes('green') || posterURL.includes('xanh-la')) return 'green';
    if (posterURL.includes('purple') || posterURL.includes('tim')) return 'purple';
    if (posterURL.includes('orange') || posterURL.includes('cam')) return 'orange';
    if (posterURL.includes('cyan') || posterURL.includes('xanh-ngoc')) return 'cyan';
    
    return 'blue'; // Mặc định
}

// Phát hiện theme từ category hoặc tags
function detectThemeFromMetadata() {
    // Lấy thể loại phim
    const categoryElement = document.querySelector('.meta-value');
    const category = categoryElement ? categoryElement.textContent.toLowerCase() : '';
    
    // Lấy tags
    const tags = Array.from(document.querySelectorAll('.tag'))
        .map(tag => tag.textContent.toLowerCase())
        .join(' ');
    
    const allText = (category + ' ' + tags).toLowerCase();
    
    // Map thể loại sang màu
    if (allText.includes('tình cảm') || allText.includes('romance') || allText.includes('lãng mạn')) return 'pink';
    if (allText.includes('hài') || allText.includes('comedy') || allText.includes('vui')) return 'yellow';
    if (allText.includes('hành động') || allText.includes('action') || allText.includes('chiến tranh')) return 'red';
    if (allText.includes('kinh dị') || allText.includes('horror') || allText.includes('ma')) return 'purple';
    if (allText.includes('phiêu lưu') || allText.includes('adventure') || allText.includes('thiên nhiên')) return 'green';
    if (allText.includes('khoa học') || allText.includes('sci-fi') || allText.includes('tương lai')) return 'cyan';
    if (allText.includes('gia đình') || allText.includes('family') || allText.includes('hoạt hình')) return 'orange';
    
    return 'blue'; // Mặc định
}

// Phát hiện màu từ ảnh poster (dùng ColorThief hoặc Canvas API)
async function detectThemeFromPosterImage() {
    const poster = document.querySelector('.poster img');
    if (!poster || !poster.complete) {
        console.log('⚠️ Poster chưa load xong');
        return 'blue';
    }
    
    try {
        // Tạo canvas để phân tích màu
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        
        // Resize nhỏ để phân tích nhanh hơn
        canvas.width = 100;
        canvas.height = 150;
        
        // Vẽ ảnh lên canvas
        ctx.drawImage(poster, 0, 0, 100, 150);
        
        // Lấy dữ liệu pixel
        const imageData = ctx.getImageData(0, 0, 100, 150);
        const data = imageData.data;
        
        // Tính màu trung bình (bỏ qua pixel quá tối/sáng)
        let r = 0, g = 0, b = 0, count = 0;
        
        for (let i = 0; i < data.length; i += 16) { // Skip pixels để tăng tốc
            const red = data[i];
            const green = data[i + 1];
            const blue = data[i + 2];
            const brightness = (red + green + blue) / 3;
            
            // Chỉ lấy pixel có độ sáng vừa phải
            if (brightness > 40 && brightness < 220) {
                r += red;
                g += green;
                b += blue;
                count++;
            }
        }
        
        if (count === 0) return 'blue';
        
        r = Math.round(r / count);
        g = Math.round(g / count);
        b = Math.round(b / count);
        
        // Chuyển RGB sang theme
        return getThemeFromRGB(r, g, b);
        
    } catch (error) {
        console.log('⚠️ Không thể phân tích poster:', error.message);
        return 'blue';
    }
}

// Chuyển RGB sang theme
function getThemeFromRGB(r, g, b) {
    const max = Math.max(r, g, b);
    const min = Math.min(r, g, b);
    const delta = max - min;
    
    // Nếu màu xám (saturation thấp)
    if (delta < 30) return 'blue';
    
    // Tính hue
    let hue = 0;
    if (max === r) {
        hue = ((g - b) / delta) % 6;
    } else if (max === g) {
        hue = (b - r) / delta + 2;
    } else {
        hue = (r - g) / delta + 4;
    }
    
    hue = Math.round(hue * 60);
    if (hue < 0) hue += 360;
    
    // Map hue sang theme
    if (hue >= 330 || hue < 15) return 'red';
    if (hue >= 15 && hue < 45) return 'orange';
    if (hue >= 45 && hue < 75) return 'yellow';
    if (hue >= 75 && hue < 150) return 'green';
    if (hue >= 150 && hue < 200) return 'cyan';
    if (hue >= 200 && hue < 260) return 'blue';
    if (hue >= 260 && hue < 290) return 'purple';
    if (hue >= 290 && hue < 330) return 'pink';
    
    return 'blue';
}

// Khởi tạo theme khi trang load
async function initializeTheme() {
    console.log('🎨 Đang khởi tạo theme...');
    
    // Thêm theme switcher
    addThemeSwitcher();
    
    // Kiểm tra xem có theme đã lưu không
    const savedTheme = localStorage.getItem('movieTheme');
    
    // Ưu tiên 1: Theme người dùng đã chọn
    if (savedTheme) {
        console.log('✅ Dùng theme đã lưu:', savedTheme);
        applyTheme(savedTheme);
        return;
    }
    
    // Ưu tiên 2: Phát hiện từ metadata (nhanh nhất)
    const metadataTheme = detectThemeFromMetadata();
    if (metadataTheme !== 'blue') {
        console.log('✅ Phát hiện theme từ thể loại/tag:', metadataTheme);
        applyTheme(metadataTheme);
        return;
    }
    
    // Ưu tiên 3: Phát hiện từ tên file poster
    const urlTheme = detectThemeFromPosterURL();
    if (urlTheme !== 'blue') {
        console.log('✅ Phát hiện theme từ URL poster:', urlTheme);
        applyTheme(urlTheme);
        return;
    }
    
    // Ưu tiên 4: Chờ poster load xong rồi phân tích màu
    const poster = document.querySelector('.poster img');
    if (poster) {
        if (poster.complete) {
            const imageTheme = await detectThemeFromPosterImage();
            console.log('✅ Phát hiện theme từ ảnh poster:', imageTheme);
            applyTheme(imageTheme);
        } else {
            poster.addEventListener('load', async () => {
                const imageTheme = await detectThemeFromPosterImage();
                console.log('✅ Phát hiện theme từ ảnh poster (sau khi load):', imageTheme);
                applyTheme(imageTheme);
            });
        }
    } else {
        console.log('⚠️ Dùng theme mặc định: blue');
        applyTheme('blue');
    }
}

// Khởi tạo khi DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeTheme);
} else {
    initializeTheme();
}
// Xóa theme cũ khi vào trang mới
localStorage.removeItem('movieTheme');
