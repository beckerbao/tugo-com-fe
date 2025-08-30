import { useState } from 'react'
import { useLocation } from 'react-router-dom'
import '../styles/genqr.css'

interface GenQrProps {
  variant?: 'vngroup'
}

const GenQr = ({ variant }: GenQrProps) => {
  const location = useLocation()
  const isVnGroup =
    variant === 'vngroup' || location.pathname.includes('genqr-vngroup')
  const [tourName, setTourName] = useState('')
  const [startDate, setStartDate] = useState('')
  const [endDate, setEndDate] = useState('')
  const [guideName, setGuideName] = useState('')
  const [qrUrl, setQrUrl] = useState('')

  const primaryColor = isVnGroup ? '#007BFF' : '#660066'

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    const baseUrl = `${window.location.origin}/${isVnGroup ? 'reviewbyqr-vngroup' : 'reviewbyqr'}`
    const params = new URLSearchParams({
      tour_name: tourName,
      start_date: startDate,
      end_date: endDate,
      guide_name: guideName,
    })
    setQrUrl(`${baseUrl}?${params.toString()}`)
  }

  return (
    <div className="genqr-container">
      <h1 style={{ color: primaryColor }}>Generate QR Code</h1>
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label htmlFor="tour_name">Tour Name</label>
          <input
            type="text"
            id="tour_name"
            name="tour_name"
            value={tourName}
            onChange={(e) => setTourName(e.target.value)}
            required
          />
        </div>
        <div className="form-group">
          <label htmlFor="start_date">Start Date</label>
          <input
            type="date"
            id="start_date"
            name="start_date"
            value={startDate}
            onChange={(e) => setStartDate(e.target.value)}
            required
          />
        </div>
        <div className="form-group">
          <label htmlFor="end_date">End Date</label>
          <input
            type="date"
            id="end_date"
            name="end_date"
            value={endDate}
            onChange={(e) => setEndDate(e.target.value)}
            required
          />
        </div>
        <div className="form-group">
          <label htmlFor="guide_name">Guide Name</label>
          <input
            type="text"
            id="guide_name"
            name="guide_name"
            value={guideName}
            onChange={(e) => setGuideName(e.target.value)}
            required
          />
        </div>
        <button
          type="submit"
          className="submit-button"
          style={{ backgroundColor: primaryColor }}
        >
          Generate QR Code
        </button>
      </form>
      {qrUrl && (
        <div className="qr-code-section">
          <h2>QR Code</h2>
          <p>Scan this QR code to review the tour:</p>
          <img
            src={`https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(qrUrl)}`}
            alt="QR Code"
          />
          <p>
            <strong>URL:</strong>{' '}
            <a
              href={qrUrl}
              target="_blank"
              rel="noopener noreferrer"
              style={{ color: primaryColor }}
            >
              {qrUrl}
            </a>
          </p>
        </div>
      )}
    </div>
  )
}

export default GenQr
