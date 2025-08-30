import { useCallback, useEffect, useRef, useState } from 'react'
import { Link, useSearchParams } from 'react-router-dom'
import apiClient from '../services/apiClient'
import { Post, Statistics } from '../types'
import PostReview from '../components/PostReview'
import PostGeneral from '../components/PostGeneral'
import PostReviewSale from '../components/PostReviewSale'

const PAGE_SIZE = 20

const Home = () => {
  const [stats, setStats] = useState<Statistics>({})
  const [posts, setPosts] = useState<Post[]>([])
  const [cursor, setCursor] = useState<string | null>(null)
  const [loading, setLoading] = useState(false)
  const [searchParams] = useSearchParams()
  const type = searchParams.get('type') ?? 'all'
  const loader = useRef<HTMLDivElement>(null)

  const fetchInitial = useCallback(async () => {
    setLoading(true)
    try {
      const statsRes = await apiClient.get<{ data: Statistics }>('/statistics')
      setStats(statsRes.data || {})
      const postsRes = await apiClient.get<{
        data: { posts: Post[]; cursor?: string }
      }>(
        `/posts?page_size=${PAGE_SIZE}&page=1&type=${
          type === 'all' ? '' : type
        }`,
      )
      setPosts(postsRes.data.posts || [])
      setCursor(postsRes.data.cursor || null)
    } catch (err) {
      console.error(err)
    } finally {
      setLoading(false)
    }
  }, [type])

  useEffect(() => {
    fetchInitial()
  }, [fetchInitial])

  const loadMore = useCallback(async () => {
    if (!cursor || loading) return
    setLoading(true)
    try {
      const res = await apiClient.get<{
        data: { posts: Post[]; cursor?: string }
      }>(`/posts?cursor=${cursor}&type=${type === 'all' ? '' : type}`)
      setPosts((prev) => [...prev, ...(res.data.posts || [])])
      setCursor(res.data.cursor || null)
    } catch (err) {
      console.error(err)
    } finally {
      setLoading(false)
    }
  }, [cursor, type, loading])

  useEffect(() => {
    const el = loader.current
    if (!el) return
    const observer = new IntersectionObserver((entries) => {
      if (entries[0].isIntersecting) {
        loadMore()
      }
    })
    observer.observe(el)
    return () => observer.disconnect()
  }, [loadMore])

  const filters = [
    { value: 'all', label: 'Tất cả' },
    { value: 'review', label: 'Đánh giá tour' },
    { value: 'review_sale', label: 'Đánh giá tư vấn' },
    { value: 'general', label: 'Giới thiệu tour' },
  ]

  return (
    <>
      <div className="statistics">
        <div className="stat">
          <div className="value">{stats.users ?? 0}+</div>
          <div className="label">Thành viên</div>
        </div>
        <div className="stat">
          <div className="value">{stats.posts ?? 0}+</div>
          <div className="label">Bài viết</div>
        </div>
      </div>
      <div className="filter-container">
        {filters.map((f) => (
          <Link
            key={f.value}
            to={`/?type=${f.value}`}
            className={type === f.value ? 'active' : ''}
          >
            {f.label}
          </Link>
        ))}
      </div>
      <div className="feed" id="feed">
        {posts.map((post) => {
          switch (post.type) {
            case 'review':
              return <PostReview key={post.id} post={post} />
            case 'general':
              return <PostGeneral key={post.id} post={post} />
            case 'review_sale':
              return <PostReviewSale key={post.id} post={post} />
            default:
              return null
          }
        })}
      </div>
      {cursor && <div ref={loader} />}
      {loading && <p style={{ textAlign: 'center' }}>Loading...</p>}
    </>
  )
}

export default Home
