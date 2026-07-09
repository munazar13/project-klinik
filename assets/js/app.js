// =====================================
// VALIDASI FORM & KONFIRMASI AKSI
// =====================================
document.querySelectorAll(".validate-form").forEach(form => {
    form.addEventListener("submit", function (e) {
        let valid = true;
        const semuaInput = this.querySelectorAll("input, select, textarea");

        semuaInput.forEach(input => {
            if (input.hasAttribute("required")) {
                if (input.value.trim() === "") {
                    valid = false;
                    input.classList.add("is-invalid");
                } else {
                    input.classList.remove("is-invalid");
                }
            }
        });

        if (!valid) {
            e.preventDefault();
            alert("⚠️ Semua kolom wajib harus diisi!");
        }
    });

    form.addEventListener("reset", function () {
        setTimeout(() => {
            this.querySelectorAll(".is-invalid").forEach(el => el.classList.remove("is-invalid"));
        }, 50);
    });
});

document.querySelectorAll(".btn-delete").forEach(tombol => {
    tombol.addEventListener("click", function (e) {
        const yakin = confirm("Apakah Anda yakin ingin menghapus data ini?");
        if (!yakin) {
            e.preventDefault();
        }
    });
});
