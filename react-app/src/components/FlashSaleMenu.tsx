import { FC } from 'react'

interface MenuItem {
  id: string
  name: string
}

const FlashSaleMenu: FC<{ items: MenuItem[] }> = ({ items }) => {
  const scrollTo = (id: string) => {
    const el = document.getElementById(id)
    if (el) {
      el.scrollIntoView({ behavior: 'smooth', block: 'start' })
    }
  }

  return (
    <div className="flashsale-menu">
      {items.map((item) => (
        <button key={item.id} onClick={() => scrollTo(item.id)}>
          {item.name}
        </button>
      ))}
    </div>
  )
}

export default FlashSaleMenu
