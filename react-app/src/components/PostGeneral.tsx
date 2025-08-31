import { useEffect, useRef, useState } from 'react'
import type { Post } from '../types'
import { getImageUrl, getTimeAgo } from '../services/helpers'

const PostGeneral = ({ post }: { post: Post }) => {
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

  return (
    <div className="post">
      <div className="user">
        <img src={profileImage} alt="User Profile" />
        <div className="name">{post.user.name}</div>
      </div>
      <div className="meta">
        <i>🗺️</i> <span>Tiêu đề: {post.tour_name}</span>
      </div>
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
          dangerouslySetInnerHTML={{ __html: post.content || '' }}
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

export default PostGeneral
