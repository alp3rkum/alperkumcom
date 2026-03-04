import i18n from "i18next";
import { initReactI18next } from "react-i18next";
import LanguageDetector from "i18next-browser-languagedetector";
import enTranslation from "../locales/en/translation.json";
import trTranslation from "../locales/tr/translation.json";

i18n
  .use(LanguageDetector)
  .use(initReactI18next)
  .init({
    fallbackLng: "en",
    debug: false,
    interpolation: { escapeValue: false },
    resources: {
      en: { translation: enTranslation },
      tr: { translation: trTranslation }
    }
  });

// Dil değiştiğinde <html lang> güncelle
i18n.on("languageChanged", (lng) => {
  document.documentElement.lang = lng;
});

export default i18n;