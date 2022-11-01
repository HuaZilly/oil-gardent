<?php
/**
 * The template for rendering the search/sort/filter form
 *
 * @var string   $action  The form action URL
 * @var string   $search  The search box HTML
 * @var string   $sort    The sort box HTML
 * @var string[] $filters HTML for each of the filter selects
 */
?>

<div class="bc-product-archive__filter">
    <div class="bc-product-archive__nav" data-js="bc-product-archive__nav">
        <a class="filter-toggle" data-target=".bc-product-archive__dropdown" href="javascript:void(0)">Filter</a>
    </div>
</div>
<div class="bc-product-archive__sortby">
    <form action="<?php echo esc_url( $action ); ?>" method="get" class="bc-form">
        <div class="bc-product-archive__select bc-product-archive--sort">
            <label class="bc-product-archive__select-label">Sort by</label>
            <select class="radio-list" name="bc-sort" onchange="this.form.submit()">
                <?php
                $bc_sort = 'sales';
                if (isset($_GET['bc-sort'])) $bc_sort = $_GET['bc-sort'];
                ?>
                <option value="sales"<?php if ($bc_sort === 'sales') echo " selected"; ?>>Best Sellers</option>
                <option value="date"<?php if ($bc_sort === 'date') echo " selected"; ?> />Newest</option>
                <option value="price_asc"<?php if ($bc_sort === 'price_asc') echo " selected"; ?> />Price (low to high)</option>
                <option value="price_desc"<?php if ($bc_sort === 'price_desc') echo " selected"; ?> />Price (high to low)</option>
            </select>
        </div>
    </form>
</div>
<div class="bc-product-archive__dropdown">
    <form action="<?php echo esc_url( $action ); ?>" method="get" class="bc-form">
        <?php
        $obj = get_queried_object();
        $bc_cat = '0';
        $bc_price = "";
        $bc_size = "";
        $current_cat = $bc_cat;
        if (isset($_GET['bigcommerce_category'])) $bc_cat = $_GET['bigcommerce_category'];
        if (isset($_GET['fwp_price'])) $bc_price = $_GET['fwp_price'];
        if (isset($_GET['fwp_size'])) $bc_size = $_GET['fwp_size'];

        $args = array(
            'taxonomy' => 'bigcommerce_category',
            'hide_empty' => true, // Set to true to avoid
            'parent' => 0,
            'exclude' => 6
        );

        if (get_class($obj) === "WP_Term") {
            // Get category children
            $args = array_merge( $args, array(
                'parent' => $obj->term_id,
            ) );

            if ($bc_cat === '0') $bc_cat = $obj->slug;
            $current_cat = $obj->slug;
        }
        $categories = get_terms( $args );
        if (count($categories) > 0) :
            ?>

            <div class="bc-product-archive__select bc-product-archive--filter">
                <label class="bc-product-archive__select-label">Subcategories</label>
                <div class="radio-list">
                    <div><input type="radio" name="bigcommerce_category" onclick="this.form.submit()" id="bigcommerce_category_<?php echo $current_cat ?>" value="<?php echo $current_cat ?>"<?php if ($bc_cat === $current_cat) echo " checked"; ?> /><span></span> <label for="bigcommerce_category_<?php echo $current_cat ?>">All</label></div>
                    <?php foreach ($categories as $c) : ?>
                        <?php if($c->term_id !== 43): ?>
                            <div><input type="radio" name="bigcommerce_category" onclick="this.form.submit()" id="bigcommerce_category_<?php echo $c->slug ?>" value="<?php echo $c->slug ?>"<?php if ($bc_cat === $c->slug) echo " checked"; ?> /><span></span> <label for="bigcommerce_category_<?php echo $c->slug ?>"><?php echo esc_html($c->name) ?></label></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="bc-product-archive__select bc-product-archive--filter">
            <label class="bc-product-archive__select-label">Price</label>
            <div class="radio-list">
                <!--<div><input type="radio" name="fwp_price" onclick="this.form.submit()" id="fwp_price_all" value=""<?php if ($bc_price === "") echo " checked"; ?> /><span></span> <label for="fwp_price_all">All</label></div> -->
                <?php echo do_shortcode('[facetwp facet="pricenew"]') //do_shortcode('[facetwp facet="price"]'); ?>
            </div>
        </div>

        <div class="bc-product-archive__select bc-product-archive--filter">
            <label class="bc-product-archive__select-label">Size</label>
            <div class="radio-list">
                <div><input type="radio" name="fwp_size" onclick="this.form.submit()" id="fwp_size_all" value=""<?php if ($bc_size === "") echo " checked"; ?> /><span></span> <label for="fwp_size_all">All</label></div>
                <?php echo do_shortcode('[facetwp facet="size"]'); ?>
            </div>
        </div>
    </form>
</div>