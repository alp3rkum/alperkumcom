<?php

?>
<main class="bg-gray-50 p-6 min-h-screen">
    <div class="max-w-screen-xl mx-auto">
        <?php pageTitle("Panel Logosu", "Admin panelinizde sol menüde görünecek olan logoyu bu sayfadan ayarlayabilirsiniz."); ?>
        <form id="logoForm" class="space-y-6" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
             <!--form inputs go here-->
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <div class="col-xs-12 mt-2 text-center">
                <img id="logo-img" src="<?= $logo['var_value'] ?>" class="img-fluid mx-auto" style="max-height: 500px;">
                <input type="file" id="file-input" name="file-input" class="form-control-file my-2 mt-3" accept="image/*">
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
        const form = document.getElementById('logoForm');
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
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
        });
    });
</script>