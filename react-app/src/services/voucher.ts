import apiClient from './apiClient'
import type { Voucher } from '../types'

export const getVoucher = (token: string, id: string) =>
  apiClient
    .get<{ data: Voucher }>(`/voucher/${id}`, {
      headers: { Authorization: `Bearer ${token}` },
    })
    .then((res) => res.data)

export default { getVoucher }
