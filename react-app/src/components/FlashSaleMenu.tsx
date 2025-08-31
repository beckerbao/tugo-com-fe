import type { FC } from 'react'
import styles from '../styles/flashsale-menu.module.css'

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
    <div className={styles['flashsale-menu']}>
      {items.map((item) => (
        <button key={item.id} onClick={() => scrollTo(item.id)}>
          {item.name}
        </button>
      ))}
    </div>
  )
}

export default FlashSaleMenu
