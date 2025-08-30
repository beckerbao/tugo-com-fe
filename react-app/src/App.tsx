import { BrowserRouter, Route, Routes, Outlet } from 'react-router-dom'
import Header from './components/Header'
import Navbar from './components/Navbar'
import Footer from './components/Footer'
import RouteGuard from './components/RouteGuard'
import Home from './pages/Home'
import Profile from './pages/Profile'
import EditProfile from './pages/EditProfile'
import Voucher from './pages/Voucher'
import Flashsale from './pages/Flashsale'
import FlashSaleDetail from './pages/FlashSaleDetail'
import Login from './pages/Login'
import LoginOtp from './pages/LoginOtp'
import LoginVerifyOtp from './pages/LoginVerifyOtp'
import Register from './pages/Register'
import Review from './pages/Review'
import ReviewByQr from './pages/ReviewByQr'
import GenQr from './pages/GenQr'
import RedirectApp from './pages/RedirectApp'
import VerifyOtp from './pages/VerifyOtp'
import ReviewSale from './pages/ReviewSale'
import ThankyouVngroup from './pages/ThankyouVngroup'
import ReelFb from './pages/ReelFb'
import { AuthProvider } from './hooks/useAuth'

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
          <Route path="/flashsale/:id" element={<FlashSaleDetail />} />
          <Route path="/login" element={<Login />} />
          <Route path="/login-otp" element={<LoginOtp />} />
          <Route path="/login-verify-otp" element={<LoginVerifyOtp />} />
          <Route path="/register" element={<Register />} />
          <Route path="/review" element={<Review />} />
          <Route path="/reviewbyqr" element={<ReviewByQr />} />
          <Route path="/genqr" element={<GenQr />} />
          <Route path="/genqr-vngroup" element={<GenQr variant="vngroup" />} />
          <Route path="/redirectapp" element={<RedirectApp />} />
          <Route path="/verify-otp" element={<VerifyOtp />} />
          <Route path="/reviewsale" element={<ReviewSale />} />
          <Route path="/thankyou-vngroup" element={<ThankyouVngroup />} />
          <Route path="/reelfb" element={<ReelFb />} />
          <Route element={<RouteGuard />}>
            <Route path="/profile" element={<Profile />} />
            <Route path="/edit-profile" element={<EditProfile />} />
            <Route path="/voucher/:id" element={<Voucher />} />
          </Route>
        </Route>
      </Routes>
    </BrowserRouter>
  </AuthProvider>
)

export default App
