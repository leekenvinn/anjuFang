<?php 

class AttractionsRepo {
	private static $attraction = array();
	
	protected static function init(){
		$attraction = array();
		
		$fp = fopen('data/attractions.tsv', 'r');

		fgets($fp, 2048);
		while (!feof($fp))
		{
			$line = fgets($fp, 2048);
			$data = str_getcsv($line, "\t");

			array_push($attraction,
				new Attraction(1,
				$data[0],
				$data[1],
				$data[2],
				$data[3],
				$data[4],
				$data[5],
				$data[6],
				$data[7],
				$data[8])
			);
		}                              
		fclose($fp);
		
		self::$attraction = $attraction;
	}
	
	public static function getAttractions(){
		if(count(self::$attraction) === 0){
			self::init();
		}
		return self::$attraction;
	}
	
	public static function getAttraction($p_id){
		$m_attractions = self::getAttractions();

		for ($x = 0; $x < count($m_attractions); $x++) {
			if($m_attractions[$x]->id == $p_id)
			{
				return $m_attractions[$x];
			}
		}
		return;
	}
}

class Attraction { 
	public $id;
    public $name;
    public $location;
    public $description;
    public $priceRange;
    public $imageFile;
	public $operatingHours;
	public $tips;
	public $attractionType;
	public $city;
    
    function __construct($p_id,
			$p_name,
			$p_location,
			$p_description,
			$p_priceRange,
			$p_operatingHours,
			$p_tips,
			$p_attractionType,
			$p_city,
			$p_imageFile = '') {		
	    $this->id = $p_id;
		$this->name = $p_name;
		$this->location = $p_location;
		$this->description = $p_description;
		$this->priceRange = $p_priceRange;
		$this->operatingHours = $p_operatingHours;
		$this->tips = $p_tips;
		$this->attractionType = $p_attractionType;
		$this->city = $p_city;
		if($p_imageFile === ''){
			$this->imageFile = $p_name;
		}else{
			$this->imageFile = $p_imageFile;
		}	
	}
} 
?>
