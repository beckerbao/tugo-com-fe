import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import apiClient from '../services/apiClient'
import FlashSaleMenu from '../components/FlashSaleMenu'
import styles from '../styles/flashsale.module.css'
import type { FlashSaleCollection } from '../types'

type HomeResponse = {
  data: {
    campaign_info: { end_time: string }
    collections: FlashSaleCollection[]
  }
}

const idMap: Record<string, string> = {
  'Tour Đài Loan': 'tour_dai_loan',
  'Tour Hàn Quốc': 'tour_han_quoc',
  'Tour Nhật Bản': 'tour_nhat_ban',
  'Tour Châu Á': 'tour_chau_a',
  'Tour Châu Úc': 'tour_uc',
  'Tour Trung Quốc': 'tour_trung_quoc',
  'Tour Châu Âu': 'tour_chau_au',
}

const getIdCodeByName = (name: string) =>
  idMap[name] || name.toLowerCase().replace(/\s+/g, '_')

const formatDate = (iso?: string) => {
  if (!iso) return ''
  const d = new Date(iso)
  return `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth() + 1)
    .toString()
    .padStart(2, '0')}`
}

const formatPrice = (price: number) => `${(price / 1_000_000).toFixed(1)}tr`

const Flashsale = () => {
  const [collections, setCollections] = useState<FlashSaleCollection[]>([])
  const [countdown, setCountdown] = useState('')

  useEffect(() => {
    const fetchData = async () => {
      const res = await apiClient.get<HomeResponse>(
        '/flashsale/homepage?campaign_id=3',
      )
      setCollections(res.data.collections || [])
      const end = res.data.campaign_info?.end_time
      if (end) {
        const update = () => {
          const diff = new Date(end).getTime() - Date.now()
          if (diff <= 0) {
            setCountdown('Flash Sale đã kết thúc')
          } else {
            const days = Math.floor(diff / 86400000)
            const hours = Math.floor((diff % 86400000) / 3600000)
            const minutes = Math.floor((diff % 3600000) / 60000)
            const seconds = Math.floor((diff % 60000) / 1000)
            setCountdown(
              `Còn ${days} ngày ${hours.toString().padStart(2, '0')}:${minutes
                .toString()
                .padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`,
            )
          }
        }
        update()
        const id = setInterval(update, 1000)
        return () => clearInterval(id)
      }
    }
    fetchData()
  }, [])

  const menuItems = collections.map((c) => ({
    id: getIdCodeByName(c.collection_name),
    name: c.collection_name,
  }))

  return (
    <div className={styles['flashsale-page']}>
      <h1>Flash Sale</h1>
      {countdown && <p className={styles.countdown}>{countdown}</p>}
      <FlashSaleMenu items={menuItems} />
      {collections.map((col) => (
        <section
          id={getIdCodeByName(col.collection_name)}
          key={col.collection_name}
        >
          <h2>{col.collection_name}</h2>
          <div className={styles.tours}>
            {col.tours.map((tour) => (
              <div key={tour.tour_id} className={styles['tour-card']}>
                <img src={tour.tour_image} alt={tour.tour_name} />
                <h3>{tour.tour_name}</h3>
                <div className={styles.departures}>
                  {tour.departures.slice(0, 4).map((dep) => (
                    <div
                      key={dep.date || dep.departure_date}
                      className={styles.departure}
                    >
                      <span>{formatDate(dep.date || dep.departure_date)}</span>
                      <span> - {formatPrice(dep.price)}</span>
                    </div>
                  ))}
                </div>
                <Link to={`/flashsale/${tour.tour_id}`}>Xem chi tiết</Link>
              </div>
            ))}
          </div>
        </section>
      ))}
    </div>
  )
}

export default Flashsale
