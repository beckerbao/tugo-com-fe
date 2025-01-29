function toggleCommentInput(postId) {
    const commentInput = document.querySelector(`#${postId} .comment-input`);
    commentInput.style.display = commentInput.style.display === 'block' ? 'none' : 'block';
}

function submitComment() {
    alert('Comment submitted!');
}