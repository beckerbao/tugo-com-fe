/* @vitest-environment jsdom */
import { describe, it, expect } from 'vitest'
import { sanitizeHtml } from './sanitizeHtml'

describe('sanitizeHtml', () => {
  it('removes script tags', () => {
    const dirty = '<p>Hello</p><script>alert("x")</script>'
    expect(sanitizeHtml(dirty)).toBe('<p>Hello</p>')
  })

  it('strips dangerous attributes', () => {
    const dirty = '<img src="x" onerror="alert(1)">'
    const cleaned = sanitizeHtml(dirty)
    expect(cleaned).toContain('src="x"')
    expect(cleaned).not.toContain('onerror')
  })
})
