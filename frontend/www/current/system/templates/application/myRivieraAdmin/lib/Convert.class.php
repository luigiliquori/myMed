    <?php
    /**
     * @author Florent Cardot
     * Y = Latitude
     * X = Longitude
     */
    /*
    $lon = 5802906.829;
    $lat = 6453674.479;
    $L = new Convert($lon, $lat);
    $L->convert();
    */
    class Convert
    {
    	private $X;
    	private $Y;
    	private $Coord;
    	private $Cm;
    	private $n;
    	private $XSm;
    	private $YSm;
    	private $a;
    	private $f1;
    	/**Contructeur (initialise les variables qui doivent l'être)**/
    	function __construct($X, $Y)
    	{
    		$this->Cm = 11745793.393435;
    		$this->n = 0.728968627421412;
    		$this->XSm = 600000;
    		$this->YSm = 8199695.76800186;
    		$this->a = 6378249.2000;
    		$this->f1 = 6356515.0000;
    		$this->X = $X - $this->XSm;
    		$this->Y = $Y - $this->YSm;
    	}//end function
    	public function convertion()
    	{
    		$this->Coord[0] = self::ConvertX(); //X
    		$this->Coord[1] = self::ConvertY(); //Y
    		return $this->Coord;
    	}//end function
    	public function ConvertX()
    	{
    		$longitude = atan(-($this->X)/($this->Y));
    		$longitude = $longitude / $this->n;
    		$longitude = $longitude * 180 / pi();
    		$constante = 2 + (20 / 60) + (14.025 / 3600);
    		$longitude = $longitude + $constante;
    		return($longitude);
    	}//end function
    	public function ConvertY()
    	{
    		$latitude = sqrt(pow($this->X, 2) + pow($this->Y, 2));
    		$f = ($this->a - $this->f1) / $this->a;
    		$e² = 2 * $f - pow($f, 2);
    		$e = sqrt($e²);
    		$Latiso = log($this->Cm / $latitude) / $this->n;
    		$latitude = tanh($Latiso);
    		for ($i = 0; $i < 6; $i++)
    		{
    			$latitude = tanh($Latiso + $e * self::atanh($e * $latitude));
    		}
    		$latitude = asin($latitude);
    		$latitude = $latitude / pi();
    		$latitude = $latitude * 180;
    		return($latitude);
    	}//end function
    	public function atanh($x)
    	{
    		$resultat = log((1 + $x) / (1 - $x)) / 2;
    		return $resultat;
    	}//end function
    }//end classs
    ?>