# React Migration Requirements

## 1. Project setup
- Use a modern React scaffold (e.g., Vite or Create React App).
- Implement environment handling (`.env`) for API base URLs (`BASE_URL`, `DOMAIN_URL`).
- Configure absolute imports and linting consistent with current naming conventions.

## 2. Routing & layout
- Use React Router to map existing PHP routes to React pages:
  - `/` → Home feed
  - `/login`, `/login-otp`, `/login-verify-otp`
  - `/profile`, `/edit-profile`, `/review`
  - `/flashsale` and `/flashsale/:tourId`
  - `/voucher/:id`
  - `/genqr`, `/reviewbyqr`
  - Additional pages for `review18days`, `reviewsale`, etc.
- Implement shared layout components:
  - `<Header />` with analytics scripts.
  - `<Navbar />` for bottom navigation.
  - `<Footer />`.

## 3. State management & authentication
- Store JWT in memory + persistent storage (e.g., localStorage) with React Context or Redux.
- Protect authenticated routes (`profile`, `review`, etc.) using route guards.
- Implement login flows:
  - Phone/password authentication.
  - Phone/OTP workflow with intermediate verification screen.

## 4. API integration
- Replace `APICaller` PHP class with a JavaScript service layer using `fetch` or `axios`.
- Provide wrappers for GET, POST, PUT, DELETE, and multipart requests.
- Load base URLs from `.env`; expose helper functions similar to `get_image_domain()` and `get_time_ago()`.

## 5. Feature parity
- **Home feed**
  - Fetch posts with pagination; implement “load more” as infinite scroll.
  - Recreate post components for `review`, `general`, `review_sale` with content toggling.
- **Profile & vouchers**
  - Display user info, voucher list, and review list.
  - Implement edit-profile form and logout.
- **Review submission**
  - Forms for standard review and QR-based review (pre-filled fields).
  - Image upload using `FormData`.
  - OTP verification screen before final submission.
- **QR workflow**
  - Generate QR link client-side.
  - Handle scanning redirect and browser detection (e.g., Zalo).
- **Flash sale**
  - Campaign landing page with countdown timers.
  - Tour detail page with dynamic pricing, date selection, quantity controls, and booking API call.
- **Misc pages**
  - Reproduce utility pages (e.g., `redirectapp`, `thankyou-vngroup`, `reviewsale`) as needed.

## 6. UI/UX & styling
- Port existing CSS to modular styles (CSS Modules, Tailwind, or styled-components).
- Maintain responsive layout and Vietnamese text content.
- Integrate tracking scripts via React hooks (`useEffect`) matching `includes/tracking.php`.

## 7. Testing & quality
- Use Jest/React Testing Library for unit tests.
- Ensure accessibility (ARIA attributes) and SEO (meta tags equivalent to PHP templates).
- Validate form inputs, handle API errors, and provide user feedback.

## 8. Deployment
- Build for static hosting or integrate with a Node/Express server if SSR is required.
- Ensure environment variables and analytics IDs are configurable per deployment environment.
