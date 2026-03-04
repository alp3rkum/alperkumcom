import { useEffect } from "react";
import { useLocation } from "react-router-dom";
import { setGlobalSEO, setPageSEO } from "/seoindex.js";

function SEOHandler() {
  const location = useLocation();

  useEffect(() => {
    const path = location.pathname;

    if (path.startsWith("/portfoy/")) {
      // /portfoy/{proje}
      const projectSlug = path.split("/")[2]; // ikinci segment
      setPageSEO(`/ajax/projectseo.php?url=${projectSlug}`);
    } else if (path.startsWith("/blog/")) {
      // /blog/{kategori}/{gönderi}
      const segments = path.split("/");
      const blogSlug = segments[3]; // üçüncü segment {gönderi}
      setPageSEO(`/ajax/blogseo.php?url=${blogSlug}`);
    } else {
      // Global SEO
      setGlobalSEO();
    }
  }, [location]);

  return null;
}

export default SEOHandler;