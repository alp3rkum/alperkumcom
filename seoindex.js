// seoindex.js

// Basit bir helper: meta etiketini güncelle
function setMeta(name, content) {
  let tag = document.querySelector(`meta[name="${name}"]`);
  if (!tag) {
    tag = document.createElement("meta");
    tag.setAttribute("name", name);
    document.head.appendChild(tag);
  }
  tag.setAttribute("content", content);
}

// Global SEO fetch
export function setGlobalSEO() {
  fetch("/ajax/mainseo.php")
    .then(res => res.json())
    .then(seo => {
      if (seo.site_baslik) document.title = seo.site_baslik;
      if (seo.site_aciklama) setMeta("description", seo.site_aciklama);
      if (seo.site_keywords) setMeta("keywords", seo.site_keywords);
    })
    .catch(err => console.error("SEO fetch error:", err));
}

// Sayfa bazlı SEO fetch (örnek: blog detay)
export function setPageSEO(endpoint) {
  fetch(endpoint)
    .then(res => res.json())
    .then(seo => {
      // Dil bilgisini <html lang="..."> üzerinden alıyoruz
      const lang = document.documentElement.lang || "tr";

      if (lang === "tr") {
        if (seo.meta_title_tr) document.title = seo.meta_title_tr;
        if (seo.meta_desc_tr) setMeta("description", seo.meta_desc_tr);
        if (seo.meta_keyword_tr) setMeta("keywords", seo.meta_keyword_tr);
      } else {
        if (seo.meta_title_en) document.title = seo.meta_title_en;
        if (seo.meta_desc_en) setMeta("description", seo.meta_desc_en);
        if (seo.meta_keyword_en) setMeta("keywords", seo.meta_keyword_en);
      }
    })
    .catch(err => console.error("Page SEO fetch error:", err));
}