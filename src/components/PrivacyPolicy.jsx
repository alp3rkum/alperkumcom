import React from "react";
import { useTranslation } from "react-i18next";
import { Helmet } from "react-helmet";

export default function PrivacyPolicy() {
  const { t } = useTranslation();

  return (
    <div className="max-w-4xl mx-auto p-6 space-y-8 bg-black/20 border border-blue-500/20 rounded-xl my-10 text-gray-300">
      <Helmet>
        <title>{t("privacy_title")} | Portfolio</title>
      </Helmet>

      <header className="border-b border-blue-400/30 pb-4">
        <h1 className="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">
          {t("privacy_title")}
        </h1>
      </header>

      <section className="space-y-4">
        <p className="leading-relaxed">
          {t("privacy_intro")}
        </p>

        <div className="p-4 bg-blue-900/10 border-l-4 border-blue-500 rounded-r-lg">
          <h2 className="text-xl font-semibold text-blue-300 mb-2">
            {t("privacy_cookies_title")}
          </h2>
          <p className="text-sm md:text-base leading-relaxed">
            {t("privacy_cookies_text")}
          </p>
        </div>

        <div>
          <h2 className="text-xl font-semibold text-blue-300 mb-2">
            {t("privacy_data_title")}
          </h2>
          <p className="leading-relaxed">
            {t("privacy_data_text")}
          </p>
        </div>
      </section>

      <footer className="pt-6 border-t border-blue-400/30 text-sm italic text-gray-500">
        <p>Last updated: {new Date().toLocaleDateString()}</p>
      </footer>
    </div>
  );
}