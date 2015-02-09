<?php

class Postcode extends Phalcon\Mvc\User\Component
{
	public function get($args)
	{
		$postcode = preg_replace('/\s+/', '', $args['postcode']); // whitespace weg
		$huisnummer = explode('-', $args['huisnummer']); 
		$huisnummer = preg_replace('[\D]', '', $huisnummer[0]); // alleen maar cijfers
		$key = '8Mb5DiM5vqDfKEcVD7NqtqbBr1HLfdtTNdTiY7Q5K3J';
		$secret = '242dXGPvtWEkMUc3MXON3VIyd74qxg43g2KvoudtA4C';
		$url = "https://api.postcode.nl/rest/addresses/".$args['postcode']."/".$huisnummer;
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_USERPWD, $key . ':' . $secret);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);

		return curl_exec($curl);
	}
}
?>
