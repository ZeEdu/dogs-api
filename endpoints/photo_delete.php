<?php
function api_photo_delete($request)
{
		$id_post =  $request['id'];
		$user = wp_get_current_user();
		$post = get_post($id_post);

		$author_id = (int) $post->post_author;
		$user_id = (int) $user->ID;

		if ($user_id !== $author_id || !isset($post)) {
			$response = new WP_Error('error', 'Sem permissão', ['status' => 401]);
			return rest_ensure_response($response);
		}
		
		$attachment_id = get_post_meta($id_post, 'img', true);
		wp_delete_attachment($attachment_id, true);
		wp_delete_post($id_post);

    return rest_ensure_response('Post Deleted');
}

function register_api_photo_delete()
{
    register_rest_route('api', '/photo/(?P<id>[0-9]+)', [
        'methods' => WP_REST_Server::DELETABLE,
        'callback' => 'api_photo_delete',
    ]);
}

add_action('rest_api_init', 'register_api_photo_delete');
