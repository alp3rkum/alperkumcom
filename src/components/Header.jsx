import { useEffect, useState } from "react";
import { NavLink, useNavigate, useLocation } from "react-router-dom";
import i18n from "../i18n";
import { useTranslation } from "react-i18next";

const clickSound = new Audio("/sounds/menu.mp3");

export default function Header() {
  const [menuOpen, setMenuOpen] = useState(false);
  const [activeIndex, setActiveIndex] = useState(0);
  const navigate = useNavigate();
  const location = useLocation();
  const [currentLang, setCurrentLang] = useState(i18n.language);
  const { t } = useTranslation();

  const menuItems = [
    { label: t("menu_home"), path: "/" },
    { label: t("menu_about"), path: "/hakkimda" },
    { label: t("menu_portfolio"), path: "/portfoy" },
    { label: t("menu_blogs"), path: "/bloglar" },
    { label: t("menu_contact"), path: "/iletisim" },
    { label: t("menu_privacy"), path: "/gizlilik" }
  ];

  function playClickSound() {
    clickSound.currentTime = 0; // başa sar
    clickSound.play();
  }

  useEffect(() => {
    const currentIndex = menuItems.findIndex(
      (item) =>
        location.pathname === item.path ||
        (item.path !== "/" && location.pathname.startsWith(item.path))
    );
    if (currentIndex !== -1) {
      setActiveIndex(currentIndex);
    }
  }, [location.pathname]);

  useEffect(() => {
    playClickSound();
    const handleKey = (e) => {
      if (e.key === "ArrowRight") {
        setActiveIndex((prev) => {
          const next = (prev + 1) % menuItems.length;
          navigate(menuItems[next].path);   // yeni index ile git
          return next;
        });
      } else if (e.key === "ArrowLeft") {
        setActiveIndex((prev) => {
          const next = (prev - 1 + menuItems.length) % menuItems.length;
          navigate(menuItems[next].path);   // yeni index ile git
          return next;
        });
      }
    };
    window.addEventListener("keydown", handleKey);
    return () => window.removeEventListener("keydown", handleKey);
  }, [navigate]);

  const toggleLang = () => {
    playClickSound();
    const newLang = currentLang === "tr" ? "en" : "tr";
    i18n.changeLanguage(newLang);
    setCurrentLang(newLang); // state güncelle
  };

  return (
    <>
      {/* Header */}
      <div className="relative w-full h-16 bg-black/92 backdrop-blur-md border-b border-white/20 overflow-hidden flex items-center px-8 justify-between z-50">
        {/* BIOS marquee efektli div */}
        <div className="absolute top-0 bottom-0 w-[40%] bg-gradient-to-r from-transparent via-blue-600/80 to-transparent blur-2xl animate-p4-marquee"></div>

        <div className="relative z-10 flex flex-col">
          <h1 className="text-blue-400 text-xl font-black tracking-[0.3em] font-mono italic crt-glow leading-none">
            ALPER KUM
          </h1>
          <span className="text-[10px] text-white/40 tracking-[0.5em] font-mono mt-1">
            COMPUTER_SCIENTIST
          </span>
        </div>

        {/* Hamburger button (mobilde görünür) */}
        <button
          className="md:hidden relative z-10 text-white"
          onClick={() => setMenuOpen(!menuOpen)}
        >
          ☰
        </button>
      </div>

      {/* Normal navbar (desktop) */}
      <nav className="hidden md:flex w-full bg-black/92 backdrop-blur-md border-b border-white/10 p-4 items-center justify-between">
  {/* Sol taraf: menü linkleri */}
  <div className="flex gap-4">
    {menuItems.map((item, i) => (
      <NavLink
        key={item.path}
        to={item.path}
        onClick={() => {
          setActiveIndex(i);
        }}
        className={({ isActive }) => {
          const isPortfoyActive =
            item.path === "/portfoy" &&
            location.pathname.startsWith("/portfoy") &&
            item.path !== "/";
          const isBlogActive =
            item.path === "/bloglar" &&
            (location.pathname === "/bloglar" ||
              location.pathname.startsWith("/blog/"));
          const active =
            isActive || i === activeIndex || isPortfoyActive || isBlogActive;

          return `btn btn-sm bg-black/0 font-mono tracking-widest border border-white/50 ${
            active
              ? "btn-active text-blue-400 cursor-default pointer-events-none"
              : "text-white hover:text-blue-400"
          }`;
        }}
      >
        {item.label}
      </NavLink>
    ))}
  </div>

  {/* Sağ taraf: dil butonu */}
  <div className="flex items-center gap-2">
    <div className="w-px h-6 bg-white/30"></div>
    <button
      onClick={toggleLang}
      className="btn btn-sm bg-black/0 font-mono tracking-widest border border-white/50 text-white hover:text-blue-400 w-[81px]"
    >
      {currentLang === "tr" ? "TÜRKÇE" : "ENGLISH"}
    </button>
  </div>
</nav>

      {/* Açılır kapanır menü (mobil) */}
      <nav
  className={`md:hidden absolute top-16 left-0 w-full bg-black/92 backdrop-blur-md border-b border-white/10 flex flex-col p-4 gap-4 transform transition-transform duration-300 ease-out z-49 ${
    menuOpen ? "translate-y-0" : "-translate-y-full"
  }`}
>
  {menuItems.map((item, i) => (
    <NavLink
      key={item.path}
      to={item.path}
      end={item.path === "/"} // sadece ana sayfa için tam eşleşme
      onClick={() => {
        playClickSound();
        setActiveIndex(i);
        setMenuOpen(false); // mobilde tıklayınca menüyü kapatmak istersen
      }}
      className={({ isActive }) => {
        const isPortfoyActive =
          item.path === "/portfoy" && location.pathname.startsWith("/portfoy");
        const active = isActive || i === activeIndex || isPortfoyActive;

        return `btn btn-sm bg-black/0 font-mono tracking-widest border border-white/50 ${
          active
            ? "btn-active text-blue-400 cursor-default pointer-events-none"
            : "text-white hover:text-blue-400"
        }`;
      }}
    >
      {item.label}
    </NavLink>
  ))}
  <hr/>
  <button
      onClick={toggleLang}
      className="btn btn-sm bg-black/0 font-mono tracking-widest border border-white/50 text-white hover:text-blue-400"
    >
      {currentLang === "tr" ? "TÜRKÇE" : "ENGLISH"}
    </button>
</nav>
    </>
  );
}