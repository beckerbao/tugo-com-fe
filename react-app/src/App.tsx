import { BrowserRouter, Route, Routes, Outlet } from 'react-router-dom'
import Header from './components/Header'
import Navbar from './components/Navbar'
import Footer from './components/Footer'
import RouteGuard from './components/RouteGuard'
import Home from './pages/Home'
import Profile from './pages/Profile'
import Flashsale from './pages/Flashsale'
import Login from './pages/Login'
import LoginOtp from './pages/LoginOtp'
import LoginVerifyOtp from './pages/LoginVerifyOtp'
import Register from './pages/Register'
import Review from './pages/Review'
import ReviewByQr from './pages/ReviewByQr'
import VerifyOtp from './pages/VerifyOtp'
import { AuthProvider } from './hooks/useAuth'
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
  <AuthProvider>
    <BrowserRouter>
      <Routes>
        <Route element={<Layout />}>
          <Route path="/" element={<Home />} />
          <Route path="/flashsale" element={<Flashsale />} />
          <Route path="/login" element={<Login />} />
          <Route path="/login-otp" element={<LoginOtp />} />
          <Route path="/login-verify-otp" element={<LoginVerifyOtp />} />
          <Route path="/register" element={<Register />} />
          <Route path="/review" element={<Review />} />
          <Route path="/reviewbyqr" element={<ReviewByQr />} />
          <Route path="/verify-otp" element={<VerifyOtp />} />
          <Route element={<RouteGuard />}>
            <Route path="/profile" element={<Profile />} />
          </Route>
        </Route>
      </Routes>
    </BrowserRouter>
  </AuthProvider>
)

export default App
