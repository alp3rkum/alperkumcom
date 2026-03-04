import { React, useState, useEffect } from 'react'
import { useTranslation } from "react-i18next";

const Footer = () => {
  const [time, setTime] = useState(new Date());
  const { t } = useTranslation();

  useEffect(() => {
    const timer = setInterval(() => setTime(new Date()), 1000);
    return () => clearInterval(timer);
  }, []);

  return (
    <footer className="fixed bottom-0 left-0 w-full text-gray-400 text-sm p-2 bg-black/50 flex items-center justify-between">
      {/* Sistem Zamanı her zaman görünür */}
      <div>
        {t("system_time")}:{" "}
        <span className="ml-1 font-bold text-blue-400">
          {time.toLocaleTimeString("tr-TR", {
            hour: "2-digit",
            minute: "2-digit",
            second: "2-digit",
          })}
        </span>
      </div>

      {/* Ekran Seç kısmı sadece sm ve üstünde görünür */}
      <div className="hidden sm:flex items-center gap-2">
        <span className="text-blue-400">|←→|</span>
        <span className="text-gray-400">{t("screen_select")}</span>
      </div>
    </footer>
  );
};

export default Footer;