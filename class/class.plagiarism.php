<?php
	foreach (range('a', 'z') as $letter) $varSar[] = $letter;
	foreach (range('a', 'z') as $letter) $funcSar[] = $letter.'_'.rand(0, 100);
	$fucn = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));

	function arFilter ($var) {
		return ($var !== NULL && $var !== FALSE && $var !== '');
	}

class Plagiarism extends Config {
	private $hashTokenAr;
	private $tokens_CPP = Array (
    'datatypes' => 'ATOM BOOL BOOLEAN BYTE CHAR COLORREF DWORD DWORDLONG DWORD_PTR DWORD32 DWORD64 FLOAT HACCEL HALF_PTR HANDLE HBITMAP HBRUSH HCOLORSPACE HCONV HCONVLIST HCURSOR HDC HDDEDATA HDESK HDROP HDWP HENHMETAFILE HFILE HFONT HGDIOBJ HGLOBAL HHOOK HICON HINSTANCE HKEY HKL HLOCAL HMENU HMETAFILE HMODULE HMONITOR HPALETTE HPEN HRESULT HRGN HRSRC HSZ HWINSTA HWND INT INT_PTR INT32 INT64 LANGID LCID LCTYPE LGRPID LONG LONGLONG LONG_PTR LONG32 LONG64 LPARAM LPBOOL LPBYTE LPCOLORREF LPCSTR LPCTSTR LPCVOID LPCWSTR LPDWORD LPHANDLE LPINT LPLONG LPSTR LPTSTR LPVOID LPWORD LPWSTR LRESULT PBOOL PBOOLEAN PBYTE PCHAR PCSTR PCTSTR PCWSTR PDWORDLONG PDWORD_PTR PDWORD32 PDWORD64 PFLOAT PHALF_PTR PHANDLE PHKEY PINT PINT_PTR PINT32 PINT64 PLCID PLONG PLONGLONG PLONG_PTR PLONG32 PLONG64 POINTER_32 POINTER_64 PSHORT PSIZE_T PSSIZE_T PSTR PTBYTE PTCHAR PTSTR PUCHAR PUHALF_PTR PUINT PUINT_PTR PUINT32 PUINT64 PULONG PULONGLONG PULONG_PTR PULONG32 PULONG64 PUSHORT PVOID PWCHAR PWORD PWSTR SC_HANDLE SC_LOCK SERVICE_STATUS_HANDLE SHORT SIZE_T SSIZE_T TBYTE TCHAR UCHAR UHALF_PTR UINT UINT_PTR UINT32 UINT64 ULONG ULONGLONG ULONG_PTR ULONG32 ULONG64 USHORT USN VOID WCHAR WORD WPARAM WPARAM WPARAM',
    'types' => 'char bool short int __int32 __int64 __int8 __int16 long float double __wchar_t clock_t _complex _dev_t _diskfree_t div_t ldiv_t _exception _EXCEPTION_POINTERS FILE _finddata_t _finddatai64_t _wfinddata_t _wfinddatai64_t __finddata64_t __wfinddata64_t _FPIEEE_RECORD fpos_t _HEAPINFO _HFILE lconv intptr_t jmp_buf mbstate_t _off_t _onexit_t _PNH ptrdiff_t _purecall_handler sig_atomic_t size_t _stat __stat64 _stati64 terminate_function time_t __time64_t _timeb __timeb64 tm uintptr_t _utimbuf va_list wchar_t wctrans_t wctype_t wint_t signed',
    'keywords' => 'break case catch class const __finally __exception __try const_cast continue private public protected __declspec default delete deprecated dllexport dllimport do dynamic_cast else enum explicit extern if for friend goto inline mutable naked namespace new noinline noreturn nothrow register reinterpret_cast return selectany sizeof static static_cast struct switch template this thread throw true false try typedef typeid typename union using uuid virtual void volatile whcar_t while stdin std',
    'functions' => 'assert isalnum isalpha iscntrl isdigit isgraph islower isprintispunct isspace isupper isxdigit tolower toupper errno localeconv setlocale acos asin atan atan2 ceil cos cosh exp fabs floor fmod frexp ldexp log log10 modf pow sin sinh sqrt tan tanh jmp_buf longjmp setjmp raise signal sig_atomic_t va_arg va_end va_start clearerr fclose feof ferror fflush fgetc fgetpos fgets fopen fprintf fputc fputs fread freopen fscanf fseek fsetpos ftell fwrite getchar getch getc main gets perror printf putc putchar puts remove cout cin rename rewind scanf setbuf setvbuf sprintf sscanf tmpfile tmpnam ungetc vfprintf vprintf vsprintf abort abs atexit atof atoi atol bsearch calloc div exit free getenv labs ldiv malloc mblen mbstowcs mbtowc qsort rand realloc srand strtod strtol strtoul system wcstombs wctomb memchr memcmp memcpy memmove memset strcat strchr strcmp strcoll strcpy strcspn strerror strlen strncat strncmp strncpy strpbrk strrchr strspn strstr strtok strxfrm asctime clock ctime difftime gmtime localtime mktime strftime time',
    'pattern' => '\{ \} \# \( \) \, \; \" \' \& \< \>',
    'math' => '\+ \- \* \/ \=== \!== \== \!= \= % \< \>',
    'more' => 'var func'
);
	
	function hash_token ($lang) {
		if ($lang == 'cpp' || $lang == 'c') {
			$tokens_ = $this->tokens_CPP;
			$tokens_['pattern'] = str_replace('\\', '', $this->tokens_CPP['pattern']);
			$tokens_['math'] = str_replace('\\', '', $this->tokens_CPP['math']);
			$tokensAr = $tokens_;
			$tokensAr['more'] = 'var func';
			$hashTokenAr = array();
			$k = 1;
			foreach ($tokensAr as $oneTokensType => $tokens_str) {
				$tokens_ar = explode(' ', $tokens_str);
				foreach ($tokens_ar as $key => $oneToken) {
					$hashTokenAr[$oneToken] = ($key+1)*pow(10, $k);
				}
				$k++;
			}
			$this->hashTokenAr = $hashTokenAr;
			return $hashTokenAr;
		}
		return false;
	}

	public function checkOnline () {
		$checkInternet = checkInternet();
		if ($checkInternet) {
			$cx = GG_CX_ID;
			$key = GG_API_KEY;
			
			$file = $this->_fileFormat;

			$handl = fopen($file, "r");
			if ($handl) {
				while (($line = fgets($handl)) !== false) {
					$buffer = trim(strip_comments($line));
//					echo $line.'~~'.$file;
					if (!preg_match('/((^#)|(^#.*?\n)|include|return 0|(else {$)|(}$)|^{$|(int main)|getch|if|using)/', $buffer)) {
						$ggsearch = ggsearch($buffer, GG_CX_ID);
						$checkOnline = json_decode($ggsearch, true);
//						echo $buffer.'~~~~~~';
//						print_r($checkOnline);
						$checkOnline['status'] = 'success';
						$this->checkOnline = $checkOnline;
						break;
					}
				}
				fclose($handl);
			}

			return ($ggsearch);
		} 
		return $this->checkOnline = array('status' => 'error', 'content' => 'No internet connection.');
	}

	function isSubmit ($file) {
		$query = "SELECT
					id
				FROM
					submissions
				WHERE 
					file = ?
				LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $file);
		$stmt->execute();
		return $stmt->rowCount();
	}
	
	public function checkLocal () {
		$_dir = $this->_dir;
		$_fileFormat = $this->_fileFormat;
		$_fileC = $this->_file;
		$_ext = end(explode('.', $_fileFormat));
		
	// only accepts c_cpp file
	$allowExt = array('cpp', 'c');
	if (in_array($_ext, $allowExt)) {
		$_Ar = array();
		$_content = file_get_contents($_fileC);
//		echo $_fileC.'~'.$_content;

		$_hA = $this->make($_content, 'cpp', 4, 4);
		$_FPS = $_hA['fingerprint'];
//		print_r($_hA);

		if (is_dir($this->_dir)) {
			$files = scandir($this->_dir);
			foreach ($files as $fk => $fi) {
				$file = $_dir.'/'.$fi;
				$fiAr = explode('.', $fi);
				$ext = end($fiAr);
				$fiU = explode('u', $fiAr[0])[1];
				$original_file = "{$_dir}/u{$fiU}/{$ext}/{$fiAr[1]}.{$ext}";

//				if (!is_file($file) || $file == $_fileFormat || $ext != $_ext || strpos($fi, 'time') == true || mb_substr($fi, 0, 2) == 'ut' ) { // not check file submitted from the contest
				if (!is_file($file) || $file == $_fileFormat || !in_array($ext, $allowExt) || check($fi, 'time')) { // check file submitted from the contest
					unset($files[$fk]);
				}
				// check if file is submitted
				else if (!$this->isSubmit($original_file)) {
					unset($files[$fk]);
				}
				else {
					$u = explode('/', explode('u', $_fileC)[1])[0];
				//	if (check($u, 't')) $u = explode('t', $u)[1];
					$fu = explode('u', explode('.', $fi)[0])[1];
					/*preg_match('/\/u(.*?)\/'.$_ext.'/', $_fileC, $match);
					$u = $match[1];*/

					if (!$fu || $fu == $u) unset($files[$fk]);
					else {
						$fiext = end(explode('.', $fi));
						$diff = $sAr = array();
						$fileC = $_dir."/u$fu/$ext/".explode('.', $fi)[1].".$ext";
//						$fileC = $original_file;
						$content = file_get_contents($fileC);
						$hA = $this->make($content, 'cpp', 4, 4);
						$FPS = $hA['fingerprint'];

						$similar = $this->_array_intersect($_FPS, $FPS);
						$perSi = round(count($similar)/count($_FPS)*100, 2);
						$perSi2 = round(count($similar)/count($FPS)*100, 2);
						if ($perSi < 70 || $perSi2 < 70) unset($files[$fk]);
						else {
					/*		echo $_hA['original'].' ~~~~ '.$hA['original'].' ========== ';
							echo $_hA['format'].' ~~~~ '.$hA['format'].' ========== ';
							print_r($_FPS);
							print_r($FPS);
							print_r($similar);
							echo $_fileC.' ~ '. $fileC.' ========== ';
							echo $perSi.' ~ '. $perSi2.' ========== ';
					*/		$perSi = ($perSi > $perSi2) ? $perSi : $perSi2;
							$ufid = preg_replace('/u(.*?)\.(.*)/', "$1", $fi);
							$isTeam = check($ufid, 't');
							if ($isTeam) { // is team
								$ufid = explode('t', $ufid)[1];
								$ufin = $this->getTeamInfo($ufid);
							} else {
								$ufin = $this->getUserInfo($ufid);
							}
							$files[$fk] = array('file' => $fi, 'u' => $ufid, 'uname' => $ufin['name'], 'isTeam' => $isTeam, 'ext' => $fiext, 'per' => $perSi, 'sAr' => $similar, 'p1' => htmlentities(file_get_contents($_fileC)), 'p2' => htmlentities(file_get_contents($fileC)));
							$_Ar['simi'][] = $fi.'::'.$perSi;
						}
					}
				}
			}
			$_Ar['similar'] = array_values($files);
			$_Ar['status'] = 'success';
		} else $_Ar = array('status' => 'error', 'content' => 'Directory '.$this->_dir.' not found.');
	} else $_Ar = array('status' => 'disabled', 'content' => 'Sorry. Plagiarism checker only accepts C++ file.');
		$this->checkLocal = $_Ar;
	}

	function getTeamInfo ($teamID) {
		$query = "SELECT id,title FROM team WHERE id = ? limit 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $teamID);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->title = $row['name'] = $row['title'];
		$this->link = $this->tmLink.'/'.$row['id'];
		return $row;
	}
	
	function showDetection ($uNameAr = null) {
		echo '<h3 class="toggle-opens">Comparison details </h3>
		<div class="pair-compare toggles">';
		foreach ($this->cont as $p => $content) {
//			$content = $txtAr[$p];
//			echo nl2br($content);
			$pAr[$p] = $pA = $this->make($content, 'cpp', 4, 4);
			$wH = $pA['winnow']['hash'];
			$wC = $pA['winnow']['char'];
			$FPS = $FPSar[$p] = $pA['fingerprint'];

			echo '<div class="col-lg-6 one">';

			echo '<h3 id="p'.$p.'" class="ppd"><i>';
			echo ($uNameAr[$p]) ? $uNameAr[$p] : '&nbsp;';
			echo '</i></h3>';
			echo '<div class="compare-one">

			<h4>Orginal</h4> <pre class="code"><code>'.($pA['original']).'</code></pre>';
			echo '<h4>Formatted</h4> <pre class="beautify"></pre>';
			echo '<h4>Processed</h4> <pre class="toTokens"><span class="token">'.str_replace(' ', '</span> <span class="token">', htmlentities($pA['format'])).'</span></pre>';
			
			echo '<h4>Fingerprint</h4> <pre class="code"><code>';
			print_r($FPS);
			echo '</code></pre>';
			
			echo '<h4 class="toggle-open hash-tbl-open">Hash table</h4> 
			<div class="toggle hash-tbl">';
			$l = 0;
			foreach ($wH as $i => $wO) { // one winnow
				$ii = $i + 1;
				echo '<div class="line line-'.$i.'"><i class="lines-count" title="Line '.$ii.'">'.$ii.'</i>';
				echo '<table class="tbl table-bordered">';
				foreach ($wO as $j => $wL) { // one winnow line
					echo '<tr>';
					foreach ($wL as $k => $v) { // one value
						if (isset($FPS[$l]) && $FPS[$l] == $v) $cls = 'bold';
						else $cls = '';
						echo '<td class="'.$cls.'">'.$v.'
						<span class="gensmall">'.$wC[$i][$j][$k].'</span>
						</td>';
					}
					echo '</tr>';
					$l++;
				}
				echo '</table>';
				echo '</div> <!-- .line -->';
			}
			echo '</div> <!-- .toggle -->';

			echo '</div> <!-- .compare-one -->';
			echo '</div> <!-- .col-lg-6 -->';
		}
		echo '<div class="clearfix"></div>';
		$similar = $this->_array_intersect($FPSar[0], $FPSar[1]);
		$perSi = round(count($similar)/count($FPSar[0])*100, 2);
		$perSi2 = round(count($similar)/count($FPSar[1])*100, 2);
		if ($perSi > 70 && $perSi2 > 70) $detected = 'detected';
		else $detected = 'safe';
		$perSi = ($perSi > $perSi2) ? $perSi : $perSi2;
		echo '<div class="similarity"><h4>Similarity</h4>
		<div class="similarity-ar col-lg-8 no-padding"><pre><code>'; print_r($similar); echo '</code></pre></div>';
		echo '<div class="col-lg-4 no-padding-right"><div class="detect '.$detected.'">'.$detected.' <span class="small">'.$perSi.'%</span></div></div>';
		echo '</div> <!-- .similarity -->
		<div class="clearfix"></div>';
		echo '</div> <!-- .pair-compare -->';
	}

	
	
	function _array_intersect ($haystack1 = array(), $haystack2 = array()) {
		$haystack = array();
		foreach ($haystack1 as $key1 => $value) {
			if (in_array($value, $haystack2)) {
				$key2 = array_search($value, $haystack2);
				$haystack[] = $value;
				unset($haystack1[$key1]);
				unset($haystack2[$key2]);
			}
		}
		return $haystack;
	}

	function get_hash_of_token ($str) {
		if (!$this->hashTokenAr) $this->hash_token('cpp');
		return $this->hashTokenAr[$str];
	}

	function _hash ($content, $token = 2, $w = 4) {
		$_Ar = array_values(array_filter(explode('; ', html_entity_decode($content)), 'arFilter'));
		$fps = $FPS = array();
		// merge all lines of $_Ar
		$str_merge = '';
		foreach ($_Ar as $line => $string) {
			$str_merge .= $string;
			unset($_Ar[$line]);
		}
		$string = $str_merge;
	//	foreach ($_Ar as $line => $string) {
	//		$string = html_entity_decode($string);
			$_ar = array_values(array_filter(explode(' ', $string), 'arFilter'));
			$length = count($_ar);
			$str = '';
			$wn = 0;
	//		$fps[$line][$wn] = 0; // Set default value for this winnow
			if ($length > $token) {
/*				for ($i = 0; $i < $length - $token + 1; $i++) {
					$str = implode('', array_slice($_ar, $i, $token));
*/				for ($i = 0; $i < $length-1; $i++) {
					//$intT = ord($str);
					$strp = $_ar[$i];
					$strn = $_ar[$i+1];
					$str = $strp.$strn;
					//echo $str.'~~~~~~~';
					$intT = $this->get_hash_of_token($strp)+$this->get_hash_of_token($strn);

					// Save minimum value of this group to fingerprint array
					if (!isset($fps[$line][$wn])) $fps[$line][$wn] = $intT; // set if value is not set
					else if ($intT < $fps[$line][$wn]) { // if n greater than value of this winnow
						$fps[$line][$wn] = $intT; // then change value of this winnow to n
					}

					$H[$line][$wn][] = $intT;
					/* Save char to create table, not neccessary */
					$C[$line][$wn][] = $str;
					
					if (($i+1)%$w == 0) {
						$wn++;
	//					$fps[$line][$wn] = 0; // Set value for new winnow
					}
				}
			} else {
				if ($length == 1) {
					if (!preg_match('/\d|\s/', $str)) $str = $_ar[0];
				} else {
					for ($i = 0; $i < $length; $i++)
						$str .= $_ar[$i];
				}

				$intT = ord($str);

				// Save minimum value of this group to fingerprint array
				if (!isset($fps[$line][$wn])) $fps[$line][$wn] = $intT; // set if value is not set
				else if ($intT < $fps[$line][$wn]) { // if n greater than value of this winnow
					$fps[$line][$wn] = $intT; // then change value of this winnow to n
				}

				$H[$line][$wn][] = $intT;
				/* Save char to create table, not neccessary */
				$C[$line][$wn][] = $str;
				
				$wn++;
			}
			$FPS = array_merge($FPS, array_values(array_filter($fps[$line])) );
//		} // end foreach $_Ar

		$A['winnow']['hash'] = $H;
		$A['winnow']['char'] = $C;
	//	$A['all']['hash'] = $h;
	//	$A['all']['char'] = $c;
		$A['fingerprint'] = $FPS;
		return $A;
	}


	function make ($original_content, $ext, $kgram = 2, $w = 4) {
		$content = rtrim($original_content);
		// remove comment
		$content = preg_replace('!/\*.*?\*/!s', '', $content);
		$content = preg_replace('#^\s*//.+$#m', '', $content);
		$content = preg_replace('/\n\s*\n/', "\n", $content);

		//  Removes multi-line comments and does not create
		//  a blank line, also treats white spaces/tabs 
		$content = preg_replace('!^[ \t]*/\*.*?\*/[ \t]*[\r\n]!s', '', $content);
		//  Removes single line '//' comments, treats blank characters
		$content = preg_replace('![ \t]*//.*[ \t]*[\r\n]!', '', $content);
		//  Strip blank lines
		$content = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $content);
		
		// remove include
		$content = preg_replace("/^.*(?:#|include|using namespace std\;|std\:\:).*$/m", "", $content);
		
		$content = $cont = trim(preg_replace('/\n|\s+/', '', $content));

		// Format content
	//	$content = _format($content); // Called in tokenize function

		// Tokenize
		$tAr = $this->_tokens($content, $ext);
		$content = $tAr['txt'];
		$content = preg_replace('/{|}/', '', $content);
		$content = str_replace("'", '"', $content);
//		echo $content.'~~~~';

		$H = $this->_hash($content, $kgram, $w);
//		print_r($H);
	//	$A = $H['all'];
		$W = $H['winnow'];
	//	$c = $A['char'];
	//	$h = $A['hash'];
		
		return array (
			'original' => $original_content,
			'format' => $content,
	//		'all' => $A,
			'winnow' => $W,
			'fingerprint' => $H['fingerprint']
		);
	}

	function _format ($content, $types) {
		// remove using namespace std;
//		$content = str_replace('using namespace std;', '', $content);
		
		// Convert printf to cout
		$content = htmlspecialchars_decode($content);
		preg_match_all('/printf\("(.*?)",(.*?)\)/', $content, $matches);
//		preg_match_all('/\((["])([^"]+)\1,(.*?)\)/', $content, $matches);
//		print_r($matches);
//		echo '<hr/>';
		foreach ($matches[1] as $i => $m) {
			if (isset($matches[2][$i])) $r = rtrim($matches[2][$i]);
			else $r = '';
/*			preg_match('/(.*?|\d)%((\.|\d)[0-9][a-z]{1})/', $matches[1][$i], $mat);
			print_r($mat);
			echo '<hr/>';
			if (isset($mat[1]) && $mat[1]) $t = preg_replace('/(.*?|\d)%((\.|\d)[0-9][a-z]{1})/', "\"$1\">>{$r}", $matches[1][$i]);
			else $t = preg_replace('/%((\.|\d)[0-9][a-z]{1})/', $r, $matches[1][$i]);
*/
			$t = $r;
			$rp = 'cout<<'.$t;
			$content = str_replace($matches[0][$i], $rp, $content);
		}

		// Convert scanf to cin
		preg_match_all('/scanf\("(.*?)",(.*?)\)/', $content, $matches);
//		print_r($matches);
//		echo '<hr/>';
		foreach ($matches[1] as $i => $m) {
			$spl1 = array_values(array_filter(preg_split('%', $matches[1][$i])));
			$spl2 = array_values(array_filter(preg_split('/&|,/', $matches[2][$i])));
			$t = preg_replace('/scanf\("(.*?)"(.*?)\)/', $r, implode('>>', $spl2));
			$rp = 'cin>>'.$t;
			$content = str_replace($matches[0][$i], $rp, $content);
		}

		// Convert float a, b to float a; float b
		$types = str_replace(' ', '|', $types);
		preg_match_all('/(float|int)(.*?)(float|int|\(|\;)/', $content, $matches);
	//	$matches = split($types, $content);
	//	print_r($matches);
		$matches[1] = array_values(array_filter($matches[1]));
		$matches[2] = array_values(array_filter($matches[2]));
		foreach ($matches[2] as $i => $m) {
			$vars = array_values(array_filter(explode(',', $m)));
			if (count($vars) > 1) {
				$type = $matches[1][$i];
				$rp = $type.implode(';'.$type, $vars).';';
				$content = str_replace($matches[0][$i], $rp, $content);
			}
		}
		
		// Convert do while to while
		preg_match_all('/do\{(.*?)\}while\((.*)\)/', $content, $matches);
		foreach ($matches[1] as $i => $m) {
			$rp = 'while('.$matches[2][$i].'){'.$m.'}';
			$content = str_replace($matches[0][$i], $rp, $content);
		}
		
		// Convert while to for (not work)
		preg_match_all('/while\((.*?)\)\{(.*)\}/', $content, $matches); // Catch fine
		foreach ($matches[1] as $i => $m) {
			$mA = str_split($m);
			$mI = $mA[0];
			$mN = $mA[2];
			$mS = $mA[1];
			if ($mS == '<') {
				preg_match_all('/'.$mI.'\+\+/', $content, $iadd); // Catch i++
			}
		}

		return $content;
	}


	function train ($fi, $fk) {
		$_dir = $this->_dir;
		if (is_dir($this->_dir)) {
			$files = scandir($this->_dir);
			foreach ($files as $fk => $fi) {
				$file = $_dir.'/'.$fi;
				$ext = end(explode('.', $fi));
				if (!is_file($file) || strpos($fi, 'time') == true || $ext != 'cpp' || $ext != 'c') 
					unset($files[$fk]);
				else {
					$content = file_get_contents($file);
					$tokensAr = $this->_tokens($content, $ext);
					$this->trainedData[] = array('file' => $file, 'content' => $content, 'data' => $tokensAr);
				}
			}
			return $this->trainedData;
		}
		return false;
	}
	

	function tokenize ($input, $tokens, $tokens_) {
		global $varSar, $funcSar;
		$tokensStr = implode(' ', $tokens);
		$tokensStr = str_replace(' ', '|', $tokensStr);
		$input = html_entity_decode($input);
		$inputA = trim(preg_replace('/\s+/', '', $input));
		$_tokensAr['all'] = $tokensAr = preg_split("/({$tokensStr})/", $inputA, 0, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
	//	print_r($tokensAr);

		$tokAr = $func = $var = array();
		foreach ($tokens_ as $toke) {
			$tokAr = array_merge($tokAr, explode(' ', $toke));
		}

		$var_n = $func_n = -1;
	//	print_r($funcSar);
		foreach ($tokensAr as $k => $token) {
	//		preg_match('/^((?![0-9]).)/', $token, $match);
			preg_match('/^[0-9](.*)$/', $token, $match);
			if (!preg_match('/^[0-9](.*)$/', $token) && !in_array($token, $tokAr)) {
		/*		if ($token != 'include' && 
					(	!isset($tokensAr[$k-2]) || 
						$tokensAr[$k-2] != 'include'
					) && 
					(	!isset($tokensAr[$k-3]) || 
						!in_array($tokensAr[$k-3], array('printf', 'cin', 'cout', 'scanf')) 
					) && 
					(	!isset($tokensAr[$k-4]) || 
						(
							$tokensAr[$k-1] != '%' &&
							!in_array($tokensAr[$k-4], array('cin', 'scanf'))
						) // scanf("%d")
					) 
				) { */
					$tokensAr[$k] = '';
					if (!isset($tokensAr[$k+1]) || $tokensAr[$k+1] != '(') {
						if (!in_array($token, $var)) {
							$var[] = $token;
							$var_n++;
						} else $var_n = array_search($token, $var);
//						$tokensAr[$k] = $varSar[$var_n];
						// change all to var
						$tokensAr[$k] = 'var';
					} else {
						if (!in_array($token, $func)) {
							$func[] = $token;
							$func_n++;
						} else $func_n = array_search($token, $func);
//						$tokensAr[$k] = $funcSar[$func_n];
						// change all to func
						$tokensAr[$k] = 'func';
					}
			//	}
			}
		}
	//	print_r($tokensAr);
	//	print_r($func);

/*		$remove = str_replace(' ', '|', $tokens['pattern'].' main');
		unset($tokens['pattern']);
		unset($tokens['math']);
*/
	//	$inputS = $input;
		$inputS = implode(' ', $tokensAr);
	//	$based = 'include if else';
	//	$base = str_replace(' ', '', implode(' ', $tokens).' '.$based);
	//	$bAr = implode(' ', $base);
	//	$inputS = preg_replace("/[^\binclude\b]/i", ' dis ', $inputS);
	//	echo $inputS;
	//	$inputS = preg_replace("/{$remove}/", ' ', $inputS);
	//	$inputS = preg_replace('/[^ \w]+/i', '', strtolower($inputS));

	//	$inputS = preg_replace('!\s+!', ' ', $inputS);
	//	$inputS = preg_replace('/(^\s+)|(\s+\S*$)/', '', $inputS);

		$_tokensAr['txt'] = $inputS;
		$_tokensAr['tokens'] = $tokensAr;
		
		$_tokens = count($_tokensAr);
		return $_tokensAr;
	}

	function _tokens ($input, $_fLang) {
		if ($_fLang == 'cpp' || $_fLang == 'c') { // only accepts C++ (C) file
			$tokens_ = $tokens = $this->tokens_CPP;
			$tokens_['pattern'] = str_replace('\\', '', $this->tokens_CPP['pattern']);
			$tokens_['math'] = str_replace('\\', '', $this->tokens_CPP['math']);
		}
		$input = $this->_format($input, $types);
		return $this->tokenize ($input, $tokens, $tokens_);
	}

}
