import { useEffect, useRef, useState } from 'react'
import { useParams } from 'react-router-dom'
import { Helmet } from 'react-helmet'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import apiClient from '../services/apiClient'
import ItineraryMap from '../components/ItineraryMap'
import styles from '../styles/flashsale-detail.module.css'
import mapStyles from '../styles/itinerary-map.module.css'
import type { FlashSaleDeparture } from '../types'
import { formatCurrency } from '../utils/formatCurrency'
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png'
import markerIcon from 'leaflet/dist/images/marker-icon.png'
import markerShadow from 'leaflet/dist/images/marker-shadow.png'

delete (L.Icon.Default.prototype as unknown as Record<string, unknown>)
  ._getIconUrl
L.Icon.Default.mergeOptions({
  iconRetinaUrl: markerIcon2x,
  iconUrl: markerIcon,
  shadowUrl: markerShadow,
})

type DetailResponse = {
  data: {
    tour: {
      collection_name?: string
      summary?: string
      output_json: {
        data: {
          name: string
          duration?: string
          image?: string
          highlights?: { title?: string; description: string }[]
          itinerary?: {
            day: string
            title: string
            description: string
          }[]
          photo_gallery?: { image: string }[]
          whats_included?: { description: string }[]
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
  const [designing, setDesigning] = useState(false)
  const [tourSummary, setTourSummary] = useState('')
  const [tourHighlights, setTourHighlights] = useState<
    { description: string; title?: string }[]
  >([])
  const [tourItinerary, setTourItinerary] = useState<
    { day: string; title: string; description: string }[]
  >([])
  const [tourGallery, setTourGallery] = useState<{ image: string }[]>([])
  const [tourServices, setTourServices] = useState<{ description: string }[]>(
    [],
  )
  const [locations, setLocations] = useState<
    { lat: number; lng: number; name: string }[]
  >([])
  const [mapOpen, setMapOpen] = useState(false)

  const overviewRef = useRef<HTMLElement>(null)
  const bookingRef = useRef<HTMLElement>(null)
  const itineraryRef = useRef<HTMLElement>(null)
  const attractionsRef = useRef<HTMLElement>(null)
  const servicesRef = useRef<HTMLElement>(null)
  const [activeTab, setActiveTab] = useState('overview')

  const sectionRefs: Record<string, React.RefObject<HTMLElement>> = {
    overview: overviewRef,
    booking: bookingRef,
    itinerary: itineraryRef,
    attractions: attractionsRef,
    includes: servicesRef,
  }

  const scrollToSection = (key: keyof typeof sectionRefs) => {
    const ref = sectionRefs[key]
    if (ref.current) {
      const top = ref.current.getBoundingClientRect().top + window.scrollY - 80
      window.scrollTo({ top, behavior: 'smooth' })
    }
  }

  const handleTabClick = (key: keyof typeof sectionRefs) => {
    setActiveTab(key)
    scrollToSection(key)
  }

  useEffect(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const id = entry.target.id.replace(
              'tab-',
              '',
            ) as keyof typeof sectionRefs
            setActiveTab(id)
          }
        })
      },
      { rootMargin: '-120px 0px -80% 0px', threshold: 0 },
    )
    Object.values(sectionRefs).forEach((ref) => {
      if (ref.current) observer.observe(ref.current)
    })
    return () => {
      Object.values(sectionRefs).forEach((ref) => {
        if (ref.current) observer.unobserve(ref.current)
      })
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])

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
      setTourSummary(res.data.tour.summary || '')
      setTourHighlights(tourData.highlights || [])
      setTourItinerary(tourData.itinerary || [])
      setTourGallery(tourData.photo_gallery || [])
      setTourServices(tourData.whats_included || [])
      const locRes = await apiClient.get<{
        data: {
          locations: {
            latitude: number
            longitude: number
            name: string
          }[]
        }
      }>(`/flashsale/tours/locations?tour_id=${id}`)
      setLocations(
        (locRes.data.locations || []).map((loc) => ({
          lat: Number(loc.latitude),
          lng: Number(loc.longitude),
          name: loc.name,
        })),
      )
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
              `Còn ${days} ngày ${hours.toString().padStart(2, '0')}:${minutes
                .toString()
                .padStart(2, '0')}:${seconds
                .toString()
                .padStart(2, '0')} là kết thúc`,
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
  const selectedDeparture = prices.find((p) => p.departure_date === selected)
  const strikePrice = Math.ceil(selectedPrice * 1.15)
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

  const designTour = async () => {
    if (!id) return
    setDesigning(true)
    try {
      const res = await apiClient.post<{
        status: string
        data?: { custom_tour_id?: string }
      }>('/api/v1/custom-tours/from-app-tour', {
        tour_id: id,
        departure_date: selected,
        expected_guests: quantity,
      })
      if (res.status === 'success' && res.data?.custom_tour_id) {
        window.location.href = `https://customtour.tugo.com.vn/?id=${res.data.custom_tour_id}`
      } else {
        alert('Đã có lỗi xảy ra')
      }
    } catch {
      alert('Không thể gửi yêu cầu')
    } finally {
      setDesigning(false)
    }
  }

  return (
    <>
      <Helmet>
        <title>{`${tourName} | Tugo`}</title>
        <meta property="og:title" content={`${tourName} | Tugo`} />
        {tourSummary && (
          <meta property="og:description" content={tourSummary} />
        )}
        {tourImage && <meta property="og:image" content={tourImage} />}
        <meta
          property="og:url"
          content={typeof window !== 'undefined' ? window.location.href : ''}
        />
        <meta name="twitter:title" content={`${tourName} | Tugo`} />
        {tourSummary && (
          <meta name="twitter:description" content={tourSummary} />
        )}
        {tourImage && <meta name="twitter:image" content={tourImage} />}
        <meta
          name="twitter:url"
          content={typeof window !== 'undefined' ? window.location.href : ''}
        />
      </Helmet>
      <div className={`relative ${styles['hero-banner']}`}>
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

      <section className={styles.section}>
        <div className="max-w-7xl mx-auto">
          <div className="bg-white rounded-lg shadow-sm sticky top-16 z-40">
            <div className="flex overflow-x-auto scrollbar-hide">
              <button
                onClick={() => handleTabClick('overview')}
                className={`flex-1 px-4 py-3 text-center font-medium border-b-2 whitespace-nowrap ${activeTab === 'overview' ? styles['tab-active'] : 'text-gray-500 border-transparent'}`}
              >
                Tổng quan
              </button>
              <button
                onClick={() => handleTabClick('booking')}
                className={`flex-1 px-4 py-3 text-center font-medium border-b-2 whitespace-nowrap ${activeTab === 'booking' ? styles['tab-active'] : 'text-gray-500 border-transparent'}`}
              >
                Đặt tour
              </button>
              <button
                onClick={() => handleTabClick('itinerary')}
                className={`flex-1 px-4 py-3 text-center font-medium border-b-2 whitespace-nowrap ${activeTab === 'itinerary' ? styles['tab-active'] : 'text-gray-500 border-transparent'}`}
              >
                Lịch trình
              </button>
              <button
                onClick={() => handleTabClick('attractions')}
                className={`flex-1 px-4 py-3 text-center font-medium border-b-2 whitespace-nowrap ${activeTab === 'attractions' ? styles['tab-active'] : 'text-gray-500 border-transparent'}`}
              >
                Điểm tham quan
              </button>
              <button
                onClick={() => handleTabClick('includes')}
                className={`flex-1 px-4 py-3 text-center font-medium border-b-2 whitespace-nowrap ${activeTab === 'includes' ? styles['tab-active'] : 'text-gray-500 border-transparent'}`}
              >
                Dịch vụ bao gồm
              </button>
            </div>
          </div>
        </div>
      </section>

      <section id="tab-overview" ref={overviewRef} className={styles.section}>
        <div className="max-w-7xl mx-auto">
          <div className="bg-white rounded-lg shadow-sm p-6">
            <h2 className="text-xl font-bold text-gray-900 mb-4">
              Tổng quan về tour
            </h2>
            <div dangerouslySetInnerHTML={{ __html: tourSummary }} />
          </div>
        </div>
      </section>

      <section id="tab-booking" ref={bookingRef} className={styles.section}>
        <div className="max-w-7xl mx-auto">
          <div className="p-6 rounded-lg border shadow-sm bg-white">
            <div className="flex flex-wrap gap-4 text-sm">
              <div className="flex items-center">
                <i className="ri-calendar-line text-primary mr-2" /> Khởi hành:
                <strong className="ml-1">
                  {selected
                    ? new Date(selected).toLocaleDateString('vi-VN')
                    : ''}
                </strong>
              </div>
              <div className="flex items-center">
                <i className="ri-group-line text-primary mr-2" /> Số chỗ còn
                nhận:
                <strong className="ml-1">
                  {selectedDeparture?.available_slots ?? 0}
                </strong>
              </div>
            </div>
            <div className="mt-6">
              <span className="text-gray-500 line-through text-lg">
                {formatCurrency(strikePrice)}
              </span>
              <div className="text-2xl font-bold text-primary">
                {formatCurrency(selectedPrice)}
              </div>
              <div className="mt-2">
                <button
                  id="customTourBtn"
                  className="bg-primary text-white px-5 py-2 rounded font-bold"
                  onClick={designTour}
                  disabled={designing}
                >
                  Thiết kế tour riêng
                </button>
              </div>
            </div>
            <div className="mt-4">
              <div className={`${styles.departures} mt-2`}>
                {prices
                  .filter((p) => p.available_slots > 0)
                  .map((p) => (
                    <button
                      key={p.departure_date ?? ''}
                      className={
                        styles['date-option'] +
                        (selected === p.departure_date
                          ? ' ' + styles.selected
                          : '')
                      }
                      onClick={() =>
                        p.departure_date && setSelected(p.departure_date)
                      }
                    >
                      <div>
                        <div className="font-medium">
                          {new Date(p.departure_date!).toLocaleDateString(
                            'vi-VN',
                          )}
                        </div>
                        <div className="text-sm text-gray-500">
                          Còn {p.available_slots} chỗ
                        </div>
                        {p.short_title && (
                          <div className="text-xs text-red-600">
                            ({p.short_title})
                          </div>
                        )}
                      </div>
                      <div className="text-right">
                        {p.is_flash_sale && (
                          <span className="text-gray-500 line-through text-sm">
                            {formatCurrency(Math.ceil(p.price * 1.15))}
                          </span>
                        )}
                        <div className="text-primary font-bold">
                          {formatCurrency(p.price)}
                        </div>
                      </div>
                    </button>
                  ))}
              </div>
              <div className={styles['quantity-selector']}>
                <button
                  className={styles['quantity-button']}
                  onClick={() => setQuantity((q) => Math.max(1, q - 1))}
                  aria-label="Decrease quantity"
                >
                  <i className="ri-subtract-line"></i>
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
                  aria-label="Increase quantity"
                >
                  <i className="ri-add-line"></i>
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
              </div>
              <div className={styles['price-summary']}>
                <p id="subtotal-text" data-base={selectedPrice}>
                  {formatCurrency(selectedPrice)} x{' '}
                  <span id="current-qty">{quantity}</span>
                </p>
                <p id="total-price" className={styles['booking-total']}>
                  {formatCurrency(total)}
                </p>
              </div>
              <p className="text-xs text-gray-500 mt-1">
                Giá/khách (đã bao gồm thuế VAT)
              </p>
              <p className="text-xs text-gray-500">Giá chưa bao gồm TIP</p>
              <button
                className={`${styles['booking-button']} w-full mt-4`}
                onClick={book}
                disabled={loading}
              >
                {loading && (
                  <svg
                    className="animate-spin h-5 w-5 mr-2 text-white"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <circle
                      className="opacity-25"
                      cx="12"
                      cy="12"
                      r="10"
                      stroke="currentColor"
                      strokeWidth="4"
                    />
                    <path
                      className="opacity-75"
                      fill="currentColor"
                      d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                    />
                  </svg>
                )}
                {loading ? 'Đang xử lý...' : 'Đặt ngay'}
              </button>
            </div>
          </div>
        </div>
      </section>

      <section id="tab-itinerary" ref={itineraryRef} className={styles.section}>
        <div className="max-w-7xl mx-auto">
          <div className="bg-white rounded-lg shadow-sm p-6">
            <div className="flex justify-between items-center mb-4">
              <h2 className="text-xl font-bold text-gray-900">
                Lịch trình chi tiết
              </h2>
              {locations.length > 0 && (
                <button
                  onClick={() => setMapOpen(true)}
                  className="px-4 py-2 bg-purple-700 text-white rounded hover:bg-purple-800 text-sm md:text-base"
                >
                  Bản đồ hành trình
                </button>
              )}
            </div>
            <p className="text-sm italic text-red-500 mb-4">
              (tuỳ theo ngày khởi hành có thể sẽ khác nhau)
            </p>
            {mapOpen && (
              <div
                className={mapStyles['map-overlay']}
                onClick={(e) => {
                  if (e.target === e.currentTarget) setMapOpen(false)
                }}
              >
                <div className={mapStyles['map-inner']}>
                  <button
                    className={mapStyles['close-button']}
                    onClick={() => setMapOpen(false)}
                  >
                    ×
                  </button>
                  <ItineraryMap locations={locations} />
                </div>
              </div>
            )}
            {tourItinerary.map((day, i) => (
              <div key={i} className="relative pl-10 pb-8">
                <div className={styles['timeline-dot']} />
                {i < tourItinerary.length - 1 && (
                  <div className={styles['timeline-line']} />
                )}
                <h3 className="font-bold text-gray-900 mb-2">
                  {`${day.day}: ${day.title}`}
                </h3>
                <div className="bg-gray-50 p-4 rounded-lg">
                  <p className="text-gray-700 whitespace-pre-line">
                    {day.description}
                  </p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      <section
        id="tab-attractions"
        ref={attractionsRef}
        className={styles.section}
      >
        <div className="max-w-7xl mx-auto">
          <div className="bg-white rounded-lg shadow-sm p-6">
            <h2 className="text-xl font-bold text-gray-900 mb-4">
              Điểm nổi bật của tour
            </h2>
            <ul className="space-y-2 text-gray-800">
              {tourHighlights.map((hl, idx) => (
                <li key={idx} className="flex items-start gap-2">
                  <i className="ri-checkbox-circle-line text-[#660066] mt-1" />
                  <span>{hl.description}</span>
                </li>
              ))}
            </ul>
          </div>
        </div>
      </section>

      <section className={styles.section}>
        <div className="max-w-7xl mx-auto">
          <div className="bg-white rounded-lg shadow-sm p-6">
            <h2 className="text-xl font-bold text-gray-900 mb-4">
              Thư viện hình ảnh
            </h2>
            <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
              {tourGallery.map((photo, idx) => (
                <div key={idx} className="overflow-hidden rounded-lg shadow-sm">
                  <img
                    src={photo.image}
                    alt="Gallery"
                    className={`w-full h-40 object-cover ${styles['gallery-img']}`}
                  />
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      <section id="tab-includes" ref={servicesRef} className={styles.section}>
        <div className="max-w-7xl mx-auto">
          <div className="bg-white rounded-lg shadow-sm p-6">
            <h2 className="text-xl font-bold text-gray-900 mb-4">
              Dịch vụ bao gồm
            </h2>
            <ul className="space-y-2 text-gray-800">
              {tourServices.map((svc, idx) => (
                <li key={idx} className="flex items-start gap-2">
                  <i className="ri-check-line text-green-600 mt-1" />
                  <span>{svc.description}</span>
                </li>
              ))}
            </ul>
          </div>
        </div>
      </section>

      {designing && (
        <div id="loadingOverlay">
          <div id="loadingContent">
            <div id="loadingIcon" />
            <div className="mt-2 text-white font-bold">
              Hệ thống đang xử lý...
            </div>
          </div>
        </div>
      )}
    </>
  )
}

export default FlashSaleDetail
