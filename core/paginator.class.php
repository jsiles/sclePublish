<?php
/**
 * Paginator
 * @desc Ajax pagination class
 * @author Omar Abdallah
 * @license GPL
 * @version 1.00
 */

class Paginator{
	
	/**
	 * total pages found for this query
	 * @var integer
	 */
	public $totalPages;
	
	/**
	 * 
	 * @var integer
	 */
	public $recordsPerPage ='5';
	
	/**
	 * Total records found from the query (without LIMIT)
	 * @var integer
	 */
	public $affected_rows;
	
	/**
	 * current page id
	 * @var integer
	 */
	public $pageId;
	
	/**
	 * the offset or the record to start from (used in the LIMIT clause)
	 * @var integer
	 */
	public $offset;
	
	/**
	 * The raw query submitted by the user
	 * @var string
	 */
	private $query;
	
	/**
	 * MySQLi connection Object
	 * @var object
	 */
	private $conn;
	
	/**
	 * mysqli_result object made for more flexibility
	 * for more info: http://us.php.net/manual/en/class.mysqli-result.php
	 * @var object
	 */
	public $result;
	
	/**
	 * the search query used in the like clause if it was specified
	 * @var string
	 */
	public $searchQuery='';
	
	/**
	 * fields to search in
	 * @var string || array
	 */
	public $fields='';
	public $by_category='';
	
	/**
	 * How many links for the next and the previous
	 * @var integer
	 */
	public $linksCountLimit = 3;
	
	public $debug=false;
	
	/**
	 * 
	 * @param $in_pageID id of the current page
	 * @param $in_recordsPerPage number of records per page
	 * @param $in_query SQL query
	 * @param $in_conn database connection object(mysqli)
	 * @return
	 */
	function __construct($in_pageID=1,$in_recordsPerPage,$in_query,$in_conn){
		$this->pageId = intval($in_pageID);
		$this->recordsPerPage = $in_recordsPerPage;
		$this->query = $in_query;		
		$this->conn = $in_conn;		
	}
	
	private function getAffectedRows(){
		if (! empty ( $this->searchQuery )) {
			$this->addSearch();
		}
		$result = $this->conn->query($this->query);
		if($result== FALSE){
			if ($this->debug)
				throw new exception('Query error ' . $this->conn->error);
			else 
				throw new exception('Query error.');
		}

		$affected_rows = $this->conn->affected_rows;
		return $affected_rows;
	}
	
	/**
	 * The Core function which does the actual pagination
	 * 
	 * @return array Associative array of rows returned
	 */
	public function paginate(){
		
		$this->offset = $this->getOffest();

		$this->affected_rows = $this->getAffectedRows();
		$this->totalPages = $this->getTotalPages();
		
		// construct the pagination query: in_query + limit clause
		$pageQ = $this->query . " LIMIT " . $this->offset . " , " . $this->recordsPerPage . ";";
		if ($this->debug){
			echo $pageQ;
		}
		
		try {
			$this->result = $this->conn->query ( $pageQ );
			if ($this->result == false) {
				throw new Exception ( 'Error: Cannot Execute Pagination Query' );
			}
		} catch ( Exception $e ) {
			die($e->getMessage());
		}
		$rows  = array();
		while ( $row = $this->result->fetch_assoc () ) {
			$rows[] = $row;
		}
		
		
		return $rows;
	}
	
	/**
	 * Add search capability by manipulating the SQL query to handle the like clause
	 * 
	 * @return 
	 */
	private function addSearch(){
		$this->searchQuery = $this->conn->real_escape_string ( $this->searchQuery );
			if (is_array ( $this->fields )) {
				$count = count ( $this->fields );
				for($i = 0; $i < $count; $i ++) {
					// its the first field we have to check for WHERE clause
					if ($i == 0) {
						if (stripos ( $this->query, 'WHERE' )) {
							$this->query .= " AND ({$this->fields[0]} like '$this->searchQuery%'";
						} else {
							$this->query .= " WHERE ({$this->fields[0]} like '$this->searchQuery%'";
						}
					}else{
						$this->query .= " OR {$this->fields[$i]} like '$this->searchQuery%'";
					}
				}
				$this->query .= ") ";
			} else {
				// if only single field to search in
				if (stripos ( $this->query, 'where' )) {
					$this->query .= " AND nel_title like '%$this->searchQuery%'";
				} else {
					$this->query .= " WHERE nel_title like '%$this->searchQuery%'";
				}
			}
		$by_cat = $this->by_category ? 'and new_nec_uid='.$this->by_category : '';
		$this->query .= $by_cat;
			
	}
	
	/**
	 * Gets the offset(start number of the LIMIT clause)
	 *
	 * @return string $offset 
	 */
	private function getOffest(){
		// multiply id with no_items_per_page
		$offset = ($this->pageId - 1) * $this->recordsPerPage;
		return $offset;
	}
	
	/**
	 * Get's the number of pages required to display the result
	 *
	 * @return string $totalPages
	 */
	public function getTotalPages(){
		// return the float to higher integer ex:4.5 to 5,3.2 to 4
		$totalPages= ceil($this->affected_rows/$this->recordsPerPage);
		
		return $totalPages;
	}
	
	/**
	 * Generates the pagination links
	 * @param $class css class that will be added to the a tag
	 * @return string
	 */
	public function getLinks($class = "") {
		$this->linksCountLimit = 4;
		// if the current page is not the first
		if ($this->pageId > 1) {
			
			
			$count = 1;
			for($i = $this->pageId; $i >= 1; $i --) {
				if ($count > $this->linksCountLimit)
					break;
				
				if ($i == $this->pageId)
					continue;
				
				$output = "<a href='' onclick='paginate({$i});return false'
				 class='{$class}'>{$i}</a>\r\n" . $output;
				
				$count ++;
			}
			
			//previous page link
			$prevPage = $this->pageId - 1;
			$output = "<a href='' onclick='paginate({$prevPage});return false' class='" 
			. $class . "'>Previous</a>\r\n" . $output;
			
			if ($prevPage > 1){
				// first page link
				$output = "<a href='' onclick='paginate(1);return false' class='" . $class 
				. "'>First</a>\r\n" . $output;
			}
			
			
			
		}
		
		$output .= "<span class='thispage'> {$this->pageId}</span>\r\n";
		
		// next pages
		$count = 1;
		for($i = $this->pageId; $i < $this->totalPages; $i ++) {
			if ($count > $this->linksCountLimit)
				break;
			
			if ($i == $this->pageId)
				continue;
			
			$output .= "<a href='' onclick='paginate({$i});return false;'
				 class='{$class}'>{$i}</a>\r\n";
			
			$count ++;
		}
		// next and last links
		if ($this->pageId < $this->totalPages) {
			// next link
			$next = $this->pageId + 1;
			$output .= "<a href='' onclick='paginate({$next});return false;' class='{$class}'>Next</a>\r\n";
			
			if ($this->totalPages != $next){
				// last page link
				$output .= "<a href='' onclick='paginate({$this->totalPages});return false;' class='{$class}'>Last</a>\r\n";
			}
			
		}
		
		return $output;
	}
}

?>