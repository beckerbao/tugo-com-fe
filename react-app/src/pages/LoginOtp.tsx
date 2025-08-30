import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import apiClient from '../services/apiClient'

const LoginOtp = () => {
  const [phone, setPhone] = useState('')
  const navigate = useNavigate()

  const handleSend = async (e: React.FormEvent) => {
    e.preventDefault()
    await apiClient.post('/login-otp', { phone })
    navigate('/login-verify-otp', { state: { phone } })
  }

  return (
    <div>
      <h1>Gửi OTP</h1>
      <form onSubmit={handleSend}>
        <input
          type="tel"
          placeholder="Số điện thoại"
          value={phone}
          onChange={(e) => setPhone(e.target.value)}
        />
        <button type="submit">Gửi OTP</button>
      </form>
    </div>
  )
}

export default LoginOtp
