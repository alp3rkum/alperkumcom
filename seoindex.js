// Helper: Meta etiketini name veya property bazlı güncelle/oluştur
function updateOrCreateMeta(attrName, attrValue, content) {
  if (!content) return; // İçerik boşsa işlem yapma
  let tag = document.querySelector(`meta[${attrName}="${attrValue}"]`);
  if (!tag) {
    tag = document.createElement("meta");
    tag.setAttribute(attrName, attrValue);
    document.head.appendChild(tag);
  }
  tag.setAttribute("content", content);
}

// Global (Genel Site) SEO fetch
export function setGlobalSEO() {
  fetch("/ajax/mainseo.php")
    .then(res => res.json())
    .then(seo => {
      const siteTitle = seo.site_baslik || "alperkum.com";
      const siteDesc = seo.site_aciklama || "";
      const siteImg = window.location.origin + "/default-cover.jpg"; // Ana site görseli

      // Standart
      if (seo.site_baslik) document.title = seo.site_baslik;
      updateOrCreateMeta("name", "description", siteDesc);
      updateOrCreateMeta("name", "keywords", seo.site_keywords);

      // Open Graph
      updateOrCreateMeta("property", "og:type", "website");
      updateOrCreateMeta("property", "og:site_name", "alperkum.com");
      updateOrCreateMeta("property", "og:title", siteTitle);
      updateOrCreateMeta("property", "og:description", siteDesc);
      updateOrCreateMeta("property", "og:image", siteImg);
      updateOrCreateMeta("property", "og:url", window.location.origin);

      // Twitter
      updateOrCreateMeta("name", "twitter:card", "summary_large_image");
      updateOrCreateMeta("name", "twitter:site", "@alperkum");
      updateOrCreateMeta("name", "twitter:title", siteTitle);
      updateOrCreateMeta("name", "twitter:description", siteDesc);
      updateOrCreateMeta("name", "twitter:image", siteImg);
    })
    .catch(err => console.error("Global SEO fetch error:", err));
}

// Sayfa bazlı SEO fetch (Blog detay vb.)
export function setPageSEO(endpoint) {
  fetch(endpoint)
    .then(res => res.json())
    .then(seo => {
      const lang = document.documentElement.lang || "tr";
      const isTr = lang === "tr";

      const title = isTr ? seo.meta_title_tr : seo.meta_title_en;
      const desc = isTr ? seo.meta_desc_tr : seo.meta_desc_en;
      const keywords = isTr ? seo.meta_keyword_tr : seo.meta_keyword_en;
      // Kapak fotoğrafı yoksa default bir resim kullan
      const image = seo.kapak_fotografi 
                    ? window.location.origin + "/" + seo.kapak_fotografi 
                    : window.location.origin + "/default-blog-cover.jpg";

      if (title) document.title = title;
      
      // Standart Meta
      updateOrCreateMeta("name", "description", desc);
      updateOrCreateMeta("name", "keywords", keywords);

      // Open Graph
      updateOrCreateMeta("property", "og:type", "article");
      updateOrCreateMeta("property", "og:title", title);
      updateOrCreateMeta("property", "og:description", desc);
      updateOrCreateMeta("property", "og:image", image);
      updateOrCreateMeta("property", "og:url", window.location.href);

      // Twitter
      updateOrCreateMeta("name", "twitter:title", title);
      updateOrCreateMeta("name", "twitter:description", desc);
      updateOrCreateMeta("name", "twitter:image", image);
    })
    .catch(err => console.error("Page SEO fetch error:", err));
}