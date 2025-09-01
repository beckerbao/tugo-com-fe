import { useEffect, useRef } from 'react'
import L from 'leaflet'
import styles from '../styles/itinerary-map.module.css'

type Location = {
  lat: number
  lng: number
  name: string
}

interface Props {
  locations: Location[]
}

const ItineraryMap = ({ locations }: Props) => {
  const mapRef = useRef<HTMLDivElement>(null)

  useEffect(() => {
    if (!mapRef.current || locations.length === 0) return

    const map = L.map(mapRef.current).setView(
      [locations[0].lat, locations[0].lng],
      5,
    )

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map)

    locations.forEach((loc) => {
      if (loc.lat && loc.lng) {
        L.marker([loc.lat, loc.lng]).addTo(map).bindPopup(loc.name)
      }
    })

    return () => {
      map.remove()
    }
  }, [locations])

  return <div ref={mapRef} className={styles['map-container']} />
}

export default ItineraryMap
