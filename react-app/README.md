# React + TypeScript + Vite

Dự án sử dụng Vite làm công cụ bundler cho ứng dụng React + TypeScript.

## Styling

Toàn bộ dự án sử dụng **CSS Modules**. Tất cả các tệp CSS được đặt trong thư mục
`src/styles` với hậu tố `.module.css` và được nhập vào component bằng cú pháp

```ts
import styles from '../styles/example.module.css'

return <div className={styles.wrapper}>Nội dung</div>
```

Điều này giúp cô lập phạm vi của lớp CSS và hỗ trợ khả năng responsive.

## ESLint

Chạy `npm run lint` để kiểm tra lint cho toàn dự án.
