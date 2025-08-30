import { useEffect, useRef, useState } from 'react'
import { Post } from '../types'
import { getImageUrl, getTimeAgo } from '../services/helpers'

const PostReview = ({ post }: { post: Post }) => {
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
  const formattedDate = post.start_date
    ? new Date(post.start_date).toLocaleDateString('vi-VN')
    : ''

  return (
    <div className="post">
      <div className="user">
        <img src={profileImage} alt="User Profile" />
        <div className="name">{post.user.name}</div>
      </div>
      {post.tour_name && (
        <div className="meta">
          <i>🗺️</i> <span>Tên tour: {post.tour_name}</span>
          {post.start_date && (
            <>
              {' '}
              <span>|</span> <span>Ngày khởi hành: {formattedDate}</span>
            </>
          )}
        </div>
      )}
      {post.guide_name && (
        <div className="meta">
          <i>🧑‍🏫</i> <span>Hướng dẫn: {post.guide_name}</span>
        </div>
      )}
      {images.map((img, idx) => {
        const src = img.startsWith('http') ? img : getImageUrl(img)
        return <img key={idx} className="image" src={src} alt="Tour Image" />
      })}
      <div className="content-wrapper">
        <div
          className="content"
          id={`content-${post.id}`}
          ref={contentRef}
          style={{ display: expanded ? 'block' : '-webkit-box' }}
          dangerouslySetInnerHTML={{ __html: post.raw_content || '' }}
        />
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
      <div className="time">Đăng: {getTimeAgo(post.created_at)}</div>
      <div className="likes">{post.likes} likes</div>
    </div>
  )
}

export default PostReview
