import { describe, it, expect } from 'vitest'
import { formatCurrency } from './formatCurrency'

describe('formatCurrency', () => {
  it('formats numbers for vi-VN locale with ₫ symbol', () => {
    expect(formatCurrency(1234567)).toBe('1.234.567₫')
  })
})
