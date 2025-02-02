function toggleCommentInput(postId) {
    const commentInput = document.querySelector(`#${postId} .comment-input`);
    commentInput.style.display = commentInput.style.display === 'block' ? 'none' : 'block';
}

function submitComment() {
    alert('Comment submitted!');
}

async function loadMore(cursor, type = 'all') {
    const loadMoreButton = document.getElementById('load-more');
    //document get from attribute data-base-url
    loadMoreButton.disabled = true;
    loadMoreButton.textContent = "Loading...";

    try {
        const response = await fetch(`load_posts.php?cursor=${cursor}&type=${type}`);
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
    
    // Kiểm tra nếu nội dung thực tế lớn hơn vùng hiển thị
    if (content.scrollHeight > content.offsetHeight) {
        toggle.style.display = 'inline-block';
    } else {
        toggle.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Gọi hàm kiểm tra overflow cho mỗi bài post sau khi DOM đã được load xong
    document.querySelectorAll('.content').forEach((content) => {
        const postId = content.id.split('-')[1];
        checkContentOverflow(postId);
    });
});

