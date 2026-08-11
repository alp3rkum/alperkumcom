import React, { useEffect, useState } from "react";
import { useParams, NavLink } from "react-router-dom";
import { useTranslation } from "react-i18next";

function BlogPage() {
  const { url } = useParams();
  const [data, setData] = useState(null);
  const { t, i18n } = useTranslation();

  useEffect(() => {
    async function fetchBlog() {
      const res = await fetch(`/ajax/blog.php?url=${url}`);
      const resultText = await res.text();
      const result = JSON.parse(resultText);
      setData(result);
    }
    fetchBlog();
  }, [url]);

  function normalizeHeadings(html) {
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, "text/html");

    doc.querySelectorAll("h3").forEach(
      (el) => (el.outerHTML = `<h4>${el.innerHTML}</h4>`)
    );
    doc.querySelectorAll("h2").forEach(
      (el) => (el.outerHTML = `<h3>${el.innerHTML}</h3>`)
    );
    doc.querySelectorAll("h1").forEach(
      (el) => (el.outerHTML = `<h2>${el.innerHTML}</h2>`)
    );

    return doc.body.innerHTML;
  }

  if (!data) return <p className="text-white">{t("loading")}</p>;

  const title =
    i18n.language === "tr" ? data.blog_baslik_tr : data.blog_baslik_en;
  const content =
    i18n.language === "tr" ? data.blog_icerik_tr : data.blog_icerik_en;

  return (
    <div className="space-y-6 p-4">
      <div className="flex space-x-4">
        <NavLink
        to="/bloglar"
        className="btn btn-primary bg-blue-600 hover:bg-blue-400 text-white font-mono tracking-widest border border-blue-400 transition-colors duration-300"
      >
        {t("back_to_blogs")}
      </NavLink>
      <button
  onClick={() => {
    if (navigator.share) {
      navigator.share({
        title: document.title, // Helmet veya SEOHandler ile güncel title
        text: document.querySelector('meta[name="description"]').getAttribute("content"),         // meta description (senin state’ten geliyor)
        url: window.location.href // o anki tam URL
      });
    } else {
      alert("Paylaşım özelliği bu tarayıcıda desteklenmiyor.");
    }
  }}
  className="btn btn-secondary bg-green-600 hover:bg-green-400 text-white font-mono tracking-widest border border-green-400 transition-colors duration-300"
>
  {t("share")}
</button>
      </div>
      

      <figure>
        <img
          src={"/" + data.kapak_fotografi}
          alt={title}
          className="w-full h-[180px] sm:h-[240px] md:h-[300px] lg:h-[400px] xl:h-[500px] object-cover border border-blue-400"
        />
      </figure>

      <h1 className="text-2xl font-bold text-blue-300">{title}</h1>

      <div
        id="content"
        className="text-gray-300"
        dangerouslySetInnerHTML={{ __html: normalizeHeadings(content) }}
      ></div>
    </div>
  );
}

export default BlogPage;