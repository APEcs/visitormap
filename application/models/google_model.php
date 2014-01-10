<?php
class Google_model extends CI_Model {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	
	/**
	 * Returns an array with following keys:
	 * 	'lat', 'long', 'country_name', 'country_code'
	 * If error, returns NULL
	 * @param String $string - the address to look for
	 * @return NULL|multitype:unknown
	 */
	public function lookup($string)
	{
		$return = array();
		$problematic_stuff=array("Í","€","…","†","›","Š","š","Ÿ","ƒ","Ž","&","‹"," ");
		$replace		  =array("O","A","O","U","o","a","o","u","E","e","and","a","+");
		$inst = str_replace($problematic_stuff, $replace, $string);
		
		$details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$inst."&sensor=false";

		//echo $details_url."<br>";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $details_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = json_decode(curl_exec($ch), true);
		// If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
		if ($response['status'] != 'OK') 
		{
			return null;
		}
		$address_components = $response['results'][0]["address_components"];
		
		
		
		foreach ($address_components as $comp)
		{
			if (count($comp["types"])!=0 && $comp["types"][0] == "country")
			{
				$return["country_code"] = strtolower($comp["short_name"]);
				$return["country_name"] = trim($comp["long_name"]);
			}
			if (count($comp["types"])!=0 && $comp["types"][0] == "street_number")
			{
				$return["street_number"] = trim($comp["short_name"]);
			}
			if (count($comp["types"])!=0 && $comp["types"][0] == "route")
			{
				$return["street"] = trim($comp["short_name"]);
			}
			if (count($comp["types"])!=0 && $comp["types"][0] == "locality")
			{
				$return["city"] = trim($comp["long_name"]);
			}
			if (count($comp["types"])!=0 && $comp["types"][0] == "postal_code")
			{
				$return["postal_code"] = trim($comp["long_name"]);
			}
		}
				
		$geometry = $response['results'][0]['geometry'];
		
		$return["lat"] = $geometry['location']['lat'];
		$return["long"] = $geometry['location']['lng'];
		
	
		return $return;

	}
	
	
	
	
}






