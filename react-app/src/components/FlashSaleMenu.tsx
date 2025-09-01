import { type FC, useState } from 'react'
import styles from '../styles/flashsale-menu.module.css'

interface MenuItem {
  id: string
  name: string
}

const FlashSaleMenu: FC<{ items: MenuItem[] }> = ({ items }) => {
  const [open, setOpen] = useState(false)

  const scrollTo = (id: string) => {
    const el = document.getElementById(id)
    if (el) {
      el.scrollIntoView({ behavior: 'smooth', block: 'start' })
    }
  }

  const handleNavigate = (id: string) => {
    scrollTo(id)
    setOpen(false)
  }

  return (
    <div className={styles['flashsale-menu']}>
      <nav className={styles['desktop-menu']}>
        {items.map((item) => (
          <button key={item.id} onClick={() => handleNavigate(item.id)}>
            {item.name}
          </button>
        ))}
      </nav>

      <button
        className={styles['menu-toggle']}
        onClick={() => setOpen(!open)}
        aria-label={open ? 'Close menu' : 'Open menu'}
      >
        {open ? (
          <i className="ri-close-line"></i>
        ) : (
          <i className="ri-menu-line"></i>
        )}
      </button>

      <div
        className={`${styles['mobile-slide-menu']} ${open ? styles.active : ''}`}
      >
        <div className={styles['mobile-menu-items']}>
          {items.map((item) => (
            <button key={item.id} onClick={() => handleNavigate(item.id)}>
              {item.name}
            </button>
          ))}
        </div>
      </div>
    </div>
  )
}

export default FlashSaleMenu
