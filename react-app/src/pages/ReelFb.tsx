const videoIds = [
  '2031352557369378',
  '1637515117123705',
  '870858001785570',
  '460136580461100',
  '1618129922913236',
  '918880443292673',
  '3988009111437157',
  '1181981580079583',
  '1333740407628915',
  '995282602656195',
  '941570277952412',
  '643411598111793',
  '595626653403411',
  '373878089143492',
  '1251478492572719',
  '972378311614140',
]

const ReelFb = () => (
  <div style={{ textAlign: 'center', padding: '20px' }}>
    <h2>10 Video Reels Nhúng từ Facebook</h2>
    <div
      style={{
        display: 'flex',
        flexWrap: 'wrap',
        justifyContent: 'center',
        gap: 20,
        marginTop: 20,
      }}
    >
      {videoIds.map((id) => (
        <div key={id} style={{ width: 267 }}>
          <iframe
            src={`https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2Freel%2F${id}%2F&show_text=true&width=267&t=0`}
            width="267"
            height="591"
            style={{ border: 'none', overflow: 'hidden' }}
            scrolling="no"
            frameBorder="0"
            allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
            allowFullScreen
          />
        </div>
      ))}
    </div>
  </div>
)

export default ReelFb
