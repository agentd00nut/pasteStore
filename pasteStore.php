<?php

class PasteStore{
	private $devKey, $userKey;
	public $private, $name, $expire, $code;
	function __construct($devKey, $userKey=Null){

		$this->setDevKey($devKey);
		$this->setUserKey($userKey);


		$this->setPrivate(1);
		$this->setExpire("N");
		$this->setName(uniqid());
	}

	public function upload($postFields){
		$url 				= 'https://pastebin.com/api/api_post.php';
		$ch 				= curl_init($url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$postFields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_NOBODY, 0);

		$response  			= curl_exec($ch);
		$info				= curl_getinfo($ch);
		if($info['http_code'] != 200){
			echo "Something went wrong.  Usually this is pastebin being 'under heavy load'.\n";
			print_r($info);
			die();
		}
		return $response;
	}

	public function uploadAsAnon($code){
		if($this->getPrivate()==2){
			return "Cant upload privately as anon.\n";
			die();
		}
		return $this->upload('api_option=paste'
			.'&api_paste_private='.$this->getPrivate()
			.'&api_paste_name='.$this->getName()
			.'&api_paste_expire_date='.$this->getExpire()
			.'&api_dev_key='.$this->getDevKey()
			.'&api_paste_code='.$code);
	}

	public function uploadAsUser($code){
		return $this->upload('api_option=paste&api_user_key='.$this->getUserKey()
			.'&api_paste_private='.$this->getPrivate()
			.'&api_paste_name='.$this->getName()
			.'&api_paste_expire_date='.$this->getExpire()
			.'&api_dev_key='.$this->getDevKey()
			.'&api_paste_code='.$code);
	}

	/**
	 * @return mixed
	 */
	public function getDevKey()
	{
		return $this->devKey;
	}

	/**
	 * @param mixed $devKey
	 * @return PasteStore
	 */
	public function setDevKey( $devKey )
	{
		$this->devKey = trim($devKey);

		return $this;
	}

	/**
	 * @return mixed
	 */
	private function getUserKey()
	{
		return $this->userKey;
	}

	/**
	 * @param mixed $userKey
	 * @return PasteStore
	 */
	public function setUserKey( $userKey )
	{
		$this->userKey = trim($userKey);

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPrivate()
	{
		return $this->private;
	}

	/**
	 * @param mixed $private
	 * @return PasteStore
	 */
	public function setPrivate( $private )
	{
		$this->private = trim($private);

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param mixed $name
	 * @return PasteStore
	 */
	public function setName( $name )
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getExpire()
	{
		return $this->expire;
	}

	/**
	 * @param mixed $expire
	 * @return PasteStore
	 */
	public function setExpire( $expire )
	{
		$this->expire = $expire;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @param mixed $code
	 * @return PasteStore
	 */
	public function setCode( $code )
	{
		$this->code = $code;

		return $this;
	}
}

function getAllSTDIN(){
	$stdin = '';
	$fh = fopen('php://stdin', 'r');
	$read  = array($fh);
	$write = NULL;
	$except = NULL;
	if ( stream_select( $read, $write, $except, 0 ) === 1 ) {
		while ($line = fgets( $fh )) {
			$stdin .= $line;
		}
	}
	fclose($fh);
	return $stdin;
}


$data = getAllSTDIN();
$paste = new PasteStore(file_get_contents("devKey"), file_get_contents("userKey"));

/* No pipe, no arg */
if ( empty( $data ) && $argc == 1 )
{
	echo "Pipe some data into me!\n";
	die();
}

/* No Pipe, Found an arg */
if( empty( $data ) && $argc == 2 )
{
	$data = file_get_contents($argv[2]);
	if ( empty( $data ) )
	{
		echo "Couldn't read file: ".$argv[2]."\n";
		die();
	}
}

/* Pipe, with an arg, using it as paste name */
if( ! empty($data) && $argc == 2){
	$paste->setName($argv[2]);
}


echo $paste->uploadAsUser($data);


