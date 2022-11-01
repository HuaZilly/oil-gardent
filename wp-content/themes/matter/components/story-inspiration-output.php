<?php
    $blocks = get_field('blocks');

    if($blocks):
        echo "<div class='tab-sections'>";
        $count = 0;
        foreach($blocks as $b):
            $count++;

            if($count == 1)
                $active = 'active';
            else
                $active = '';

            echo "<div class='$active' data-id='$count'>".$b["title"]."</div>";
            
        endforeach;
        echo "</div>";

        echo "<div class='contents-blocks'>";

        $count = 0;
        foreach($blocks as $b):
            $count++;

            if($count == 1)
                $active = 'active';
            else
                $active = '';

            echo "<div class='$active' data-target='$count'>".$b["content"]."</div>";
            
        endforeach;

        echo "</div>";

    endif;
?>