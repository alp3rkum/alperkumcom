<?php
// PHP veri çekme
try {
    // İletişim ve Çalışma Saatleri için global_vars anahtarları
    $keys = ['email_adres', 'calisma_saatleri_haftaici', 'calisma_saatleri_cumartesi', 'calisma_saatleri_pazar', 'telefon_numarasi'];
    $global_vars = $database->getGlobalVars(...$keys);
} catch (Exception $e) {
    // Hata durumunda boş dizi kullan
    $global_vars = [];
}
?>

<!-- HTML / Tailwind Kodu -->
<main class="bg-gray-50 p-6 min-h-screen">
    <div class="max-w-screen-xl mx-auto">
        <!-- Başlık -->
        <section class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">📞 İletişim Bilgileri Yönetimi</h2>
            <p class="text-gray-500 mt-1">Sitenizin iletişim e-posta, telefon ve çalışma saatlerini buradan belirleyebilirsiniz.</p>
        </section>

        <!-- İletişim Formu -->
        <form id="iletisimForm" class="mx-auto" style="max-width: 800px;">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            
            <!-- Form Kartı (Modernize Edilmiş Kart Stili) -->
            <section class="bg-white rounded-xl shadow-xl border border-gray-100 p-8 space-y-6">
                <h3 class="text-xl font-bold text-gray-800 border-b pb-3 mb-6">Temel İletişim</h3>

                <!-- E-Posta ve Telefon -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email_adres" class="block font-semibold text-sm text-gray-700 mb-2">E-Posta Adresi</label>
                        <input type="email" id="email_adres" name="email_adres" placeholder="bilgi@siteadi.com"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm"
                               value="<?= htmlspecialchars($global_vars['email_adres'] ?? '') ?>">
                    </div>
                    <div>
                        <label for="telefon_numarasi" class="block font-semibold text-sm text-gray-700 mb-2">Telefon Numarası</label>
                        <input type="tel" id="telefon_numarasi" name="telefon_numarasi" placeholder="+90 (555) 123 45 67"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm"
                               value="<?= htmlspecialchars($global_vars['telefon_numarasi'] ?? '') ?>">
                    </div>
                </div>
                
                <h3 class="text-xl font-bold text-gray-800 border-b pb-3 mb-6 pt-4">Çalışma Saatleri</h3>

                <!-- Çalışma Saatleri (Grid) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- Hafta İçi -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 shadow-sm">
                        <label for="calisma_saatleri_haftaici" class="block font-semibold text-sm text-gray-700 mb-2">Hafta İçi (Pzt-Cum)</label>
                        <input type="text" id="calisma_saatleri_haftaici" name="calisma_saatleri_haftaici" placeholder="09:00 - 18:00"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm"
                               value="<?= htmlspecialchars($global_vars['calisma_saatleri_haftaici'] ?? '') ?>">
                    </div>

                    <!-- Cumartesi -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 shadow-sm">
                        <label for="calisma_saatleri_cumartesi" class="block font-semibold text-sm text-gray-700 mb-2">Cumartesi</label>
                        <input type="text" id="calisma_saatleri_cumartesi" name="calisma_saatleri_cumartesi" placeholder="10:00 - 14:00 (Kapalı ise 'Kapalı')"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm"
                               value="<?= htmlspecialchars($global_vars['calisma_saatleri_cumartesi'] ?? '') ?>">
                    </div>

                    <!-- Pazar -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 shadow-sm">
                        <label for="calisma_saatleri_pazar" class="block font-semibold text-sm text-gray-700 mb-2">Pazar</label>
                        <input type="text" id="calisma_saatleri_pazar" name="calisma_saatleri_pazar" placeholder="Kapalı"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm"
                               value="<?= htmlspecialchars($global_vars['calisma_saatleri_pazar'] ?? '') ?>">
                    </div>

                </div>
            </section>
            
            <!-- Kaydet Butonu -->
            <section class="text-center p-6">
                <button type="submit" id="saveIletisimBtn"
                        class="bg-indigo-600 text-white font-semibold text-lg px-8 py-3 rounded-lg hover:bg-indigo-700 active:bg-indigo-800 active:scale-95 transition duration-150 shadow-lg shadow-indigo-500/50">
                    Kaydet
                </button>
            </section>
        </form>
    </div>
</main>

<script>
    // Form Gönderimini AJAX'a dönüştürme
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('iletisimForm');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            showOverlay();

            const formData = new FormData(this);

            try {
                // AJAX modül yolunu belirt
                const res = await fetch('/admin/ajax/iletisim.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await res.json();
                
                // Varsayılan Notification objeniz ile bildirim gönder
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