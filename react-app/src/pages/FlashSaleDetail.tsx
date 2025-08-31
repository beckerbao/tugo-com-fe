import { useEffect, useState } from 'react'
import { useParams } from 'react-router-dom'
import apiClient from '../services/apiClient'
import styles from '../styles/flashsale.module.css'
import type { FlashSaleDeparture } from '../types'

type DetailResponse = {
  data: {
    tour: { output_json: { data: { name: string } } }
    prices: FlashSaleDeparture[]
  }
}

const formatCurrency = (val: number) =>
  new Intl.NumberFormat('vi-VN').format(val) + '₫'

const FlashSaleDetail = () => {
  const { id } = useParams()
  const [tourName, setTourName] = useState('')
  const [prices, setPrices] = useState<FlashSaleDeparture[]>([])
  const [selected, setSelected] = useState<string>('')
  const [quantity, setQuantity] = useState(1)
  const [name, setName] = useState('')
  const [phone, setPhone] = useState('')
  const [loading, setLoading] = useState(false)

  useEffect(() => {
    const fetchData = async () => {
      if (!id) return
      const res = await apiClient.get<DetailResponse>(
        `/flashsale/tour-detail?tour_id=${id}&force_refresh=true`,
      )
      setTourName(res.data.tour.output_json.data.name)
      setPrices(res.data.prices || [])
      if (res.data.prices?.length) {
        setSelected(res.data.prices[0].departure_date)
      }
    }
    fetchData()
  }, [id])

  const selectedPrice =
    prices.find((p) => p.departure_date === selected)?.price || 0
  const total = selectedPrice * quantity

  const book = async () => {
    if (!id || !selected || !name || !phone) {
      alert('Vui lòng nhập đầy đủ thông tin')
      return
    }
    setLoading(true)
    try {
      await apiClient.post('/flashsale/booking', {
        tour_id: id,
        departure_date: selected,
        name,
        phone,
        quantity,
        price: selectedPrice,
      })
      alert('Đặt tour thành công!')
    } catch {
      alert('Không thể đặt tour')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className={styles['flashsale-page']}>
      <h1>{tourName}</h1>
      <div className={styles.departures}>
        {prices
          .filter((p) => p.available_slots > 0)
          .map((p) => (
            <button
              key={p.departure_date}
              className={
                styles.departure +
                (selected === p.departure_date ? ' ' + styles.selected : '')
              }
              onClick={() => setSelected(p.departure_date)}
            >
              {new Date(p.departure_date).toLocaleDateString('vi-VN')}
            </button>
          ))}
      </div>
      <div className={styles.quantity}>
        <button onClick={() => setQuantity((q) => Math.max(1, q - 1))}>
          -
        </button>
        <input
          type="number"
          value={quantity}
          min={1}
          onChange={(e) => setQuantity(parseInt(e.target.value) || 1)}
        />
        <button onClick={() => setQuantity((q) => q + 1)}>+</button>
      </div>
      <div>
        <input
          placeholder="Họ và tên"
          value={name}
          onChange={(e) => setName(e.target.value)}
        />
      </div>
      <div>
        <input
          placeholder="Số điện thoại"
          value={phone}
          onChange={(e) => setPhone(e.target.value)}
        />
      </div>
      <p>Tổng cộng: {formatCurrency(total)}</p>
      <button onClick={book} disabled={loading}>
        {loading ? 'Đang xử lý...' : 'Đặt ngay'}
      </button>
    </div>
  )
}

export default FlashSaleDetail
