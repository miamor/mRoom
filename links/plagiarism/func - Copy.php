<?
function generateRandomString ($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
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

function arFilter ($var) {
	return ($var !== NULL && $var !== FALSE && $var !== '');
}

function _hash ($content, $token = 2, $w = 4) {
	$_Ar = array_values(array_filter(explode('; ', html_entity_decode($content)), 'arFilter'));
	$fps = $FPS = array();
	foreach ($_Ar as $line => $string) {
//		$string = html_entity_decode($string);
		$_ar = array_values(array_filter(explode(' ', $string), 'arFilter'));
		$length = count($_ar);
		$str = '';
		$wn = 0;
//		$fps[$line][$wn] = 0; // Set default value for this winnow
		if ($length > $token) {
			for ($i = 0; $i < $length - $token + 1; $i++) {
				$str = implode('', array_slice($_ar, $i, $token));
				$intT = ord($str);

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
	}
//	print_r($h);
//	print_r($H);
//	print_r($fps);
//	print_r($FPS);
	$A['winnow']['hash'] = $H;
	$A['winnow']['char'] = $C;
//	$A['all']['hash'] = $h;
//	$A['all']['char'] = $c;
	$A['fingerprint'] = $FPS;
	return $A;
}


function make ($original_content, $kgram = 2, $w = 4) {
	$content = $cont = trim(preg_replace('/\n|\s+/', '', $original_content));

	// Format content
//	$content = _format($content); // Called in tokenize function

	// Tokenize
	$tAr = _tokens($content, 'cpp');
	$content = $tAr['txt'];
	$content = preg_replace('/{|}/', '', $content); // remove brackets
	$content = str_replace("'", '"', $content);

	$H = _hash($content, $kgram, $w);
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


function showDetection (array $compareAr, array $txtAr, string $eg, bool $usingML = false) {
foreach ($compareAr as $pair) {
	if ($eg) $si = '<span style="font-size:17px">(Should be <b>' . ( ($pair[2]) ? '<span class="text-success">true</span>' : '<span class="text-danger">false</span>' ) . '</b>)</span>';
	else $si = '';
	echo '<div class="pair" id="'.$pair[0].'-'.$pair[1].'">
	<h3 class="toggle-opens">Pair '.$pair[0].' - '.$pair[1].' '.$si.'</h3>
	<div class="pair-compair toggles">';
	foreach ($pair as $k => $p) {
		if ($k != 2) {
			$content = $txtAr[$p];
			$pAr[$p] = $pA = make($content, 4, 4);
			$wH = $pA['winnow']['hash'];
			$wC = $pA['winnow']['char'];
			$FPS = $FPSar[$p] = $pA['fingerprint'];

			echo '<div class="col-lg-6 one">';

			echo '<h3 id="p'.$p.'" class="ppd"><i>p'.$p.'</i></h3>';
			echo '<div class="compare-one">

			<h4>Orginal</h4> <pre class="code"><code>'.htmlentities($pA['original']).'</code></pre>';
			echo '<h4>Processed</h4> <pre class="toTokens"><span class="token">'.str_replace(' ', '</span> <span class="token">', htmlentities($pA['format'])).'</span></pre>';
			
			echo '<h4>Fingerprint</h4> <pre class="code"><code>';
			print_r($FPS);
			echo '</code></pre>';
			
			echo '<h4 class="toggle-open hash-tbl-open">Hash table (only for display purpose, no neccessary for work)</h4> 
			<div class="toggle hash-tbl">';
			$l = 0;
			foreach ($wH as $i => $wO) { // one winnow
				$ii = $i + 1;
				echo '<div class="line line-'.$i.'"><i title="Line '.$ii.'">'.$ii.'</i>';
				echo '<table class="tbl table-bordered margin">';
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
		} // end if k != 2
	}
	echo '<div class="clearfix"></div>';
	$p1 = $pair[0];
	$p2 = $pair[1];
	$similar = _array_intersect($FPSar[$p1], $FPSar[$p2]);
	$check = round(count($similar)/count($FPSar[$p1])*100, 2);
	if ($check >= 75) $detected = 'detected';
	else $detected = 'safe';
	echo '<div class="similarity"><h4>Similarity</h4>
	<pre><code>'; print_r($similar); echo '</code></pre>';
	echo '<div class="detect '.$detected.'">'.$detected.' <span class="small">'.$check.'%</span></div>';
	echo '</div> <!-- .similarity -->
	<div class="clearfix"></div>';
	echo '</div> <!-- .pair-compair -->
	</div> <!-- .pair -->';
}
}

function getDocMLDetails ($content, $docID = null, $runIfTrained = false, $threshold = 5) {
	// threshold is k minimum value to get
	global $_SESSION;
	
	// if no docID, generate random string
	if ($docID == null) $docID = generateRandomString(3);
	
	if (!$runIfTrained) return false;
	
	// check if this docID is in train data
//	if (isset($_SESSION['Author'][$docID])) return false;
	
	$displayAr = array();
	
	if ($content) {
		// get LTF
		$LTF[$docID] = $displayAr['LTF'] = getLTF($content);
		// get LWC
		$LWC[$docID] = $_SESSION['LWC'][$docID] = $displayAr['LWC'] = getLWC($content);
		
		$dmin = $d_tmp = 1000;
		// get distance of this doc to all training docs
		foreach ($_SESSION['Author'] as $i => $author) {
			if ($i != $docID) { // runIfFalse = true
				$LTF_docTrain = $LTF[$i] = $_SESSION['LTF'][$i];
				$LWC_docTrain = $LWC[$i] = $_SESSION['LWC'][$i];
				
				// compare LWC
				$m = 0;
				foreach ($LWC[$docID]['LWC'] as $tk => $oneT) {
					if (!isset($LWC_docTrain['LWC'][$tk])) $vp = 0;
					else $vp = $LWC_docTrain['LWC'][$tk];
				//	echo $tk.' => '.$oneT.'~~~~~~'.$vp.'~~~~~~~~~<br/>';
					$m += ($oneT - $vp)*($oneT - $vp);
				}
				$dist['LWC'][$i] = round(sqrt($m), 2);
				if ($dist['LWC'][$i] < $dmin) {
					$dmin = $dist['LWC'][$i];
					$use = 'LWC';
					// save nearest document
					$nD = $i;
					$authorDocID = $author;
				}
				
				// compare LTF
				$m = 0;
				foreach ($LTF[$docID]['LTF'] as $tk => $oneT) {
					if (!isset($LTF_docTrain['LTF'][$tk])) $vp = 0;
					else $vp = $LTF_docTrain['LTF'][$tk];
				//	echo $tk.' => '.$oneT.'~~~~~~'.$vp.'~~~~~~~~~<br/>';
					$m += ($oneT - $vp)*($oneT - $vp);
				}
				$dist['LTF'][$i] = round(sqrt($m), 2);
				if ($dist['LTF'][$i] < $dmin) {
					$dmin = $dist['LTF'][$i];
					$use = 'LTF';
					// save nearest document
					$nD = $i;
					// save author
					$authorDocID = $author;
				}
				
				// save everything of min
				$dminAr = array(
					'dist' => $dmin,
					'use' => $use,
					'nearestDoc' => $nD,
				);
			}
		}
		sort($dist['LWC']);
		print_r($dist['LWC']);
		$displayAr['Author'] = $authorDocID; // save author of this doc to trained doc author
		$displayAr['dmin'] = $dminAr;
		$displayAr['dist'] = $dist;
	//	$displayAr['nearestDoc'] = $nD;


		// And train sat the same time
		$_SESSION['LTF'][$docID] = $LTF[$docID];
		$_SESSION['LWC'][$docID] = $LWC[$docID];
		$_SESSION['Author'][$docID] = $authorDocID;


		// return full value for displaying purpose
		return $displayAr;
	} return false;
}

function showDetectionML ($content, $docID = null) {
	global $_SESSION;
	
	$doc = getDocMLDetails($content, $docID, true);
	$nD = $doc['dmin']['nearestDoc'];
	$LWC_DT = $_SESSION['LWC'][$nD];
	$LTF_DT = $_SESSION['LTF'][$nD];
//	print_r($doc);

	echo '<br/>Most similar to: <b>'.$nD.'</b><br/>
	Minimum distance: <b>'.$doc['dmin']['dist'].'</b><br/>
	Belongs to: Author<b>'.$doc['Author'].'</b><br/>';
	echo '<h4>Comparison to all documents</h4>
<table class="table ml-detection" valign="top" width="100%">
<thead>
<th style="width:50px"></th>
<th>Doc <b>'.$docID.'</b></th>';
foreach ($_SESSION['Author'] as $i => $Author) {
	if ($i != $docID) echo '<th>Doc <b>'.$i.'</b></th>';
}
echo '</thead>
<tbody>
<tr>
<td>LWC</td>
<td><pre class="ml-pre LWC_ar">';print_r($doc['LWC']['LWC']); echo '</pre></td>';
foreach ($_SESSION['LWC'] as $k => $LWC_ars) {
	if ($k != $docID) {
		echo '<td><pre class="ml-pre LWC_nD_ar">';print_r($LWC_ars['LWC']); echo '</pre>
		<b>'.$doc['dist']['LWC'][$k].'</b></td>';
	}
}
echo '</tr>
<tr>
<td>LTF</td>
<td><pre class="ml-pre LTF_ar">';print_r($doc['LTF']['LTF']); echo '</pre></td>';
foreach ($_SESSION['LTF'] as $k => $LTF_ar) {
	if ($k != $docID) {
		echo '<td><pre class="ml-pre LWC_nD_ar">';print_r($LTF_ar['LTF']); echo '</pre>
		<b>'.$doc['dist']['LTF'][$k].'</b></td>';
	}
}
echo '</tr>
</tbody>
</table>';
	echo '<hr/>';

	echo '<h4>Details</h4>
<table class="table" valign="top" width="100%">
<thead>
<th style="width:170px"></th>
<th>Doc <b>'.$docID.'</b></th>
<th>Doc <b>'.$nD.'</b></th>
</thead>
<tbody>
<tr>
<td>LWC</td>
<td><pre class="ml-pre LWC_ar">';print_r($doc['LWC']['LWC']); echo '</pre></td>
<td><pre class="ml-pre LWC_nD_ar">';print_r($LWC_DT['LWC']); echo '</pre></td>
</tr>
<tr>
<td>LTF</td>
<td><pre class="ml-pre LTF_ar">';print_r($doc['LTF']['LTF']); echo '</pre></td>
<td><pre class="ml-pre LTF_nD_ar">';print_r($LTF_DT['LTF']); echo '</pre></td>
</tr>
<tr>
<td>Type</td>
<td></td>
<td>'.$doc['dmin']['use'].'</td>
</tr>
<tr>
<td>Minimum distance</td>
<td></td>
<td>'.$doc['dmin']['dist'].'</td>
</tr>
</tbody>
</table>';
}

foreach (range('a', 'z') as $letter) $varSar[] = $letter;
foreach (range('a', 'z') as $letter) $funcSar[] = $letter.'_'.rand(0, 100);
$fucn = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));

function tokenize ($input, $tokens, $tokens_) {
	global $varSar, $funcSar;
	$tokensStr = implode(' ', $tokens);
	$tokensStr = str_replace(' ', '|', $tokensStr);
	$input = html_entity_decode($input);
	$inputA = trim(preg_replace('/\s+/', '', $input));
	$_tokensAr['all'] = $tokensAr = preg_split("/({$tokensStr})/", $inputA, 0, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
	if (count($tokensAr) > 0) {
//		print_r($tokensAr);
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
					$tokensAr[$k] = '';
					if (!isset($tokensAr[$k+1]) || $tokensAr[$k+1] != '(') {
						if (!in_array($token, $var)) {
							$var[] = $token;
							$var_n++;
						} else $var_n = array_search($token, $var);
						$tokensAr[$k] = $varSar[$var_n];
					} else {
						if (!in_array($token, $func)) {
							$func[] = $token;
							$func_n++;
						} else $func_n = array_search($token, $func);
						$tokensAr[$k] = $funcSar[$func_n];
					}
				}
			}
		}
	//	print_r($tokensAr);
	//	print_r($func);

		$remove = str_replace(' ', '|', $tokens['pattern'].' main');
		unset($tokens['pattern']);
		unset($tokens['math']);

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
	return false;
}

function _tokens ($input, $_fLang) {
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
						
		$pattern = '\{ \} \# \( \) \, \; \" \' \& \< \>';
		$math = '\+ \- \* \/ \=== \!== \== \!= \= % \< \>';

		$tokens['datatypes'] = $tokens_['datatypes'] = $datatypes;
		$tokens['types'] = $tokens_['types'] = $types;
		$tokens['keywords'] = $tokens_['keywords'] = $keywords;
		$tokens['functions'] = $tokens_['functions'] = $functions;
		$tokens['pattern'] = $pattern;
		$tokens['math'] = $math;
		$tokens_['pattern'] = str_replace('\\', '', $pattern);
		$tokens_['math'] = str_replace('\\', '', $math);
	
	$input = _format($input, $types);
	return tokenize ($input, $tokens, $tokens_);
}


// Handle large data

// calculate base on LineWordsCalculate (count number of tokens per line)
function getLWC (string $content) {
	// re-indent code
	$content = preg_replace('!\s+!', ' ', $content);
	$content = preg_replace('/(.*?) {/', '$1<br/>{<br/>', $content);
	$content = preg_replace('/(.*?);/', '$1;<br/>', $content);
	$content = preg_replace('/}/', '<br/>}<br/>', $content);
	$content = preg_replace("/(<br\s*\/?>\s*)+/", "<br/>", $content);
	$content = preg_replace('/\n/', '', $content);

	$content = preg_replace('/({|})/', '', $content); // remove brackets
	// split lines
	preg_match_all('/(.*?)(\n|(<br\s*\/?>\s*))(.*?)/', $content, $lines);
	$lines = $lines[1];
	//print_r($lines);

	// set cs array
	$LWC = $LWC_display = array();

	// get tokens per line
	foreach ($lines as $oneLine) {
		// remove all lines containing only blank space
		$lineContent = preg_replace('/\s+/', '', $oneLine);
		if (strlen($lineContent) > 0) {
	//		$lineContent = preg_replace('/{|}/', '', $lineContent); // remove brackets
			$lineContent = str_replace("'", '"', $lineContent);
			$tAr = _tokens($lineContent, 'cpp');
	//		echo '<code>';print_r($tAr);echo '</code>';
			$lineContent = $tAr['txt'];
			$numTokens = count($tAr['tokens']);
//			echo '<code>'.$lineContent.'</code><br/>Tokens num: '.$numTokens.'<hr/>';
			if (!isset($LWC[$numTokens])) $LWC[$numTokens] = 1;
			else $LWC[$numTokens]++;
			// save line-tokenize document for displaying purpose
			$merge_content[] = $lineContent;
		}
	}
	if (count($LWC) > 0) {
		$LWC_display['txt'] = implode('<br/>', $merge_content);
		$LWC_display['LWC'] = $LWC;
	}
	return $LWC_display;
}

// calculate base on tokens type (get frequency of each type of tokens) (LineTokenFrequency)
function getLTF (string $content) {
	// re-indent code
	$content = preg_replace('!\s+!', ' ', $content);
	$content = preg_replace('/(.*?) {/', '$1<br/>{<br/>', $content);
	$content = preg_replace('/(.*?);/', '$1;<br/>', $content);
	$content = preg_replace('/}/', '<br/>}<br/>', $content);
	$content = preg_replace("/(<br\s*\/?>\s*)+/", "<br/>", $content);
	$content = preg_replace('/\n/', '', $content);

	$content = preg_replace('/({|})/', '', $content); // remove brackets
	// split lines
	preg_match_all('/(.*?)(\n|(<br\s*\/?>\s*))(.*?)/', $content, $lines);
	$lines = $lines[1];

	// set cs array
	$LTF = $LTF_display = $tokensFrequency = array();

	// get tokens per line
	foreach ($lines as $oneLine) {
		// remove all lines containing only blank space
		$lineContent = preg_replace('/\s+/', '', $oneLine);
		if (strlen($lineContent) > 0) {
	//		$lineContent = preg_replace('/{|}/', '', $lineContent); // remove brackets
			$lineContent = str_replace("'", '"', $lineContent);
			$tAr = _tokens($lineContent, 'cpp');
			$tokensAr = $tAr['tokens'];
			foreach ($tokensAr as $oneToken) {
				if (!isset($tokensFrequency[$oneToken])) $tokensFrequency[$oneToken] = 1;
				else $tokensFrequency[$oneToken]++;
			}
			//echo '<code>';print_r($tAr);echo '</code>';
			// save tokensFrequency
			$LTF = $tokensFrequency;
			
			// save line-tokenize document for displaying purpose
			$merge_content[] = $lineContent;
		}
	}
	if (count($LTF) > 0) {
		$LTF_display['txt'] = implode('<br/>', $merge_content);
		$LTF_display['LTF'] = $LTF;
	}
	return $LTF_display;
}
