<?php
    $hakkinda_icerik = $database->getGlobalVars('hakkinda_icerik')['hakkinda_icerik'];
    $hakkinda_icerik_en = $database->getGlobalVars('hakkinda_icerik_en')['hakkinda_icerik_en'];
?>
<main class="bg-gray-50 p-6 min-h-screen">
    <div class="max-w-screen-xl mx-auto">
        <?php pageTitle("Hakkında Sayfası", "Bu sayfada sitenizin Hakkında sayfasının içeriğini düzenleyebilirsiniz."); ?>
        <form id="hakkindaForm" class="space-y-6" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
             <!--form inputs go here-->
            <div class="mt-4">
              <label for="hakkinda_icerik" class="block font-semibold text-sm text-gray-700 mb-2">Sayfa İçeriği (TR)</label>
              <textarea id="hakkinda_icerik" name="hakkinda_icerik" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm"></textarea>
            </div>
            <div class="mt-4">
              <label for="hakkinda_icerik_en" class="block font-semibold text-sm text-gray-700 mb-2">Sayfa İçeriği (EN)</label>
              <textarea id="hakkinda_icerik_en" name="hakkinda_icerik_en" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm"></textarea>
            </div>
            <div class="pt-2 text-right">
                <button type="submit" id="saveBtn" class="bg-indigo-600 text-white font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700 active:bg-indigo-800 active:scale-95 transition duration-150 shadow-md shadow-indigo-500/30">
                    Kaydet
                </button>
            </div>
        </form>
    </div>
</main>
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/classic/ckeditor.js"></script>
<script>
    let editorInstance, editorInstance_en;

    const editorConfig = {
        ckfinder: { uploadUrl: '/admin/ajax/upload.php' },
        toolbar: ['heading', '|', 'imageUpload', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList'],
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraf', class: 'ck-heading_paragraph' },
                { model: 'heading1', view: 'h1', title: 'Başlık 1', class: 'ck-heading_heading1' },
                { model: 'heading2', view: 'h2', title: 'Başlık 2', class: 'ck-heading_heading2' },
                { model: 'heading3', view: 'h3', title: 'Başlık 3', class: 'ck-heading_heading3' }
            ]
        }
    };

    ClassicEditor.create(document.querySelector('#hakkinda_icerik'), editorConfig)
        .then(editor => { 
            editorInstance = editor; 
            editor.ui.view.editable.element.style.minHeight = '300px';

            editor.plugins.get('FileRepository').createUploadAdapter = loader => {
                return {
                    upload: () => {
                        return loader.file.then(file => {
                            const data = new FormData();
                            data.append('file', file);
                            data.append('csrf_token', "<?= $_SESSION['csrf_token'] ?>"); // token ekleniyor

                            return fetch('/admin/ajax/upload.php', {
                                method: 'POST',
                                body: data
                            })
                            .then(res => res.json());
                        });
                    }
                };
            };
        }).catch(console.error);
    ClassicEditor.create(document.querySelector('#hakkinda_icerik_en'), editorConfig)
        .then(editor => { 
            editorInstance_en = editor; 
            editor.ui.view.editable.element.style.minHeight = '300px';

            editor.plugins.get('FileRepository').createUploadAdapter = loader => {
                return {
                    upload: () => {
                        return loader.file.then(file => {
                            const data = new FormData();
                            data.append('file', file);
                            data.append('csrf_token', "<?= $_SESSION['csrf_token'] ?>"); // token ekleniyor

                            return fetch('/admin/ajax/upload.php', {
                                method: 'POST',
                                body: data
                            })
                            .then(res => res.json());
                        });
                    }
                };
            };
        }).catch(console.error);
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        editorInstance.setData("<?= $hakkinda_icerik ?>");
        editorInstance_en.setData("<?= $hakkinda_icerik_en ?>");
        const form = document.getElementById('hakkindaForm');
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            showOverlay();
            const content = editorInstance.getData().trim();
            const content_en = editorInstance_en.getData().trim();
            if (!content) {
                new Notification({
                    text: "Lütfen içerik giriniz.",
                    type: "error",
                    position: "top-right"
                });
                return;
            }
            const formData = new FormData(this);
            formData.set('hakkinda_icerik', content);
            formData.set('hakkinda_icerik_en', content_en);
            formData.append("csrf_token","<?= $_SESSION['csrf_token'] ?>");
            
            try {
                const res = await fetch('/admin/ajax/updategv.php', {
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