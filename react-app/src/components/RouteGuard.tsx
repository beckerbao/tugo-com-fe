import { Navigate, Outlet } from 'react-router-dom'
import { useAuth } from '../hooks/useAuth'

const RouteGuard = () => {
  const { token } = useAuth()
  return token ? <Outlet /> : <Navigate to="/login" replace />
}

export default RouteGuard
