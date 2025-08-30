import { BrowserRouter, Route, Routes, Outlet } from 'react-router-dom'
import Header from './components/Header'
import Navbar from './components/Navbar'
import Footer from './components/Footer'
import Home from './pages/Home'
import Profile from './pages/Profile'
import Flashsale from './pages/Flashsale'
import './styles/App.css'

const Layout = () => (
  <>
    <Header />
    <Outlet />
    <Navbar />
    <Footer />
  </>
)

const App = () => (
  <BrowserRouter>
    <Routes>
      <Route element={<Layout />}>
        <Route path="/" element={<Home />} />
        <Route path="/profile" element={<Profile />} />
        <Route path="/flashsale" element={<Flashsale />} />
      </Route>
    </Routes>
  </BrowserRouter>
)

export default App
