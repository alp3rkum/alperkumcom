import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { useTranslation } from "react-i18next";

function Blogs() {
  const [blogCategories, setBlogCategories] = useState([]);
  const [blogPosts, setBlogPosts] = useState([]);
  const [selectedCategory, setSelectedCategory] = useState(null);
  const { t, i18n } = useTranslation();

  useEffect(() => {
    async function preloadBlogs() {
      try {
        const res = await fetch("/ajax/bloglar.php");
        const resultText = await res.text();
        const result = JSON.parse(resultText);

        setBlogCategories(result.categories || []);
        setBlogPosts(result.blogs || []);
      } catch (err) {
        console.error("Error:", err);
      }
    }
    preloadBlogs();
  }, []);

  const navigate = useNavigate();

  // Yardımcı fonksiyon: category_id → kategori adı
  const getCategoryName = (categoryId) => {
    const cat = blogCategories.find((c) => c.id === categoryId);
    if (!cat) return t("no_category");
    return i18n.language === "tr" ? cat.kategori_adi_tr : cat.kategori_adi_en;
  };

  // Yardımcı fonksiyon: category_id → kategori açıklaması
  const getCategoryDescription = (categoryId) => {
    if (categoryId === null) return t("default_category_description");
    const cat = blogCategories.find((c) => c.id === categoryId);
    if (!cat) return "";
    return i18n.language === "tr"
      ? cat.kategori_aciklama_tr
      : cat.kategori_aciklama_en;
  };

  const handleCategoryClick = (categoryId) => {
    setSelectedCategory(categoryId);
  };

  const filteredPosts = blogPosts.filter((blog) => {
    return selectedCategory === null || blog.kategori_id === selectedCategory;
  });

  return (
    <div className="space-y-6 p-4">
      {/* Başlık */}
      <div className="flex items-center gap-6">
        <h1 className="text-3xl font-bold text-white tracking-wide whitespace-nowrap">
          {t("blog_title")}
        </h1>
        <div className="h-px w-full bg-white/10"></div>
      </div>

      {/* Kategoriler + RSS */}
      <div className="flex gap-4 flex-wrap items-center">
        <div className="flex gap-4 flex-wrap items-center overflow-x-auto max-w-full">
          {/* Kategori butonları */}
        <button
          className={`btn btn-sm transition-colors duration-300 ${
            selectedCategory === null
              ? "bg-cyan-500 hover:bg-cyan-400 text-white border border-cyan-300"
              : "bg-blue-600 hover:bg-blue-400 text-white border border-blue-400"
          }`}
          onClick={() => setSelectedCategory(null)}
        >
          {t("all_blogs")}
        </button>

        {blogCategories.map((cat) => (
          <button
            key={cat.id}
            className={`btn btn-sm transition-colors duration-300 ${
              selectedCategory === cat.id
                ? "bg-cyan-500 hover:bg-cyan-400 text-white border border-cyan-300"
                : "bg-blue-600 hover:bg-blue-400 text-white border border-blue-400"
            }`}
            onClick={() => handleCategoryClick(cat.id)}
          >
            {i18n.language === "tr" ? cat.kategori_adi_tr : cat.kategori_adi_en}
          </button>
        ))}
        </div>

        {/* Dikey çizgi */}
        <div className="w-px h-6 bg-white/30 ml-auto"></div>

        {/* RSS Feed butonu */}
        <a
          href={i18n.language === "tr" ? "/ajax/rss_tr.php" : "/ajax/rss_en.php"}
          target="_blank"
          rel="noopener noreferrer"
          className="btn btn-sm bg-blue-600 hover:bg-blue-400 text-white border border-blue-400 transition-colors duration-300"
        >
          {t("rss_feed")}
        </a>
      </div>

      {/* Kategori Açıklaması */}
      <p className="text-gray-300 italic mt-2">
        {getCategoryDescription(selectedCategory)}
      </p>

      {/* Blog Kartları */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {filteredPosts.length > 0 ? (
          filteredPosts.map((blog) => {
            const title =
              i18n.language === "tr"
                ? blog.blog_baslik_tr
                : blog.blog_baslik_en;
            // const metaUrl =
            //   i18n.language === "tr" ? blog.meta_url_tr : blog.meta_url_en;

            return (
              <div
                key={blog.id}
                className="card bg-black/60 border border-blue-400 shadow-md cursor-pointer 
                          hover:border-cyan-300 hover:scale-[1.02] transition-transform"
                onClick={() => {
                  const cat = blogCategories.find(c => c.id === blog.kategori_id);
                  const categoryUrl = cat
                    ? (i18n.language === "tr" ? cat.cat_url_tr : cat.cat_url_en)
                    : "uncategorized";
                  const metaUrl = i18n.language === "tr" ? blog.meta_url_tr : blog.meta_url_en;
                  navigate(`/blog/${categoryUrl}/${metaUrl}`);
                }}
              >
                <figure className="bg-black">
                  <img
                    src={blog.kapak_fotografi}
                    alt={title}
                    className="object-cover w-full h-48 border-b border-blue-400"
                  />
                </figure>
                <div className="card-body text-green-300">
                  <h2 className="card-title text-blue-300 tracking-wider">
                    {title}
                  </h2>
                  <p className="text-sm text-gray-400">
                    {getCategoryName(blog.kategori_id)}
                  </p>
                </div>
              </div>
            );
          })
        ) : (
          <p className="text-white col-span-full text-center py-10">
            {selectedCategory === null
              ? t("no_blogs")
              : `${getCategoryName(selectedCategory)} ${
                  i18n.language === "tr"
                    ? "kategorisinde yayınlanmış yazı bulunamadı."
                    : "category has no published posts."
                }`}
          </p>
        )}
      </div>
    </div>
  );
}

export default Blogs;