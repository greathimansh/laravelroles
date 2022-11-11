<?php

namespace App\Http;

use Illuminate\Support\Facades\Response as Response;
use Exception;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\App;

class ApiResponse extends Response 
{
    public static function validation ($validator, $stringify=false, $status='412', $headers=array() ) {
		if ( !($validator instanceof Validator) )
			throw new Exception('Argument is not a Validator instance ('.get_class($validator).' found).');

		$response = array('messages'=>[]);

		if ( $validator->fails() ) {
			$errors = $validator->messages()->toArray();
			if ( $stringify ) {
				$response = '';
				if ( is_array($errors) ) {
					foreach ($errors as $key => $value) {
						if ( self::isAssocArray($value) ){
							$response .= $key.' ';
							foreach ($value as $key => $val) {
								$response .= strtolower($key).'. ';
							}
						}
						else for ($i=0; $i <count($value) ; $i++) { 
								$response .= $value[$i].' ';
						}
					}
				}
				else $response .= $errors;
			}
			else{

				$message = 'Something went wrong.';

				if($firstError = current($errors)) {
					list($message) = $firstError; 
				}
				
				$response = $errors;
			}
		}
		
		// return json_encode(array( 'validation' => $response) );
		return self::json( array('messages' => $response, 'message' => $message, 'code' => $status), $status, $headers);
	}

    /**
	 *	@param url $url protocol to redirect to
	 *	@return ApiResponse Response to client
	 */
	public static function toApplication ( $url_suffix, $protocol=null ) {
        $response = self::make();
        $protocol = empty($protocol)? Str::slug( Config::get('app.name') ) : $protocol;
        $response->header('Location', $protocol.'://'.$url_suffix);
        return $response;
    }

	/**
	 *	Similar to 403 Forbidden, but specifically for use when authentication is required and 
	 *  has failed or has not yet been provided. The response must include a WWW-Authenticate header field 
	 *  containing a challenge applicable to the requested resource.
	 *	@param array|string $date Message to format
	 *	@param array $headers Additional header to append to the request
	 * 	@return ApiResponse JSON representation of the error message
	 */
	public static function errorUnauthorized( $data=array(), $headers=array() ){
		return self::json( $data, '401', $headers );
	}

	/**
	 *	The request was a valid request, but the server is refusing to respond to it. 
	 *	Unlike a 401 Unauthorized response, authenticating will make no difference.
	 *	@param array|string $date Message to format
	 *	@param array $headers Additional header to append to the request
	 * 	@return ApiResponse JSON representation of the error message
	 */
	public static function errorForbidden( $data=array(), $headers=array() ){
		return self::json( $data, '403', $headers );
	}

	/**
	 *	The requested resource could not be found but may be available again in the future. 
	 *	Subsequent requests by the client are permissible.
	 *	@param array|string $date Message to format
	 *	@param array $headers Additional header to append to the request
	 * 	@return ApiResponse JSON representation of the error message
	 */
	public static function errorNotFound( $data=array(), $headers=array() ){
		return self::json( $data, '404', $headers );
	}

	/**
	 *	A generic error message, given when an unexpected condition was encountered and no more specific message is suitable.
	 *	@param array|string $date Message to format
	 *	@param array $headers Additional header to append to the request
	 * 	@return ApiResponse JSON representation of the error message
	 */
	public static function errorInternal($data = array(), $headers=array()) {
		Log::error($data);
		if(App::environment() == 'production') {
			if(empty($data)) {
				$data = [
					'message' => 'Something went wrong, Please contact admin.'
				];
			}else{
				$data = [
					'message' => 'Something went wrong, Please contact admin.'
				];	
			}
		}else{
			//static::sendCrashReport($data);
			if($data instanceof Throwable && method_exists($data, 'getMessage')) {
				$data = [
					'message' => $data->getMessage()
				];
			}
		}

		return self::json( $data, '500', $headers );
	}

	/**
	 *	@param array $array Message to format
	 *	@return boolean true is associative array, false otherwise
	 */
	protected static function isAssocArray( $array ){
		if ( empty($array) ) return false;
    	return (bool)count(array_filter(array_keys($array), 'is_string'));
	}

	
	/**
	 *	General error messages. 
	 *	@param array|string $date Message to format
	 *  @param Int Code
	 *	@param array $headers Additional header to append to the request
	 * 	@return ApiResponse JSON representation of the error message
	 */
    public static function success($data=array(), $message = 'Success', $code = 200, $headers=array()) 
	{
		return self::json(['data' => $data, 'message' => $message, 'code' => $code], $code, $headers);
	}

	/**
	 *	General error messages. 
	 *	@param string Message 
	 *  @param Int Code
	 *	@param array $headers Additional header to append to the request
	 * 	@return ApiResponse JSON representation of the error message
	 */
	public static function errorGeneral($message = 'Some internal error.', $code = 412, $headers = [])
	{
		return self::json(['message' => $message, 'code' => $code], $code, $headers);
	}

}