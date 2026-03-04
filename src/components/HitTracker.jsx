import { useEffect } from "react";
import { useLocation } from "react-router-dom";

const HitTracker = () => {
  const location = useLocation();

  useEffect(() => {
    const logHit = async () => {
      const payload = {
        page_url: location.pathname + location.search,
        referrer: document.referrer || null,
        user_id: null
      };

      const formData = new FormData();
      formData.append("data", JSON.stringify(payload));

      try {
        await fetch("/ajax/log_hit.php", {
          method: "POST",
          body: formData,
        });
      } catch (error) {
        console.error("Hit error:", error);
      }
    };

    logHit();
  }, [location]);

  return null;
};

export default HitTracker;