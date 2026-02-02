<?php
$bloglar = $database->selectMulti("* FROM bloglar");
$kategoriler = $database->selectMulti("id, kategori_adi_tr FROM blog_kategoriler");
?>
<main class="bg-gray-50 p-6 min-h-screen">
    <div class="max-w-screen-xl mx-auto">
        <?php pageTitle("Bloglar", "Bu sayfada, sitenizde yayınladığınız blog gönderilerini ekleyip düzenleyebilirsiniz."); ?>

        <div class="grid grid-cols-1 lg:grid-cols-[300px_auto] gap-6">
            <aside class="bg-white rounded-xl shadow-lg border border-gray-100 p-4 h-[400px] overflow-y-auto">
                <h3 class="text-lg font-bold text-gray-800 border-b pb-3 mb-3">Blog Listesi</h3>
                <ul id="dataUl" class="space-y-2 overflow-auto">
                    <?php foreach ($bloglar as $blog): ?>
                        <li class="flex items-center bg-gray-100 hover:bg-indigo-50/70 text-gray-800 px-3 py-2 rounded-lg cursor-pointer transition duration-150" data-id="<?= $blog['id'] ?>">
                            <span class="font-medium"><?= htmlspecialchars($blog['blog_baslik_tr']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </aside>
            <section class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <form id="dataForm" class="space-y-6" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id" value="0">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label for="blog_baslik_tr" class="block font-semibold text-sm text-gray-700 mb-2">Blog Başlığı (Türkçe)</label>
                            <input type="text" id="blog_baslik_tr" name="blog_baslik_tr" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm" minlength="3" maxlength="150" required>
                        </div>
                        <div>
                            <label for="blog_baslik_en" class="block font-semibold text-sm text-gray-700 mb-2">Blog Başlığı (İngilizce)</label>
                            <input type="text" id="blog_baslik_en" name="blog_baslik_en" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm" minlength="3" maxlength="150" required>
                        </div>
                        <div class="mt-4">
                            <label for="blog_icerik_tr" class="block font-semibold text-sm text-gray-700 mb-2">Blog İçeriği (Türkçe)</label>
                            <textarea id="blog_icerik_tr" name="blog_icerik_tr" rows="3"></textarea>
                        </div>
                        <div class="mt-4">
                            <label for="blog_icerik_en" class="block font-semibold text-sm text-gray-700 mb-2">Blog İçeriği (İngilizce)</label>
                            <textarea id="blog_icerik_en" name="blog_icerik_en" rows="3"></textarea>
                        </div>
                    </div>

                    <div>
                        <label for="kategori_id" class="block font-semibold text-sm text-gray-700 mb-2">Kategori</label>
                        <select id="kategori_id" name="kategori_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm" required>
                            <?php foreach ($kategoriler as $kategori): ?>
                                <option value="<?= $kategori['id'] ?>"><?= $kategori['kategori_adi_tr'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label for="meta_title_tr" class="block font-semibold text-sm text-gray-700 mb-2">SEO Başlık (TR)</label>
                            <input type="text" id="meta_title_tr" name="meta_title_tr" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                            <label for="meta_desc_tr" class="block font-semibold text-sm text-gray-700 mt-3 mb-2">SEO Açıklama (TR)</label>
                            <input type="text" id="meta_desc_tr" name="meta_desc_tr" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                            <label for="meta_keyword_tr" class="block font-semibold text-sm text-gray-700 mt-3 mb-2">SEO Keyword (TR)</label>
                            <input type="text" id="meta_keyword_tr" name="meta_keyword_tr" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                            <label for="meta_url_tr" class="block font-semibold text-sm text-gray-700 mt-3 mb-2">URL (TR)</label>
                            <input type="text" id="meta_url_tr" name="meta_url_tr" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5" readonly>
                        </div>
                        <div>
                            <label for="meta_title_en" class="block font-semibold text-sm text-gray-700 mb-2">SEO Başlık (EN)</label>
                            <input type="text" id="meta_title_en" name="meta_title_en" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                            <label for="meta_desc_en" class="block font-semibold text-sm text-gray-700 mt-3 mb-2">SEO Açıklama (EN)</label>
                            <input type="text" id="meta_desc_en" name="meta_desc_en" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                            <label for="meta_keyword_en" class="block font-semibold text-sm text-gray-700 mt-3 mb-2">SEO Keyword (EN)</label>
                            <input type="text" id="meta_keyword_en" name="meta_keyword_en" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                            <label for="meta_url_en" class="block font-semibold text-sm text-gray-700 mt-3 mb-2">URL (EN)</label>
                            <input type="text" id="meta_url_en" name="meta_url_en" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5" readonly>
                        </div>
                    </div>

                    <div class="flex justify-between gap-2 pt-4">
                        <button type="button" id="new" class="flex-1 hidden bg-gray-200 text-indigo-600 font-semibold px-4 py-2 rounded-lg hover:bg-gray-300 transition">Yeni Ekle</button>
                        <button type="submit" id="saveOrUpdate" class="flex-1 bg-indigo-600 text-white font-semibold px-4 py-2 rounded-lg hover:bg-indigo-700 transition shadow-md shadow-indigo-500/30">Kaydet</button>
                        <button type="button" id="delete" class="flex-1 hidden bg-red-600 text-white font-semibold px-4 py-2 rounded-lg hover:bg-red-700 transition shadow-md shadow-red-500/30">Sil</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</main>

<script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/classic/ckeditor.js"></script>
<script>
    // Global editor instances
    let editorInstanceTr, editorInstanceEn;

    const editorConfig = {
        ckfinder: { uploadUrl: '/upload.php' },
        toolbar: ['heading', '|', 'imageUpload', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList'],
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraf', class: 'ck-heading_paragraph' },
                { model: 'heading1', view: 'h1', title: 'Başlık 1', class: 'ck-heading_heading1' },
                { model: 'heading2', view: 'h2', title: 'Başlık 2', class: 'ck-heading_heading2' }
            ]
        }
    };

    // TR Editor Init
    ClassicEditor.create(document.querySelector('#blog_icerik_tr'), editorConfig)
        .then(editor => { 
            editorInstanceTr = editor; 
            editor.ui.view.editable.element.style.minHeight = '300px';
        }).catch(console.error);

    // EN Editor Init
    ClassicEditor.create(document.querySelector('#blog_icerik_en'), editorConfig)
        .then(editor => { 
            editorInstanceEn = editor; 
            editor.ui.view.editable.element.style.minHeight = '300px';
        }).catch(console.error);

    function slugify(text) {
        return text.toString().normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/ı/g, "i").replace(/İ/g, "i").replace(/ş/g, "s").replace(/Ş/g, "s").replace(/ğ/g, "g").replace(/Ğ/g, "g").replace(/ç/g, "c").replace(/Ç/g, "c").replace(/ö/g, "o").replace(/Ö/g, "o").replace(/ü/g, "u").replace(/Ü/g, "u").toLowerCase().trim().replace(/[^a-z0-9]+/g, "-").replace(/^-+|-+$/g, "");
    }

    document.getElementById("blog_baslik_tr").addEventListener("input", e => document.getElementById("meta_url_tr").value = slugify(e.target.value));
    document.getElementById("blog_baslik_en").addEventListener("input", e => document.getElementById("meta_url_en").value = slugify(e.target.value));

    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById("dataForm");
        const saveOrUpdateBtn = document.getElementById("saveOrUpdate");
        const newBtn = document.getElementById("new");
        const deleteBtn = document.getElementById("delete");
        const dataUl = document.getElementById("dataUl");

        // LIST CLICK (READ)
        const setupLiClick = (li) => {
            li.addEventListener("click", async () => {
                const id = li.dataset.id;
                const formData = new FormData();
                formData.append("table", "bloglar");
                formData.append("where", "id = " + id);

                try {
                    const res = await fetch("/admin/ajax/read.php", { method: "POST", body: formData });
                    const result = await res.json();

                    if (result.success) {
                        const row = result.data[0];
                        
                        // Fill standard inputs
                        Object.keys(row).forEach(key => {
                            if(form[key]) form[key].value = row[key];
                        });

                        // FILL CKEDITORS (Kritik nokta burası)
                        if(editorInstanceTr) editorInstanceTr.setData(row.blog_icerik_tr || '');
                        if(editorInstanceEn) editorInstanceEn.setData(row.blog_icerik_en || '');

                        dataUl.querySelectorAll("li").forEach(item => item.classList.remove("bg-white", "shadow-inner", "pointer-events-none"));
                        li.classList.add("bg-white", "shadow-inner", "pointer-events-none");

                        saveOrUpdateBtn.textContent = "Güncelle";
                        newBtn.classList.remove("hidden");
                        deleteBtn.classList.remove("hidden");
                    }
                } catch (err) { console.error("Hata:", err); }
            });
        };

        dataUl.querySelectorAll("li").forEach(setupLiClick);

        // NEW BUTTON
        newBtn.addEventListener("click", () => {
            form.reset();
            form.id.value = "0";
            if(editorInstanceTr) editorInstanceTr.setData('');
            if(editorInstanceEn) editorInstanceEn.setData('');
            saveOrUpdateBtn.textContent = "Kaydet";
            newBtn.classList.add("hidden");
            deleteBtn.classList.add("hidden");
            dataUl.querySelectorAll("li").forEach(item => item.classList.remove("bg-white", "shadow-inner", "pointer-events-none"));
        });

        // SAVE / UPDATE
        saveOrUpdateBtn.addEventListener("click", async (e) => {
            e.preventDefault();
            const id = form.id.value;
            const formData = new FormData();
            formData.append("table", "bloglar");

            const data = {};
            Array.from(form.elements).forEach(el => {
                if (el.name && el.type !== "file") data[el.name] = el.value;
            });

            // GET CKEDITOR DATA (Kritik nokta burası)
            if(editorInstanceTr) data['blog_icerik_tr'] = editorInstanceTr.getData();
            if(editorInstanceEn) data['blog_icerik_en'] = editorInstanceEn.getData();

            formData.append("data", JSON.stringify(data));

            let url = id === "0" ? "/admin/ajax/create.php" : "/admin/ajax/update.php";
            if (id !== "0") formData.append("where", "id = " + id);

            try {
                const res = await fetch(url, { method: "POST", body: formData });
                const result = await res.json();

                new Notification({ text: result.message, type: result.success ? "success" : "error" });

                if (result.success) {
                    if(id === "0") {
                        // Yeni eklenen öğeyi listeye ekle
                        const li = document.createElement("li");
                        li.className = "flex items-center bg-gray-100 hover:bg-indigo-50/70 text-gray-800 px-3 py-2 rounded-lg cursor-pointer transition duration-150";
                        li.dataset.id = result.id;
                        li.innerHTML = `<span class="font-medium">${data.blog_baslik_tr}</span>`;
                        dataUl.appendChild(li);
                        setupLiClick(li);
                    } else {
                        // Mevcut olanın başlığını güncelle
                        const existingLi = dataUl.querySelector(`li[data-id="${id}"] span`);
                        if (existingLi) existingLi.textContent = data.blog_baslik_tr;
                    }
                }
            } catch (err) { console.error(err); }
        });

        // DELETE BUTTON
        deleteBtn.addEventListener("click", async () => {
            const id = form.id.value;
            if (id === "0") return;

            if (confirm("Bu blog yazısını silmek istediğinize emin misiniz?")) {
                const formData = new FormData();
                formData.append("table", "bloglar");
                formData.append("where", "id = " + id);

                try {
                    const res = await fetch("/admin/ajax/delete.php", { method: "POST", body: formData });
                    const result = await res.json();

                    if (result.success) {
                        dataUl.querySelector(`li[data-id="${id}"]`).remove();
                        newBtn.click();
                        new Notification({ text: result.message, type: "success" });
                    }
                } catch (err) { console.error(err); }
            }
        });
    });
</script>