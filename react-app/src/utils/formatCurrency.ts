export const formatCurrency = (val: number) =>
  new Intl.NumberFormat('vi-VN').format(val) + '₫'
