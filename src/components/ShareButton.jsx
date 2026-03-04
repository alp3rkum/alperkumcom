export default function ShareButton({ url, title, text }) {
  const handleShare = async () => {
    if (navigator.share) {
      try {
        await navigator.share({
          title,
          text,
          url
        });
      } catch (err) {
        console.error("Share failed:", err);
      }
    } else {
      alert("Paylaşım özelliği bu tarayıcıda desteklenmiyor.");
    }
  };

  return (
    <button
      onClick={handleShare}
      className="btn btn-secondary bg-green-600 hover:bg-green-400 text-white font-mono tracking-widest border border-green-400 transition-colors duration-300"
    >
      Paylaş
    </button>
  );
}