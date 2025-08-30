import { Link } from 'react-router-dom'

const Navbar = () => (
  <div className="bottom-nav">
    <Link to="/">
      <i>🏠</i>
      Trang chủ
    </Link>
    <Link to="/profile">
      <i>📄</i>
      Tài khoản
    </Link>
    <Link to="/flashsale">
      <i>🔥</i>
      Tour khuyến mãi
    </Link>
  </div>
)

export default Navbar
