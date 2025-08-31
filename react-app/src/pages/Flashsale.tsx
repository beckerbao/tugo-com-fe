import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import apiClient from '../services/apiClient'
import FlashSaleMenu from '../components/FlashSaleMenu'
import styles from '../styles/flashsale-home.module.css'
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

const flagMap: Record<string, string> = {
  'Tour Đài Loan': '🇹🇼',
  'Tour Hàn Quốc': '🇰🇷',
  'Tour Nhật Bản': '🇯🇵',
  'Tour Châu Âu': '🇪🇺',
  'Tour Châu Úc': '🇦🇺',
  'Tour Trung Quốc': '🇨🇳',
}

const getCountryFlag = (name: string) => flagMap[name] || ''

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
    <div>
      <section className={styles['hero-section']}>
        <div className={styles['hero-container']}>
          <div className={styles['hero-content']}>
            <h1>Flash Sale - Ngày Đẹp Đôi – Tour Giá Xinh 8.8</h1>
            <p className={styles.tagline}>Bùng Nổ 8.8 – Bay Ngay Giá Sốc</p>
            {countdown && (
              <p className={styles.countdown}>
                <i className="ri-time-line ri-lg mr-2" />
                {countdown}
              </p>
            )}
          </div>
        </div>
      </section>
      <FlashSaleMenu items={menuItems} />
      {collections.map((col) => (
        <section
          id={getIdCodeByName(col.collection_name)}
          key={col.collection_name}
        >
          <div className={styles['collection-card']}>
            <img src={col.collection_image} alt={col.collection_name} />
            <div className={styles['collection-overlay']} />
            <div className={styles['collection-title']}>
              <h2>
                <span className={styles['flag-icon']}>
                  {getCountryFlag(col.collection_name)}
                </span>
                {col.collection_name}
              </h2>
            </div>
          </div>
          <div className={styles['tours-grid']}>
            {col.tours.map((tour) => (
              <div key={tour.tour_id} className={styles['tour-card']}>
                <img src={tour.tour_image} alt={tour.tour_name} />
                <h3>{tour.tour_name}</h3>
                <div>
                  {tour.departures.slice(0, 4).map((dep) => (
                    <div
                      key={dep.date || dep.departure_date}
                      className={styles['date-pill']}
                    >
                      <span>{formatDate(dep.date || dep.departure_date)}</span>
                      <span>-</span>
                      <span>{formatPrice(dep.price)}</span>
                    </div>
                  ))}
                </div>
                <Link
                  to={`/flashsale/${tour.tour_id}`}
                  className="bg-primary text-white rounded flex items-center justify-center"
                >
                  Xem chi tiết <i className="ri-arrow-right-line ri-lg ml-1" />
                </Link>
              </div>
            ))}
          </div>
        </section>
      ))}
    </div>
  )
}

export default Flashsale
