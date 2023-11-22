<?php
function update_post_status_form_shortcode($atts) {
    // Check if the user is logged in
    if (is_user_logged_in()) {
        // Get the current user ID
        $current_user_id = get_current_user_id();

        // Check if the user has the capability to edit posts
        if (current_user_can('edit_posts')) {
            // Get the post ID from the shortcode attribute
            $post_id = isset($_GET['pid']) ? absint($_GET['pid']) : false;

            // Check if the post ID is valid
            if ($post_id && get_post_status($post_id)) {



                $old_status = get_post_status($post_id);
                // Check if the form is submitted
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status_nonce']) && wp_verify_nonce($_POST['update_status_nonce'], 'update_status_nonce')) {
                    // Update the post status
                    $new_status = sanitize_text_field($_POST['new_status']);
                    wp_update_post(array('ID' => $post_id, 'post_status' => $new_status));

                    if ($old_status != get_post_status($post_id)){
                        if (isset(get_post_meta($post_id)["your_email_address"])){
                            $title = get_the_title( $post_id );
                            $category = get_the_category( $post_id );
                            $category = reset($category)->name;
                            $author = get_post_meta($post_id);
                            $author_email = reset($author["your_email_address"]);
                            $author_name = reset($author["your_name"]);
                            if ($new_status == 'draft'){
                                $subject = get_option('mail_content_denied_mail_subject');
                                $message = get_option('mail_content_denied_mail_content');
                                eval("\$message = \"$message\";");
                                // Send the email
                                wp_mail($author_email, $subject, $message);
                            } elseif ($new_status == 'publish'){
                                $subject = get_option('mail_content_approved_mail_subject');
                                $message = get_option('mail_content_approved_mail_content');
                                eval("\$message = \"$message\";");
                                // Send the email
                                wp_mail($author_email, $subject, $message);
                            }
                        }
                    }
                }
                $status = get_post_status($post_id);
                // Display the form
                ob_start(); ?>
                <form method="post">
                    <!--<label for="new_status">New Status:</label>-->
                    <select name="new_status">
                        <option value="publish" <?php echo ($status == 'publish') ? 'selected' : ''; ?>>Publish</option>
                        <option value="draft" <?php echo ($status == 'draft') ? 'selected' : ''; ?>>Draft</option>
                        <option value="pending" <?php echo ($status == 'pending') ? 'selected' : ''; ?>>Pending</option>
                    </select>

                    <?php wp_nonce_field('update_status_nonce', 'update_status_nonce'); ?>

                    <input type="hidden" name="post_id" value="<?php echo esc_attr($post_id); ?>">
                    <input type="submit" value="Update Status">
                </form>
                <?php
                return ob_get_clean();
            } else {
                return '<p>Invalid post ID.</p>';
            }
        } else {
            return '<p>Sorry, you do not have permission to edit posts.</p>';
        }
    } else {
        return '<p>Please log in to update post status.</p>';
    }
}

add_shortcode('update_post_status_form', 'update_post_status_form_shortcode');


function trash_post_email_to_user( $post_id ) {

    $title = get_the_title( $post_id );
    $category_obj = get_the_category( $post_id );
    $category_obj = reset($category_obj);
    $category = $category_obj->name;
    $category_slug = $category_obj->slug;
    if (isset(get_post_meta($post_id)["your_email_address"])){
        $author = get_post_meta($post_id);
        $author_email = reset($author["your_email_address"]);
        $author_name = reset($author["your_name"]);
        $subject = get_option('mail_content_trashed_mail_subject');
        $message = get_option('mail_content_trashed_mail_content');
        eval("\$message = \"$message\";");
        // Send the email
        wp_mail($author_email, $subject, $message);
    }

    global $wp;
    $current_url = add_query_arg( $_SERVER['QUERY_STRING'], '', home_url( $wp->request ) );
    if (str_contains($current_url, '?section=legacy&category=')) {
        wp_redirect( home_url( $wp->request ).'?section=legacy&category='.$category_slug );
        exit ;
    }
}

add_action('trashed_post', 'trash_post_email_to_user');
