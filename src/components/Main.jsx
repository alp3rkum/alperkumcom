import { React } from "react";
import { Helmet } from "react-helmet";
import { useTranslation } from "react-i18next";

export default function Main() {
  const { t } = useTranslation();

  return (
    <>
      
      <div className="space-y-4 p-3">
        <h1 className="text-4xl lg:text-6xl font-bold tracking-tight">
          <span className="block text-center md:text-left text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">
            {t("hello_world")}
          </span>
          <hr className="text-blue-400/33 border-1 mt-4 mb-3" />
          <p className="text-center md:text-left text-xl lg:text-2xl text-white font-light max-w-3xl leading-relaxed">
            {t("intro_text")}
          </p>
        </h1>

        <div className="mt-12 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 items-stretch">
          {/* Identity */}
          <div className="p-4 border border-blue-500 rounded bg-black/40">
            <h2 className="text-center md:text-left text-blue-400 font-bold">{t("system_identity")}</h2>
            <p className="text-center md:text-left text-gray-300">{t("identity_title")}</p>
            <p className="text-center md:text-left text-sm italic text-blue-400/75">
              {t("identity_sub")}
            </p>
          </div>

          {/* Processor */}
          <div className="p-4 border border-blue-500 rounded bg-black/40">
            <h2 className="text-center md:text-left text-blue-400 font-bold">{t("system_processor")}</h2>
            <p className="text-center md:text-left text-gray-300">{t("processor_title")}</p>
            <p className="text-center md:text-left text-sm italic text-blue-400/75">
              {t("processor_sub")}
            </p>
          </div>

          {/* Memory */}
          <div className="p-4 border border-blue-500 rounded bg-black/40">
            <h2 className="text-center md:text-left text-blue-400 font-bold">{t("neural_memory")}</h2>
            <p className="text-center md:text-left text-gray-300">{t("memory_title")}</p>
            <p className="text-center md:text-left text-sm italic text-blue-400/75">{t("memory_sub")}</p>
          </div>

          {/* Version */}
          <div className="p-4 border border-blue-500 rounded bg-black/40">
            <h2 className="text-center md:text-left text-blue-400 font-bold">{t("kernel_version")}</h2>
            <p className="text-center md:text-left text-gray-300">{t("version_title")}</p>
            <p className="text-center md:text-left text-sm italic text-blue-400/75">
              {t("version_sub")}
            </p>
          </div>
        </div>

        <div className="p-6 border border-blue-500 rounded bg-black/40 col-span-1 sm:col-span-2 xl:col-span-4">
          <h2 className="text-center md:text-left text-blue-400 font-bold">
            {t("architecture_manifest")}
          </h2>
          <p className="text-center md:text-left text-gray-300 leading-relaxed">{t("manifest_text")}</p>
          <p className="text-center md:text-left text-sm italic text-blue-400/75 mt-2">
            {t("manifest_sub")}
          </p>
        </div>
      </div>
    </>
  );
}
