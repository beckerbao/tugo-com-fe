<?php
$adminReplies = isset($post['admin_replies']) && is_array($post['admin_replies'])
    ? $post['admin_replies']
    : [];
?>
<section class="admin-reply-section" data-post-id="<?php echo (int) $post['id']; ?>">
    <?php if (!empty($adminReplies)): ?>
        <div class="admin-replies">
            <div class="admin-reply-heading">Phản hồi từ Tugo</div>
            <?php foreach ($adminReplies as $adminReply): ?>
                <article class="admin-reply">
                    <div class="admin-reply-author">Tugo</div>
                    <div class="admin-reply-content"><?php echo nl2br(htmlspecialchars((string) ($adminReply['content'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></div>
                    <?php if (!empty($adminReply['created_at'])): ?>
                        <time class="admin-reply-time"><?php echo htmlspecialchars((string) $adminReply['created_at'], ENT_QUOTES, 'UTF-8'); ?></time>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <button type="button" class="admin-reply-toggle" onclick="openAdminReplyModal(<?php echo (int) $post['id']; ?>)">
        Reply
    </button>

    <div class="admin-reply-modal" id="admin-reply-modal-<?php echo (int) $post['id']; ?>" role="dialog" aria-modal="true" aria-labelledby="admin-reply-modal-title-<?php echo (int) $post['id']; ?>" hidden>
        <div class="admin-reply-modal-backdrop" onclick="closeAdminReplyModal(<?php echo (int) $post['id']; ?>)"></div>
        <div class="admin-reply-modal-content">
            <div class="admin-reply-modal-header">
                <h2 id="admin-reply-modal-title-<?php echo (int) $post['id']; ?>">Reply review</h2>
                <button type="button" class="admin-reply-modal-close" aria-label="Đóng" onclick="closeAdminReplyModal(<?php echo (int) $post['id']; ?>)">&times;</button>
            </div>
            <form class="admin-reply-form" id="admin-reply-form-<?php echo (int) $post['id']; ?>" data-post-id="<?php echo (int) $post['id']; ?>" onsubmit="submitAdminReply(event)">
                <label>
                    Mã xác nhận
                    <input type="password" name="admin_reply_code" autocomplete="off" required>
                </label>
                <label>
                    Nội dung phản hồi
                    <textarea name="content" maxlength="5000" rows="5" required></textarea>
                </label>
                <div class="admin-reply-form-actions">
                    <button type="submit">Gửi phản hồi</button>
                    <button type="button" class="admin-reply-cancel" onclick="closeAdminReplyModal(<?php echo (int) $post['id']; ?>)">Hủy</button>
                </div>
                <p class="admin-reply-error" role="alert" hidden></p>
            </form>
        </div>
    </div>
</section>
