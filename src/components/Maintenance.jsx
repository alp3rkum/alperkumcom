import React from 'react';
import { useTranslation } from 'react-i18next';

export default function Maintenance() {
  const { t } = useTranslation();

  return (
    <div className="min-h-screen w-full flex items-center justify-center bg-black p-6">
      <div className="max-w-2xl w-full space-y-8 text-center">
        {/* Durum İkonu & Başlık */}
        <div className="space-y-4">
          <div className="inline-block px-3 py-1 border border-amber-500/50 rounded text-amber-500 text-xs font-mono animate-pulse">
            SYSTEM_STATUS: OFFLINE
          </div>
          <h1 className="text-4xl lg:text-6xl font-bold tracking-tight">
            <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">
              {t("maintenance_title")}
            </span>
          </h1>
          <hr className="text-blue-400/33 border-1 mx-auto w-24" />
        </div>

        {/* Ana Mesaj Kutusu */}
        <div className="p-8 border border-blue-500 rounded bg-black/40 backdrop-blur-sm relative overflow-hidden">
          {/* Arka plan süsü */}
          <div className="absolute top-0 right-0 p-2 text-[10px] font-mono text-blue-500/20 select-none">
            ERR_CODE: 503_MAINTENANCE_MODE
          </div>
          
          <p className="text-xl text-white font-light leading-relaxed">
            {t("maintenance_description")}
          </p>
          
          <div className="mt-8 flex flex-col items-center justify-center space-y-2">
            <div className="w-full bg-blue-500/10 h-1 rounded-full overflow-hidden">
              <div className="bg-blue-500 h-full w-2/3 animate-[loading_2s_ease-in-out_infinite]"></div>
            </div>
            <p className="text-sm italic text-blue-400/75 font-mono">
              {t("system_recalibrating")}...
            </p>
          </div>
        </div>

        {/* Alt Bilgi */}
        <p className="text-gray-500 text-sm font-light">
          {t("maintenance_footer")}
        </p>
      </div>

      <style jsx>{`
        @keyframes loading {
          0% { transform: translateX(-100%); }
          100% { transform: translateX(200%); }
        }
      `}</style>
    </div>
  );
}