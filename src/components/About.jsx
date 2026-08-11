import {React} from 'react'
import { useTranslation } from "react-i18next";

function About({content}) {

  const { t, i18n } = useTranslation();

  if (!content) {
    return <p className="text-white">{t("loading")}</p>;
  }
    function normalizeHeadings(html) {
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, "text/html");
      doc.querySelectorAll("h3").forEach(el => {
        el.outerHTML = `<h4>${el.innerHTML}</h4>`;
      });
      doc.querySelectorAll("h2").forEach(el => {
        el.outerHTML = `<h3>${el.innerHTML}</h3>`;
      });
      doc.querySelectorAll("h1").forEach(el => {
        el.outerHTML = `<h2>${el.innerHTML}</h2>`;
      });

      return doc.body.innerHTML;
    }

  const selectedContent =
    i18n.language === "tr"
      ? content.hakkinda_icerik
      : content.hakkinda_icerik_en;

  return (
    <>
          <div className="space-y-4 p-3">
      <div className="flex items-center gap-6">
        <h2 className="text-2xl font-bold text-white tracking-wide whitespace-nowrap">{t("about")}</h2>
        <div className="h-px w-full bg-white/10"></div>
      </div>
      <div className="bg-black/50 border-2 border-blue-400 pl-3 overflow-y-auto">
        <div id='content' className='text-white' dangerouslySetInnerHTML={{ __html: normalizeHeadings(selectedContent) }} />
      </div>
      
    </div>
    </>
    
  )
}

export default About