<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$this->load->model('catalog/category');
		$this->load->model('tool/image');
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}


		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
				$image="";
				if ($category['image']) {
					$image = $this->model_tool_image->resize($category['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder,png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_height'));
				}
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);


				foreach ($children as $child) {
					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);
					$children2_data=array();
                    $children2 = $this->model_catalog_category->getCategories($child['category_id']);

                    foreach ($children2 as $child2) {
    					$children2_data[] = array(
                            'category_id' => $child2['category_id'],
    						'name'  => $child2['name'],
    						'href'  => $this->url->link('product/category', 'path=' . $child2['category_id']),
    					);
                        
                    }

					$children_data[] = array(
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
						'name'  => $child['name'],
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']),
						'child' => $children2_data
					);
				}


				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id']),
					'image'		=> $image
				);
		}

		$this->load->model('catalog/manufacturer');

		$manufacturers = $this->model_catalog_manufacturer->getManufacturers();

		foreach ($manufacturers as $manufacturer) {
			if (is_numeric(utf8_substr($manufacturer['name'], 0, 1))) {
				$key = '0 - 9';
			} else {
				$key = utf8_substr(utf8_strtoupper($manufacturer['name']), 0, 1);
			}

			$data['manufacturers'][] = array(
				'name' => $manufacturer['name'],
				'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']),
				'image' => $this->model_tool_image->resize($manufacturer['image'], '230', '100')
			);
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/home', $data));
	}
}
