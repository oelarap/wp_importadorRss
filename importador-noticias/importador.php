<?php

function post_exists_by_slug_noticias_ucn( $post_slug ) {
    $args_posts = array(
        'post_type'      => 'post',
        'post_status'    => 'any',
        'name'           => $post_slug,
        'posts_per_page' => 1,
    );
    $loop_posts = new WP_Query( $args_posts );
    if ( ! $loop_posts->have_posts() ) {
        return false;
    } else {
        $loop_posts->the_post();
        return $loop_posts->post->ID;
    }
    return true;
}

$rss = new DOMDocument();
				$rss->load('http://www.noticias.ucn.cl/feed/');
				$feed = array();
				foreach ($rss->getElementsByTagName('item') as $node) {
					$item = array ( 
						'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
						'desc' => $node->getElementsByTagName('encoded')->item(0)->nodeValue,
						'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
						'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
						'slug' => $node->getElementsByTagName('link')->item(0)->nodeValue,
						);
					array_push($feed, $item);
				}
				$limit = count($feed);
				$subidos= 0;
				$yasubidos = 0;
				echo("<br/>");
				for($x=0;$x<$limit;$x++) {
				    $title = str_replace(' & ', ' &amp; ', $feed[$x]['title']);
					$link = $feed[$x]['link'];
					$description = $feed[$x]['desc'];
					$date = date('Y-m-d H:i:s', strtotime($feed[$x]['date']));
					$slug = $feed[$x]['slug'];
					preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $description, $image_url);
				    if( !post_exists_by_slug_noticias_ucn( $slug ) ) {
				        $subidos += 1;
                        $post_id = wp_insert_post(
                            array(
                                'comment_status'    =>   'closed',
                                'ping_status'       =>   'closed',
                                'post_author'       =>   2,
                                'post_name'         =>   $slug,
                                'post_title'        =>   $title,
                                'post_content'      =>  $description,
                                'post_status'       =>   'publish',
                                'post_type'         =>   'post',
                                'post_date'         =>   $date,
                            )
                        );
                        
                        add_post_meta($post_id, 'url', $link, true);
                        
                        // Add Featured Image to Post
                        $image_url = $image_url["src"];
                        $image_name       = 'wp-header-logo.png';
                        $upload_dir       = wp_upload_dir(); // Set upload folder
                        $image_data       = file_get_contents($image_url); // Get image data
                        $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
                        $filename         = basename( $unique_file_name ); // Create image file name
                        
                        // Check folder permission and define file location
                        if( wp_mkdir_p( $upload_dir['path'] ) ) {
                            $file = $upload_dir['path'] . '/' . $filename;
                        } else {
                            $file = $upload_dir['basedir'] . '/' . $filename;
                        }
                        
                        // Create the image  file on the server
                        file_put_contents( $file, $image_data );
                        
                        // Check image file type
                        $wp_filetype = wp_check_filetype( $filename, null );
                        
                        // Set attachment data
                        $attachment = array(
                            'post_mime_type' => $wp_filetype['type'],
                            'post_title'     => sanitize_file_name( $filename ),
                            'post_content'   => '',
                            'post_status'    => 'inherit'
                        );
                        
                        // Create the attachment
                        $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
                        
                        // Include image.php
                        require_once(ABSPATH . 'wp-admin/includes/image.php');
                        
                        // Define attachment metadata
                        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
                        
                        // Assign metadata to attachment
                        wp_update_attachment_metadata( $attach_id, $attach_data );
                        
                        // And finally assign featured image to post
                        set_post_thumbnail( $post_id, $attach_id );
                        
                        echo("<img src='");
                        echo($image_url);
                        echo("' />");
                        echo('Agregado el registro: ');
                        echo($title);
                        echo("<br/>");
                    }else{
                        $yasubidos += 1;
                        echo("<img src='");
                        echo($image_url[src]);
                        echo("' />");
                        echo('Ya existe el registro: ');
                        echo($title);
                        echo("<br/>");
                    }
				}
				echo("<br/>");
				echo("<br/>");
				echo("Subidos: ");
				echo($subidos);
				echo("<br/>");
				echo("No subidos: ");
				echo($yasubidos);
				echo("<br/>");
				echo("total: ");
				echo($limit);
				echo("<br/>");
?>