import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { useTranslation } from "react-i18next";
import { Helmet } from "react-helmet";

function Portfolio() {
  const [portfolioContent, setPortfolioContent] = useState(null);
  const navigate = useNavigate();
  const { i18n, t } = useTranslation();

  useEffect(() => {
    async function preloadPortfolio() {
      try {
        const res = await fetch("/ajax/portfolio.php");
        const resultText = await res.text();
        const result = JSON.parse(resultText);
        setPortfolioContent(result);
      } catch (err) {
        console.error("Error:", err);
      }
    }
    preloadPortfolio();
  }, []);

  if (!portfolioContent) {
    return <p className="text-white">{t("loading")}</p>;
  } else if (portfolioContent.length === 0) {
    return <p className="text-white">{t("no_projects")}</p>;
  }

  return (
    <div className="space-y-6 px-2 md:px-4 py-4">
      <div className="flex items-center gap-6">
        <h1 className="text-3xl font-bold text-white tracking-wide whitespace-nowrap">
          {t("portfolio_title")}
        </h1>
        <div className="h-px w-full bg-white/10"></div>
      </div>
      <p className="text-center md:text-left text-gray-300 italic mt-2">
        {t("portfolio_subtitle")}
      </p>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 px-2 md:px-4 py-4">
      {portfolioContent.map((project) => {
        const title =
          i18n.language === "tr"
            ? project.proje_baslik_tr
            : project.proje_baslik_en;

        const metaUrl =
          i18n.language === "tr"
            ? project.meta_url_tr
            : project.meta_url_en;

        return (
          <div
            key={project.id}
            className="card bg-black/60 border border-blue-400 shadow-md cursor-pointer 
                      hover:border-cyan-300 hover:scale-[1.02] transition-transform"
            onClick={() => navigate(`/portfoy/${metaUrl}`)}
          >
            <figure className="bg-black">
              <img
                src={project.kapak_foto}
                alt={title}
                className="object-cover w-full h-48 border-b border-blue-400"
              />
            </figure>
            <div className="card-body text-green-300">
              <h2 className="w-full flex justify-center md:justify-start text-center md:text-left card-title text-blue-300 tracking-wider">
                {title}
              </h2>
              <p className="text-center md:text-left text-sm text-gray-400">
                {project.proje_teknolojiler}
              </p>
            </div>
          </div>
        );
      })}
    </div>
    </div>
  );
}

export default Portfolio;