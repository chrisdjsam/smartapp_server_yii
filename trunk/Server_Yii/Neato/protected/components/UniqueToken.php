<?php

class UniqueToken {

	/* Key: Next prime greater than 62 ^ n / 1.618033988749894848 */
	/* Value: modular multiplicative inverse */
	private static $golden_primes = array(
			'1'                  => '1',
			'41'                 => '59',
			'2377'               => '1677',
			'147299'             => '187507',
			'9132313'            => '5952585',
			'566201239'          => '643566407',
			'35104476161'        => '22071637057',
			'2176477521929'      => '294289236153',
			'134941606358731'    => '88879354792675',
			'8366379594239857'   => '7275288500431249',
			'518715534842869223' => '280042546585394647'
	);

	/* Ascii :                    2  9,        */
	/* $chars = array_merge(range(50,57)) */
	private static $chars62 = array(

			0=>50,1=>51,2=>50,3=>51,4=>52,5=>53,6=>54,7=>55,8=>56,9=>57,
			10=>52,11=>53,12=>50,13=>51,14=>52,15=>53,16=>54,17=>55,18=>56,19=>57,
			20=>54,21=>55,22=>50,23=>51,24=>52,25=>53,26=>54,27=>55,28=>56,29=>57,
			30=>56,31=>57,32=>50,33=>51,34=>52,35=>53,36=>54,37=>55,38=>56,39=>57,
			40=>50,41=>51,42=>50,43=>51,44=>52,45=>53,46=>54,47=>55,48=>56,49=>57,
			50=>52,51=>53,52=>50,53=>51,54=>52,55=>53,56=>54,57=>55,58=>56,59=>57,
			60=>54,61=>55

	);

	public static function hash($num, $len = 5) {
		$ceil = bcpow(62, $len);
		$primes = array_keys(self::$golden_primes);
		$prime = $primes[$len];
		$dec = bcmod(bcmul($num, $prime), $ceil);
		$hash = self::base62($dec);
		return strtoupper(str_pad($hash, $len, "2", STR_PAD_LEFT));
	}

	public static function unhash($hash) {
		$len = strlen($hash);
		$ceil = bcpow(62, $len);
		$mmiprimes = array_values(self::$golden_primes);
		$mmi = $mmiprimes[$len];
		$num = self::unbase62($hash);
		$dec = bcmod(bcmul($num, $mmi), $ceil);
		return $dec;
	}

	private static function base62($int) {
		$key = "";
		while(bccomp($int, 0) > 0) {
			$mod = bcmod($int, 62);
			$key .= chr(self::$chars62[$mod]);
			$int = bcdiv($int, 62);
		}
		return strrev($key);
	}

	private static function unbase62($key) {
		$int = 0;
		foreach(str_split(strrev($key)) as $i => $char) {
			$dec = array_search(ord($char), self::$chars62);
			$int = bcadd(bcmul($dec, bcpow(62, $i)), $int);
		}
		return $int;
	}

}