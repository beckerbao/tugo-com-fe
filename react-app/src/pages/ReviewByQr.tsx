import { useSearchParams } from 'react-router-dom'
import ReviewForm from '../components/ReviewForm'

const ReviewByQr = () => {
  const [searchParams] = useSearchParams()
  const tourId = searchParams.get('tour_id') ?? undefined

  return (
    <div>
      <h1>Đánh giá tour</h1>
      <ReviewForm tourId={tourId} />
    </div>
  )
}

export default ReviewByQr
