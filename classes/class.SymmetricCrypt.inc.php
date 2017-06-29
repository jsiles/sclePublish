<?php
class SymmetricCrypt
{
	// Encryption/decryption key
	// The initialization vector
	private static $_msHexaIv = 'bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3';
	// Use the Rijndael Encryption Algorithm
	private static $_msCipherAlgorithm = MCRYPT_RIJNDAEL_128;
    //size 
	
		/* Function encrypts plain-text string received as parameter
		and returns the result in hexadecimal format */
		public static function Encrypt($plainString)
			{
				// Pack SymmetricCrypt::_msHexaIv into a binary string
				$key = pack('H*', self::$_msHexaIv);
				$iv_size = mcrypt_get_iv_size(self::$_msCipherAlgorithm, MCRYPT_MODE_CBC);
				//echo "LLave".strlen($binary_iv);die;
				// Encrypt $plainString
				$binary_encrypted_string = mcrypt_encrypt(
											self::$_msCipherAlgorithm,
											$key,
											$plainString,
											MCRYPT_MODE_CBC,
											'bcb04b7e103a0cd8');
				// Convert $binary_encrypted_string to hexadecimal format
				$hexa_encrypted_string = base64_encode($binary_encrypted_string);
				return $hexa_encrypted_string;
			}
		/* Function decrypts hexadecimal string received as parameter
		and returns the result in hexadecimal format */
		public static function Decrypt($encryptedString)
			{
				// Pack Symmetric::_msHexaIv into a binary string
				$key = pack('H*', self::$_msHexaIv);
				$iv_size = mcrypt_get_iv_size(self::$_msCipherAlgorithm, MCRYPT_MODE_CBC);
				$ciphertext_dec = base64_decode($encryptedString);
				// Decrypt $binary_encrypted_string
				$decrypted_string = mcrypt_decrypt(
											self::$_msCipherAlgorithm,
											$key,
											$ciphertext_dec,
											MCRYPT_MODE_CBC,
											'bcb04b7e103a0cd8');
				return trim($decrypted_string);
			}
}
?>
