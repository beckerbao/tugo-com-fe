import { useState } from 'react'
import { useLocation, useNavigate } from 'react-router-dom'
import apiClient from '../services/apiClient'
import { useAuth } from '../hooks/useAuth'

const LoginVerifyOtp = () => {
  const [otp, setOtp] = useState('')
  const navigate = useNavigate()
  const { state } = useLocation() as { state: { phone: string } }
  const { login } = useAuth()

  const handleVerify = async (e: React.FormEvent) => {
    e.preventDefault()
    const { token } = await apiClient.post<{ token: string }>(
      '/login-verify-otp',
      {
        phone: state?.phone,
        otp,
      },
    )
    login(token)
    navigate('/profile')
  }

  return (
    <div>
      <h1>Xác nhận OTP</h1>
      <form onSubmit={handleVerify}>
        <input
          type="text"
          placeholder="Mã OTP"
          value={otp}
          onChange={(e) => setOtp(e.target.value)}
        />
        <button type="submit">Xác nhận</button>
      </form>
    </div>
  )
}

export default LoginVerifyOtp
