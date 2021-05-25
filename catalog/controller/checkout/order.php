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


	public function Saveorder(){
		$this->load->model('checkout/order');
		$order_data['customer_id'] = 0;
		$order_data['customer_group_id'] = 0;
		$order_data['firstname'] = $this->request->post['full_name'];
		$order_data['lastname'] = $this->request->post['full_name'];
		$order_data['email'] = $this->request->post['email'];
		$order_data['telephone'] = $this->request->post['mobile_number'];
		$order_data['payment_city'] = $this->request->post['city'];
		$order_data['payment_address_1'] = $this->request->post['address'];

		$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
		$order_data['store_id'] = $this->config->get('config_store_id');
		$order_data['store_name'] = $this->config->get('config_name');
		if ($order_data['store_id']) {
			$order_data['store_url'] = $this->config->get('config_url');
		} else {
			if ($this->request->server['HTTPS']) {
				$order_data['store_url'] = HTTPS_SERVER;
			} else {
				$order_data['store_url'] = HTTP_SERVER;
			}
		}

		$order_data['payment_firstname'] = $this->request->post['full_name'];
		$order_data['payment_lastname'] = $this->request->post['full_name'];
		$order_data['payment_country'] = 'Viet Nam';
		$order_data['payment_country_id'] = '230';
		$order_data['payment_address_format'] = '';
		$order_data['payment_company'] = '';
		$order_data['payment_address_1'] = $this->request->post['address'];
		$order_data['payment_address_2'] = '';
		$order_data['payment_postcode'] = '55000';
		$order_data['payment_zone_id'] = '';
		$order_data['payment_code'] = '';
		$order_data['payment_method'] = '';
		$order_data['payment_zone'] = '';

		$order_data['shipping_firstname'] = $this->request->post['full_name'];
		$order_data['shipping_lastname'] = $this->request->post['full_name'];
		$order_data['shipping_country'] = 'Viet Nam';
		$order_data['shipping_country_id'] = '230';
		$order_data['shipping_address_format'] = $this->request->post['address'];
		$order_data['shipping_company'] = '';
		$order_data['shipping_address_1'] = '';
		$order_data['shipping_address_2'] = '';
		$order_data['shipping_postcode'] = '55000';
		$order_data['shipping_zone'] = '';
		$order_data['shipping_zone_id'] = '';
		$order_data['shipping_code'] = '';
		$order_data['shipping_method'] = '';
		$order_data['shipping_city'] = '';
		


		$order_data['currency_code'] = 'VND';
		$order_data['total_data'] = '0';
		
		$order_data['currency_id'] = '1';
		$order_data['currency_value'] = 1;
		$order_data['language_id'] = $this->config->get('config_language_id');
		$order_data['order_status_id'] = 1;
		$order_data['ip'] = $this->request->server['REMOTE_ADDR'];

		$order_data['comment'] = $this->request->post['comment'];
		$order_data['total'] = 0;

		if (isset($this->request->cookie['tracking'])) {
			$order_data['tracking'] = $this->request->cookie['tracking'];

			$subtotal = $this->cart->getSubTotal();

			// Affiliate
			$affiliate_info = $this->model_account_customer->getAffiliateByTracking($this->request->cookie['tracking']);

			if ($affiliate_info) {
				$order_data['affiliate_id'] = $affiliate_info['customer_id'];
				$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
			} else {
				$order_data['affiliate_id'] = 0;
				$order_data['commission'] = 0;
			}

			// Marketing
			$this->load->model('checkout/marketing');

			$marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

			if ($marketing_info) {
				$order_data['marketing_id'] = $marketing_info['marketing_id'];
			} else {
				$order_data['marketing_id'] = 0;
			}
		} else {
			$order_data['affiliate_id'] = 0;
			$order_data['commission'] = 0;
			$order_data['marketing_id'] = 0;
			$order_data['tracking'] = '';
		}

		if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
			$order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
		} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
			$order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
		} else {
			$order_data['forwarded_ip'] = '';
		}

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
		} else {
			$order_data['user_agent'] = '';
		}

		if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
			$order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
		} else {
			$order_data['accept_language'] = '';
		}

		if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
			$order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
		} else {
			$order_data['accept_language'] = '';
		}

		$order_id = $this->model_checkout_order->addOrder($order_data);
		if (isset($this->request->post['products'])) {
			$products = $this->request->post['products'];
			foreach($products as $product){
				$data[] = array(
					'order_id'	=> $order_id,
					'product_id'=> $product['product_id'],
					'name'		=> $product['name'],
					'model'		=> $product['model'],
					'quantity'	=> $product['qty'],
					'price'		=> $product['price'],
					'total'		=> $product['total'],
					'tax'		=> 0,
					'reward'	=> 0
				);

				$last_product = $this->model_checkout_order->addOrderProducts($data);
			}
		} else {
			$products = 0;
		}

		if(!empty($order_id) && !empty($last_product)){


			$data['order_by_user'] = $this->model_checkout_order->getOrder($order_id);

			$data['products_order'] = $this->model_checkout_order->getOrderProducts($order_id);


			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('checkout/orderstatus', $data));
		}


	}

	public function detailOrder() {

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if(!empty($order_id)){
			$this->load->model('checkout/order');
			$data['order_by_user'] = $this->model_checkout_order->getOrder($order_id);

			$data['products_order'] = $this->model_checkout_order->getOrderProducts($order_id);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('checkout/orderStatus', $data));
		}

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
