const DOMAIN_URL = import.meta.env.VITE_DOMAIN_URL || ''

export const getImageUrl = (path?: string) =>
  path ? `${DOMAIN_URL}${path}` : ''

export const getTimeAgo = (date: string | number | Date): string => {
  const past = new Date(date)
  const diff = Date.now() - past.getTime()
  const seconds = Math.floor(diff / 1000)
  if (seconds < 60) return `${seconds} giây trước`
  const minutes = Math.floor(seconds / 60)
  if (minutes < 60) return `${minutes} phút trước`
  const hours = Math.floor(minutes / 60)
  if (hours < 24) return `${hours} giờ trước`
  const days = Math.floor(hours / 24)
  if (days < 30) return `${days} ngày trước`
  const months = Math.floor(days / 30)
  if (months < 12) return `${months} tháng trước`
  const years = Math.floor(months / 12)
  return `${years} năm trước`
}
