import { React, useState } from 'react';
import { useTranslation } from "react-i18next";
import { Trans } from "react-i18next";

function Contact({ csrf }) {
    const { t } = useTranslation();
  // Form verilerini tutmak için state
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    subject: '',
    message: '',
  });
  
  // Sunucu yanıtını tutmak için state (başarı/hata mesajları için)
  const [responseMessage, setResponseMessage] = useState(null); 

  const csrfToken = csrf;

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
    if (responseMessage) setResponseMessage(null); 
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    // Yanıtı temizle ve yükleme durumunu göster
    setResponseMessage({ type: 'sending', text: 'Mesaj gönderiliyor...' });
    
    // FormData'yı sunucuya göndermek için hazırlama
    const dataToSend = new FormData();
    dataToSend.append('name', formData.name);
    dataToSend.append('email', formData.email);
    dataToSend.append('subject', formData.subject);
    dataToSend.append('message', formData.message);
    // **CSRF Token'ı buraya eklemeniz gerekebilir:**
    dataToSend.append('csrf_token', csrfToken); 

    try {
        const res = await fetch("/ajax/contact.php", {
            method: 'POST',
            body: dataToSend,
        });

        const result = await res.json();

        if (res.ok && result.success === true) {
            // Başarılı yanıt
            setResponseMessage({ type: 'success', text: result.message || 'Mesajınız başarıyla iletildi!' });
            // Formu temizle
            setFormData({ name: '', email: '', subject: '', message: '' }); 
        } else {
            // PHP'den gelen hata (validation_error, csrf_error vb.)
            const messageText = result.message || 'Sunucu hatası: Mesaj gönderilemedi.';
            setResponseMessage({ type: 'error', text: messageText });
        }
        
    } catch (error) {
        console.error("AJAX Gönderim Hatası:", error);
        setResponseMessage({ type: 'error', text: 'Ağ hatası. Sunucuya ulaşılamadı.' });
    }
  };
  
  // Yanıt mesajını göstermek için yardımcı fonksiyon (Style bazlı)
  const renderResponseMessage = () => {
      if (!responseMessage) return null;
      console.log(responseMessage);

      let classes = "";
      if (responseMessage.type === 'success') {
          classes = "text-green-400 font-bold p-2 border border-green-500 bg-green-900/30 rounded";
      } else if (responseMessage.type === 'error') {
          classes = "text-red-400 font-bold p-2 border border-red-500 bg-red-900/30 rounded";
      } else if (responseMessage.type === 'sending') {
          return (
              <p className="text-yellow-400 flex items-center pt-2">
                  <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                      <path className="opacity-75" fill="currentColor" d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0z"></path>
                  </svg>
                  {responseMessage.text}
              </p>
          );
      }

      return <p className={classes}>{responseMessage.text}</p>;
  };


  return (
    <div className="space-y-6 p-4 max-w-4xl mx-auto">
  {/* Başlık */}
  <div className="flex items-center gap-6">
    <h1 className="text-3xl font-bold text-white tracking-wide whitespace-nowrap">
      <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">
        {t("contact_title")}
      </span>
    </h1>
    <div className="h-px w-full bg-white/10"></div>
  </div>

  {/* Bilgi Kutucukları */}
  <div className="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
    {/* İletişim E-posta */}
    <div className="text-center md:text-left p-4 border border-blue-500 rounded bg-black/40">
      <h2 className="text-blue-400 font-bold mb-1">{t("email_target")}</h2>
      <a
        href="mailto:alper@alperkum.com"
        className="text-gray-300 hover:text-cyan-300 transition-colors"
      >
        {t("email_address")}
      </a>
      <p className="text-sm italic text-blue-400/75 mt-1">
        {t("email_response_time")}
      </p>
    </div>

    {/* Sosyal Medya */}
    <div className="text-center md:text-left p-4 border border-blue-500 rounded bg-black/40">
      <h2 className="text-blue-400 font-bold mb-1">{t("social_link")}</h2>
      <div className="flex flex-col items-center md:items-start space-y-2 mt-2">
        {/* LinkedIn Linki */}
        <a 
          href="https://www.linkedin.com/in/alper-kum" 
          target="_blank" 
          rel="noopener noreferrer"
          className="text-blue-400 hover:text-indigo-300 transition-colors font-medium"
        >
          {t("social_linkedin")}
        </a>

        {/* GitHub Linki */}
        <a 
          href="https://github.com/alp3rkum" 
          target="_blank" 
          rel="noopener noreferrer" 
          className="text-blue-400 hover:text-indigo-300 transition-colors font-medium"
        >
          {t("social_github")}
        </a>
      </div>
      <p className="text-sm italic text-blue-400/75 mt-1">
        {t("social_detail")}
      </p>
    </div>

    {/* Durum */}
    <div className="text-center md:text-left p-4 border border-blue-500 rounded bg-black/40">
      <h2 className="text-blue-400 font-bold mb-1">{t("system_status")}</h2>
      <p className="text-gray-300">{t("status_text")}</p>
      <p className="text-sm italic text-blue-400/75 mt-1">{t("status_sub")}</p>
    </div>
  </div>

  {/* İletişim Formu */}
  <form
    onSubmit={handleSubmit}
    className="space-y-4 p-6 border-2 border-cyan-400/50 rounded-lg bg-black/50 shadow-lg"
  >
    <h2 className="text-center md:text-left text-2xl font-semibold text-cyan-300 tracking-wider border-b border-blue-600 pb-2 mb-4">
      {t("send_message_form")}
    </h2>

    {/* Adınız */}
    <div>
      <label
        htmlFor="name"
        className="block text-sm font-medium text-blue-300 mb-1"
      >
        {t("form_name")} <span className="text-red-500">*</span>
      </label>
      <input
        type="text"
        id="name"
        name="name"
        value={formData.name}
        onChange={handleChange}
        required
        className="w-full p-3 bg-black/50 border border-blue-500 text-white rounded focus:border-cyan-300 focus:ring-1 focus:ring-cyan-300 transition-all duration-200 placeholder-gray-500"
        placeholder={t("form_name_placeholder")}
      />
    </div>

    {/* E-posta */}
    <div>
      <label
        htmlFor="email"
        className="block text-sm font-medium text-blue-300 mb-1"
      >
        {t("form_email")} <span className="text-red-500">*</span>
      </label>
      <input
        type="email"
        id="email"
        name="email"
        value={formData.email}
        onChange={handleChange}
        required
        className="w-full p-3 bg-black/50 border border-blue-500 text-white rounded focus:border-cyan-300 focus:ring-1 focus:ring-cyan-300 transition-all duration-200 placeholder-gray-500"
        placeholder={t("form_email_placeholder")}
      />
    </div>

    {/* Konu */}
    <div>
      <label
        htmlFor="subject"
        className="block text-sm font-medium text-blue-300 mb-1"
      >
        {t("form_subject")} <span className="text-red-500">*</span>
      </label>
      <input
        type="text"
        id="subject"
        name="subject"
        value={formData.subject}
        onChange={handleChange}
        required
        className="w-full p-3 bg-black/50 border border-blue-500 text-white rounded focus:border-cyan-300 focus:ring-1 focus:ring-cyan-300 transition-all duration-200 placeholder-gray-500"
        placeholder={t("form_subject_placeholder")}
      />
    </div>

    {/* Mesaj */}
    <div>
      <label
        htmlFor="message"
        className="block text-sm font-medium text-blue-300 mb-1"
      >
        {t("form_message")} <span className="text-red-500">*</span>
      </label>
      <textarea
        id="message"
        name="message"
        rows="5"
        value={formData.message}
        onChange={handleChange}
        required
        className="w-full p-3 bg-black/50 border border-blue-500 text-white rounded focus:border-cyan-300 focus:ring-1 focus:ring-cyan-300 transition-all duration-200 placeholder-gray-500 resize-none"
        placeholder={t("form_message_placeholder")}
      ></textarea>
    </div>

    {/* Durum Mesajı Gösterimi */}
    {renderResponseMessage()}

    {/* Gönder Butonu */}
    <button
      type="submit"
      disabled={responseMessage && responseMessage.type === "sending"}
      className={`w-full mt-6 p-3 font-bold tracking-widest uppercase rounded transition-all duration-300 
        ${
          responseMessage && responseMessage.type === "sending"
            ? "bg-gray-600 text-gray-400 cursor-not-allowed"
            : "bg-cyan-600 hover:bg-cyan-400 text-white border-2 border-cyan-300 hover:border-cyan-200"
        }`}
    >
      {responseMessage && responseMessage.type === "sending"
        ? t("form_sending")
        : t("form_submit")}
    </button>
  </form>
</div>
  );
}

export default Contact;