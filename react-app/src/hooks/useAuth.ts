import {
  createContext,
  useContext,
  useEffect,
  useState,
  ReactNode,
  createElement,
} from 'react'

type AuthContextType = {
  token: string | null
  login: (token: string) => void
  logout: () => void
}

const TOKEN_KEY = 'jwt_token'

const AuthContext = createContext<AuthContextType | undefined>(undefined)

export const AuthProvider = ({ children }: { children: ReactNode }) => {
  const [token, setToken] = useState<string | null>(() =>
    localStorage.getItem(TOKEN_KEY),
  )

  useEffect(() => {
    if (token) {
      localStorage.setItem(TOKEN_KEY, token)
    } else {
      localStorage.removeItem(TOKEN_KEY)
    }
  }, [token])

  const login = (newToken: string) => setToken(newToken)
  const logout = () => setToken(null)

  return createElement(
    AuthContext.Provider,
    { value: { token, login, logout } },
    children,
  )
}

export const useAuth = () => {
  const context = useContext(AuthContext)
  if (!context) {
    throw new Error('useAuth must be used within AuthProvider')
  }
  return context
}
