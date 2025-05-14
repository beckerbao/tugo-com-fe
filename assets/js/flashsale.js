document.addEventListener('DOMContentLoaded', () => {
    const minusBtn = document.getElementById('minusButton');
    const plusBtn = document.getElementById('plusButton');
    const qtyInput = document.getElementById('tour_quantity');
    const subtotalText = document.getElementById('subtotal-text');
    const totalText = document.getElementById('total-price');

    const basePrice = parseInt(subtotalText.dataset.base, 10);
    // const discount = parseInt(subtotalText.dataset.discount, 10);

    function formatCurrency(val) {
      return new Intl.NumberFormat('vi-VN').format(val) + '₫';
    }
    
    function getSelectedPrice() {
      const selectedOption = document.querySelector(".date-option.selected");
      return selectedOption ? parseFloat(selectedOption.getAttribute("data-price")) : 0;
    }
    
    function updatePrice() {
      const qty = parseInt(qtyInput.value, 10) || 1;
      const currentPrice = getSelectedPrice();
      document.getElementById('current-qty').textContent = qty;
      subtotalText.innerHTML = formatCurrency(currentPrice) + ' x <span id="current-qty">' + qty + '</span>';
      totalText.textContent = formatCurrency(currentPrice * qty);
    }

    minusBtn.addEventListener('click', () => {
        let current = parseInt(qtyInput.value, 10);
        if (current > 1) {
        qtyInput.value = current - 1;
        updatePrice();
        }
    });

    plusBtn.addEventListener('click', () => {
        qtyInput.value = parseInt(qtyInput.value, 10) + 1;
        updatePrice();
    });

    updatePrice(); // Initial

    //======================
    // const bookButton = document.querySelector("button.bg-primary");
    const bookButton = document.getElementById("bookButton");

    bookButton.addEventListener("click", async function () {
      const selectedOption = document.querySelector(".date-option.selected");
      const contactName = document.getElementById("contact_name").value.trim();
      const contactPhone = document.getElementById("contact_phone").value.trim();
      const quantity = parseInt(document.getElementById("tour_quantity").value) || 1;
      const spinner = document.getElementById("spinner");
      const buttonText = document.getElementById("button-text");

      if (!selectedOption || !contactName || !contactPhone) {
        alert("Vui lòng nhập đầy đủ tên, số điện thoại và chọn ngày khởi hành.");
        return;
      }

      const departureDate = selectedOption.getAttribute("data-date");
      const selectedPrice = parseFloat(selectedOption.getAttribute("data-price"));
      const tourId = window.TOUR_ID;

      const payload = {
        tour_id: tourId,
        departure_date: departureDate,
        name: contactName,
        phone: contactPhone,
        quantity: quantity,
        price: selectedPrice
      };
      
      spinner.classList.remove("hidden");
      buttonText.textContent = "Đang xử lý..."; 
      bookButton.disabled = true;

      try {
        const response = await fetch(window.API_URL + "/flashsale/booking", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (response.ok) {
          alert("Đặt tour thành công!");
        } else {
          alert("Lỗi: " + (data.message || "Không thể đặt tour"));
        }
      } catch (error) {
        alert("Lỗi kết nối đến máy chủ.");
        console.error(error);
      } finally {
        spinner.classList.add("hidden");
        buttonText.textContent = "Đặt ngay";
        bookButton.disabled = false;
      }
    });

    // Xử lý chọn ngày khởi hành
    document.querySelectorAll(".date-option").forEach(option => {
      option.addEventListener("click", function () {
        document.querySelectorAll(".date-option").forEach(el => el.classList.remove("selected"));
        this.classList.add("selected");
        updatePrice(); 
      });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    // Date option selection
    const dateOptions = document.querySelectorAll(".date-option");
    dateOptions.forEach((option) => {
      option.addEventListener("click", function () {
        dateOptions.forEach((opt) => opt.classList.remove("selected"));
        this.classList.add("selected");
      });
    });
    // Quantity controls
    const minusButtons = document.querySelectorAll(
      "button:has(.ri-subtract-line)",
    );        
    // Tab navigation
    const tabButtons = document.querySelectorAll(
      ".bg-white.rounded-lg.shadow-sm.mb-6.sticky.top-16.z-40 button",
    );
    tabButtons.forEach((button) => {
      button.addEventListener("click", function () {
        tabButtons.forEach((btn) => {
          btn.classList.remove("tab-active");
          btn.classList.add("text-gray-500", "border-transparent");
        });
        this.classList.add("tab-active");
        this.classList.remove("text-gray-500", "border-transparent");
      });
    });
    // Countdown timer
    function updateCountdown() {
      const countdownElement = document.querySelector(".countdown span");
      const countdownText = countdownElement.textContent;
      const match = countdownText.match(/còn (\d+) ngày (\d+):(\d+):(\d+)/i);
      if (match) {
        let days = parseInt(match[1]);
        let hours = parseInt(match[2]);
        let minutes = parseInt(match[3]);
        let seconds = parseInt(match[4]);
        seconds--;
        if (seconds < 0) {
          seconds = 59;
          minutes--;
        }
        if (minutes < 0) {
          minutes = 59;
          hours--;
        }
        if (hours < 0) {
          hours = 23;
          days--;
        }
        if (days < 0) {
          countdownElement.textContent = "Flash Sale đã kết thúc";
        } else {
          countdownElement.textContent = `Flash Sale còn ${days} ngày ${hours.toString().padStart(2, "0")}:${minutes.toString().padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
        }
      }
    }
    setInterval(updateCountdown, 1000);
});

document.addEventListener('DOMContentLoaded', () => {
  const buttons = document.querySelectorAll('[data-target]');
  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      const targetId = btn.dataset.target;
      const targetEl = document.getElementById(targetId);
      if (targetEl) {
        const offsetTop = targetEl.getBoundingClientRect().top + window.scrollY - 80; // trừ đi sticky header
        window.scrollTo({ top: offsetTop, behavior: 'smooth' });
      }

      // Cập nhật active class
      buttons.forEach(b => b.classList.remove('tab-active', 'text-gray-900'));
      btn.classList.add('tab-active', 'text-gray-900');
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  
});

document.getElementById('customTourBtn').addEventListener('click', async function() {
  const overlay = document.getElementById('loadingOverlay');
  overlay.style.display = 'flex';

  try {
      const response = await fetch( window.API_GO_URL + '/api/v1/custom-tours/from-app-tour', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
              user_id: 12,
              tour_id: window.TOUR_ID,
              departure_date: '2025-06-15T00:00:00Z',
              expected_guests: 2
          })
      });

      const result = await response.json();

      if (result.status === 'success') {
          const customTourId = result.data.custom_tour_id;
          window.location.href = `https://customtour.tugo.com.vn/?id=${customTourId}`;
      } else {
          overlay.style.display = 'none';
          alert('Đã có lỗi xảy ra: ' + (result.message || 'Không rõ lỗi.'));
      }
  } catch (error) {
      overlay.style.display = 'none';
      alert('Lỗi kết nối đến hệ thống.');
      console.error(error);
  }
});