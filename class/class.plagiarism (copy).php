<?php
class Plagiarism extends Diff {
	
	public function _tokens ($input, $_fLang) {
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
						'ULONGLONG ULONG_PTR ULONG32 ULONG64 USHORT USN VOID WCHAR WORD WPARAM WPARAM WPARAM ' .
						'char bool short int __int32 __int64 __int8 __int16 long float double __wchar_t ' .
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
						'using uuid virtual void volatile whcar_t while';
					
		$functions =	'assert isalnum isalpha iscntrl isdigit isgraph islower isprint' .
						'ispunct isspace isupper isxdigit tolower toupper errno localeconv ' .
						'setlocale acos asin atan atan2 ceil cos cosh exp fabs floor fmod ' .
						'frexp ldexp log log10 modf pow sin sinh sqrt tan tanh jmp_buf ' .
						'longjmp setjmp raise signal sig_atomic_t va_arg va_end va_start ' .
						'clearerr fclose feof ferror fflush fgetc fgetpos fgets fopen ' .
						'fprintf fputc fputs fread freopen fscanf fseek fsetpos ftell ' .
						'fwrite getc getchar gets perror printf putc putchar puts remove ' .
						'rename rewind scanf setbuf setvbuf sprintf sscanf tmpfile tmpnam ' .
						'ungetc vfprintf vprintf vsprintf abort abs atexit atof atoi atol ' .
						'bsearch calloc div exit free getenv labs ldiv malloc mblen mbstowcs ' .
						'mbtowc qsort rand realloc srand strtod strtol strtoul system ' .
						'wcstombs wctomb memchr memcmp memcpy memmove memset strcat strchr ' .
						'strcmp strcoll strcpy strcspn strerror strlen strncat strncmp ' .
						'strncpy strpbrk strrchr strspn strstr strtok strxfrm asctime ' .
						'clock ctime difftime gmtime localtime mktime strftime time';
						
		$pattern = '{ } # \( \)';

		$tokens = $datatypes.' '.$keywords.' '.$functions.' '.$pattern;
		return $this->_tokensAr ($input, $tokens);
	}
	
	public function _tokensAr ($input, $tokens) {
		$tokens = str_replace(' ', '|', $tokens);
		$input = trim(preg_replace('/\s+/', '', $input));
		$_tokensAr = preg_split("/({$tokens})/", $input, 0, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
		$_tokens = count($_tokensAr);
		return $_tokensAr;
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

	public function checkLocal () {
		$_dir = $this->_dir;
		$_fileFormat = $this->_fileFormat;
		$_ext = end(explode('.', $_fileFormat));
		$_Ar = array();
		if (is_dir($this->_dir)) {
			$files = scandir($this->_dir);
			foreach ($files as $fk => $fi) {
//				if (strpos($fi, 'ut') == 0) {
				$file = $_dir.'/'.$fi;
				$ext = end(explode('.', $fi));
				if (!is_file($file) || $file == $_fileFormat || $ext != $_ext || strpos($fi, 'time') == true || mb_substr($fi, 0, 2) == 'ut' ) unset($files[$fk]);
				else {
					$u = $this->u;
					$fu = explode('u', explode('.', $fi)[0])[1];
					if (!$fu || $fu == $u) unset($files[$fk]);
					else {
						$fiext = end(explode('.', $fi));
						$diff = $sAr = array();
						$diffAll = Diff::compareCodeFiles($_fileFormat, $file, 65);
						if ($diffAll) {
							$diff = $diffAll['diff'];
//							print_r($diffAll);
				//			$similar = 0;
				//			$sAr = array();
		//					$totalLines = count($diff);
							$perSiT = $lns = 0;
							foreach ($diff as $difo) {
								if ($difo[2] == 0) {
									$perSiT += $difo[3]; $lns++;
									$sAr['diff'][] = array($difo[0], $difo[1], $difo[3]);
								}
							}
							$totalLines = $diffAll['lines'];
							$sAr['tokens'] = $diffAll['tokens'];
				//			$sAr['lines'] = $diffAll['lines'];
				//			$sAr = $diffAll;
		//					$similar = count($sAr);
		//					$perSiLines = $similar/$totalLines*100;
							$perSi = round($perSiT/$lns, 2);
							$perSiLn = round(100*$lns/$totalLines, 2);
//							$perSi = round($perSi/$totalLines, 2);
							if ($perSiLn > 50 && $perSi > 70) {
								$ufid = str_split(explode('.', $fi)[0])[1];
								$ufin = $this->getUserInfo($ufid);
								$files[$fk] = array('file' => $fi, 'u' => $ufid, 'uname' => $ufin['username'], 'ext' => $fiext, 'per' => $perSi, 'sAr' => $sAr);
								$_Ar['simi'][] = $fi.'::'.$perSi;
							} else unset($files[$fk]);
						} else unset($files[$fk]);
					}
				}
//				}
			}
			$_Ar['similar'] = array_values($files);
		}
		$this->checkLocal = $_Ar;
	}
}
