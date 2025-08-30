import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import apiClient from '../services/apiClient'

type Props = {
  tourId?: string
}

const ReviewForm = ({ tourId }: Props) => {
  const [content, setContent] = useState('')
  const [files, setFiles] = useState<FileList | null>(null)
  const navigate = useNavigate()

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    const formData = new FormData()
    formData.append('content', content)
    if (tourId) {
      formData.append('tour_id', tourId)
    }
    if (files) {
      Array.from(files).forEach((file) => {
        formData.append('images', file)
      })
    }
    try {
      await apiClient.postMultipart('/reviews', formData)
      navigate('/verify-otp', { state: { tourId } })
    } catch (err) {
      console.error(err)
    }
  }

  return (
    <form onSubmit={handleSubmit}>
      <textarea
        placeholder="Nội dung đánh giá"
        value={content}
        onChange={(e) => setContent(e.target.value)}
      />
      <input type="file" multiple onChange={(e) => setFiles(e.target.files)} />
      <button type="submit">Gửi đánh giá</button>
    </form>
  )
}

export default ReviewForm
