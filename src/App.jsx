import { useState, useEffect } from 'react';
import './App.css'
import Footer from './components/Footer'
import Header from './components/Header'
import Main from './components/Main'
import About from './components/About'
import Portfolio from './components/Portfolio'
import PortfolioDetail from "./components/PortfolioDetail"
import BlogPage from "./components/BlogPage"
import Blogs from './components/Blogs'
import Contact from './components/Contact'
import SEOHandler from './components/SEOHandler';
import HitTracker from './components/HitTracker';
import { Routes, Route } from 'react-router-dom'
import "./i18n";
import Maintenance from './components/Maintenance';
import PrivacyPolicy from './components/PrivacyPolicy';


function App() {
  const [aboutContent, setAboutContent] = useState(null);
  const [CSRFToken, setCSRFToken] = useState('');
  const [offline, setOffline] = useState(0);

  useEffect(() => {
        try
        {
          async function preloadAbout() {
            const res = await fetch("/ajax/about.php");
            const resultText = await res.text();
            const result = JSON.parse(resultText);
            setAboutContent(result);
          }
          async function CSRF() {
            const res = await fetch("/ajax/session.php");
            const resultText = await res.text();
            setCSRFToken(resultText);
          }
          async function isOffline() {
            const res = await fetch("/ajax/isoffline.php");
            const result = await res.json();
            console.log(result);
            setOffline(result.is_offline);
          }
          
          preloadAbout();
          CSRF();
          isOffline();
        }
        catch(err)
        {
          console.error("Error:", err);
        }
      },[]);

  return (
    <>
      <SEOHandler/>
      <Header/>
      {offline == 0 && (
        <div className='flex bg-black'>
          <div className="w-screen min-h-[calc(100vh-64px)] sm:min-h-[calc(100vh-64px)] max-h-[calc(100vh-64px)] sm:max-h-[calc(100vh-64px)] bg-blue-950/50 flex items-center justify-center px-0 md:px-2 py-6">
            <div className="w-full h-full overflow-y-auto scrollbar-custom">
              <HitTracker />
              <Routes>
                <Route path='/' element={<Main/>}></Route>
                <Route path='/hakkimda' element={<About content={aboutContent}/>}></Route>
                <Route path='/portfoy' element={<Portfolio/>}></Route>
                <Route path="/portfoy/:url" element={<PortfolioDetail />} />
                <Route path='/bloglar' element={<Blogs/>}></Route>
                <Route path="/blog/:category/:url" element={<BlogPage />} />
                <Route path='/iletisim' element={<Contact csrf={CSRFToken}/>}></Route>
                <Route path='/gizlilik' element={<PrivacyPolicy/>}></Route>
              </Routes>
            </div>
          </div>
        </div>
      )}
      {offline == 1 && (
        <Maintenance/>
      )}
      <Footer/>
    </>
  )
}

export default App
