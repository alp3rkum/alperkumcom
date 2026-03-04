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


function App() {
  const [aboutContent, setAboutContent] = useState(null);
  const [CSRFToken, setCSRFToken] = useState('');

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
          
          
          preloadAbout();
          CSRF();
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
      <div className='flex bg-black'>
        <div className="w-screen min-h-[calc(100vh-64px)] sm:min-h-[calc(100vh-129px)] max-h-[calc(100vh-64px)] sm:max-h-[calc(100vh-129px)] bg-blue-950/50 flex items-center justify-center px-2 py-6">
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
            </Routes>
          </div>
        </div>
      </div>
      <Footer/>
    </>
  )
}

export default App
