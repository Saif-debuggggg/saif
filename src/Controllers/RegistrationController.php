<?php

namespace WilokeProductFurniture\Controllers;


use Elementor\Controls_Manager;
use WilokeProductFurniture\Controllers\CategoryPostControl\CustomCategoryPostControl;
use WilokeProductFurniture\Controllers\CategoryProductControl\CustomCategoryProductControl;
use WilokeProductFurniture\Controllers\PostControl\CustomPostControl;
use WilokeProductFurniture\Controllers\ProductControl\ProductControl;
use WilokeProductFurniture\Share\App;

class RegistrationController
{
	public static string $WilokeProductFurniture = 'WilokeProductFurniture';

	public function __construct()
	{
		add_filter('elementor/frontend/print_google_fonts', '__return_false');
		$aConfigs = json_decode(file_get_contents(plugin_dir_path(__FILE__) . '../Configs/config.json'), true);
		App::bind('dataConfig', $aConfigs);
		add_action('elementor/elements/categories_registered', [$this, 'registerCategories']);
		add_action('elementor/widgets/register', [$this, 'registerAddon']);
		add_action('elementor/controls/register', [$this, 'registerControls']);
		add_action('wp_enqueue_scripts', [$this, 'registerScripts']);
	}

	public function registerCategories($oElementsManager)
	{
		$key = App::get('dataConfig')['category']['key'] ?? 'wiloke-category';
		if (!array_key_exists($key, $oElementsManager->get_categories())) {
			$oElementsManager->add_category(
				$key,
				[
					'title' => App::get('dataConfig')['category']['title'] ??
						esc_html__('Wiloke', 'wil-product-funiture'),
					'icon'  => App::get('dataConfig')['category']['icon'] ?? 'eicon-font',
				]
			);
		}
	}

	public function registerScripts()
	{
		$aHandleCss = [];
		$aHandleJs = [];
		wp_register_style(
			WILOKE_WILOKEPRODUCTFURNITURE_NAMESPACE . md5(App::get('dataConfig')['css']),
			App::get('dataConfig')['css'],
			[],
			WILOKE_WILOKEPRODUCTFURNITURE_VERSION);

		$aHandleCss[] = WILOKE_WILOKEPRODUCTFURNITURE_NAMESPACE . md5(App::get('dataConfig')['css']);

		wp_register_script(
			WILOKE_WILOKEPRODUCTFURNITURE_NAMESPACE . md5(App::get('dataConfig')['js']),
			App::get('dataConfig')['js'],
			['elementor-frontend'],
			WILOKE_WILOKEPRODUCTFURNITURE_VERSION,
			true
		);
		$aHandleJs[] = WILOKE_WILOKEPRODUCTFURNITURE_NAMESPACE . md5(App::get('dataConfig')['js']);
		if (isset(App::get('dataConfig')['libs']['css']) && !empty($aLibCss = App::get('dataConfig')['libs']['css'])) {
			foreach ($aLibCss as $urlCss) {
				wp_register_style(
					WILOKE_WILOKEPRODUCTFURNITURE_NAMESPACE . md5($urlCss),
					$urlCss,
					[], WILOKE_WILOKEPRODUCTFURNITURE_VERSION);
				$aHandleCss[] = WILOKE_WILOKEPRODUCTFURNITURE_NAMESPACE . md5($urlCss);
			}
		}
		App::bind('handleCss', $aHandleCss);

		if (isset(App::get('dataConfig')['libs']['js']) && !empty($aLibJs = App::get('dataConfig')['libs']['js'])) {
			foreach ($aLibJs as $urlJs) {
				wp_register_script(
					WILOKE_WILOKEPRODUCTFURNITURE_NAMESPACE . md5($urlJs),
					$urlJs,
					[],
					WILOKE_WILOKEPRODUCTFURNITURE_VERSION,
					true
				);
			}
			$aHandleJs[] = WILOKE_WILOKEPRODUCTFURNITURE_NAMESPACE . md5($urlJs);
		}
		App::bind('handleJs', $aHandleJs);
		wp_localize_script('jquery', 'WilokeProductFurniture', [
			'prefix' => WILOKE_WILOKEPRODUCTFURNITURE_NAMESPACE,
			'userID' => get_current_user_id(),
			'ajaxUrl' => admin_url('admin-ajax.php')
		]);
	}

	public function registerAddon($oWidgetManager)
	{
		$oWidgetManager->register(new PluginAddon());
	}

	public function registerControls(Controls_Manager $oControlManager)
	{
		
		
		$oControlManager->register(new ProductControl());
		
	}
}