import { useEffect, useState } from 'react'
import { useParams } from 'react-router-dom'
import apiClient from '../services/apiClient'
import styles from '../styles/flashsale-detail.module.css'
import type { FlashSaleDeparture } from '../types'
import { formatCurrency } from '../utils/formatCurrency'

type DetailResponse = {
  data: {
    tour: {
      collection_name?: string
      output_json: {
        data: {
          name: string
          duration?: string
          image?: string
        }
      }
    }
    prices: FlashSaleDeparture[]
    campaign_info?: { end_time?: string }
  }
}

const FlashSaleDetail = () => {
  const { id } = useParams()
  const [tourName, setTourName] = useState('')
  const [tourImage, setTourImage] = useState('')
  const [collection, setCollection] = useState('')
  const [duration, setDuration] = useState('')
  const [countdown, setCountdown] = useState('')
  const [prices, setPrices] = useState<FlashSaleDeparture[]>([])
  const [selected, setSelected] = useState<string | undefined>()
  const [quantity, setQuantity] = useState(1)
  const [name, setName] = useState('')
  const [phone, setPhone] = useState('')
  const [loading, setLoading] = useState(false)

  useEffect(() => {
    let timer: number | undefined
    const fetchData = async () => {
      if (!id) return
      const res = await apiClient.get<DetailResponse>(
        `/flashsale/tour-detail?tour_id=${id}&force_refresh=true`,
      )
      const tourData = res.data.tour.output_json.data
      setTourName(tourData.name)
      setTourImage(tourData.image || '')
      setDuration(tourData.duration || '')
      setCollection(res.data.tour.collection_name || '')
      setPrices(res.data.prices || [])
      if (res.data.prices && res.data.prices[0]?.departure_date) {
        setSelected(res.data.prices[0].departure_date)
      }
      const end = res.data.campaign_info?.end_time
      if (end) {
        const endTime = new Date(end).getTime()
        const update = () => {
          const diff = endTime - Date.now()
          if (diff <= 0) {
            setCountdown('Flash Sale đã kết thúc')
          } else {
            const days = Math.floor(diff / 86400000)
            const hours = Math.floor((diff % 86400000) / 3600000)
            const minutes = Math.floor((diff % 3600000) / 60000)
            const seconds = Math.floor((diff % 60000) / 1000)
            setCountdown(
              `Còn ${days} ngày ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')} là kết thúc`,
            )
          }
        }
        update()
        timer = window.setInterval(update, 1000)
      }
    }
    fetchData()
    return () => {
      if (timer) clearInterval(timer)
    }
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
    <div>
      <div className="relative h-96">
        {tourImage && (
          <img
            src={tourImage}
            alt={tourName}
            className="w-full h-full object-cover object-top"
          />
        )}
        <div className={styles.overlay} />
        <a href="/flashsale" className={styles['back-button']}>
          <i className="ri-arrow-left-line ri-lg mr-1" />
          <span>Quay lại</span>
        </a>
        <div className="absolute bottom-0 left-0 w-full p-6">
          <div className="max-w-7xl mx-auto">
            <div className="flex items-center">
              <h1 className="text-3xl font-bold text-white">{tourName}</h1>
            </div>
            <div className="mt-2 flex flex-wrap items-center gap-3 text-white">
              {collection && (
                <div className="flex items-center">
                  <i className="ri-map-pin-line mr-1" /> {collection}
                </div>
              )}
              {duration && (
                <div className="flex items-center">
                  <i className="ri-time-line mr-1" /> {duration}
                </div>
              )}
              {countdown && (
                <div
                  className={`${styles.countdown} inline-flex items-center px-3 py-1 bg-primary text-white text-sm font-medium rounded-full`}
                >
                  <i className="ri-flashlight-line mr-1" />
                  {countdown}
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
      <div className={styles.departures}>
        {prices
          .filter((p) => (p.available_slots ?? 0) > 0)
          .map((p) => (
            <button
              key={p.departure_date ?? ''}
              className={
                styles['date-option'] +
                (selected === p.departure_date ? ' ' + styles.selected : '')
              }
              onClick={() => p.departure_date && setSelected(p.departure_date)}
            >
              {new Date(p.departure_date!).toLocaleDateString('vi-VN')}
            </button>
          ))}
      </div>
      <div className={styles['quantity-selector']}>
        <button
          className={styles['quantity-button']}
          onClick={() => setQuantity((q) => Math.max(1, q - 1))}
        >
          -
        </button>
        <input
          className={styles['quantity-input']}
          type="number"
          value={quantity}
          min={1}
          onChange={(e) => setQuantity(parseInt(e.target.value) || 1)}
        />
        <button
          className={styles['quantity-button']}
          onClick={() => setQuantity((q) => q + 1)}
        >
          +
        </button>
      </div>
      <div className={styles['booking-form']}>
        <input
          className={styles['booking-input']}
          placeholder="Họ và tên"
          value={name}
          onChange={(e) => setName(e.target.value)}
        />
        <input
          className={styles['booking-input']}
          placeholder="Số điện thoại"
          value={phone}
          onChange={(e) => setPhone(e.target.value)}
        />
        <p>Tổng cộng: {formatCurrency(total)}</p>
        <button
          className={styles['booking-button']}
          onClick={book}
          disabled={loading}
        >
          {loading ? 'Đang xử lý...' : 'Đặt ngay'}
        </button>
      </div>
    </div>
  )
}

export default FlashSaleDetail
