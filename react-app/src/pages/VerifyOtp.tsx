import { useState } from 'react'
import { useNavigate, useLocation } from 'react-router-dom'
import apiClient from '../services/apiClient'

type State = {
  tourId?: string
}

const VerifyOtp = () => {
  const [otp, setOtp] = useState('')
  const navigate = useNavigate()
  const { state } = useLocation() as { state: State }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    try {
      await apiClient.post('/verify-otp', { otp, tour_id: state?.tourId })
      navigate('/')
    } catch (err) {
      console.error(err)
    }
  }

  return (
    <div>
      <h1>Xác nhận OTP</h1>
      <form onSubmit={handleSubmit}>
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

export default VerifyOtp
