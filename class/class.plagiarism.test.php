<?php
	foreach (range('a', 'z') as $letter) $varSar[] = $letter;
	foreach (range('a', 'z') as $letter) $funcSar[] = $letter.'_'.rand(0, 100);
	$fucn = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));

	function arFilter ($var) {
		return ($var !== NULL && $var !== FALSE && $var !== '');
	}

class Plagiarism extends Config {

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

	public function checkLocal () {
		$_dir = $this->_dir;
		$_fileFormat = $this->_fileFormat;
		$_fileC = $this->_file;
		$_ext = end(explode('.', $_fileFormat));
		
	// only accepts cpp file
	if ($_ext == 'cpp') {
		$_Ar = array();

		$_content = file_get_contents($this->_fileFormat);

		if (is_dir($this->_dir)) {
			$files = scandir($this->_dir);
			foreach ($files as $fk => $fi) {
				$file = $_dir.'/'.$fi;
				$ext = end(explode('.', $fi));
				if (!is_file($file) || $file == $_fileFormat || $ext != $_ext || strpos($fi, 'time') == true || mb_substr($fi, 0, 2) == 'ut' ) 
					unset($files[$fk]);
				else {
					$u = $this->u;
					$fu = explode('u', explode('.', $fi)[0])[1];
					if (!$fu || $fu == $u) unset($files[$fk]);
					else {
						$fiext = end(explode('.', $fi));
						$diff = $sAr = array();
						$fileC = $_dir."/u$fu/$ext/".explode('.', $fi)[1].".$ext";
						
						$content = file_get_contents($file);
						$hA = $this->make($content, $ext, 4, 4);
						$FPS = $hA['fingerprint'];

						$similar = $this->_array_intersect($_FPS, $FPS);
						$perSi = round(count($similar)/count($_FPS)*100, 2);
						
						if ($perSi < 75) unset($files[$fk]);
						else {
							$ufid = str_split(explode('.', $fi)[0])[1];
							$ufin = $this->getUserInfo($ufid);
							$files[$fk] = array('file' => $fi, 'u' => $ufid, 'uname' => $ufin['username'], 'ext' => $fiext, 'per' => $perSi, 'sAr' => $similar, 'p1' => htmlentities(file_get_contents($_fileC)), 'p2' => htmlentities(file_get_contents($fileC)));
							$_Ar['simi'][] = $fi.'::'.$perSi;
						}
					}
				}
			}
			$_Ar['similar'] = array_values($files);
			$_Ar['status'] = 'success';
		} else $_Ar = array('status' => 'error', 'content' => 'No directory found.');
	} else {
		$_Ar = array('status' => 'disabled', 'content' => 'Sorry. Plagiarism checker only accepts C and C++ file.');
	}
		$this->checkLocal = $_Ar;
	}

	
	function train ($fi, $fk) {
		$_dir = $this->_dir;
		if (is_dir($this->_dir)) {
			$files = scandir($this->_dir);
			foreach ($files as $fk => $fi) {
				$file = $_dir.'/'.$fi;
				$ext = end(explode('.', $fi));
				if (!is_file($file) || strpos($fi, 'time') == true || $ext != 'cpp') 
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
	
	function checkCode ($content) {
		$tokensAr = $this->_tokens($content, $ext);
		// do the check here
		
	}

	function _format ($content, $types) {
		// Remove include
		$content = preg_replace("/^(include|#).*?\n/m", '', $content);
		
		// Convert printf to cout
		preg_match_all('/printf\("(.*?)"(.*?)\)/', $content, $matches);
		foreach ($matches[1] as $i => $m) {
			if ($matches[2][$i]) $r = substr($matches[2][$i], 1);
			else $r = '';
			
			preg_match('/(.*?|\d)%((\.|\d)[0-9][a-z]{1})/', $matches[1][$i], $mat);
			if (isset($mat[1]) && $mat[1]) $t = preg_replace('/(.*?|\d)%((\.|\d)[0-9][a-z]{1})/', "\"$1\">>{$r}", $matches[1][$i]);
			else $t = preg_replace('/%((\.|\d)[0-9][a-z]{1})/', $r, $matches[1][$i]);
			$rp = 'cout>>'.$t;
			$content = str_replace($matches[0][$i], $rp, $content);
		}

		// Convert scanf to cin
		preg_match_all('/scanf\("(.*?)"(.*?)\)/', $content, $matches);
		foreach ($matches[1] as $i => $m) {
			$spl1 = array_values(array_filter(preg_split('%', $matches[1][$i])));
			$spl2 = array_values(array_filter(preg_split('&|,', $matches[2][$i])));
			$t = preg_replace('/scanf\("(.*?)"(.*?)\)/', $r, implode('<<', $spl2));
			$rp = 'cin<<'.$t;
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

	function tokenize ($input, $tokens, $tokens_) {
		global $varSar, $funcSar;
		$tokensStr = implode(' ', $tokens);
		$tokensStr = str_replace(' ', '|', $tokensStr);
		$input = html_entity_decode($input);
		$inputA = trim(preg_replace('/\s+/', '', $input));
		//$_tokensAr['all'] =
		$tokensAr = preg_split("/({$tokensStr})/", $inputA, 0, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);

		$langTokens = $this->CppTokens;

		$var_n = $func_n = -1;
		
		$tokAr = $func = $var = array();
		foreach ($tokens_ as $toke) {
			$tokAr = array_merge($tokAr, explode(' ', $toke));
		}
		
	//	print_r($funcSar);
		foreach ($tokensAr as $k => $token) {
	//		preg_match('/^((?![0-9]).)/', $token, $match);
			preg_match_all("/{$tokensStr}/", $token, $match);
			//print_r($match);

//			if (!in_array($token, $langTokens)) {
			if (!preg_match('/^[0-9](.*)$/', $token) && !in_array($token, $tokAr)) {
				if ($token != 'include' && 
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
				) {
					//echo $token.'~<br/>';
					$tokensAr[$k] = '';
					if (!isset($tokensAr[$k+1]) || $tokensAr[$k+1] != '(') {
						if (!in_array($token, $var)) {
							$var[] = $token;
							$var_n++;
						} else $var_n = array_search($token, $var);
						//$tokensAr[$k] = $varSar[$var_n];
						$tokensAr[$k] = 'var_'.$var_n;
					} else {
						if (!in_array($token, $func)) {
							$func[] = $token;
							$func_n++;
						} else $func_n = array_search($token, $func);
						//$tokensAr[$k] = $funcSar[$func_n];
						$tokensAr[$k] = 'func_'.$func_n;
					}
				}
			}
		}
	//	print_r($tokensAr);
	//	print_r($func);
	//	print_r($langTokens);
		
	//	foreach ($langTokens as $tokenKey => $oneToken) $tokensCountAr[$tokenKey] = 0;
		foreach ($tokensAr as $oneToken) {
			if (strpos($oneToken, 'var') !== false) $tokenKey = array_search('var', $langTokens);
			else if (strpos($oneToken, 'func') !== false) $tokenKey = array_search('func', $langTokens);
			else $tokenKey = array_search($oneToken, $langTokens);
			
			if ($tokenKey >= 0) $tokensCountAr[$tokenKey]++;
			else $tokensCountAr[$tokenKey] = 0;
			//echo $oneToken.' ~ '.$tokenKey.' ~ '.$tokensCountAr[$tokenKey].'<br/>';
		}
		ksort($tokensCountAr); // sort by tokenKey
		$inputS = implode(' ', $tokensAr);
		
/*		echo 'InputS '; echo '<pre>'.$inputS.'</pre>';
		echo 'File Tokens Ar '; echo '<pre>'; print_r($tokensAr); echo '</pre>';
		echo 'Lang Tokens '; echo '<pre>'; print_r($langTokens); echo '</pre>';
		echo 'Tokens count (Matrix) '; echo '<pre>'; print_r($tokensCountAr); echo '</pre>';
*/		
		$_tokensAr['txt'] = $inputS;
		$_tokensAr['tokens'] = $tokensAr;
		$_tokensAr['tokensCountAr'] = $tokensCountAr;
		
		$_tokens = count($_tokensAr);
		return $_tokensAr;
	}

	function _tokens ($input, $_fLang) {
		if ($_fLang == 'cpp') {
			$datatypes =	'ATOM BOOL BOOLEAN BYTE CHAR COLORREF DWORD DWORDLONG DWORD_PTR ' .
							'DWORD32 DWORD64 FLOAT HACCEL HALF_PTR HANDLE HBITMAP HBRUSH ' .
							'HCOLORSPACE HCONV HCONVLIST HCURSOR HDC HDDEDATA HDESK HDROP HDWP ' .
							'HENHMETAFILE HFILE HFONT HGDIOBJ HGLOBAL HHOOK HICON HINSTANCE HKEY ' .
							'HKL HLOCAL HMENU HMETAFILE HMODULE HMONITOR HPALETTE HPEN HRESULT ' .
							'HRGN HRSRC HSZ HWINSTA HWND INT INT_PTR INT32 INT64 LANGID LCID LCTYPE ' .
							'LGRPID LONG LONGLONG LONG_PTR LONG32 LONG64 LPARAM LPBOOL LPBYTE LPCOLORREF ' .
							'LPCSTR LPCTSTR LPCVOID LPCWSTR LPDWORD LPHANDLE LPINT LPLONG LPSTR LPTSTR ' .
							'LPVOID LPWORD LPWSTR LRESULT PBOOL PBOOLEAN PBYTE PCHAR PCSTR PCTSTR PCWSTR ' .
							'PDWORDLONG PDWORD_PTR PDWORD32 PDWORD64 PFLOAT PHALF_PTR PHANDLE PHKEY PINT ' .
							'PINT_PTR PINT32 PINT64 PLCID PLONG PLONGLONG PLONG_PTR PLONG32 PLONG64 POINTER_32 ' .
							'POINTER_64 PSHORT PSIZE_T PSSIZE_T PSTR PTBYTE PTCHAR PTSTR PUCHAR PUHALF_PTR ' .
							'PUINT PUINT_PTR PUINT32 PUINT64 PULONG PULONGLONG PULONG_PTR PULONG32 PULONG64 ' .
							'PUSHORT PVOID PWCHAR PWORD PWSTR SC_HANDLE SC_LOCK SERVICE_STATUS_HANDLE SHORT ' .
							'SIZE_T SSIZE_T TBYTE TCHAR UCHAR UHALF_PTR UINT UINT_PTR UINT32 UINT64 ULONG ' .
							'ULONGLONG ULONG_PTR ULONG32 ULONG64 USHORT USN VOID WCHAR WORD WPARAM WPARAM WPARAM';
			$types = 'char bool short int __int32 __int64 __int8 __int16 long float double __wchar_t ' .
							'clock_t _complex _dev_t _diskfree_t div_t ldiv_t _exception _EXCEPTION_POINTERS ' .
							'FILE _finddata_t _finddatai64_t _wfinddata_t _wfinddatai64_t __finddata64_t ' .
							'__wfinddata64_t _FPIEEE_RECORD fpos_t _HEAPINFO _HFILE lconv intptr_t ' .
							'jmp_buf mbstate_t _off_t _onexit_t _PNH ptrdiff_t _purecall_handler ' .
							'sig_atomic_t size_t _stat __stat64 _stati64 terminate_function ' .
							'time_t __time64_t _timeb __timeb64 tm uintptr_t _utimbuf ' .
							'va_list wchar_t wctrans_t wctype_t wint_t signed';

			$keywords =	'break case catch class const __finally __exception __try ' .
							'const_cast continue private public protected __declspec ' .
							'default delete deprecated dllexport dllimport do dynamic_cast ' .
							'else enum explicit extern if for friend goto inline ' .
							'mutable naked namespace new noinline noreturn nothrow ' .
							'register reinterpret_cast return selectany ' .
							'sizeof static static_cast struct switch template this ' .
							'thread throw true false try typedef typeid typename union ' .
							'using uuid virtual void volatile whcar_t while stdin';
						
			$functions =	'assert isalnum isalpha iscntrl isdigit isgraph islower isprint' .
							'ispunct isspace isupper isxdigit tolower toupper errno localeconv ' .
							'setlocale acos asin atan atan2 ceil cos cosh exp fabs floor fmod ' .
							'frexp ldexp log log10 modf pow sin sinh sqrt tan tanh jmp_buf ' .
							'longjmp setjmp raise signal sig_atomic_t va_arg va_end va_start ' .
							'clearerr fclose feof ferror fflush fgetc fgetpos fgets fopen ' .
							'fprintf fputc fputs fread freopen fscanf fseek fsetpos ftell ' .
							'fwrite getchar getch getc main gets perror printf putc putchar puts remove ' .
							'cout cin ' .
							'rename rewind scanf setbuf setvbuf sprintf sscanf tmpfile tmpnam ' .
							'ungetc vfprintf vprintf vsprintf abort abs atexit atof atoi atol ' .
							'bsearch calloc div exit free getenv labs ldiv malloc mblen mbstowcs ' .
							'mbtowc qsort rand realloc srand strtod strtol strtoul system ' .
							'wcstombs wctomb memchr memcmp memcpy memmove memset strcat strchr ' .
							'strcmp strcoll strcpy strcspn strerror strlen strncat strncmp ' .
							'strncpy strpbrk strrchr strspn strstr strtok strxfrm asctime ' .
							'clock ctime difftime gmtime localtime mktime strftime time';
							
			$pattern = '<< >> \{ \} \# \( \) \, \; \" \' \&';
			$math = '\+ \- \* \/ \=== \!== \== \!= \= % \< \>';

			$tokens['datatypes'] = $tokens_['datatypes'] = $datatypes;
			$tokens['types'] = $tokens_['types'] = $types;
			$tokens['keywords'] = $tokens_['keywords'] = $keywords;
			$tokens['functions'] = $tokens_['functions'] = $functions;
			$tokens['pattern'] = $pattern;
			$tokens['math'] = $math;
			
			$tokensAr[] = 'var';
			$tokensAr[] = 'func';
			$tokensAr = array_merge(
					$tokensAr,
					explode(' ', "{ } # ( ) , ; \" ' &"), 
					explode(' ', "+ - * / === !== == != = % < >"),
					explode(' ', $keywords), 
					explode(' ', $functions), 
					explode(' ', $types), 
					explode(' ', $datatypes)
				);

			
			$this->CppTokens = $tokensAr;
			
			$tokens_['pattern'] = str_replace('\\', '', $pattern);
			$tokens_['math'] = str_replace('\\', '', $math);
		}
		$input = $this->_format($input, $types);
		return $this->tokenize ($input, $tokens, $tokens_);
	}

}
