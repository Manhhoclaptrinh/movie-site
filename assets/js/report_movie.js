document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("reportForm");

    if (!form) {
        console.error("❌ Không tìm thấy form reportForm");
        return;
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault(); // ❗ CỰC KỲ QUAN TRỌNG

        const formData = new FormData(form);

        fetch("/controllers/MovieErrorReportController.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            document.getElementById("result").innerHTML = data;
            form.reset();
        })
        .catch(err => {
            document.getElementById("result").innerHTML =
                "<span style='color:red'>❌ Gửi thất bại</span>";
            console.error(err);
        });
    });
});
