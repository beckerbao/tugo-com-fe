import { useEffect, useState } from 'react'
import { useParams, useNavigate } from 'react-router-dom'
import { useAuth } from '../hooks/useAuth'
import voucherService from '../services/voucher'
import { getImageUrl } from '../services/helpers'
import { Voucher } from '../types'

const VoucherPage = () => {
  const { id } = useParams()
  const { token } = useAuth()
  const navigate = useNavigate()
  const [voucher, setVoucher] = useState<Voucher | null>(null)

  useEffect(() => {
    const fetchData = async () => {
      if (!token || !id) return
      const data = await voucherService.getVoucher(token, id)
      setVoucher(data)
    }
    fetchData()
  }, [token, id])

  if (!voucher) return <div>Loading...</div>

  const imgSrc = (path?: string) =>
    path?.startsWith('http') ? path : getImageUrl(path)

  return (
    <div className="voucher-container">
      <button onClick={() => navigate(-1)}>Back</button>
      <div className="voucher-header">
        <img src={imgSrc(voucher.qr_image)} alt="QR Code" />
      </div>
      <div className="voucher-details">
        <h2>{voucher.name}</h2>
        <p>
          <strong>Mã voucher:</strong> {voucher.code}
        </p>
        <p>
          <strong>Ngày hiệu lực:</strong> {voucher.valid_until}
        </p>
        <p>
          <strong>Trạng thái:</strong>{' '}
          <span className={voucher.status.toLowerCase()}>{voucher.status}</span>
        </p>
        <div className="policy-container">
          <h3>Điều kiện áp dụng</h3>
          <p>{voucher.policy}</p>
        </div>
      </div>
    </div>
  )
}

export default VoucherPage
