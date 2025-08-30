import { useEffect } from 'react'
import { useSearchParams } from 'react-router-dom'

const RedirectApp = () => {
  const [searchParams] = useSearchParams()
  const token = searchParams.get('token') || ''
  const deepLink = `tugo://reset-password?token=${token}`
  const isZalo = /zalo/i.test(navigator.userAgent)

  useEffect(() => {
    if (!isZalo) {
      window.location.href = deepLink
    }
  }, [deepLink, isZalo])

  return (
    <div style={{ textAlign: 'center', padding: '40px', color: '#333' }}>
      <h2>🔄 Đang mở ứng dụng Tugo...</h2>
      {isZalo && (
        <p>Trình duyệt Zalo không hỗ trợ mở trực tiếp. Vui lòng mở bằng trình duyệt khác.</p>
      )}
      <p>Nếu bạn thấy thông báo này quá lâu, hãy nhấn nút bên dưới để mở thủ công:</p>
      <a
        href={deepLink}
        style={{
          display: 'inline-block',
          marginTop: 20,
          padding: '12px 24px',
          backgroundColor: '#1e88e5',
          color: '#fff',
          textDecoration: 'none',
          borderRadius: 6,
        }}
      >
        Mở ứng dụng Tugo
      </a>
    </div>
  )
}

export default RedirectApp
