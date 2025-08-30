const BASE_URL = import.meta.env.VITE_BASE_URL || ''

type RequestOptions = RequestInit & { baseUrl?: string }

const request = async <T>(
  path: string,
  options: RequestOptions = {},
): Promise<T> => {
  const url = `${options.baseUrl ?? BASE_URL}${path}`
  const res = await fetch(url, options)
  if (!res.ok) {
    throw new Error(await res.text())
  }
  return res.json()
}

const get = <T>(path: string, options?: RequestOptions) =>
  request<T>(path, { ...options, method: 'GET' })

const post = <T>(path: string, data: unknown, options?: RequestOptions) =>
  request<T>(path, {
    ...options,
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      ...(options?.headers || {}),
    },
    body: JSON.stringify(data),
  })

const put = <T>(path: string, data: unknown, options?: RequestOptions) =>
  request<T>(path, {
    ...options,
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      ...(options?.headers || {}),
    },
    body: JSON.stringify(data),
  })

const del = <T>(path: string, options?: RequestOptions) =>
  request<T>(path, { ...options, method: 'DELETE' })

const postMultipart = <T>(
  path: string,
  data: FormData,
  options?: RequestOptions,
) => request<T>(path, { ...options, method: 'POST', body: data })

export default { get, post, put, del, postMultipart }
