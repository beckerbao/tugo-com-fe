function toggleCommentInput(postId) {
    const commentInput = document.querySelector(`#${postId} .comment-input`);
    commentInput.style.display = commentInput.style.display === 'block' ? 'none' : 'block';
}

function submitComment() {
    alert('Comment submitted!');
}

function openAdminReplyModal(postId) {
    const modal = document.getElementById(`admin-reply-modal-${postId}`);
    if (!modal) {
        return;
    }

    modal.hidden = false;
    document.body.classList.add('admin-reply-modal-open');
    const codeInput = modal.querySelector('input[name="admin_reply_code"]');
    if (codeInput) {
        codeInput.focus();
    }
}

function closeAdminReplyModal(postId) {
    const modal = document.getElementById(`admin-reply-modal-${postId}`);
    if (!modal) {
        return;
    }

    modal.hidden = true;
    document.body.classList.remove('admin-reply-modal-open');
}

function closeAdminReplyModalOnEscape(event) {
    if (event.key !== 'Escape') {
        return;
    }

    const openModal = document.querySelector('.admin-reply-modal:not([hidden])');
    if (openModal) {
        closeAdminReplyModal(openModal.id.replace('admin-reply-modal-', ''));
    }
}

async function submitAdminReply(event) {
    event.preventDefault();

    const form = event.currentTarget;
    const postId = form.dataset.postId;
    const code = form.elements.admin_reply_code.value.trim();
    const content = form.elements.content.value.trim();
    const errorElement = form.querySelector('.admin-reply-error');
    const submitButton = form.querySelector('button[type="submit"]');

    errorElement.hidden = true;
    if (!code || !content) {
        errorElement.textContent = 'Vui lòng nhập mã Admin và nội dung phản hồi.';
        errorElement.hidden = false;
        return;
    }

    submitButton.disabled = true;
    submitButton.textContent = 'Đang gửi...';

    try {
        const response = await fetch(`review_replies.php?post_id=${encodeURIComponent(postId)}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Admin-Reply-Code': code
            },
            body: JSON.stringify({ content })
        });
        const data = await response.json();

        if (!response.ok || data.status !== 'success') {
            throw new Error(data.message || 'Không thể gửi phản hồi.');
        }

        window.location.reload();
    } catch (error) {
        errorElement.textContent = error.message;
        errorElement.hidden = false;
        submitButton.disabled = false;
        submitButton.textContent = 'Gửi phản hồi';
    }
}

async function loadMore(page, type = 'all') {
    const loadMoreButton = document.getElementById('load-more');
    //document get from attribute data-base-url
    loadMoreButton.disabled = true;
    loadMoreButton.textContent = "Loading...";

    try {
        const params = new URLSearchParams({ page, type });
        const response = await fetch(`load_posts.php?${params.toString()}`);
        if (!response.ok) {
            throw new Error(`HTTP Error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.html && data.html.trim() !== "") {
            const feed = document.getElementById('feed');
            feed.insertAdjacentHTML("beforeend", data.html);

            if (!data.nextCursor) {
                document.getElementById('load-more-container').remove();
            } else {
                loadMoreButton.textContent = "Xem thêm";
                loadMoreButton.setAttribute("onclick", `loadMore('${data.nextCursor}', '${type}')`);
                loadMoreButton.disabled = false;
            }
        } else {
            document.getElementById('load-more-container').remove();
        }
    } catch (error) {
        console.error("Error loading more posts:", error);
        loadMoreButton.textContent = "Xem thêm";
        loadMoreButton.disabled = false;
    }
}

function toggleContent(postId) {
    const content = document.getElementById(`content-${postId}`);
    const toggle = document.getElementById(`toggle-${postId}`);
    
    if (content.classList.contains('expanded')) {
        content.classList.remove('expanded');
        content.style.display = '-webkit-box';
        toggle.textContent = 'Show More';
    } else {
        content.classList.add('expanded');
        content.style.display = 'block';
        toggle.textContent = 'Show Less';
    }
}

function checkContentOverflow(postId) {
    const content = document.getElementById(`content-${postId}`);
    const toggle = document.getElementById(`toggle-${postId}`);
    
    //if content or toggle is null then return
    if (!content || !toggle) {
        console.log('Content or toggle element not found.');
        return;
    }

    // Kiểm tra nếu nội dung thực tế lớn hơn vùng hiển thị
    if (content.scrollHeight > content.offsetHeight) {
        toggle.style.display = 'inline-block';
    } else {
        toggle.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('keydown', closeAdminReplyModalOnEscape);
    // Gọi hàm kiểm tra overflow cho mỗi bài post sau khi DOM đã được load xong
    document.querySelectorAll('.content').forEach((content) => {
        const postId = content.id.split('-')[1];
        checkContentOverflow(postId);
        console.log(postId);
    });
});
