import { useEffect } from 'react'

const addScript = (
  content: string,
  options: { async?: boolean; src?: string } = {},
): HTMLScriptElement => {
  const script = document.createElement('script')
  if (options.src) {
    script.src = options.src
  }
  if (options.async) {
    script.async = true
  }
  script.innerHTML = content
  document.head.appendChild(script)
  return script
}

const addNoscript = (src: string): HTMLElement => {
  const noscript = document.createElement('noscript')
  noscript.innerHTML = `<img height="1" width="1" style="display:none" src="${src}" />`
  document.head.appendChild(noscript)
  return noscript
}

const Header = () => {
  useEffect(() => {
    document.title = 'My Community'

    const link = document.createElement('link')
    link.rel = 'stylesheet'
    link.href = '/assets/css/styles.css'
    document.head.appendChild(link)

    const scripts: HTMLScriptElement[] = []
    const noscripts: HTMLElement[] = []

    scripts.push(
      addScript('', {
        async: true,
        src: 'https://www.googletagmanager.com/gtag/js?id=G-8WSFWE0HWR',
      }),
    )
    scripts.push(
      addScript(`window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-8WSFWE0HWR');`),
    )
    scripts.push(
      addScript(
        `!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod? n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init', '519318054382965');fbq('track', 'PageView');`,
      ),
    )
    noscripts.push(
      addNoscript(
        'https://www.facebook.com/tr?id=519318054382965&ev=PageView&noscript=1',
      ),
    )
    scripts.push(
      addScript(
        `!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod? n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init', '829982304468433');fbq('track', 'PageView');`,
      ),
    )
    noscripts.push(
      addNoscript(
        'https://www.facebook.com/tr?id=829982304468433&ev=PageView&noscript=1',
      ),
    )

    return () => {
      document.head.removeChild(link)
      scripts.forEach((s) => document.head.removeChild(s))
      noscripts.forEach((n) => document.head.removeChild(n))
    }
  }, [])

  return null
}

export default Header
