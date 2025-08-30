import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import apiClient from '../services/apiClient'

const Register = () => {
  const [phone, setPhone] = useState('')
  const [password, setPassword] = useState('')
  const navigate = useNavigate()

  const handleRegister = async (e: React.FormEvent) => {
    e.preventDefault()
    await apiClient.post('/register', { phone, password })
    navigate('/login')
  }

  return (
    <div>
      <h1>Đăng ký</h1>
      <form onSubmit={handleRegister}>
        <input
          type="tel"
          placeholder="Số điện thoại"
          value={phone}
          onChange={(e) => setPhone(e.target.value)}
        />
        <input
          type="password"
          placeholder="Mật khẩu"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
        />
        <button type="submit">Đăng ký</button>
      </form>
    </div>
  )
}

export default Register
