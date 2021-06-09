<?php
class ModelExtensionNews extends Model {
	public function getNews($news_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news n WHERE n.news_id = '" . (int)$news_id . "'");

		return $query->row;
	}

	public function getAllNews($data) {
		$sql = "SELECT * FROM " . DB_PREFIX . "news ORDER BY date_added DESC";
		
		if (isset($data['start']) && isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	
		
		$query = $this->db->query($sql);
 
		return $query->rows;
	}

	public function getAllNewsByCategory($data) {
		$sql = "
			SELECT * FROM " . DB_PREFIX . "news AS n
			INNER JOIN " . DB_PREFIX . "news_to_category AS nc ON n.news_id = nc.news_id
			WHERE nc.category_id = 10
			ORDER BY n.date_added DESC";
		
		if (isset($data['start']) && isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	
		
		$query = $this->db->query($sql);
 
		return $query->rows;
	}

	public function getTotalNews() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "news");

		return $query->row['total'];
	}
}
