document.addEventListener('DOMContentLoaded', () => {
    const otpInputs = document.querySelectorAll('.otp-input-group input');

    otpInputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            const value = e.target.value;
            if (value.length > 0) {
                // Chuyển đến ô tiếp theo nếu có dữ liệu
                if (index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && e.target.value === '') {
                // Chuyển đến ô trước đó nếu xóa dữ liệu
                if (index > 0) {
                    otpInputs[index - 1].focus();
                }
            }
        });
    });
});