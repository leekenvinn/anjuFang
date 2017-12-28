<?php 

class DevelopmentProjectRepo {
	private static $developmentProjects = array();
	
	protected static function init(){
		$developmentProjects = array();
		
		$fp = fopen('data/projects.tsv', 'r');

		$currentId = 0;
		fgets($fp, 2048);
		while (!feof($fp))
		{
			$line = fgets($fp, 2048);
			$data = str_getcsv($line, "\t");
			$currentId++;
			
			array_push($developmentProjects,
				new DevelopmentProject($currentId,
				$data[0],
				$data[1],
				$data[2],
				$data[3],
				$data[4],
				$data[5],
				$data[6],
				$data[7])
			);
		}                              
		fclose($fp);
		
		self::$developmentProjects = $developmentProjects;
	}
	
	public static function getDevelopmentProjects(){
		if(count(self::$developmentProjects) === 0){
			self::init();
		}
		return self::$developmentProjects;
	}
	
	public static function getDevelopmentProject($p_id){
		$m_developmentProjects = self::getDevelopmentProjects();

		for ($x = 0; $x < count($m_developmentProjects); $x++) {
			if($m_developmentProjects[$x]->id == $p_id)
			{
				return $m_developmentProjects[$x];
			}
		}
		return;
	}
}

class DevelopmentProject { 
	public $id;
    public $name;
    public $location;
    public $description;
    public $priceRange;
    public $lowestPrice;
    public $imageFolder;
	public $propertyType;
	public $city;
    
    function __construct($p_id,
			$p_name,
			$p_location,
			$p_description,
			$p_priceRange,
			$p_lowestPrice,
			$p_propertyType,
			$p_city,
			$p_imageFolder = '') {		
	    $this->id = $p_id;
		$this->name = $p_name;
		$this->location = $p_location;
		$this->description = $p_description;
		$this->priceRange = $p_priceRange;
		$this->lowestPrice = $p_lowestPrice;
		$this->propertyType = $p_propertyType;
		$this->city = $p_city;
		if($p_imageFolder === ''){
			$this->imageFolder = $p_name;
		}else{
			$this->imageFolder = $p_imageFolder;
		}	
	}
} 
?>
