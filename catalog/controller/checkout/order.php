<?php
class ControllerCheckoutOrder extends Controller {
	public function index() {

		$this->load->model('catalog/category');



		$this->load->language('checkout/cart');
		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home'),
			'text' => $this->language->get('text_home')
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('checkout/cart'),
			'text' => $this->language->get('heading_title')
		);
		
		$categories = $this->model_catalog_category->getCategories(0);
		if(!empty($categories)){
			$data['categories'] = $categories;
		}
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('checkout/order', $data));
	}

	public function getProducts() {
		$this->load->language('checkout/cart');
		$this->load->model('catalog/product');
		$json = array();

		if (isset($this->request->get['category_id'])) {
			$category_id = (int)$this->request->get['category_id'];
		} else {
			$category_id = 0;
		}

		$filter_data = array(
			'filter_category_id' => $category_id,
		);
		$results = $this->model_catalog_product->getProducts($filter_data);

		foreach ($results as $result) {
			$json[] = array(
				'product_id'  => $result['product_id'],
				'name'        => $result['name'],
			);
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getSteelGrade() {
		$this->load->language('checkout/cart');
		$this->load->model('catalog/product');
		$json = array();

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$results = $this->model_catalog_product->getProductGrade($product_id);

		foreach ($results as $result) {
			$json[] = array(
				'id'  => $result['attribute_id'],
				'name'        => $result['name'],
			);
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getSteelManufacturer() {
		$this->load->language('checkout/cart');
		$this->load->model('catalog/product');
		$json = array();

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		if (isset($this->request->get['steelgrade_id'])) {
			$steelgrade_id = (int)$this->request->get['steelgrade_id'];
		} else {
			$steelgrade_id = 0;
		}

		$results = $this->model_catalog_product->getProductManufacturer($product_id,$steelgrade_id);

		foreach ($results as $result) {
			$json[] = array(
				'id'  => $result['manufacturer_id'],
				'name'=> $result['manufacturer_name'],
				'price'=> $result['price'],
			);
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getProduct() {
		$this->load->language('checkout/cart');
		$this->load->model('catalog/product');
		$json = array();

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$result = $this->model_catalog_product->getProduct($product_id);

		$json[] = array(
			'id'  => $result['product_id'],
			'weight'=> $this->weight->getUnit($result['weight_class_id']),
		);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


}
