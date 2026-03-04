<?php
$logo_yolu = "/admin/logo.png";
$sunucu_yolu = $_SERVER['DOCUMENT_ROOT'] . $logo_yolu;
if (file_exists($sunucu_yolu)) {
    $gosterilecek_logo = $logo_yolu . "?v=" . filemtime($sunucu_yolu); // v parametresi cache'i önler
} else {
    $gosterilecek_logo = "https://placehold.co/400x200?text=Logo";
}
?>
<main class="bg-gray-50 p-6 min-h-screen">
    <div class="max-w-screen-xl mx-auto">
        <?php pageTitle("Panel Logosu", "Admin panelinizde sol menüde görünecek olan logoyu bu sayfadan ayarlayabilirsiniz."); ?>
        <form id="logoForm" class="space-y-6" enctype="multipart/form-data">
             <!--form inputs go here-->
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <div class="col-xs-12 mt-2 text-center">
                <img id="logo-img" src="<?= $gosterilecek_logo ?>" class="block mx-auto img-fluid rounded shadow-sm" style="max-height: 500px; width: auto; object-fit: contain;" onerror="this.onerror=null;this.src='https://placehold.co/400x200?text=Logo';">
                <!-- <input type="file" id="file-input" name="file-input" class="form-control-file my-2 mt-3" accept="image/*"> -->
                 <div class="flex justify-center space-x-2 mt-4">
                <label for="file-input" class="bg-indigo-600 text-white font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700 active:bg-indigo-800 active:scale-95 transition duration-150 shadow-md shadow-indigo-500/30">📂 Dosya Seç</label>
                <input type="file" id="file-input" name="file-input" class="hidden" accept="image/*">
                </div>
            </div>
            <div class="pt-2 text-right">
                <button type="submit" id="saveBtn" class="bg-indigo-600 text-white font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700 active:bg-indigo-800 active:scale-95 transition duration-150 shadow-md shadow-indigo-500/30">
                    Kaydet
                </button>
            </div>
        </form>
    </div>
</main>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const fileInput = document.getElementById('file-input');
  const logoImg = document.getElementById('logo-img');

  fileInput.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        logoImg.src = e.target.result; // seçilen dosyayı önizleme
      };
      reader.readAsDataURL(file);
    }
  });
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('logoForm');
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            showOverlay();
            const formData = new FormData(this);
            try {
                const res = await fetch('/admin/ajax/logo.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await res.json();

                new Notification({
                    text: result.message,
                    type: result.status === "success" ? "success" : "error",
                    position: "top-right",
                    autoClose: 3000,
                    showProgress: true
                });
            } catch (error) {
                new Notification({
                    text: "İşlem sırasında bir hata oluştu: " + error.message,
                    type: "error",
                    position: "top-right"
                });
            }
            finally {
                hideOverlay();
            }
        });
    });
</script>