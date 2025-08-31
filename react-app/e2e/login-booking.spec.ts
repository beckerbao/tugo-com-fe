import { test, expect } from '@playwright/test'

test('user can log in and book tour', async ({ page }) => {
  await page.route('**/login', (route) => {
    route.fulfill({
      status: 200,
      body: JSON.stringify({ token: 'fake-token' }),
      headers: { 'Content-Type': 'application/json' },
    })
  })

  await page.route('**/flashsale/tour-detail?tour_id=1&force_refresh=true', (route) => {
    route.fulfill({
      status: 200,
      body: JSON.stringify({
        data: {
          tour: { output_json: { data: { name: 'Tour 1' } } },
          prices: [
            { departure_date: '2025-01-01', price: 1000, available_slots: 10 },
          ],
        },
      }),
      headers: { 'Content-Type': 'application/json' },
    })
  })

  await page.route('**/flashsale/booking', (route) => {
    route.fulfill({ status: 200, body: '{}' })
  })

  await page.goto('/login')
  await page.fill('input[placeholder="Số điện thoại"]', '0123456789')
  await page.fill('input[placeholder="Mật khẩu"]', 'password')
  await page.click('button[type="submit"]')

  await page.goto('/flashsale/1')
  await page.fill('input[placeholder="Họ và tên"]', 'Test User')
  await page.fill('input[placeholder="Số điện thoại"]', '0123456789')

  const dialogPromise = page.waitForEvent('dialog')
  await page.click('text=Đặt ngay')
  const dialog = await dialogPromise
  expect(dialog.message()).toBe('Đặt tour thành công!')
  await dialog.dismiss()
})
