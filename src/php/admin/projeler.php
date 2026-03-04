<?php
$projeler = $database->selectMulti("* FROM projeler");
?>
<main class="bg-gray-50 p-6 min-h-screen">
    <div class="max-w-screen-xl mx-auto">
        <?php pageTitle("Projeler Sayfası", "Proje ve medya dosyalarını buradan yönetebilirsiniz."); ?>

        <div class="grid grid-cols-1 lg:grid-cols-[300px_auto] gap-6">
            <!-- Sol Liste -->
            <aside class="bg-white rounded-xl shadow-lg border border-gray-100 p-4 h-[600px] overflow-y-auto">
                <h3 class="text-lg font-bold text-gray-800 border-b pb-3 mb-3">Projeler</h3>
                <ul id="dataUl" class="space-y-2">
                    <?php foreach ($projeler as $proje): ?>
                        <li class="flex items-center bg-gray-100 hover:bg-indigo-50/70 text-gray-800 px-3 py-2 rounded-lg cursor-pointer transition duration-150" data-id="<?= $proje['id'] ?>">
                            <span class="font-medium"><?= htmlspecialchars($proje['proje_baslik_tr']) ?></span>
                        </li>   
                    <?php endforeach; ?>
                </ul>
            </aside>

            <!-- Sağ Form -->
            <section class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <form id="dataForm" class="space-y-6" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id" value="0">

                    <!-- Başlık ve İçerik -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-sm text-gray-700 mb-2">Proje Başlığı (TR)</label>
                            <input type="text" name="proje_baslik_tr" id="proje_baslik_tr" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                        </div>
                        <div>
                            <label class="block font-semibold text-sm text-gray-700 mb-2">Proje Başlığı (EN)</label>
                            <input type="text" name="proje_baslik_en" id="proje_baslik_en" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block font-semibold text-sm text-gray-700 mb-2">Proje Açıklama (TR)</label>
                            <textarea id="proje_aciklama_tr" name="proje_aciklama_tr"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block font-semibold text-sm text-gray-700 mb-2">Proje Açıklama (EN)</label>
                            <textarea id="proje_aciklama_en" name="proje_aciklama_en"></textarea>
                        </div>
                    </div>

                    <!-- Medya ve Teknolojiler -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-indigo-50/30 rounded-xl border border-indigo-100">
                        <div>
                            <label class="block font-semibold text-sm text-indigo-900 mb-2">Yeni Medya Yükle (Resim/Video)</label>
                            <input type="file" id="media_files" name="media_files[]" class="w-full text-sm" accept="image/*,video/*" multiple>
                            <p class="text-[10px] text-indigo-500 mt-1">Aynı anda birden fazla dosya seçebilirsiniz.</p>
                        </div>
                        <div>
                            <label class="block font-semibold text-sm text-indigo-900 mb-2">Teknolojiler</label>
                            <input type="text" name="proje_teknolojiler" class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="Örn: Vue, Node.js, Docker">
                        </div>
                    </div>

                    <!-- MEVCUT GÖRSELLER GALERİSİ -->
                    <div id="mediaGalleryContainer" class="hidden">
                        <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Mevcut Medyalar
                        </h4>
                        <div id="mediaGallery" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            <!-- JS ile doldurulacak -->
                        </div>
                    </div>

                    <h4 class="text-md font-bold text-gray-800 border-b pb-2 mb-2 pt-4">SEO ve Meta Verileri</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Türkçe SEO -->
                        <div class="space-y-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <h5 class="font-bold text-indigo-700 text-sm uppercase">Türkçe Meta Bilgileri</h5>
                            <div>
                                <label for="meta_title_tr" class="block text-xs font-bold text-gray-600 mb-1">SEO Başlık</label>
                                <input type="text" id="meta_title_tr" name="meta_title_tr" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label for="meta_desc_tr" class="block text-xs font-bold text-gray-600 mb-1">SEO Açıklama (Description)</label>
                                <textarea id="meta_desc_tr" name="meta_desc_tr" rows="2" class="w-full border border-gray-300 rounded px-3 py-2 text-sm"></textarea>
                            </div>
                            <div>
                                <label for="meta_keyword_tr" class="block text-xs font-bold text-gray-600 mb-1">Anahtar Kelimeler (Keywords)</label>
                                <input type="text" id="meta_keyword_tr" name="meta_keyword_tr" placeholder="kelime, kelime, kelime" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label for="meta_url_tr" class="block text-xs font-bold text-gray-600 mb-1">Sayfa URL (Slug)</label>
                                <input type="text" id="meta_url_tr" name="meta_url_tr" class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2 text-sm text-gray-500" readonly>
                            </div>
                        </div>

                        <!-- İngilizce SEO -->
                        <div class="space-y-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <h5 class="font-bold text-indigo-700 text-sm uppercase">İngilizce Meta Bilgileri</h5>
                            <div>
                                <label for="meta_title_en" class="block text-xs font-bold text-gray-600 mb-1">SEO Title</label>
                                <input type="text" id="meta_title_en" name="meta_title_en" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label for="meta_desc_en" class="block text-xs font-bold text-gray-600 mb-1">SEO Description</label>
                                <textarea id="meta_desc_en" name="meta_desc_en" rows="2" class="w-full border border-gray-300 rounded px-3 py-2 text-sm"></textarea>
                            </div>
                            <div>
                                <label for="meta_keyword_en" class="block text-xs font-bold text-gray-600 mb-1">Keywords</label>
                                <input type="text" id="meta_keyword_en" name="meta_keyword_en" placeholder="word, word, word" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label for="meta_url_en" class="block text-xs font-bold text-gray-600 mb-1">Page URL (Slug)</label>
                                <input type="text" id="meta_url_en" name="meta_url_en" class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2 text-sm text-gray-500" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between gap-2 pt-6">
                        <button type="button" id="new" class="flex-1 hidden bg-gray-200 text-indigo-600 font-semibold px-4 py-2 rounded-lg hover:bg-gray-300 transition">Yeni Proje</button>
                        <button type="submit" id="saveOrUpdate" class="flex-3 bg-indigo-600 text-white font-semibold px-8 py-2 rounded-lg hover:bg-indigo-700 transition">Projeyi Kaydet</button>
                        <button type="button" id="delete" class="flex-1 hidden bg-red-600 text-white font-semibold px-4 py-2 rounded-lg hover:bg-red-700 transition">Sil</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</main>

<script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/classic/ckeditor.js"></script>
<script>
    let editorInstanceTr, editorInstanceEn;
    const editorConfig = {
        ckfinder: { uploadUrl: '/admin/ajax/upload.php' },
        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'imageUpload', 'bulletedList', 'numberedList', 'blockQuote'],
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraf', class: 'ck-heading_paragraph' },
                { model: 'heading1', view: 'h1', title: 'Başlık 1', class: 'ck-heading_heading1' },
                { model: 'heading2', view: 'h2', title: 'Başlık 2', class: 'ck-heading_heading2' },
                { model: 'heading3', view: 'h3', title: 'Başlık 3', class: 'ck-heading_heading3' }
            ]
        }
    };

    const customUploadAdapter = (loader) => {
        return {
            upload: () => {
                return loader.file.then(file => {
                    return new Promise((resolve, reject) => {
                        const data = new FormData();
                        data.append('file', file);
                        data.append('csrf_token', "<?= $_SESSION['csrf_token'] ?>");

                        fetch('/admin/ajax/upload.php', {
                            method: 'POST',
                            body: data
                        })
                        .then(response => {
                            if (!response.ok) throw new Error("Sunucu hatası: " + response.status);
                            return response.json();
                        })
                        .then(result => {
                            // CKEditor BURAYI BEKLER:
                            if (result.success && result.path) {
                                resolve({
                                    default: result.path // Editör bu URL'yi alıp <img> src'sine yazar
                                });
                            } else {
                                reject(result.message || "Yükleme başarısız");
                            }
                        })
                        .catch(error => {
                            reject("Hata: " + error.message);
                        });
                    });
                });
            },
            abort: () => {}
        };
    };

    ClassicEditor.create(document.querySelector('#proje_aciklama_tr'), editorConfig)
    .then(editor => {
        editorInstanceTr = editor;

        // Upload adapter override → CSRF token ekleniyor
        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
            return customUploadAdapter(loader);
        };
    })
    .catch(console.error);

    ClassicEditor.create(document.querySelector('#proje_aciklama_en'), editorConfig)
    .then(editor => {
        editorInstanceEn = editor;

        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
            return customUploadAdapter(loader);
        };
    })
    .catch(console.error);

    function slugify(text) {
        return text.toString().normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/ı/g, "i").replace(/İ/g, "i").replace(/ş/g, "s").replace(/Ş/g, "s").replace(/ğ/g, "g").replace(/Ğ/g, "g").replace(/ç/g, "c").replace(/Ç/g, "c").replace(/ö/g, "o").replace(/Ö/g, "o").replace(/ü/g, "u").replace(/Ü/g, "u").toLowerCase().trim().replace(/[^a-z0-9]+/g, "-").replace(/^-+|-+$/g, "");
    }

    document.getElementById("proje_baslik_tr").addEventListener("input", e => document.getElementById("meta_url_tr").value = slugify(e.target.value));
    document.getElementById("proje_baslik_en").addEventListener("input", e => document.getElementById("meta_url_en").value = slugify(e.target.value));

    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById("dataForm");
        const dataUl = document.getElementById("dataUl");
        const mediaGallery = document.getElementById("mediaGallery");
        const galleryContainer = document.getElementById("mediaGalleryContainer");

        // PROJE MEDYALARINI GETİR VE GÖSTER
        const fetchAndRenderMedia = async (proje_id) => {
            const fd = new FormData();
            fd.append("table", "proje_gorseller");
            fd.append("where", "proje_id = " + proje_id);
            fd.append("csrf_token","<?= $_SESSION['csrf_token'] ?>");

            try {
                const res = await fetch("/admin/ajax/read.php", { method: "POST", body: fd });
                const result = await res.json();
                
                mediaGallery.innerHTML = '';
                if (result.success && result.data.length > 0) {
                    galleryContainer.classList.remove("hidden");
                    result.data.forEach(item => {
                        const div = document.createElement("div");
                        div.className = "relative group border rounded-lg overflow-hidden bg-gray-50 aspect-video";
                        
                        let content = '';
                        if (item.gorsel_tipi === 'video') {
                            content = `<video src="/${item.gorsel_yolu}" class="w-full h-full object-cover"></video>`;
                        } else {
                            content = `<img src="/${item.gorsel_yolu}" class="w-full h-full object-cover">`;
                        }

                        div.innerHTML = `
                            ${content}
                            <button type="button" onclick="deleteSingleMedia(${item.id}, this)" class="absolute top-1 right-1 bg-red-600 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition shadow-lg">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        `;
                        mediaGallery.appendChild(div);
                    });
                } else {
                    galleryContainer.classList.add("hidden");
                }
            } catch (err) { console.error(err); }
        };

        // LİSTE TIKLAMA
        const handleLiClick = async (li) => {
            showOverlay();
            const id = li.dataset.id;
            const fd = new FormData();
            fd.append("table", "projeler");
            fd.append("where", "id = " + id);
            fd.append("csrf_token","<?= $_SESSION['csrf_token'] ?>");

            try {
                const res = await fetch("/admin/ajax/read.php", { method: "POST", body: fd });
                const resultText = await res.text();
                console.log(resultText);
                const result = JSON.parse(resultText);
                //const result = await res.json();
                if (result.success) {
                    const row = result.data[0];
                    Object.keys(row).forEach(key => { if(form[key]) form[key].value = row[key]; });
                    editorInstanceTr.setData(row.proje_aciklama_tr || '');
                    editorInstanceEn.setData(row.proje_aciklama_en || '');
                    
                    // Medyaları çek
                    fetchAndRenderMedia(id);

                    dataUl.querySelectorAll("li").forEach(i => i.classList.remove("bg-white", "shadow-inner", "pointer-events-none"));
                    li.classList.add("bg-white", "shadow-inner", "pointer-events-none");
                    document.getElementById("saveOrUpdate").textContent = "Güncelle";
                    document.getElementById("new").classList.remove("hidden");
                    document.getElementById("delete").classList.remove("hidden");
                }
            } catch (err) { console.error(err); }
            finally {
                hideOverlay();
            }
        };

        dataUl.querySelectorAll("li").forEach(li => li.addEventListener("click", () => handleLiClick(li)));

        // KAYDET / GÜNCELLE
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            showOverlay();
            const id = form.id.value;
            const formData = new FormData(form); // Otomatik olarak file dahil her şeyi alır
            
            formData.append("table", "projeler");
            const data = {};
            Array.from(form.elements).forEach(el => {
                if (el.name && el.type !== 'file') data[el.name] = el.value;
            });
            data['proje_aciklama_tr'] = editorInstanceTr.getData();
            data['proje_aciklama_en'] = editorInstanceEn.getData();
            
            formData.append("data", JSON.stringify(data));
            formData.append("kategori","proje");

            let url = id === "0" ? "/admin/ajax/create.php" : "/admin/ajax/update.php";
            formData.append("csrf_token","<?= $_SESSION['csrf_token'] ?>");
            if (id !== "0") formData.append("where", "id = " + id);

            try {
                const res = await fetch(url, { method: "POST", body: formData });
                const result = await res.json();
                new Notification({ text: result.message, type: result.success ? "success" : "error" });
                if (result.success)
                {
                    if(id === "0") {
                        const li = document.createElement("li");
                        li.className = "flex items-center bg-gray-100 hover:bg-indigo-50/70 text-gray-800 px-3 py-2 rounded-lg cursor-pointer transition duration-150";
                        li.dataset.id = result.id;
                        li.innerHTML = `<span class="font-medium">${data.proje_baslik_tr}</span>`;
                        li.addEventListener("click", () => handleLiClick(li));
                        dataUl.appendChild(li);
                        document.getElementById("new").click();
                    } else {
                        const existingLi = dataUl.querySelector(`li[data-id="${id}"] span`);
                        if (existingLi) existingLi.textContent = data.proje_baslik_tr;
                        existingLi.classList.remove("pointer-events-none");
                        existingLi.click();
                    }
                }
            } catch (err) { console.error(err); }
            finally
            {
                hideOverlay();
            }
        });

        // YENİ EKLE
        document.getElementById("new").addEventListener("click", () => {
            showOverlay();
            form.reset();
            form.id.value = "0";
            editorInstanceTr.setData('');
            editorInstanceEn.setData('');
            galleryContainer.classList.add("hidden");
            document.getElementById("saveOrUpdate").textContent = "Kaydet";
            document.getElementById("new").classList.add("hidden");
            document.getElementById("delete").classList.add("hidden");
            hideOverlay();
        });

        // PROJE SİL (Cascade delete backend tarafında olmalı)
        document.getElementById("delete").addEventListener("click", async () => {
            const id = form.id.value;
            if (confirm("Bu projeyi ve tüm medya dosyalarını silmek istediğinize emin misiniz?")) {
                showOverlay();
                const fd = new FormData();
                fd.append("table", "projeler");
                fd.append("where", "id = " + id);
                fd.append("csrf_token","<?= $_SESSION['csrf_token'] ?>");
                const res = await fetch("/admin/ajax/delete.php", { method: "POST", body: fd });
                const result = await res.json();
                if(result.success){
                    dataUl.querySelector(`li[data-id="${id}"]`).remove();
                    document.getElementById("new").click();
                    const fd2 = new FormData();
                    fd2.append("table", "proje_gorseller");
                    fd2.append("where", "proje_id = " + id);
                    fd2.append("csrf_token","<?= $_SESSION['csrf_token'] ?>");
                    const res2 = await fetch("/admin/ajax/delete.php", { method: "POST", body: fd2 });
                    const resText = await res.text();
                    console.log(resText);
                    const result2 = await JSON.parse(resText);
                    new Notification({ text: result2.message, type: result2.success ? "success" : "error" });
                }
                hideOverlay();
            }
        });
    });

    // TEKİL MEDYA SİLME (Global fonksiyon)
    async function deleteSingleMedia(mediaId, btnElement) {
        if (confirm("Bu dosyayı silmek istediğinize emin misiniz?")) {
            showOverlay();
            const fd = new FormData();
            fd.append("table", "proje_gorseller");
            fd.append("where", "id = " + mediaId);
            fd.append("csrf_token","<?= $_SESSION['csrf_token'] ?>");

            try {
                const res = await fetch("/admin/ajax/delete.php", { method: "POST", body: fd });
                const result = await res.json();
                if (result.success) {
                    btnElement.parentElement.remove();
                    new Notification({ text: "Medya silindi", type: "success" });
                }
            } catch (err) { console.error(err); }
            finally {
                hideOverlay();
            }
        }
    }
</script>