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
