<script type="text/javascript">var __namespace = '<?php echo $namespace; ?>';</script>
<div class="wrap <?php echo $namespace; ?>">
    <h2><?php echo $page_title; ?></h2>
    <div>
        <h3>How to use:</h3>
        <ul>
          <li>Use shortcode in a post editor <code>[seg_instaportfolio]</code></li>
          <li>Use shortcode in a PHP file (outside the post editor) <code>echo do_shortcode( '[seg_instaportfolio]' );</code></li>
        </ul>
    </div>

    <form action="" method="post" id="<?php echo $namespace; ?>-form">
        <?php wp_nonce_field( $namespace . "-update-options" ); ?>
        <div class="submit-row">
            <input type="submit" name="submit" class="button-primary" value="<?php _e( "Save Changes", $namespace ) ?>" />
        </div>
        <div id="<?php echo $namespace; ?>-content">
            <?php
                 //lets loop trhoug our fields and check if they are already stored, if not store default values
                foreach($this->my_opts as $opt) {
                    if($opt['tabs'] != NULL) { 
                        foreach($opt['tabs'] as $tab) { 
                            echo '<div class="group-options down '  . $tab['class'] .  '">';
                            echo '<div class="title-group-options">'; 
                            echo '<h4>' . $tab['title'] . '<span class="group-collapse up" group="'  . $tab['class'] .  '" >&uarr;</span></h4>';
                            echo '</div>';
                            if($tab['fields'] != NULL) { 
                                echo '<div class="group-fields">';
                                foreach($tab['fields'] as $field) {   
                                    switch($field['type']){
                                        case 'text': 
                                                include( SEG_INSTAPORTFOLIO_CURRENT_FOLDER . '/admin/view/fields/text.php' );
                                                break;
                                        case 'text_number': 
                                                include( SEG_INSTAPORTFOLIO_CURRENT_FOLDER . '/admin/view/fields/text.php' );
                                                break;
                                        case 'select': 
                                                include( SEG_INSTAPORTFOLIO_CURRENT_FOLDER . '/admin/view/fields/select.php' );
                                                break;
                                        case 'colour': 
                                                include( SEG_INSTAPORTFOLIO_CURRENT_FOLDER . '/admin/view/fields/colour.php' );
                                                break;
                                        case 'checkbox': 
                                                include( SEG_INSTAPORTFOLIO_CURRENT_FOLDER . '/admin/view/fields/checkbox.php' );
                                                break;
                                        case 'opacity': 
                                                include( SEG_INSTAPORTFOLIO_CURRENT_FOLDER . '/admin/view/fields/opacity.php' );
                                                break;
                                        case 'textarea': 
                                                include( SEG_INSTAPORTFOLIO_CURRENT_FOLDER . '/admin/view/fields/textarea.php' );
                                                break;
                                    }

                                    if (array_key_exists('subfields', $field)) {
                                        if($field['subfields'] != NULL){
                                            echo '<div class="group-subfields">';
                                            foreach($field['subfields'] as $field) {  
                                                    switch($field['type']){
                                                        case 'text': 
                                                                include( SEG_INSTAPORTFOLIO_CURRENT_FOLDER . '/admin/view/fields/text.php' );
                                                                break;
                                                        case 'text_number': 
                                                                include( SEG_INSTAPORTFOLIO_CURRENT_FOLDER . '/admin/view/fields/text.php' );
                                                                break;
                                                        case 'select': 
                                                                include( SEG_INSTAPORTFOLIO_CURRENT_FOLDER . '/admin/view/fields/select.php' );
                                                                break;
                                                        case 'colour': 
                                                                include( SEG_INSTAPORTFOLIO_CURRENT_FOLDER . '/admin/view/fields/colour.php' );
                                                                break;
                                                        case 'checkbox': 
                                                                include( SEG_INSTAPORTFOLIO_CURRENT_FOLDER . '/admin/view/fields/checkbox.php' );
                                                                break;
                                                        case 'opacity': 
                                                                include( SEG_INSTAPORTFOLIO_CURRENT_FOLDER . '/admin/view/fields/opacity.php' );
                                                                break;
                                                        case 'textarea': 
                                                                include( SEG_INSTAPORTFOLIO_CURRENT_FOLDER . '/admin/view/fields/textarea.php' );
                                                                break;
                                                    }
                                            }
                                        }
                                    }
                                }
                                echo "</div>";
                             } 
                             echo "</div>";
                        }
                    } 
                }
            ?>
            <div class="submit-row">
                <input type="submit" name="submit" class="button-primary" value="<?php _e( "Save Changes", $namespace ) ?>" />
            </div>
        </div>
    </form>
</div>