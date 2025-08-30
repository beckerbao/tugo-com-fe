export interface User {
  name: string
  profile_image: string
}

export interface Post {
  id: number
  type: 'review' | 'general' | 'review_sale'
  user: User
  tour_name?: string | null
  start_date?: string | null
  guide_name?: string | null
  images?: string[]
  raw_content?: string
  content?: string
  likes: number
  created_at: string
}

export interface Statistics {
  users?: number
  posts?: number
}

export interface Voucher {
  id: number
  code: string
  name?: string | null
  valid_until?: string | null
  status: string
  qr_image?: string | null
  policy?: string | null
}

export interface Review {
  id: number
  tour_name?: string | null
  guide_name?: string | null
  content?: string | null
  created_at: string
}

export interface ProfileData {
  user: User & { phone?: string }
  vouchers: Voucher[]
  reviews: Review[]
}
