import { useEffect, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { useAuth } from '../hooks/useAuth'
import profileService from '../services/profile'

const EditProfile = () => {
  const { token } = useAuth()
  const navigate = useNavigate()
  const [name, setName] = useState('')
  const [phone, setPhone] = useState('')
  const [password, setPassword] = useState('')
  const [profileImage, setProfileImage] = useState<File | null>(null)

  useEffect(() => {
    const fetchData = async () => {
      if (!token) return
      const { user } = await profileService.getProfile(token, {
        voucher: false,
        review: false,
      })
      setName(user.name)
      setPhone(user.phone || '')
    }
    fetchData()
  }, [token])

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    if (!token) return
    await profileService.updateProfile(token, {
      name,
      phone,
      password: password || undefined,
      profile_image: profileImage || undefined,
    })
    navigate('/profile')
  }

  return (
    <div className="edit-profile-container">
      <h1>Cập nhật tài khoản</h1>
      <form onSubmit={handleSubmit}>
        <label htmlFor="name">Tên hiển thị</label>
        <input
          id="name"
          value={name}
          onChange={(e) => setName(e.target.value)}
          required
        />
        <label htmlFor="phone">Số điện thoại</label>
        <input
          id="phone"
          value={phone}
          onChange={(e) => setPhone(e.target.value)}
          required
        />
        <label htmlFor="password">Mật khẩu (nếu không đổi, hãy để trống)</label>
        <input
          id="password"
          type="password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
        />
        <label htmlFor="profile_image">Ảnh đại diện</label>
        <input
          id="profile_image"
          type="file"
          accept="image/*"
          onChange={(e) => setProfileImage(e.target.files?.[0] || null)}
        />
        <button type="submit">Lưu</button>
      </form>
      <button onClick={() => navigate('/profile')}>← Trang tài khoản</button>
    </div>
  )
}

export default EditProfile
