import { useEffect, useRef, useState } from 'react'
import type { Post } from '../types'
import { getImageUrl, getTimeAgo } from '../services/helpers'

const displayRatingStars = (rating: number) => {
  const max = 10
  return (
    <>
      {Array.from({ length: max }, (_, i) => (
        <span key={i} style={{ color: i < rating ? 'gold' : '#ccc' }}>
          {i < rating ? '★' : '☆'}
        </span>
      ))}
    </>
  )
}

const PostReviewSale = ({ post }: { post: Post }) => {
  const [expanded, setExpanded] = useState(false)
  const [showToggle, setShowToggle] = useState(false)
  const contentRef = useRef<HTMLDivElement>(null)

  useEffect(() => {
    const el = contentRef.current
    if (el && el.scrollHeight > el.offsetHeight) {
      setShowToggle(true)
    }
  }, [])

  const profileImage = post.user.profile_image.startsWith('http')
    ? post.user.profile_image
    : getImageUrl(post.user.profile_image)

  const images = post.images || []

  type Decoded = { rating?: number; review?: string; booking_id?: string }
  let decoded: Decoded = {}
  try {
    decoded = post.raw_content ? JSON.parse(post.raw_content) : {}
  } catch {
    decoded = {}
  }

  const rating = typeof decoded.rating === 'number' ? decoded.rating : 10
  const review = decoded.review || ''
  const bookingId = decoded.booking_id
  const ratingDisplay = displayRatingStars(rating)
  const createdDate = post.created_at
    ? new Date(post.created_at).toLocaleDateString('vi-VN')
    : ''

  return (
    <div className="post">
      <div className="user">
        <img src={profileImage} alt="User Profile" />
        <div className="name">{post.user.name}</div>
      </div>
      <div className="meta">
        <i>🆔</i> <span>Mã booking: {bookingId}</span>
      </div>
      {post.tour_name && (
        <div className="meta">
          <i>🗺️</i> <span>Tên tour: {post.tour_name}</span> <span>|</span>{' '}
          <span>Ngày đánh giá: {createdDate}</span>
        </div>
      )}
      {post.guide_name && (
        <>
          <div className="meta">
            <i>🧑‍🏫</i> <span>Nhân viên Sale: {post.guide_name}</span>
          </div>
          <div className="meta">
            <i>🌟</i> <span>Rating: {ratingDisplay}</span> | ({rating}/10)
          </div>
          <div className="content-wrapper">
            <div
              className="content"
              id={`content-${post.id}`}
              ref={contentRef}
              style={{ display: expanded ? 'block' : '-webkit-box' }}
            >
              <i>📝</i> Nội dung đánh giá: {review}
            </div>
            {showToggle && (
              <a
                href="#"
                className="toggle-content"
                onClick={(e) => {
                  e.preventDefault()
                  setExpanded((p) => !p)
                }}
                id={`toggle-${post.id}`}
              >
                {expanded ? 'Show Less' : 'Show More'}
              </a>
            )}
          </div>
        </>
      )}
      {images.map((img, idx) => {
        const src = img.startsWith('http') ? img : getImageUrl(img)
        return <img key={idx} className="image" src={src} alt="Tour Image" />
      })}
      <div className="time">Đăng: {getTimeAgo(post.created_at)}</div>
      <div className="likes">{post.likes} likes</div>
    </div>
  )
}

export default PostReviewSale
