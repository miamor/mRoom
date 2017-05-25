<?php
$__pattern = '/mRoom';

define('MAIN_PATH', './');
define('MAIN_PATH_EXEC', '/opt/lampp/htdocs/mRoom/');
define('HOST_URL', '//localhost'.$__pattern);
//define('HOST_URL', '//192.168.43.30'.$__pattern);
define('MAIN_URL', 'http:'.HOST_URL);
define('ASSETS', MAIN_URL.'/assets');
define('CSS', ASSETS.'/dist/css');
define('JS', ASSETS.'/dist/js');
define('IMG', ASSETS.'/dist/img');
define('PLUGINS', ASSETS.'/plugins');
define('GG_API_KEY', 'AIzaSyA5xbqBF1tGx96z6-QLhGGmvqIQ5LUrt4s');
define('GG_CX_ID', '014962602028620469778:yf4br-mf6mk');
/*define('EXEC_PATH_C_CPP', 'I:\Dev-Cpp\MinGW64\bin/');
define('EXEC_PATH_JAVA', 'I:\Java\jdk1.8.0_91\bin/');
define('EXEC_PATH_PYTHON', 'I:\Python2.7.12/');
*/
define('EXEC_PATH_C_CPP', '');
define('EXEC_PATH_JAVA', '');
define('EXEC_PATH_PYTHON', '');

$__page = str_replace($__pattern.'/', '', $_SERVER['REQUEST_URI']);
define('__HOST', 'ubuntu');
//define('__HOST', 'window');

class Config {

	// specify your own database credentials
	private $host = "localhost";
	private $db_name = "mroom";
	private $username = "root";
	private $password = "";
	protected $conn;
	public $u;
	public $request;
	public $JS;

	public function __construct () {
		$this->pLink = MAIN_URL.'/p'; // problems (c++)
		$this->uLink = MAIN_URL.'/u';
		$this->cLink = MAIN_URL.'/c';
		$this->bLink = MAIN_URL.'/b'; // blog
		$this->hLink = MAIN_URL.'/help';
		$this->aLink = MAIN_URL.'/about';
		$this->fLink = MAIN_URL.'/f'; // files
		$this->tLink = MAIN_URL.'/t';
		$this->wLink = MAIN_URL.'/w'; // web
		$this->tmLink = MAIN_URL.'/team';
		$this->codeDir = MAIN_PATH.'data/code';
		$this->wDir = MAIN_PATH.'data/web';
		$this->JS = '';
		$this->u = $_SESSION['user_id'];
		if ($this->getConnection()) {
			$this->me = $this->getUserInfo();
			return true;
		} else return false;
	}

	// get the database connection
	public function getConnection() {

		$this->conn = null;

		try {
			$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
			$this->conn->exec("set names utf8");
		} catch (PDOException $exception) {
			echo "Connection error: " . $exception->getMessage();
		}

		return $this->conn;
	}

	// used for the 'created' field when creating a product
	function getTimestamp(){
		date_default_timezone_set('Asia/Manila');
		$this->timestamp = date('Y-m-d H:i:s');
	}

	public function getUserInfo ($u = null, $fields = null) {
		if (!$u) $u = $this->u;
		$defaultFields = 'id,avatar,username,first_name,last_name,online,rank,submissions,AC,total_tests,score,is_mod,is_admin';
		if (!$fields) $fields = $defaultFields;
		else $fields .= ','.$defaultFields;
		$query = "SELECT
					{$fields}
				FROM
					members
				WHERE
					id = ?
				LIMIT
					0,1";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $u);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$row['name'] = ($row['last_name']) ? ($row['last_name'].' '.$row['first_name']) : $row['first_name'];
//		$row['name'] = ($row['last_name']) ? ($row['first_name'].' '.$row['last_name']) : $row['first_name'];
		$row['link'] = $this->uLink.'/'.$row['username'];
		$row['score'] = round($row['score'], 2);
		$row['is_mod'] = (int)$row['is_mod'];
		$row['is_admin'] = (int)$row['is_admin'];
		return $row;
	}

	function sGetUserInfo ($u) {
		if (!$u) $u = $this->u;
		$query = "SELECT
					id,username,first_name,last_name
				FROM
					members
				WHERE
					id = ?
				LIMIT
					0,1";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $u);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$row['name'] = ($row['last_name']) ? ($row['last_name'].' '.$row['first_name']) : $row['first_name'];
		$row['link'] = $this->uLink.'/'.$row['username'];
		return $row;
	}

	function addNoti ($valueAr, $u) {
		$condAr = array();
		foreach ($valueAr as $vK => $oneField) {
			$oneField = htmlspecialchars(strip_tags($oneField));
			$condAr[] = "{$vK} = {$oneField}";
		}
		$cond = implode(', ', $condAr);
		
		$query = "UPDATE
					members
				SET
					{$cond}
				WHERE
					id = :id";

		$stmt = $this->conn->prepare($query);

		// bind parameters
		$stmt->bindParam(':id', $u);

		// execute the query
		if ($stmt->execute()) return true; 
		else return false;
	}
	
	function getDetails ($u) {
		if (!$u) $u = $this->u;
		$query = "SELECT compile_details,iid,score FROM submissions WHERE uid = ?";

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $u);
		$stmt->execute();

		$probAr = array();
		$division = $score = $aScore = 0;
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$ar = explode('|', $row['compile_details']);
			$iid = $row['iid'];
			if (!in_array($iid, $probAr)) $probAr[$iid] = 1;
			else $probAr[$iid]++;
			$resultsChart = array_count_values($ar);
			$division++;
			$score += $row['score'];
		}
		if ($score != 0) $aScore = round($score/$division);
		$results = array('score' => $aScore, 'totalProb' => count($probAr), 'totalSub' => count($stmt->rowCount()), 'chart' => $resultsChart);
		return $results;
	}

	function get ($char) {
		$request = $this->request;
		if ($request && check($request, $char) > 0) {
			$c = explode($char.'=', $request)[1];
			$c = explode('&', $c)[0];
			$request = str_replace("{$char}={$c}&", "", $request);
			return $c;
		}
		return null;
	}

	function getFilesNum ($_udir, $_fLang) {
		$_files = $this->getFiles($_udir, $_fLang);
		$_fNum = count($_files);
		return $_fNum;
	}
	function getFiles ($_udir, $_fLang, $checkSubmit = false) {
		if (is_dir($_udir)) {
			$_files = scandir($_udir);
			foreach ($_files as $_k => $_fileo) {
				$_fileO = $_udir.'/'.$_fileo;
				$_fOext = end(explode('.', $_fileO));
				if (!is_file($_fileO) || $_fOext != $_fLang) unset($_files[$_k]);
				else {
					$fullFilePath_ = MAIN_PATH.str_replace('./', '/', $_fileO);
					$fullFilePath = MAIN_PATH.str_replace('./', '', $_fileO);
					$_fOmode = $_fOext;
					if ($_fOext == 'cpp') $_fOmode = 'c_cpp';
					if ($checkSubmit == false)
						$_files[$_k] = 
							array(
								'dir' => $_udir.'/'.$_fileo, 
								'u' => preg_match('/(?<=u)(.*)(?=\/)/', $_udir), 
								'filename' => explode('.', $_fileo)[0], 
								'ext' => $_fOext, 
								'mode' => $_fOmode
							);
					else {
						$query = "SELECT id,compile_stt,console,console_pla,AC,compile_details FROM submissions WHERE (file = ? OR file = ? OR file = ?) LIMIT 0,1";
						$stmt = $this->conn->prepare($query);
						$stmt->bindParam(1, $fullFilePath_);
						$stmt->bindParam(2, $fullFilePath);
						$stmt->bindParam(3, $_fileO);
						$stmt->execute();
						$num = $stmt->rowCount();
						$row = $stmt->fetch(PDO::FETCH_ASSOC);
						if ($row['compile_stt'] == 0) $stt = 'AC';
						else if ($row['compile_stt'] == -1) $stt = 'WA';
						$_files[$_k] = 
							array(
								'dir' => $_udir.'/'.$_fileo, 
								//'u' => preg_match('/(?<=u)(.*)(?=\/)/', $_udir), 
								'filename' => explode('.', $_fileo)[0], 
								'submit' => $num, 
								'sid' => $row['id'],
								'compile_stt' => $stt,
								'console' => json_decode($row['console'], true),
								'console_pla' => json_decode($row['console_pla'], true),
								'compile_details' => $row['compile_details'],
								'AC' => $row['AC'],
								'ext' => $_fOext, 
								'mode' => $_fOmode
							);
					}
				}
			}
			return array_values($_files);
		} 
		return null;
	}
	
	function addJS ($type, $link) {
		if ($type == 'dist') $type = 'dist/js';
		$this->JS .= ASSETS.'/'.$type.'/'.$link.'|';
	}
	
	function echoJS () {
		$exJS = explode('|', $this->JS);
		foreach ($exJS as $exjs) {
			if ($exjs) echo '	<script src="'.$exjs.'"></script>
	';
		}
	}

}



function checkInternet ($sCheckHost = 'www.google.com')  {
	$connected = @fsockopen($sCheckHost, 80); 
	return (bool) $connected;
}

	function ggsearch ($query, $cx) {
		$key = GG_API_KEY;
		$cx = urlencode($cx);
		$query = urlencode($query);
		$url = "https://www.googleapis.com/customsearch/v1?cx={$cx}&key={$key}&q={$query}";
//		echo $url;
		$google_search = file_get_contents($url);
		return ($google_search);
	}

	function check ($haystack, $needle) {
	//	return strlen(strstr($string, $word)); // Find $word in $string
		return substr_count($haystack, $needle); // Find $word in $string
	}

	function checkURL ($word) {
		return check($_SERVER['REQUEST_URI'], $word);
	}

	function strip_comments ($str) {
		$str = preg_replace('!/\*.*?\*/!s', '', $str);
		$str = preg_replace('/\n\s*\n/', "\n", $str);
		$str = preg_replace('![ \t]*//.*[ \t]*[\r\n]!', '', $str);
		return $str;
	}

function str_insert_after ($str, $search, $insert) {
	$index = strpos($str, $search);
	if ($index === false) {
	return $str;
	}
	return substr_replace($str, $search.$insert, $index, strlen($search));
}

function str_insert_before ($str, $search, $insert) {
	$index = strpos($str, $search);
	if ($index === false) {
	return $str;
	}
	return substr_replace($str, $insert.$search, $index, strlen($search));
}

function content ($content) {
	return nl2br($content);
}

function random_color_part() {
	return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
	return random_color_part() . random_color_part() . random_color_part();
}

function generateRandomString ($length = 6) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
	$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function vn_str_filter ($str) {
	$unicode = array(
		'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
		'd'=>'đ',
		'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
		'i'=>'í|ì|ỉ|ĩ|ị',
		'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
		'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
		'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
		'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
		'D'=>'Đ',
		'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
		'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
		'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
		'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
		'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
	);
	foreach ($unicode as $nonUnicode=>$uni) {
		$str = preg_replace("/($uni)/i", $nonUnicode, $str);
	}
	return $str;
}
function encodeURL ($string) {
	$string = strtolower(str_replace(' ', '-', vn_str_filter($string)));
	return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
}

function mb_ucfirst ($string) {
	if (function_exists('mb_strtoupper') && function_exists('mb_substr'))
	{
		return mb_strtoupper(mb_substr($string, 0, 1), 'UTF-8').mb_substr($string, 1);
	}
	else
	{
		// Credit to Quicker at http://php.net/manual/en/function.ucfirst.php
		// If it does not work, replace it with another utf8 ucfirst function
		if ($string{0} >= "\xc3")
		{
		return ($string{1} >= "\xa0"
			? ($string{0}.chr(ord($string{1})-32))
			: ($string{0}.$string{1})).substr($string, 2);
		}
		return ucfirst($string);
	}
}

define('ENCRYPTION_KEY', 'd0a7e7997b6d5fcd55f4b5c32611b87cd923e88837b63bf2941ef819dc8ca282');
// Encrypt Function
function mc_encrypt ($encrypt, $key) {
	$encrypt = serialize($encrypt);
	$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
	$key = pack('H*', $key);
	$mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
	$passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
	$encoded = base64_encode($passcrypt).'|'.base64_encode($iv);
	return $encoded;
}
// Decrypt Function
function mc_decrypt ($decrypt, $key) {
	$decrypt = explode('|', $decrypt.'|');
	$decoded = base64_decode($decrypt[0]);
	$iv = base64_decode($decrypt[1]);
	if(strlen($iv)!==mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)){ return false; }
	$key = pack('H*', $key);
	$decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
	$mac = substr($decrypted, -64);
	$decrypted = substr($decrypted, 0, -64);
	$calcmac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
	if($calcmac!==$mac){ return false; }
	$decrypted = unserialize($decrypted);
	return $decrypted;
}

?>
