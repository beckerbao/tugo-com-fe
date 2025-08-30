import apiClient from './apiClient'
import { ProfileData } from '../types'

export const getProfile = (
  token: string,
  params: { voucher: boolean; review: boolean } = {
    voucher: true,
    review: true,
  },
) => {
  const query = `?voucher=${params.voucher}&review=${params.review}`
  return apiClient
    .get<{ data: ProfileData }>(`/profile${query}`, {
      headers: { Authorization: `Bearer ${token}` },
    })
    .then((res) => res.data)
}

export const updateProfile = (
  token: string,
  data: {
    name: string
    phone: string
    password?: string
    profile_image?: File
  },
) => {
  const formData = new FormData()
  formData.append('name', data.name)
  formData.append('phone', data.phone)
  if (data.password) formData.append('password', data.password)
  if (data.profile_image) formData.append('profile_image', data.profile_image)
  return apiClient.postMultipart('/profile/update', formData, {
    headers: { Authorization: `Bearer ${token}` },
  })
}

export const logout = (token: string) =>
  apiClient.post(
    '/logout',
    {},
    { headers: { Authorization: `Bearer ${token}` } },
  )

export default { getProfile, updateProfile, logout }
