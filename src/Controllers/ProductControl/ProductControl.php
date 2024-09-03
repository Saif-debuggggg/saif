<?php

namespace WilokeProductFurniture\Controllers\ProductControl;

use Elementor\Base_Control;
use WilokeProductFurniture\Share\TraitHandleCustomProduct;

class ProductControl extends Base_Control
{
  use TraitHandleCustomProduct;

  public function getFieldsOrderBy(): array
  {
    return [
      'ID'            => esc_attr__('ID', 'wil-product-funiture'),
      'author'        => esc_attr__('Author', 'wil-product-funiture'),
      'title'         => esc_attr__('Title', 'wil-product-funiture'),
      'date'          => esc_attr__('Date', 'wil-product-funiture'),
      'menu_order'    => esc_attr__('Menu Order', 'wil-product-funiture'),
      'rand'          => esc_attr__('Random', 'wil-product-funiture'),
      'comment_count' => esc_attr__('Comment Count', 'wil-product-funiture'),
    ];
  }

  public function getFieldsOrder(): array
  {
    return [
      'desc' => esc_attr__('DESC', 'wil-product-funiture'),
      'asc'  => esc_attr__('ASC', 'wil-product-funiture')
    ];
  }

  public function getFieldsFilterProduct(): array
  {
    return [
      'all'        => esc_attr__('All', 'wil-product-funiture'),
      'onSale'     => esc_attr__('On Sale', 'wil-product-funiture'),
      'outOfStock' => esc_attr__('Out Of Stock', 'wil-product-funiture'),
      'inStock'    => esc_attr__('In Stock', 'wil-product-funiture'),
      'categories' => esc_attr__('Categories', 'wil-product-funiture'),
      'tags'       => esc_attr__('Tags', 'wil-product-funiture'),
      'manual'     => esc_attr__('Manual', 'wil-product-funiture')
    ];
  }

  public function get_type()
  {
    return WILOKE_WILOKEPRODUCTFURNITURE_NAMESPACE . 'wil-product';
  }

  public function enqueue()
  {
    wp_register_script(WILOKE_WILOKEPRODUCTFURNITURE_NAMESPACE .
      '_custom_product_script',
      WILOKE_WILOKEPRODUCTFURNITURE_VIEWS_URL .
      'src/Controllers/ProductControl/ProductControl.js',
      ['jquery'],
      WILOKE_WILOKEPRODUCTFURNITURE_VERSION,
      true);
    wp_localize_script('jquery', 'WilokeProductFurniture', [
      'prefix' => WILOKE_WILOKEPRODUCTFURNITURE_NAMESPACE
    ]);
    wp_enqueue_script(WILOKE_WILOKEPRODUCTFURNITURE_NAMESPACE .
      '_custom_product_script');
  }

  public function content_template()
  {
    ?>

    <div
      class="elementor-control elementor-control-type-divider elementor-control-separator-before"></div>

    <div class="wil-mb-15">
      <label class="elementor-control-title">
        {{{data.label }}}
      </label>

      <# if ( data.description ) { #>
      <div class="elementor-control-field-description">{{{data.description}}}
      </div>
      <# } #>
    </div>

    <div class="elementor-control-field wil-mb-15">
      <input class="wil-product-id" type="hidden" name="wilPostId"
             value="<?php echo esc_attr($_GET['post'] ?? 0); ?>">
      <label
        class="elementor-control-title"><?php echo esc_html__('Product Filter',
          'wil-product-funiture') ?></label>

      <div
        class="elementor-control-input-wrapper wil-width-100 elementor-control-unit-5">
        <select class="wil-filter-product" name="wil-filter-product">
          <?php foreach ($this->getFieldsFilterProduct() as $key => $value): ?>
            <option
              value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($value); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>


    <div class="elementor-control-field wil-mb-15 wil-product-ids-wrapper">
      <label class="elementor-control-title"><?php echo esc_html__('Products',
          'wil-product-funiture');
        ?></label>

      <div class="elementor-control-input-wrapper elementor-control-unit-5">
        <select multiple
                class="wil-product-ids wil-width-100"
                name="wil-products">
        </select>
      </div>
    </div>


    <div
      class="wil-field-group elementor-control-field wil-mb-15 wil-product-categories-ids-wrapper">
      <label
        class="elementor-control-title"><?php echo esc_html__('Categories Product',
          'wil-product-funiture');
        ?></label>

      <div class="elementor-control-input-wrapper elementor-control-unit-5">
        <select multiple
                class="wil-product-categories-ids wil-width-100"
                name="wil-product-categories">
        </select>
      </div>
    </div>

    <div class="elementor-control-field wil-mb-15 wil-product-tags-ids-wrapper">
      <label
        class="elementor-control-title"><?php echo esc_html__('Tags Product',
          'wil-product-funiture'); ?></label>

      <div class="elementor-control-input-wrapper elementor-control-unit-5">
        <select multiple class="wil-product-tags-ids wil-width-100"
                name="wil-product-tags">
        </select>
      </div>
    </div>

    <div class="wil-field-group elementor-control-field wil-mb-15">

      <label
        class="elementor-control-title"><?php echo esc_html__('Number Products',
          'wil-product-funiture');
        ?></label>

      <div
        class="elementor-control-input-wrapper elementor-control-unit-5 wil-width-100">
        <input class="wil-products-number"
               type="number"
               name="productsNumber">
      </div>
    </div>

    <div class="wil-field-group elementor-control-field wil-mb-15">

      <label class="elementor-control-title"><?php echo esc_html__('Order By',
          'wil-product-funiture'); ?></label>

      <div class="elementor-control-input-wrapper elementor-control-unit-5">
        <select class="wil-product-order-by wil-width-100"
                name="wil-product-order-by">
          <?php foreach ($this->getFieldsOrderBy() as $key => $value): ?>
            <option value="<?php echo esc_attr($key); ?>">
              <?php echo esc_attr($value); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="wil-field-group elementor-control-field wil-mb-15">

      <label class="elementor-control-title"><?php echo esc_html__('Order',
          'wil-product-funiture') ?></label>

      <div class="elementor-control-input-wrapper elementor-control-unit-5">
        <select class="wil-product-order wil-width-100"
                name="wil-product-order">
          <?php foreach ($this->getFieldsOrder() as $key => $value): ?>
            <option value="<?php echo esc_attr($key); ?>">
              <?php echo esc_attr($value); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div
      class="elementor-control elementor-control-type-divider elementor-control-separator-after"></div>

    <?php

  }
}