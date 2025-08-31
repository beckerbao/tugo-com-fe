import { useEffect, useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { useAuth } from '../hooks/useAuth'
import profileService from '../services/profile'
import { getImageUrl, getTimeAgo } from '../services/helpers'
import type { ProfileData } from '../types'

const Profile = () => {
  const { token, logout } = useAuth()
  const navigate = useNavigate()
  const [data, setData] = useState<ProfileData | null>(null)

  useEffect(() => {
    const fetchData = async () => {
      if (!token) return
      const profile = await profileService.getProfile(token, {
        voucher: true,
        review: true,
      })
      setData(profile)
    }
    fetchData()
  }, [token])

  const handleLogout = async () => {
    if (!token) return
    await profileService.logout(token)
    logout()
    navigate('/')
  }

  if (!data) return <div>Loading...</div>

  const { user, vouchers, reviews } = data
  const imgSrc = (path?: string | null) =>
    path?.startsWith('http') ? path : getImageUrl(path || '')

  return (
    <div className="profile-container">
      <div className="profile-header">
        <img src={imgSrc(user.profile_image)} alt="Avatar" />
        <div className="profile-info">
          <h2>{user.name}</h2>
        </div>
        <div className="edit-profile">
          <Link to="/edit-profile">Cập nhật</Link>
          <button onClick={handleLogout} className="logout-button">
            Thoát
          </button>
        </div>
      </div>

      <h3 className="section-title">Voucher ưu đãi</h3>
      <ul className="voucher-list">
        {vouchers.map((v) => (
          <li key={v.id}>
            <div className="voucher-details">
              <strong>Mã voucher: {v.code}</strong>
              <p>Ngày hiệu lực: {v.valid_until}</p>
            </div>
            <div className="voucher-actions">
              <Link to={`/voucher/${v.id}`}>Xem chi tiết</Link>
            </div>
            <div className={`voucher-status ${v.status.toLowerCase()}`}>
              {v.status}
            </div>
          </li>
        ))}
      </ul>

      <h3 className="section-title">
        Đánh giá của bạn <Link to="/review">Viết đánh giá</Link>
      </h3>
      <div className="review-list">
        {reviews.map((r) => (
          <div className="post" key={r.id}>
            <div className="user">
              <img src={imgSrc(user.profile_image)} alt="User Profile" />
              <div className="name">{user.name}</div>
            </div>
            <div className="meta">
              <i>🗺️</i> <span>Tên tour: {r.tour_name}</span>
            </div>
            <div className="meta">
              <i>🗺️</i> <span>Hướng dẫn viên: {r.guide_name}</span>
            </div>
            <div className="content">{r.content}</div>
            <div className="time">Đăng: {getTimeAgo(r.created_at)}</div>
          </div>
        ))}
      </div>
    </div>
  )
}

export default Profile
