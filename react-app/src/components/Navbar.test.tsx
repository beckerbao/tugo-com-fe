import { render, screen } from '@testing-library/react'
import { MemoryRouter } from 'react-router-dom'
import Navbar from './Navbar'

describe('Navbar', () => {
  it('renders navigation links', () => {
    render(
      <MemoryRouter>
        <Navbar />
      </MemoryRouter>,
    )
    expect(screen.getByText('Trang chủ')).toBeInTheDocument()
  })
})
