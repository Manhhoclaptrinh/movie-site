<footer class="watch-footer">
    <div class="footer-container">

        <div class="footer-col">
            <h4>🎬 MovieSite</h4>
            <p>
                Website xem phim trực tuyến miễn phí,
                cập nhật nhanh, giao diện thân thiện.
            </p>
        </div>

        <div class="footer-col">
            <h4>Danh mục</h4>
            <ul>
                <li><a href="#">Phim mới</a></li>
                <li><a href="#">Phim hot</a></li>
                <li><a href="#">Phim bộ</a></li>
                <li><a href="#">Phim lẻ</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Hỗ trợ</h4>
            <ul>
                <li><a href="#">Liên hệ</a></li>
                <li><a href="#">Bản quyền</a></li>
                <li><a href="#">Điều khoản</a></li>
            </ul>
        </div>

    </div>

    <div class="footer-bottom">
        © <?= date('Y') ?> MovieSite. All rights reserved.
    </div>
</footer>

<style>
.watch-footer {
    background: #0b0b0b;
    border-top: 1px solid #222;
    margin-top: 60px;
}

.footer-container {
    max-width: 1300px;
    margin: auto;
    padding: 40px 30px;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 40px;
}

.footer-col h4 {
    color: #fff;
    margin-bottom: 12px;
}

.footer-col p,
.footer-col a {
    color: #aaa;
    font-size: 14px;
    text-decoration: none;
}

.footer-col ul {
    list-style: none;
    padding: 0;
}

.footer-col li {
    margin-bottom: 6px;
}

.footer-bottom {
    text-align: center;
    padding: 14px;
    font-size: 13px;
    color: #666;
    border-top: 1px solid #222;
}
</style>
