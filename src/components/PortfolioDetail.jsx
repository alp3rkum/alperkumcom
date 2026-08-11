import React, { useEffect, useState } from "react";
import { useParams, NavLink } from "react-router-dom";
import { useTranslation } from "react-i18next";
import { Helmet } from "react-helmet";

function PortfolioDetail() {
  const { url } = useParams();
  const [data, setData] = useState(null);
  const { t, i18n } = useTranslation();

  useEffect(() => {
    if (!data || !data.gorseller) return;

    const galleryImages = data.gorseller.map((g) => "/" + g.gorsel_yolu);

    window.currentIndex = 0;
    window.images = galleryImages;

    window.openImageModal = function (idx) {
      window.currentIndex = idx;
      document.getElementById("modalImage").src =
        window.images[window.currentIndex];
      document.getElementById("imageModal").showModal();
    };
    window.prevImage = function () {
      window.currentIndex =
        (window.currentIndex - 1 + window.images.length) % window.images.length;
      document.getElementById("modalImage").src =
        window.images[window.currentIndex];
    };
    window.nextImage = function () {
      window.currentIndex = (window.currentIndex + 1) % window.images.length;
      document.getElementById("modalImage").src =
        window.images[window.currentIndex];
    };
  }, [data]);

  useEffect(() => {
    async function fetchProject() {
      const res = await fetch(`/ajax/project.php?url=${url}`);
      const resultText = await res.text();
      const result = JSON.parse(resultText);
      setData(result);
    }
    fetchProject();
  }, [url]);

  if (!data) return <p className="text-white">{t("loading")}</p>;

  const { project, gorseller } = data;

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

    doc.querySelectorAll("img").forEach((el) => el.remove());

    return doc.body.innerHTML;
  }

  const title =
    i18n.language === "tr" ? project.proje_baslik_tr : project.proje_baslik_en;
  const description =
    i18n.language === "tr"
      ? project.proje_aciklama_tr
      : project.proje_aciklama_en;
  const seoTitle =
    i18n.language === "tr" ? project.meta_title_tr : project.meta_title_en;
  const seoDesc =
    i18n.language === "tr" ? project.meta_desc_tr : project.meta_desc_en;

  return (
    <>
        <div className="space-y-6 p-4">
      <div className="flex space-x-4">
  <NavLink
    to="/portfoy"
    className="btn btn-primary bg-blue-600 hover:bg-blue-400 text-white font-mono tracking-widest border border-blue-400 transition-colors duration-300"
  >
    {t("back_to_portfolio")}
  </NavLink>

  {/* Paylaşım butonları */}
  <button
    onClick={() => {
      if (navigator.share) {
        navigator.share({
          title: seoTitle,
          text: seoDesc,
          url: window.location.href
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

      {/* Kapak Fotoğrafı */}
      <figure>
        <img
          src={"/" + project.kapak_foto}
          alt={title}
          className="w-full h-[400px] sm:h-[400px] md:h-[480px] lg:h-[560px] xl:h-[640px] object-cover border border-blue-400"
        />
      </figure>

      <h1 className="text-2xl font-bold text-blue-300">{title}</h1>

      {/* Açıklama */}
      <div
        id="content"
        className="text-gray-300"
        dangerouslySetInnerHTML={{ __html: normalizeHeadings(description) }}
      ></div>

      {/* Galeri */}
      {gorseller.length > 0 && (
        <>
          <h2 className="text-xl font-semibold text-blue-300 mt-8">
            {t("project_photos")}
          </h2>
          <div className="flex overflow-x-auto snap-x snap-mandatory space-x-4 p-4 mt-4 bg-black/40 border border-blue-400 rounded-box scrollbar-hide scrollbar-custom">
            {gorseller.map((g, index) => (
              <div key={index} className="flex-shrink-0 snap-center">
                <img
                  src={"/" + g.gorsel_yolu}
                  alt={`${t("project_photos")} ${index + 1}`}
                  className="h-64 w-96 object-cover rounded-lg border border-blue-400 cursor-pointer"
                  onClick={() => window.openImageModal(index)}
                />
              </div>
            ))}
          </div>
        </>
      )}

      {/* Modal */}
      <dialog id="imageModal" className="modal">
        <div className="modal-box bg-black/90 border border-blue-400 w-[90vw] max-w-7xl mx-auto p-0">
          <img
            id="modalImage"
            alt={t("modal_image_alt")}
            className="w-full h-auto object-cover block"
          />
        </div>
        <form method="dialog" className="modal-backdrop">
          <button>close</button>
        </form>
      </dialog>
    </div>
    </>

  );
}

export default PortfolioDetail;