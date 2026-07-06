// =====================================
// VALIDASI SEMUA FORM
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
            alert("⚠️ Semua kolom yang bertanda wajib harus diisi!");
        }
    });

    // Bersihkan tanda merah saat tombol reset diklik
    form.addEventListener("reset", function () {
        setTimeout(() => {
            this.querySelectorAll(".is-invalid").forEach(el => el.classList.remove("is-invalid"));
        }, 50);
    });
});

// =====================================
// PENCARIAN SEDERHANA
// =====================================
// Cari Data Pasien
const cariPasien = document.getElementById("searchPasien");
if (cariPasien) {
    cariPasien.addEventListener("keyup", function () {
        const kata = this.value.toLowerCase();
        document.querySelectorAll("#tablePasien tbody tr").forEach(baris => {
            const teksBaris = baris.textContent.toLowerCase();
            baris.style.display = teksBaris.includes(kata) ? "" : "none";
        });
    });
}

// Cari Data Obat
const cariObat = document.getElementById("searchObat");
if (cariObat) {
    cariObat.addEventListener("keyup", function () {
        const kata = this.value.toLowerCase();
        document.querySelectorAll("#tableObat tbody tr").forEach(baris => {
            const teksBaris = baris.textContent.toLowerCase();
            baris.style.display = teksBaris.includes(kata) ? "" : "none";
        });
    });
}

// =====================================
// KONFIRMASI HAPUS DATA
// =====================================
document.querySelectorAll(".btn-delete").forEach(tombol => {
    tombol.addEventListener("click", function (e) {
        e.preventDefault();
        const yakin = confirm("Apakah Anda yakin ingin menghapus data ini?");
        if (yakin) {
            this.closest("tr").remove();
            alert("✅ Data berhasil dihapus!");
        }
    });
});