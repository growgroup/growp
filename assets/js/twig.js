'use strict';(function(c){"object"==typeof exports&&"object"==typeof module?c(require("../../lib/codemirror"),require("../../addon/mode/multiplex")):"function"==typeof define&&define.amd?define(["../../lib/codemirror","../../addon/mode/multiplex"],c):c(CodeMirror)})(function(c){c.defineMode("twig:inner",function(){function c(a,b){var c=a.peek();if(b.incomment)return a.skipTo("#}")?(a.eatWhile(/#|}/),b.incomment=!1):a.skipToEnd(),"comment";if(b.intag){if(b.operator){b.operator=!1;if(a.match(e))return"atom";
	if(a.match(g))return"number"}if(b.sign){b.sign=!1;if(a.match(e))return"atom";if(a.match(g))return"number"}if(b.instring)return c==b.instring&&(b.instring=!1),a.next(),"string";if("'"==c||'"'==c)return b.instring=c,a.next(),"string";if(a.match(b.intag+"}")||a.eat("-")&&a.match(b.intag+"}"))return b.intag=!1,"tag";if(a.match(f))return b.operator=!0,"operator";if(a.match(k))b.sign=!0;else if(a.eat(" ")||a.sol()){if(a.match(d))return"keyword";if(a.match(e))return"atom";if(a.match(g))return"number";a.sol()&&
a.next()}else a.next();return"variable"}if(a.eat("{")){if(a.eat("#"))return b.incomment=!0,a.skipTo("#}")?(a.eatWhile(/#|}/),b.incomment=!1):a.skipToEnd(),"comment";if(c=a.eat(/\{|%/))return b.intag=c,"{"==c&&(b.intag="}"),a.eat("-"),"tag"}a.next()}var d="and as autoescape endautoescape block do endblock else elseif extends for endfor embed endembed filter endfilter flush from if endif in is include import not or set spaceless endspaceless with endwith trans endtrans blocktrans endblocktrans macro endmacro use verbatim endverbatim".split(" "),
	f=/^[+\-*&%=<>!?|~^]/,k=/^[:\[\(\{]/,e="true;false;null;empty;defined;divisibleby;divisible by;even;odd;iterable;sameas;same as".split(";"),g=/^(\d[+\-\*\/])?\d+(\.\d+)?/;d=new RegExp("(("+d.join(")|(")+"))\\b");e=new RegExp("(("+e.join(")|(")+"))\\b");return{startState:function(){return{}},token:function(a,b){return c(a,b)}}});c.defineMode("twig",function(h,d){var f=c.getMode(h,"twig:inner");return d&&d.base?c.multiplexingMode(c.getMode(h,d.base),{open:/\{[{#%]/,close:/[}#%]\}/,mode:f,parseDelimiters:!0}):
	f});c.defineMIME("text/x-twig","twig")});

'use strict';(function(f){"object"==typeof exports&&"object"==typeof module?f(require("../../lib/codemirror"),require("../htmlmixed/htmlmixed"),require("../clike/clike")):"function"==typeof define&&define.amd?define(["../../lib/codemirror","../htmlmixed/htmlmixed","../clike/clike"],f):f(CodeMirror)})(function(f){function h(b){var a={};b=b.split(" ");for(var d=0;d<b.length;++d)a[b[d]]=!0;return a}function m(b,a,d){return 0==b.length?k(a):function(e,g){for(var c=b[0],l=0;l<c.length;l++)if(e.match(c[l][0]))return g.tokenize=
	m(b.slice(1),a),c[l][1];g.tokenize=k(a,d);return"string"}}function k(b,a){return function(d,e){if(!1!==a&&d.match("${",!1)||d.match("{$",!1))e.tokenize=null,d="string";else if(!1!==a&&d.match(/^\$[a-zA-Z_][a-zA-Z0-9_]*/))d.match("[",!1)&&(e.tokenize=m([[["[",null]],[[/\d[\w\.]*/,"number"],[/\$[a-zA-Z_][a-zA-Z0-9_]*/,"variable-2"],[/[\w\$]+/,"variable"]],[["]",null]]],b,a)),d.match(/\->\w/,!1)&&(e.tokenize=m([[["->",null]],[[/[\w]+/,"variable"]]],b,a)),d="variable-2";else{for(var g=!1;!d.eol()&&(g||
	!1===a||!d.match("{$",!1)&&!d.match(/^(\$[a-zA-Z_][a-zA-Z0-9_]*|\$\{)/,!1));){if(!g&&d.match(b)){e.tokenize=null;e.tokStack.pop();e.tokStack.pop();break}g="\\"==d.next()&&!g}d="string"}return d}}f.registerHelper("hintWords","php","abstract and array as break case catch class clone const continue declare default do else elseif enddeclare endfor endforeach endif endswitch endwhile extends final for foreach function global goto if implements interface instanceof namespace new or private protected public static switch throw trait try use var while xor die echo empty exit eval include include_once isset list require require_once return print unset __halt_compiler self static parent yield insteadof finally true false null TRUE FALSE NULL __CLASS__ __DIR__ __FILE__ __LINE__ __METHOD__ __FUNCTION__ __NAMESPACE__ __TRAIT__ func_num_args func_get_arg func_get_args strlen strcmp strncmp strcasecmp strncasecmp each error_reporting define defined trigger_error user_error set_error_handler restore_error_handler get_declared_classes get_loaded_extensions extension_loaded get_extension_funcs debug_backtrace constant bin2hex hex2bin sleep usleep time mktime gmmktime strftime gmstrftime strtotime date gmdate getdate localtime checkdate flush wordwrap htmlspecialchars htmlentities html_entity_decode md5 md5_file crc32 getimagesize image_type_to_mime_type phpinfo phpversion phpcredits strnatcmp strnatcasecmp substr_count strspn strcspn strtok strtoupper strtolower strpos strrpos strrev hebrev hebrevc nl2br basename dirname pathinfo stripslashes stripcslashes strstr stristr strrchr str_shuffle str_word_count strcoll substr substr_replace quotemeta ucfirst ucwords strtr addslashes addcslashes rtrim str_replace str_repeat count_chars chunk_split trim ltrim strip_tags similar_text explode implode setlocale localeconv parse_str str_pad chop strchr sprintf printf vprintf vsprintf sscanf fscanf parse_url urlencode urldecode rawurlencode rawurldecode readlink linkinfo link unlink exec system escapeshellcmd escapeshellarg passthru shell_exec proc_open proc_close rand srand getrandmax mt_rand mt_srand mt_getrandmax base64_decode base64_encode abs ceil floor round is_finite is_nan is_infinite bindec hexdec octdec decbin decoct dechex base_convert number_format fmod ip2long long2ip getenv putenv getopt microtime gettimeofday getrusage uniqid quoted_printable_decode set_time_limit get_cfg_var magic_quotes_runtime set_magic_quotes_runtime get_magic_quotes_gpc get_magic_quotes_runtime import_request_variables error_log serialize unserialize memory_get_usage var_dump var_export debug_zval_dump print_r highlight_file show_source highlight_string ini_get ini_get_all ini_set ini_alter ini_restore get_include_path set_include_path restore_include_path setcookie header headers_sent connection_aborted connection_status ignore_user_abort parse_ini_file is_uploaded_file move_uploaded_file intval floatval doubleval strval gettype settype is_null is_resource is_bool is_long is_float is_int is_integer is_double is_real is_numeric is_string is_array is_object is_scalar ereg ereg_replace eregi eregi_replace split spliti join sql_regcase dl pclose popen readfile rewind rmdir umask fclose feof fgetc fgets fgetss fread fopen fpassthru ftruncate fstat fseek ftell fflush fwrite fputs mkdir rename copy tempnam tmpfile file file_get_contents file_put_contents stream_select stream_context_create stream_context_set_params stream_context_set_option stream_context_get_options stream_filter_prepend stream_filter_append fgetcsv flock get_meta_tags stream_set_write_buffer set_file_buffer set_socket_blocking stream_set_blocking socket_set_blocking stream_get_meta_data stream_register_wrapper stream_wrapper_register stream_set_timeout socket_set_timeout socket_get_status realpath fnmatch fsockopen pfsockopen pack unpack get_browser crypt opendir closedir chdir getcwd rewinddir readdir dir glob fileatime filectime filegroup fileinode filemtime fileowner fileperms filesize filetype file_exists is_writable is_writeable is_readable is_executable is_file is_dir is_link stat lstat chown touch clearstatcache mail ob_start ob_flush ob_clean ob_end_flush ob_end_clean ob_get_flush ob_get_clean ob_get_length ob_get_level ob_get_status ob_get_contents ob_implicit_flush ob_list_handlers ksort krsort natsort natcasesort asort arsort sort rsort usort uasort uksort shuffle array_walk count end prev next reset current key min max in_array array_search extract compact array_fill range array_multisort array_push array_pop array_shift array_unshift array_splice array_slice array_merge array_merge_recursive array_keys array_values array_count_values array_reverse array_reduce array_pad array_flip array_change_key_case array_rand array_unique array_intersect array_intersect_assoc array_diff array_diff_assoc array_sum array_filter array_map array_chunk array_key_exists array_intersect_key array_combine array_column pos sizeof key_exists assert assert_options version_compare ftok str_rot13 aggregate session_name session_module_name session_save_path session_id session_regenerate_id session_decode session_register session_unregister session_is_registered session_encode session_start session_destroy session_unset session_set_save_handler session_cache_limiter session_cache_expire session_set_cookie_params session_get_cookie_params session_write_close preg_match preg_match_all preg_replace preg_replace_callback preg_split preg_quote preg_grep overload ctype_alnum ctype_alpha ctype_cntrl ctype_digit ctype_lower ctype_graph ctype_print ctype_punct ctype_space ctype_upper ctype_xdigit virtual apache_request_headers apache_note apache_lookup_uri apache_child_terminate apache_setenv apache_response_headers apache_get_version getallheaders mysql_connect mysql_pconnect mysql_close mysql_select_db mysql_create_db mysql_drop_db mysql_query mysql_unbuffered_query mysql_db_query mysql_list_dbs mysql_list_tables mysql_list_fields mysql_list_processes mysql_error mysql_errno mysql_affected_rows mysql_insert_id mysql_result mysql_num_rows mysql_num_fields mysql_fetch_row mysql_fetch_array mysql_fetch_assoc mysql_fetch_object mysql_data_seek mysql_fetch_lengths mysql_fetch_field mysql_field_seek mysql_free_result mysql_field_name mysql_field_table mysql_field_len mysql_field_type mysql_field_flags mysql_escape_string mysql_real_escape_string mysql_stat mysql_thread_id mysql_client_encoding mysql_get_client_info mysql_get_host_info mysql_get_proto_info mysql_get_server_info mysql_info mysql mysql_fieldname mysql_fieldtable mysql_fieldlen mysql_fieldtype mysql_fieldflags mysql_selectdb mysql_createdb mysql_dropdb mysql_freeresult mysql_numfields mysql_numrows mysql_listdbs mysql_listtables mysql_listfields mysql_db_name mysql_dbname mysql_tablename mysql_table_name pg_connect pg_pconnect pg_close pg_connection_status pg_connection_busy pg_connection_reset pg_host pg_dbname pg_port pg_tty pg_options pg_ping pg_query pg_send_query pg_cancel_query pg_fetch_result pg_fetch_row pg_fetch_assoc pg_fetch_array pg_fetch_object pg_fetch_all pg_affected_rows pg_get_result pg_result_seek pg_result_status pg_free_result pg_last_oid pg_num_rows pg_num_fields pg_field_name pg_field_num pg_field_size pg_field_type pg_field_prtlen pg_field_is_null pg_get_notify pg_get_pid pg_result_error pg_last_error pg_last_notice pg_put_line pg_end_copy pg_copy_to pg_copy_from pg_trace pg_untrace pg_lo_create pg_lo_unlink pg_lo_open pg_lo_close pg_lo_read pg_lo_write pg_lo_read_all pg_lo_import pg_lo_export pg_lo_seek pg_lo_tell pg_escape_string pg_escape_bytea pg_unescape_bytea pg_client_encoding pg_set_client_encoding pg_meta_data pg_convert pg_insert pg_update pg_delete pg_select pg_exec pg_getlastoid pg_cmdtuples pg_errormessage pg_numrows pg_numfields pg_fieldname pg_fieldsize pg_fieldtype pg_fieldnum pg_fieldprtlen pg_fieldisnull pg_freeresult pg_result pg_loreadall pg_locreate pg_lounlink pg_loopen pg_loclose pg_loread pg_lowrite pg_loimport pg_loexport http_response_code get_declared_traits getimagesizefromstring socket_import_stream stream_set_chunk_size trait_exists header_register_callback class_uses session_status session_register_shutdown echo print global static exit array empty eval isset unset die include require include_once require_once json_decode json_encode json_last_error json_last_error_msg curl_close curl_copy_handle curl_errno curl_error curl_escape curl_exec curl_file_create curl_getinfo curl_init curl_multi_add_handle curl_multi_close curl_multi_exec curl_multi_getcontent curl_multi_info_read curl_multi_init curl_multi_remove_handle curl_multi_select curl_multi_setopt curl_multi_strerror curl_pause curl_reset curl_setopt_array curl_setopt curl_share_close curl_share_init curl_share_setopt curl_strerror curl_unescape curl_version mysqli_affected_rows mysqli_autocommit mysqli_change_user mysqli_character_set_name mysqli_close mysqli_commit mysqli_connect_errno mysqli_connect_error mysqli_connect mysqli_data_seek mysqli_debug mysqli_dump_debug_info mysqli_errno mysqli_error_list mysqli_error mysqli_fetch_all mysqli_fetch_array mysqli_fetch_assoc mysqli_fetch_field_direct mysqli_fetch_field mysqli_fetch_fields mysqli_fetch_lengths mysqli_fetch_object mysqli_fetch_row mysqli_field_count mysqli_field_seek mysqli_field_tell mysqli_free_result mysqli_get_charset mysqli_get_client_info mysqli_get_client_stats mysqli_get_client_version mysqli_get_connection_stats mysqli_get_host_info mysqli_get_proto_info mysqli_get_server_info mysqli_get_server_version mysqli_info mysqli_init mysqli_insert_id mysqli_kill mysqli_more_results mysqli_multi_query mysqli_next_result mysqli_num_fields mysqli_num_rows mysqli_options mysqli_ping mysqli_prepare mysqli_query mysqli_real_connect mysqli_real_escape_string mysqli_real_query mysqli_reap_async_query mysqli_refresh mysqli_rollback mysqli_select_db mysqli_set_charset mysqli_set_local_infile_default mysqli_set_local_infile_handler mysqli_sqlstate mysqli_ssl_set mysqli_stat mysqli_stmt_init mysqli_store_result mysqli_thread_id mysqli_thread_safe mysqli_use_result mysqli_warning_count".split(" "));
	f.registerHelper("wordChars","php",/[\w$]/);var n={name:"clike",helperType:"php",keywords:h("abstract and array as break case catch class clone const continue declare default do else elseif enddeclare endfor endforeach endif endswitch endwhile extends final for foreach function global goto if implements interface instanceof namespace new or private protected public static switch throw trait try use var while xor die echo empty exit eval include include_once isset list require require_once return print unset __halt_compiler self static parent yield insteadof finally"),
		blockKeywords:h("catch do else elseif for foreach if switch try while finally"),defKeywords:h("class function interface namespace trait"),atoms:h("true false null TRUE FALSE NULL __CLASS__ __DIR__ __FILE__ __LINE__ __METHOD__ __FUNCTION__ __NAMESPACE__ __TRAIT__"),builtin:h("func_num_args func_get_arg func_get_args strlen strcmp strncmp strcasecmp strncasecmp each error_reporting define defined trigger_error user_error set_error_handler restore_error_handler get_declared_classes get_loaded_extensions extension_loaded get_extension_funcs debug_backtrace constant bin2hex hex2bin sleep usleep time mktime gmmktime strftime gmstrftime strtotime date gmdate getdate localtime checkdate flush wordwrap htmlspecialchars htmlentities html_entity_decode md5 md5_file crc32 getimagesize image_type_to_mime_type phpinfo phpversion phpcredits strnatcmp strnatcasecmp substr_count strspn strcspn strtok strtoupper strtolower strpos strrpos strrev hebrev hebrevc nl2br basename dirname pathinfo stripslashes stripcslashes strstr stristr strrchr str_shuffle str_word_count strcoll substr substr_replace quotemeta ucfirst ucwords strtr addslashes addcslashes rtrim str_replace str_repeat count_chars chunk_split trim ltrim strip_tags similar_text explode implode setlocale localeconv parse_str str_pad chop strchr sprintf printf vprintf vsprintf sscanf fscanf parse_url urlencode urldecode rawurlencode rawurldecode readlink linkinfo link unlink exec system escapeshellcmd escapeshellarg passthru shell_exec proc_open proc_close rand srand getrandmax mt_rand mt_srand mt_getrandmax base64_decode base64_encode abs ceil floor round is_finite is_nan is_infinite bindec hexdec octdec decbin decoct dechex base_convert number_format fmod ip2long long2ip getenv putenv getopt microtime gettimeofday getrusage uniqid quoted_printable_decode set_time_limit get_cfg_var magic_quotes_runtime set_magic_quotes_runtime get_magic_quotes_gpc get_magic_quotes_runtime import_request_variables error_log serialize unserialize memory_get_usage var_dump var_export debug_zval_dump print_r highlight_file show_source highlight_string ini_get ini_get_all ini_set ini_alter ini_restore get_include_path set_include_path restore_include_path setcookie header headers_sent connection_aborted connection_status ignore_user_abort parse_ini_file is_uploaded_file move_uploaded_file intval floatval doubleval strval gettype settype is_null is_resource is_bool is_long is_float is_int is_integer is_double is_real is_numeric is_string is_array is_object is_scalar ereg ereg_replace eregi eregi_replace split spliti join sql_regcase dl pclose popen readfile rewind rmdir umask fclose feof fgetc fgets fgetss fread fopen fpassthru ftruncate fstat fseek ftell fflush fwrite fputs mkdir rename copy tempnam tmpfile file file_get_contents file_put_contents stream_select stream_context_create stream_context_set_params stream_context_set_option stream_context_get_options stream_filter_prepend stream_filter_append fgetcsv flock get_meta_tags stream_set_write_buffer set_file_buffer set_socket_blocking stream_set_blocking socket_set_blocking stream_get_meta_data stream_register_wrapper stream_wrapper_register stream_set_timeout socket_set_timeout socket_get_status realpath fnmatch fsockopen pfsockopen pack unpack get_browser crypt opendir closedir chdir getcwd rewinddir readdir dir glob fileatime filectime filegroup fileinode filemtime fileowner fileperms filesize filetype file_exists is_writable is_writeable is_readable is_executable is_file is_dir is_link stat lstat chown touch clearstatcache mail ob_start ob_flush ob_clean ob_end_flush ob_end_clean ob_get_flush ob_get_clean ob_get_length ob_get_level ob_get_status ob_get_contents ob_implicit_flush ob_list_handlers ksort krsort natsort natcasesort asort arsort sort rsort usort uasort uksort shuffle array_walk count end prev next reset current key min max in_array array_search extract compact array_fill range array_multisort array_push array_pop array_shift array_unshift array_splice array_slice array_merge array_merge_recursive array_keys array_values array_count_values array_reverse array_reduce array_pad array_flip array_change_key_case array_rand array_unique array_intersect array_intersect_assoc array_diff array_diff_assoc array_sum array_filter array_map array_chunk array_key_exists array_intersect_key array_combine array_column pos sizeof key_exists assert assert_options version_compare ftok str_rot13 aggregate session_name session_module_name session_save_path session_id session_regenerate_id session_decode session_register session_unregister session_is_registered session_encode session_start session_destroy session_unset session_set_save_handler session_cache_limiter session_cache_expire session_set_cookie_params session_get_cookie_params session_write_close preg_match preg_match_all preg_replace preg_replace_callback preg_split preg_quote preg_grep overload ctype_alnum ctype_alpha ctype_cntrl ctype_digit ctype_lower ctype_graph ctype_print ctype_punct ctype_space ctype_upper ctype_xdigit virtual apache_request_headers apache_note apache_lookup_uri apache_child_terminate apache_setenv apache_response_headers apache_get_version getallheaders mysql_connect mysql_pconnect mysql_close mysql_select_db mysql_create_db mysql_drop_db mysql_query mysql_unbuffered_query mysql_db_query mysql_list_dbs mysql_list_tables mysql_list_fields mysql_list_processes mysql_error mysql_errno mysql_affected_rows mysql_insert_id mysql_result mysql_num_rows mysql_num_fields mysql_fetch_row mysql_fetch_array mysql_fetch_assoc mysql_fetch_object mysql_data_seek mysql_fetch_lengths mysql_fetch_field mysql_field_seek mysql_free_result mysql_field_name mysql_field_table mysql_field_len mysql_field_type mysql_field_flags mysql_escape_string mysql_real_escape_string mysql_stat mysql_thread_id mysql_client_encoding mysql_get_client_info mysql_get_host_info mysql_get_proto_info mysql_get_server_info mysql_info mysql mysql_fieldname mysql_fieldtable mysql_fieldlen mysql_fieldtype mysql_fieldflags mysql_selectdb mysql_createdb mysql_dropdb mysql_freeresult mysql_numfields mysql_numrows mysql_listdbs mysql_listtables mysql_listfields mysql_db_name mysql_dbname mysql_tablename mysql_table_name pg_connect pg_pconnect pg_close pg_connection_status pg_connection_busy pg_connection_reset pg_host pg_dbname pg_port pg_tty pg_options pg_ping pg_query pg_send_query pg_cancel_query pg_fetch_result pg_fetch_row pg_fetch_assoc pg_fetch_array pg_fetch_object pg_fetch_all pg_affected_rows pg_get_result pg_result_seek pg_result_status pg_free_result pg_last_oid pg_num_rows pg_num_fields pg_field_name pg_field_num pg_field_size pg_field_type pg_field_prtlen pg_field_is_null pg_get_notify pg_get_pid pg_result_error pg_last_error pg_last_notice pg_put_line pg_end_copy pg_copy_to pg_copy_from pg_trace pg_untrace pg_lo_create pg_lo_unlink pg_lo_open pg_lo_close pg_lo_read pg_lo_write pg_lo_read_all pg_lo_import pg_lo_export pg_lo_seek pg_lo_tell pg_escape_string pg_escape_bytea pg_unescape_bytea pg_client_encoding pg_set_client_encoding pg_meta_data pg_convert pg_insert pg_update pg_delete pg_select pg_exec pg_getlastoid pg_cmdtuples pg_errormessage pg_numrows pg_numfields pg_fieldname pg_fieldsize pg_fieldtype pg_fieldnum pg_fieldprtlen pg_fieldisnull pg_freeresult pg_result pg_loreadall pg_locreate pg_lounlink pg_loopen pg_loclose pg_loread pg_lowrite pg_loimport pg_loexport http_response_code get_declared_traits getimagesizefromstring socket_import_stream stream_set_chunk_size trait_exists header_register_callback class_uses session_status session_register_shutdown echo print global static exit array empty eval isset unset die include require include_once require_once json_decode json_encode json_last_error json_last_error_msg curl_close curl_copy_handle curl_errno curl_error curl_escape curl_exec curl_file_create curl_getinfo curl_init curl_multi_add_handle curl_multi_close curl_multi_exec curl_multi_getcontent curl_multi_info_read curl_multi_init curl_multi_remove_handle curl_multi_select curl_multi_setopt curl_multi_strerror curl_pause curl_reset curl_setopt_array curl_setopt curl_share_close curl_share_init curl_share_setopt curl_strerror curl_unescape curl_version mysqli_affected_rows mysqli_autocommit mysqli_change_user mysqli_character_set_name mysqli_close mysqli_commit mysqli_connect_errno mysqli_connect_error mysqli_connect mysqli_data_seek mysqli_debug mysqli_dump_debug_info mysqli_errno mysqli_error_list mysqli_error mysqli_fetch_all mysqli_fetch_array mysqli_fetch_assoc mysqli_fetch_field_direct mysqli_fetch_field mysqli_fetch_fields mysqli_fetch_lengths mysqli_fetch_object mysqli_fetch_row mysqli_field_count mysqli_field_seek mysqli_field_tell mysqli_free_result mysqli_get_charset mysqli_get_client_info mysqli_get_client_stats mysqli_get_client_version mysqli_get_connection_stats mysqli_get_host_info mysqli_get_proto_info mysqli_get_server_info mysqli_get_server_version mysqli_info mysqli_init mysqli_insert_id mysqli_kill mysqli_more_results mysqli_multi_query mysqli_next_result mysqli_num_fields mysqli_num_rows mysqli_options mysqli_ping mysqli_prepare mysqli_query mysqli_real_connect mysqli_real_escape_string mysqli_real_query mysqli_reap_async_query mysqli_refresh mysqli_rollback mysqli_select_db mysqli_set_charset mysqli_set_local_infile_default mysqli_set_local_infile_handler mysqli_sqlstate mysqli_ssl_set mysqli_stat mysqli_stmt_init mysqli_store_result mysqli_thread_id mysqli_thread_safe mysqli_use_result mysqli_warning_count"),
		multiLineStrings:!0,hooks:{$:function(b){b.eatWhile(/[\w\$_]/);return"variable-2"},"<":function(b,a){var d;if(d=b.match(/<<\s*/)){var e=b.eat(/['"]/);b.eatWhile(/[\w\.]/);d=b.current().slice(d[0].length+(e?2:1));e&&b.eat(e);if(d)return(a.tokStack||(a.tokStack=[])).push(d,0),a.tokenize=k(d,"'"!=e),"string"}return!1},"#":function(b){for(;!b.eol()&&!b.match("?>",!1);)b.next();return"comment"},"/":function(b){if(b.eat("/")){for(;!b.eol()&&!b.match("?>",!1);)b.next();return"comment"}return!1},'"':function(b,
																																																																																																																																  a){(a.tokStack||(a.tokStack=[])).push('"',0);a.tokenize=k('"');return"string"},"{":function(b,a){a.tokStack&&a.tokStack.length&&a.tokStack[a.tokStack.length-1]++;return!1},"}":function(b,a){a.tokStack&&0<a.tokStack.length&&!--a.tokStack[a.tokStack.length-1]&&(a.tokenize=k(a.tokStack[a.tokStack.length-2]));return!1}}};f.defineMode("php",function(b,a){var d=f.getMode(b,a&&a.htmlMode||"text/html"),e=f.getMode(b,n);return{startState:function(){var g=f.startState(d),c=a.startOpen?f.startState(e):null;
			return{html:g,php:c,curMode:a.startOpen?e:d,curState:a.startOpen?c:g,pending:null}},copyState:function(a){var c=f.copyState(d,a.html),b=a.php;b=b&&f.copyState(e,b);return{html:c,php:b,curMode:a.curMode,curState:a.curMode==d?c:b,pending:a.pending}},token:function(a,c){var b=c.curMode==e;a.sol()&&c.pending&&'"'!=c.pending&&"'"!=c.pending&&(c.pending=null);if(b)return b&&null==c.php.tokenize&&a.match("?>")?(c.curMode=d,c.curState=c.html,c.php.context.prev||(c.php=null),"meta"):e.token(a,c.curState);
			if(a.match(/^<\?\w*/))return c.curMode=e,c.php||(c.php=f.startState(e,d.indent(c.html,"",""))),c.curState=c.php,"meta";if('"'==c.pending||"'"==c.pending){for(;!a.eol()&&a.next()!=c.pending;);b="string"}else c.pending&&a.pos<c.pending.end?(a.pos=c.pending.end,b=c.pending.style):b=d.token(a,c.curState);c.pending&&(c.pending=null);var g=a.current(),h=g.search(/<\?/),k;-1!=h&&("string"==b&&(k=g.match(/['"]$/))&&!/\?>/.test(g)?c.pending=k[0]:c.pending={end:a.pos,style:b},a.backUp(g.length-h));return b},
		indent:function(a,b,f){return a.curMode!=e&&/^\s*<\//.test(b)||a.curMode==e&&/^\?>/.test(b)?d.indent(a.html,b,f):a.curMode.indent(a.curState,b,f)},blockCommentStart:"/*",blockCommentEnd:"*/",lineComment:"//",innerMode:function(a){return{state:a.curState,mode:a.curMode}}}},"htmlmixed","clike");f.defineMIME("application/x-httpd-php","php");f.defineMIME("application/x-httpd-php-open",{name:"php",startOpen:!0});f.defineMIME("text/x-php",n)});

'use strict';(function(t){"object"==typeof exports&&"object"==typeof module?t(require("../../lib/codemirror")):"function"==typeof define&&define.amd?define(["../../lib/codemirror"],t):t(CodeMirror)})(function(t){t.defineMode("javascript",function(Ja,w){function p(a,c,b){P=a;U=b;return c}function D(a,c){var b=a.next();if('"'==b||"'"==b)return c.tokenize=Ka(b),c.tokenize(a,c);if("."==b&&a.match(/^\d[\d_]*(?:[eE][+\-]?[\d_]+)?/))return p("number","number");if("."==b&&a.match(".."))return p("spread",
	"meta");if(/[\[\]{}\(\),;:\.]/.test(b))return p(b);if("="==b&&a.eat(">"))return p("=>","operator");if("0"==b&&a.match(/^(?:x[\dA-Fa-f_]+|o[0-7_]+|b[01_]+)n?/))return p("number","number");if(/\d/.test(b))return a.match(/^[\d_]*(?:n|(?:\.[\d_]*)?(?:[eE][+\-]?[\d_]+)?)?/),p("number","number");if("/"==b){if(a.eat("*"))return c.tokenize=V,V(a,c);if(a.eat("/"))return a.skipToEnd(),p("comment","comment");if(qa(a,c,1)){a:for(var d=c=!1;null!=(b=a.next());){if(!c){if("/"==b&&!d)break a;"["==b?d=!0:d&&"]"==
	b&&(d=!1)}c=!c&&"\\"==b}a.match(/^\b(([gimyus])(?![gimyus]*\2))+\b/);return p("regexp","string-2")}a.eat("=");return p("operator","operator",a.current())}if("`"==b)return c.tokenize=fa,fa(a,c);if("#"==b)return a.skipToEnd(),p("error","error");if(ra.test(b))return">"==b&&c.lexical&&">"==c.lexical.type||(a.eat("=")?"!"!=b&&"="!=b||a.eat("="):/[<>*+\-]/.test(b)&&(a.eat(b),">"==b&&a.eat(b))),p("operator","operator",a.current());if(ha.test(b)){a.eatWhile(ha);b=a.current();if("."!=c.lastType){if(sa.propertyIsEnumerable(b))return a=
	sa[b],p(a.type,a.style,b);if("async"==b&&a.match(/^(\s|\/\*.*?\*\/)*[\[\(\w]/,!1))return p("async","keyword",b)}return p("variable","variable",b)}}function Ka(a){return function(c,b){var d=!1,u;if(W&&"@"==c.peek()&&c.match(La))return b.tokenize=D,p("jsonld-keyword","meta");for(;null!=(u=c.next())&&(u!=a||d);)d=!d&&"\\"==u;d||(b.tokenize=D);return p("string","string")}}function V(a,c){for(var b=!1,d;d=a.next();){if("/"==d&&b){c.tokenize=D;break}b="*"==d}return p("comment","comment")}function fa(a,
																																																																																																																													   c){for(var b=!1,d;null!=(d=a.next());){if(!b&&("`"==d||"$"==d&&a.eat("{"))){c.tokenize=D;break}b=!b&&"\\"==d}return p("quasi","string-2",a.current())}function ja(a,c){c.fatArrowAt&&(c.fatArrowAt=null);var b=a.string.indexOf("=>",a.start);if(!(0>b)){if(m){var d=/:\s*(?:\w+(?:<[^>]*>|\[\])?|\{[^}]*\})\s*$/.exec(a.string.slice(a.start,b));d&&(b=d.index)}d=0;var g=!1;for(--b;0<=b;--b){var e=a.string.charAt(b),f="([{}])".indexOf(e);if(0<=f&&3>f){if(!d){++b;break}if(0==--d){"("==e&&(g=!0);break}}else if(3<=
	f&&6>f)++d;else if(ha.test(e))g=!0;else if(/["'\/`]/.test(e))for(;;--b){if(0==b)return;if(a.string.charAt(b-1)==e&&"\\"!=a.string.charAt(b-2)){b--;break}}else if(g&&!d){++b;break}}g&&!d&&(c.fatArrowAt=b)}}function ta(a,c,b,d,g,e){this.indented=a;this.column=c;this.type=b;this.prev=g;this.info=e;null!=d&&(this.align=d)}function f(){for(var a=arguments.length-1;0<=a;a--)d.cc.push(arguments[a])}function b(){f.apply(null,arguments);return!0}function ka(a,c){for(;c;c=c.next)if(c.name==a)return!0;return!1}
	function H(a){var c=d.state;d.marked="def";if(c.context)if("var"==c.lexical.info&&c.context&&c.context.block){var b=ua(a,c.context);if(null!=b){c.context=b;return}}else if(!ka(a,c.localVars)){c.localVars=new Q(a,c.localVars);return}w.globalVars&&!ka(a,c.globalVars)&&(c.globalVars=new Q(a,c.globalVars))}function ua(a,c){return c?c.block?(a=ua(a,c.prev))?a==c.prev?c:new R(a,c.vars,!0):null:ka(a,c.vars)?c:new R(c.prev,new Q(a,c.vars),!1):null}function X(a){return"public"==a||"private"==a||"protected"==
		a||"abstract"==a||"readonly"==a}function R(a,c,b){this.prev=a;this.vars=c;this.block=b}function Q(a,c){this.name=a;this.next=c}function I(){d.state.context=new R(d.state.context,d.state.localVars,!1);d.state.localVars=Ma}function va(){d.state.context=new R(d.state.context,d.state.localVars,!0);d.state.localVars=null}function z(){d.state.localVars=d.state.context.vars;d.state.context=d.state.context.prev}function e(a,c){var b=function(){var b=d.state,g=b.indented;if("stat"==b.lexical.type)g=b.lexical.indented;
	else for(var e=b.lexical;e&&")"==e.type&&e.align;e=e.prev)g=e.indented;b.lexical=new ta(g,d.stream.column(),a,null,b.lexical,c)};b.lex=!0;return b}function g(){var a=d.state;a.lexical.prev&&(")"==a.lexical.type&&(a.indented=a.lexical.indented),a.lexical=a.lexical.prev)}function h(a){function c(d){return d==a?b():";"==a||"}"==d||")"==d||"]"==d?f():b(c)}return c}function q(a,c){return"var"==a?b(e("vardef",c),la,h(";"),g):"keyword a"==a?b(e("form"),ma,q,g):"keyword b"==a?b(e("form"),q,g):"keyword d"==
	a?d.stream.match(/^\s*$/,!1)?b():b(e("stat"),na,h(";"),g):"debugger"==a?b(h(";")):"{"==a?b(e("}"),va,Y,g,z):";"==a?b():"if"==a?("else"==d.state.lexical.info&&d.state.cc[d.state.cc.length-1]==g&&d.state.cc.pop()(),b(e("form"),ma,q,g,wa)):"function"==a?b(x):"for"==a?b(e("form"),xa,q,g):"class"==a||m&&"interface"==c?(d.marked="keyword",b(e("form","class"==a?a:c),ya,g)):"variable"==a?m&&"declare"==c?(d.marked="keyword",b(q)):m&&("module"==c||"enum"==c||"type"==c)&&d.stream.match(/^\s*\w/,!1)?(d.marked=
		"keyword","enum"==c?b(za):"type"==c?b(Aa,h("operator"),n,h(";")):b(e("form"),y,h("{"),e("}"),Y,g,g)):m&&"namespace"==c?(d.marked="keyword",b(e("form"),l,q,g)):m&&"abstract"==c?(d.marked="keyword",b(q)):b(e("stat"),Na):"switch"==a?b(e("form"),ma,h("{"),e("}","switch"),va,Y,g,g,z):"case"==a?b(l,h(":")):"default"==a?b(h(":")):"catch"==a?b(e("form"),I,Oa,q,g,z):"export"==a?b(e("stat"),Pa,g):"import"==a?b(e("stat"),Qa,g):"async"==a?b(q):"@"==c?b(l,q):f(e("stat"),l,h(";"),g)}function Oa(a){if("("==a)return b(E,
		h(")"))}function l(a,c){return Ba(a,c,!1)}function v(a,c){return Ba(a,c,!0)}function ma(a){return"("!=a?f():b(e(")"),l,h(")"),g)}function Ba(a,c,u){if(d.state.fatArrowAt==d.stream.start){var k=u?Ca:Da;if("("==a)return b(I,e(")"),r(E,")"),g,h("=>"),k,z);if("variable"==a)return f(I,y,h("=>"),k,z)}k=u?J:K;return Ra.hasOwnProperty(a)?b(k):"function"==a?b(x,k):"class"==a||m&&"interface"==c?(d.marked="keyword",b(e("form"),Sa,g)):"keyword c"==a||"async"==a?b(u?v:l):"("==a?b(e(")"),na,h(")"),g,k):"operator"==
	a||"spread"==a?b(u?v:l):"["==a?b(e("]"),Ta,g,k):"{"==a?S(Z,"}",null,k):"quasi"==a?f(aa,k):"new"==a?b(Ua(u)):"import"==a?b(l):b()}function na(a){return a.match(/[;\}\)\],]/)?f():f(l)}function K(a,c){return","==a?b(l):J(a,c,!1)}function J(a,c,u){var k=0==u?K:J,ia=0==u?l:v;if("=>"==a)return b(I,u?Ca:Da,z);if("operator"==a)return/\+\+|--/.test(c)||m&&"!"==c?b(k):m&&"<"==c&&d.stream.match(/^([^>]|<.*?>)*>\s*\(/,!1)?b(e(">"),r(n,">"),g,k):"?"==c?b(l,h(":"),ia):b(ia);if("quasi"==a)return f(aa,k);if(";"!=
		a){if("("==a)return S(v,")","call",k);if("."==a)return b(Va,k);if("["==a)return b(e("]"),na,h("]"),g,k);if(m&&"as"==c)return d.marked="keyword",b(n,k);if("regexp"==a)return d.state.lastType=d.marked="operator",d.stream.backUp(d.stream.pos-d.stream.start-1),b(ia)}}function aa(a,c){return"quasi"!=a?f():"${"!=c.slice(c.length-2)?b(aa):b(l,Wa)}function Wa(a){if("}"==a)return d.marked="string-2",d.state.tokenize=fa,b(aa)}function Da(a){ja(d.stream,d.state);return f("{"==a?q:l)}function Ca(a){ja(d.stream,
		d.state);return f("{"==a?q:v)}function Ua(a){return function(c){return"."==c?b(a?Xa:Ya):"variable"==c&&m?b(Za,a?J:K):f(a?v:l)}}function Ya(a,c){if("target"==c)return d.marked="keyword",b(K)}function Xa(a,c){if("target"==c)return d.marked="keyword",b(J)}function Na(a){return":"==a?b(g,q):f(K,h(";"),g)}function Va(a){if("variable"==a)return d.marked="property",b()}function Z(a,c){if("async"==a)return d.marked="property",b(Z);if("variable"==a||"keyword"==d.style){d.marked="property";if("get"==c||"set"==
		c)return b($a);var e;m&&d.state.fatArrowAt==d.stream.start&&(e=d.stream.match(/^\s*:\s*/,!1))&&(d.state.fatArrowAt=d.stream.pos+e[0].length);return b(F)}if("number"==a||"string"==a)return d.marked=W?"property":d.style+" property",b(F);if("jsonld-keyword"==a)return b(F);if(m&&X(c))return d.marked="keyword",b(Z);if("["==a)return b(l,L,h("]"),F);if("spread"==a)return b(v,F);if("*"==c)return d.marked="keyword",b(Z);if(":"==a)return f(F)}function $a(a){if("variable"!=a)return f(F);d.marked="property";
		return b(x)}function F(a){if(":"==a)return b(v);if("("==a)return f(x)}function r(a,c,e){function g(k,u){return(e?-1<e.indexOf(k):","==k)?(k=d.state.lexical,"call"==k.info&&(k.pos=(k.pos||0)+1),b(function(b,d){return b==c||d==c?f():f(a)},g)):k==c||u==c?b():e&&-1<e.indexOf(";")?f(a):b(h(c))}return function(d,e){return d==c||e==c?b():f(a,g)}}function S(a,c,f){for(var k=3;k<arguments.length;k++)d.cc.push(arguments[k]);return b(e(c,f),r(a,c),g)}function Y(a){return"}"==a?b():f(q,Y)}function L(a,c){if(m){if(":"==
		a)return b(n);if("?"==c)return b(L)}}function ab(a,c){if(m&&(":"==a||"in"==c))return b(n)}function Ea(a){if(m&&":"==a)return d.stream.match(/^\s*\w+\s+is\b/,!1)?b(l,bb,n):b(n)}function bb(a,c){if("is"==c)return d.marked="keyword",b()}function n(a,c){if("keyof"==c||"typeof"==c||"infer"==c)return d.marked="keyword",b("typeof"==c?v:n);if("variable"==a||"void"==c)return d.marked="type",b(B);if("|"==c||"&"==c)return b(n);if("string"==a||"number"==a||"atom"==a)return b(B);if("["==a)return b(e("]"),r(n,
		"]",","),g,B);if("{"==a)return b(e("}"),r(T,"}",",;"),g,B);if("("==a)return b(r(oa,")"),cb,B);if("<"==a)return b(r(n,">"),n)}function cb(a){if("=>"==a)return b(n)}function T(a,c){if("variable"==a||"keyword"==d.style)return d.marked="property",b(T);if("?"==c||"number"==a||"string"==a)return b(T);if(":"==a)return b(n);if("["==a)return b(h("variable"),ab,h("]"),T);if("("==a)return f(M,T)}function oa(a,c){return"variable"==a&&d.stream.match(/^\s*[?:]/,!1)||"?"==c?b(oa):":"==a?b(n):"spread"==a?b(oa):f(n)}
	function B(a,c){if("<"==c)return b(e(">"),r(n,">"),g,B);if("|"==c||"."==a||"&"==c)return b(n);if("["==a)return b(n,h("]"),B);if("extends"==c||"implements"==c)return d.marked="keyword",b(n);if("?"==c)return b(n,h(":"),n)}function Za(a,c){if("<"==c)return b(e(">"),r(n,">"),g,B)}function ba(){return f(n,db)}function db(a,c){if("="==c)return b(n)}function la(a,c){return"enum"==c?(d.marked="keyword",b(za)):f(y,L,C,eb)}function y(a,c){if(m&&X(c))return d.marked="keyword",b(y);if("variable"==a)return H(c),
		b();if("spread"==a)return b(y);if("["==a)return S(fb,"]");if("{"==a)return S(Fa,"}")}function Fa(a,c){if("variable"==a&&!d.stream.match(/^\s*:/,!1))return H(c),b(C);"variable"==a&&(d.marked="property");return"spread"==a?b(y):"}"==a?f():"["==a?b(l,h("]"),h(":"),Fa):b(h(":"),y,C)}function fb(){return f(y,C)}function C(a,c){if("="==c)return b(v)}function eb(a){if(","==a)return b(la)}function wa(a,c){if("keyword b"==a&&"else"==c)return b(e("form","else"),q,g)}function xa(a,c){if("await"==c)return b(xa);
		if("("==a)return b(e(")"),gb,g)}function gb(a){return"var"==a?b(la,N):"variable"==a?b(N):f(N)}function N(a,c){return")"==a?b():";"==a?b(N):"in"==c||"of"==c?(d.marked="keyword",b(l,N)):f(l,N)}function x(a,c){if("*"==c)return d.marked="keyword",b(x);if("variable"==a)return H(c),b(x);if("("==a)return b(I,e(")"),r(E,")"),g,Ea,q,z);if(m&&"<"==c)return b(e(">"),r(ba,">"),g,x)}function M(a,c){if("*"==c)return d.marked="keyword",b(M);if("variable"==a)return H(c),b(M);if("("==a)return b(I,e(")"),r(E,")"),
		g,Ea,z);if(m&&"<"==c)return b(e(">"),r(ba,">"),g,M)}function Aa(a,c){if("keyword"==a||"variable"==a)return d.marked="type",b(Aa);if("<"==c)return b(e(">"),r(ba,">"),g)}function E(a,c){"@"==c&&b(l,E);return"spread"==a?b(E):m&&X(c)?(d.marked="keyword",b(E)):m&&"this"==a?b(L,C):f(y,L,C)}function Sa(a,c){return"variable"==a?ya(a,c):ca(a,c)}function ya(a,c){if("variable"==a)return H(c),b(ca)}function ca(a,c){if("<"==c)return b(e(">"),r(ba,">"),g,ca);if("extends"==c||"implements"==c||m&&","==a)return"implements"==
	c&&(d.marked="keyword"),b(m?n:l,ca);if("{"==a)return b(e("}"),A,g)}function A(a,c){if("async"==a||"variable"==a&&("static"==c||"get"==c||"set"==c||m&&X(c))&&d.stream.match(/^\s+[\w$\xa1-\uffff]/,!1))return d.marked="keyword",b(A);if("variable"==a||"keyword"==d.style)return d.marked="property",b(m?da:x,A);if("number"==a||"string"==a)return b(m?da:x,A);if("["==a)return b(l,L,h("]"),m?da:x,A);if("*"==c)return d.marked="keyword",b(A);if(m&&"("==a)return f(M,A);if(";"==a||","==a)return b(A);if("}"==a)return b();
		if("@"==c)return b(l,A)}function da(a,c){if("?"==c)return b(da);if(":"==a)return b(n,C);if("="==c)return b(v);a=d.state.lexical.prev;return f(a&&"interface"==a.info?M:x)}function Pa(a,c){return"*"==c?(d.marked="keyword",b(pa,h(";"))):"default"==c?(d.marked="keyword",b(l,h(";"))):"{"==a?b(r(Ga,"}"),pa,h(";")):f(q)}function Ga(a,c){if("as"==c)return d.marked="keyword",b(h("variable"));if("variable"==a)return f(v,Ga)}function Qa(a){return"string"==a?b():"("==a?f(l):f(ea,Ha,pa)}function ea(a,c){if("{"==
		a)return S(ea,"}");"variable"==a&&H(c);"*"==c&&(d.marked="keyword");return b(hb)}function Ha(a){if(","==a)return b(ea,Ha)}function hb(a,c){if("as"==c)return d.marked="keyword",b(ea)}function pa(a,c){if("from"==c)return d.marked="keyword",b(l)}function Ta(a){return"]"==a?b():f(r(v,"]"))}function za(){return f(e("form"),y,h("{"),e("}"),r(ib,"}"),g,g)}function ib(){return f(y,C)}function qa(a,c,b){return c.tokenize==D&&/^(?:operator|sof|keyword [bcd]|case|new|export|default|spread|[\[{}\(,;:]|=>)$/.test(c.lastType)||
		"quasi"==c.lastType&&/\{\s*$/.test(a.string.slice(0,a.pos-(b||0)))}var O=Ja.indentUnit,Ia=w.statementIndent,W=w.jsonld,G=w.json||W,m=w.typescript,ha=w.wordCharacters||/[\w$\xa1-\uffff]/,sa=function(){function a(a){return{type:a,style:"keyword"}}var c=a("keyword a"),b=a("keyword b"),d=a("keyword c"),e=a("keyword d"),g=a("operator"),f={type:"atom",style:"atom"};return{"if":a("if"),"while":c,"with":c,"else":b,"do":b,"try":b,"finally":b,"return":e,"break":e,"continue":e,"new":a("new"),"delete":d,"void":d,
			"throw":d,"debugger":a("debugger"),"var":a("var"),"const":a("var"),let:a("var"),"function":a("function"),"catch":a("catch"),"for":a("for"),"switch":a("switch"),"case":a("case"),"default":a("default"),"in":g,"typeof":g,"instanceof":g,"true":f,"false":f,"null":f,undefined:f,NaN:f,Infinity:f,"this":a("this"),"class":a("class"),"super":a("atom"),yield:d,"export":a("export"),"import":a("import"),"extends":d,await:d}}(),ra=/[+\-*&%=<>!?|~^@]/,La=/^@(context|id|value|language|type|container|list|set|reverse|index|base|vocab|graph)"/,
		P,U,Ra={atom:!0,number:!0,variable:!0,string:!0,regexp:!0,"this":!0,"jsonld-keyword":!0},d={state:null,column:null,marked:null,cc:null},Ma=new Q("this",new Q("arguments",null));z.lex=!0;g.lex=!0;return{startState:function(a){a={tokenize:D,lastType:"sof",cc:[],lexical:new ta((a||0)-O,0,"block",!1),localVars:w.localVars,context:w.localVars&&new R(null,null,!1),indented:a||0};w.globalVars&&"object"==typeof w.globalVars&&(a.globalVars=w.globalVars);return a},token:function(a,b){a.sol()&&(b.lexical.hasOwnProperty("align")||
		(b.lexical.align=!1),b.indented=a.indentation(),ja(a,b));if(b.tokenize!=V&&a.eatSpace())return null;var c=b.tokenize(a,b);if("comment"==P)return c;b.lastType="operator"!=P||"++"!=U&&"--"!=U?P:"incdec";a:{var e=P,g=U,f=b.cc;d.state=b;d.stream=a;d.marked=null;d.cc=f;d.style=c;b.lexical.hasOwnProperty("align")||(b.lexical.align=!0);for(;;)if((f.length?f.pop():G?l:q)(e,g)){for(;f.length&&f[f.length-1].lex;)f.pop()();if(d.marked){c=d.marked;break a}if(a="variable"==e)b:{for(a=b.localVars;a;a=a.next)if(a.name==
			g){a=!0;break b}for(b=b.context;b;b=b.prev)for(a=b.vars;a;a=a.next)if(a.name==g){a=!0;break b}a=void 0}if(a){c="variable-2";break a}break a}}return c},indent:function(a,b){if(a.tokenize==V)return t.Pass;if(a.tokenize!=D)return 0;var c=b&&b.charAt(0),d=a.lexical,e;if(!/^\s*else\b/.test(b))for(var f=a.cc.length-1;0<=f;--f){var h=a.cc[f];if(h==g)d=d.prev;else if(h!=wa)break}for(;!("stat"!=d.type&&"form"!=d.type||"}"!=c&&(!(e=a.cc[a.cc.length-1])||e!=K&&e!=J||/^[,\.=+\-*:?[\(]/.test(b)));)d=d.prev;Ia&&
		")"==d.type&&"stat"==d.prev.type&&(d=d.prev);e=d.type;f=c==e;return"vardef"==e?d.indented+("operator"==a.lastType||","==a.lastType?d.info.length+1:0):"form"==e&&"{"==c?d.indented:"form"==e?d.indented+O:"stat"==e?(c=d.indented,a="operator"==a.lastType||","==a.lastType||ra.test(b.charAt(0))||/[,.]/.test(b.charAt(0)),c+(a?Ia||O:0)):"switch"!=d.info||f||0==w.doubleIndentSwitch?d.align?d.column+(f?0:1):d.indented+(f?0:O):d.indented+(/^(?:case|default)\b/.test(b)?O:2*O)},electricInput:/^\s*(?:case .*?:|default:|\{|\})$/,
		blockCommentStart:G?null:"/*",blockCommentEnd:G?null:"*/",blockCommentContinue:G?null:" * ",lineComment:G?null:"//",fold:"brace",closeBrackets:"()[]{}''\"\"``",helperType:G?"json":"javascript",jsonldMode:W,jsonMode:G,expressionAllowed:qa,skipExpression:function(a){var b=a.cc[a.cc.length-1];b!=l&&b!=v||a.cc.pop()}}});t.registerHelper("wordChars","javascript",/[\w$]/);t.defineMIME("text/javascript","javascript");t.defineMIME("text/ecmascript","javascript");t.defineMIME("application/javascript","javascript");
	t.defineMIME("application/x-javascript","javascript");t.defineMIME("application/ecmascript","javascript");t.defineMIME("application/json",{name:"javascript",json:!0});t.defineMIME("application/x-json",{name:"javascript",json:!0});t.defineMIME("application/ld+json",{name:"javascript",jsonld:!0});t.defineMIME("text/typescript",{name:"javascript",typescript:!0});t.defineMIME("application/typescript",{name:"javascript",typescript:!0})});

// CodeMirror, copyright (c) by Marijn Haverbeke and others
// Distributed under an MIT license: https://codemirror.net/LICENSE

(function(mod) {
	if (typeof exports == "object" && typeof module == "object") // CommonJS
		mod(require("../../lib/codemirror"), require("../css/css"));
	else if (typeof define == "function" && define.amd) // AMD
		define(["../../lib/codemirror", "../css/css"], mod);
	else // Plain browser env
		mod(CodeMirror);
})(function(CodeMirror) {
	"use strict";

	CodeMirror.defineMode("htmltwig", function(config, parserConfig) {
		return CodeMirror.overlayMode(CodeMirror.getMode(config, parserConfig.backdrop || "text/html"), CodeMirror.getMode(config, "twig"));
	});

	CodeMirror.defineMode("sass", function(config) {
		var cssMode = CodeMirror.mimeModes["text/css"];
		var propertyKeywords = cssMode.propertyKeywords || {},
			colorKeywords = cssMode.colorKeywords || {},
			valueKeywords = cssMode.valueKeywords || {},
			fontProperties = cssMode.fontProperties || {};

		function tokenRegexp(words) {
			return new RegExp("^" + words.join("|"));
		}

		var keywords = ["true", "false", "null", "auto"];
		var keywordsRegexp = new RegExp("^" + keywords.join("|"));

		var operators = ["\\(", "\\)", "=", ">", "<", "==", ">=", "<=", "\\+", "-",
			"\\!=", "/", "\\*", "%", "and", "or", "not", ";","\\{","\\}",":"];
		var opRegexp = tokenRegexp(operators);

		var pseudoElementsRegexp = /^::?[a-zA-Z_][\w\-]*/;

		var word;

		function isEndLine(stream) {
			return !stream.peek() || stream.match(/\s+$/, false);
		}

		function urlTokens(stream, state) {
			var ch = stream.peek();

			if (ch === ")") {
				stream.next();
				state.tokenizer = tokenBase;
				return "operator";
			} else if (ch === "(") {
				stream.next();
				stream.eatSpace();

				return "operator";
			} else if (ch === "'" || ch === '"') {
				state.tokenizer = buildStringTokenizer(stream.next());
				return "string";
			} else {
				state.tokenizer = buildStringTokenizer(")", false);
				return "string";
			}
		}
		function comment(indentation, multiLine) {
			return function(stream, state) {
				if (stream.sol() && stream.indentation() <= indentation) {
					state.tokenizer = tokenBase;
					return tokenBase(stream, state);
				}

				if (multiLine && stream.skipTo("*/")) {
					stream.next();
					stream.next();
					state.tokenizer = tokenBase;
				} else {
					stream.skipToEnd();
				}

				return "comment";
			};
		}

		function buildStringTokenizer(quote, greedy) {
			if (greedy == null) { greedy = true; }

			function stringTokenizer(stream, state) {
				var nextChar = stream.next();
				var peekChar = stream.peek();
				var previousChar = stream.string.charAt(stream.pos-2);

				var endingString = ((nextChar !== "\\" && peekChar === quote) || (nextChar === quote && previousChar !== "\\"));

				if (endingString) {
					if (nextChar !== quote && greedy) { stream.next(); }
					if (isEndLine(stream)) {
						state.cursorHalf = 0;
					}
					state.tokenizer = tokenBase;
					return "string";
				} else if (nextChar === "#" && peekChar === "{") {
					state.tokenizer = buildInterpolationTokenizer(stringTokenizer);
					stream.next();
					return "operator";
				} else {
					return "string";
				}
			}

			return stringTokenizer;
		}

		function buildInterpolationTokenizer(currentTokenizer) {
			return function(stream, state) {
				if (stream.peek() === "}") {
					stream.next();
					state.tokenizer = currentTokenizer;
					return "operator";
				} else {
					return tokenBase(stream, state);
				}
			};
		}

		function indent(state) {
			if (state.indentCount == 0) {
				state.indentCount++;
				var lastScopeOffset = state.scopes[0].offset;
				var currentOffset = lastScopeOffset + config.indentUnit;
				state.scopes.unshift({ offset:currentOffset });
			}
		}

		function dedent(state) {
			if (state.scopes.length == 1) return;

			state.scopes.shift();
		}

		function tokenBase(stream, state) {
			var ch = stream.peek();

			// Comment
			if (stream.match("/*")) {
				state.tokenizer = comment(stream.indentation(), true);
				return state.tokenizer(stream, state);
			}
			if (stream.match("//")) {
				state.tokenizer = comment(stream.indentation(), false);
				return state.tokenizer(stream, state);
			}

			// Interpolation
			if (stream.match("#{")) {
				state.tokenizer = buildInterpolationTokenizer(tokenBase);
				return "operator";
			}

			// Strings
			if (ch === '"' || ch === "'") {
				stream.next();
				state.tokenizer = buildStringTokenizer(ch);
				return "string";
			}

			if(!state.cursorHalf){// state.cursorHalf === 0
				// first half i.e. before : for key-value pairs
				// including selectors

				if (ch === "-") {
					if (stream.match(/^-\w+-/)) {
						return "meta";
					}
				}

				if (ch === ".") {
					stream.next();
					if (stream.match(/^[\w-]+/)) {
						indent(state);
						return "qualifier";
					} else if (stream.peek() === "#") {
						indent(state);
						return "tag";
					}
				}

				if (ch === "#") {
					stream.next();
					// ID selectors
					if (stream.match(/^[\w-]+/)) {
						indent(state);
						return "builtin";
					}
					if (stream.peek() === "#") {
						indent(state);
						return "tag";
					}
				}

				// Variables
				if (ch === "$") {
					stream.next();
					stream.eatWhile(/[\w-]/);
					return "variable-2";
				}

				// Numbers
				if (stream.match(/^-?[0-9\.]+/))
					return "number";

				// Units
				if (stream.match(/^(px|em|in)\b/))
					return "unit";

				if (stream.match(keywordsRegexp))
					return "keyword";

				if (stream.match(/^url/) && stream.peek() === "(") {
					state.tokenizer = urlTokens;
					return "atom";
				}

				if (ch === "=") {
					// Match shortcut mixin definition
					if (stream.match(/^=[\w-]+/)) {
						indent(state);
						return "meta";
					}
				}

				if (ch === "+") {
					// Match shortcut mixin definition
					if (stream.match(/^\+[\w-]+/)){
						return "variable-3";
					}
				}

				if(ch === "@"){
					if(stream.match(/@extend/)){
						if(!stream.match(/\s*[\w]/))
							dedent(state);
					}
				}


				// Indent Directives
				if (stream.match(/^@(else if|if|media|else|for|each|while|mixin|function)/)) {
					indent(state);
					return "def";
				}

				// Other Directives
				if (ch === "@") {
					stream.next();
					stream.eatWhile(/[\w-]/);
					return "def";
				}

				if (stream.eatWhile(/[\w-]/)){
					if(stream.match(/ *: *[\w-\+\$#!\("']/,false)){
						word = stream.current().toLowerCase();
						var prop = state.prevProp + "-" + word;
						if (propertyKeywords.hasOwnProperty(prop)) {
							return "property";
						} else if (propertyKeywords.hasOwnProperty(word)) {
							state.prevProp = word;
							return "property";
						} else if (fontProperties.hasOwnProperty(word)) {
							return "property";
						}
						return "tag";
					}
					else if(stream.match(/ *:/,false)){
						indent(state);
						state.cursorHalf = 1;
						state.prevProp = stream.current().toLowerCase();
						return "property";
					}
					else if(stream.match(/ *,/,false)){
						return "tag";
					}
					else{
						indent(state);
						return "tag";
					}
				}

				if(ch === ":"){
					if (stream.match(pseudoElementsRegexp)){ // could be a pseudo-element
						return "variable-3";
					}
					stream.next();
					state.cursorHalf=1;
					return "operator";
				}

			} // cursorHalf===0 ends here
			else{

				if (ch === "#") {
					stream.next();
					// Hex numbers
					if (stream.match(/[0-9a-fA-F]{6}|[0-9a-fA-F]{3}/)){
						if (isEndLine(stream)) {
							state.cursorHalf = 0;
						}
						return "number";
					}
				}

				// Numbers
				if (stream.match(/^-?[0-9\.]+/)){
					if (isEndLine(stream)) {
						state.cursorHalf = 0;
					}
					return "number";
				}

				// Units
				if (stream.match(/^(px|em|in)\b/)){
					if (isEndLine(stream)) {
						state.cursorHalf = 0;
					}
					return "unit";
				}

				if (stream.match(keywordsRegexp)){
					if (isEndLine(stream)) {
						state.cursorHalf = 0;
					}
					return "keyword";
				}

				if (stream.match(/^url/) && stream.peek() === "(") {
					state.tokenizer = urlTokens;
					if (isEndLine(stream)) {
						state.cursorHalf = 0;
					}
					return "atom";
				}

				// Variables
				if (ch === "$") {
					stream.next();
					stream.eatWhile(/[\w-]/);
					if (isEndLine(stream)) {
						state.cursorHalf = 0;
					}
					return "variable-2";
				}

				// bang character for !important, !default, etc.
				if (ch === "!") {
					stream.next();
					state.cursorHalf = 0;
					return stream.match(/^[\w]+/) ? "keyword": "operator";
				}

				if (stream.match(opRegexp)){
					if (isEndLine(stream)) {
						state.cursorHalf = 0;
					}
					return "operator";
				}

				// attributes
				if (stream.eatWhile(/[\w-]/)) {
					if (isEndLine(stream)) {
						state.cursorHalf = 0;
					}
					word = stream.current().toLowerCase();
					if (valueKeywords.hasOwnProperty(word)) {
						return "atom";
					} else if (colorKeywords.hasOwnProperty(word)) {
						return "keyword";
					} else if (propertyKeywords.hasOwnProperty(word)) {
						state.prevProp = stream.current().toLowerCase();
						return "property";
					} else {
						return "tag";
					}
				}

				//stream.eatSpace();
				if (isEndLine(stream)) {
					state.cursorHalf = 0;
					return null;
				}

			} // else ends here

			if (stream.match(opRegexp))
				return "operator";

			// If we haven't returned by now, we move 1 character
			// and return an error
			stream.next();
			return null;
		}

		function tokenLexer(stream, state) {
			if (stream.sol()) state.indentCount = 0;
			var style = state.tokenizer(stream, state);
			var current = stream.current();

			if (current === "@return" || current === "}"){
				dedent(state);
			}

			if (style !== null) {
				var startOfToken = stream.pos - current.length;

				var withCurrentIndent = startOfToken + (config.indentUnit * state.indentCount);

				var newScopes = [];

				for (var i = 0; i < state.scopes.length; i++) {
					var scope = state.scopes[i];

					if (scope.offset <= withCurrentIndent)
						newScopes.push(scope);
				}

				state.scopes = newScopes;
			}


			return style;
		}

		return {
			startState: function() {
				return {
					tokenizer: tokenBase,
					scopes: [{offset: 0, type: "sass"}],
					indentCount: 0,
					cursorHalf: 0,  // cursor half tells us if cursor lies after (1)
									// or before (0) colon (well... more or less)
					definedVars: [],
					definedMixins: []
				};
			},
			token: function(stream, state) {
				var style = tokenLexer(stream, state);

				state.lastToken = { style: style, content: stream.current() };

				return style;
			},

			indent: function(state) {
				return state.scopes[0].offset;
			}
		};
	}, "css");

	CodeMirror.defineMIME("text/x-sass", "sass");

});


// CodeMirror, copyright (c) by Marijn Haverbeke and others
// Distributed under an MIT license: https://codemirror.net/LICENSE

(function(mod) {
	if (typeof exports == "object" && typeof module == "object") // CommonJS
		mod(require("../../lib/codemirror"));
	else if (typeof define == "function" && define.amd) // AMD
		define(["../../lib/codemirror"], mod);
	else // Plain browser env
		mod(CodeMirror);
})(function(CodeMirror) {
	"use strict";

	CodeMirror.defineMode("css", function(config, parserConfig) {
		var inline = parserConfig.inline
		if (!parserConfig.propertyKeywords) parserConfig = CodeMirror.resolveMode("text/css");

		var indentUnit = config.indentUnit,
			tokenHooks = parserConfig.tokenHooks,
			documentTypes = parserConfig.documentTypes || {},
			mediaTypes = parserConfig.mediaTypes || {},
			mediaFeatures = parserConfig.mediaFeatures || {},
			mediaValueKeywords = parserConfig.mediaValueKeywords || {},
			propertyKeywords = parserConfig.propertyKeywords || {},
			nonStandardPropertyKeywords = parserConfig.nonStandardPropertyKeywords || {},
			fontProperties = parserConfig.fontProperties || {},
			counterDescriptors = parserConfig.counterDescriptors || {},
			colorKeywords = parserConfig.colorKeywords || {},
			valueKeywords = parserConfig.valueKeywords || {},
			allowNested = parserConfig.allowNested,
			lineComment = parserConfig.lineComment,
			supportsAtComponent = parserConfig.supportsAtComponent === true;

		var type, override;
		function ret(style, tp) { type = tp; return style; }

		// Tokenizers

		function tokenBase(stream, state) {
			var ch = stream.next();
			if (tokenHooks[ch]) {
				var result = tokenHooks[ch](stream, state);
				if (result !== false) return result;
			}
			if (ch == "@") {
				stream.eatWhile(/[\w\\\-]/);
				return ret("def", stream.current());
			} else if (ch == "=" || (ch == "~" || ch == "|") && stream.eat("=")) {
				return ret(null, "compare");
			} else if (ch == "\"" || ch == "'") {
				state.tokenize = tokenString(ch);
				return state.tokenize(stream, state);
			} else if (ch == "#") {
				stream.eatWhile(/[\w\\\-]/);
				return ret("atom", "hash");
			} else if (ch == "!") {
				stream.match(/^\s*\w*/);
				return ret("keyword", "important");
			} else if (/\d/.test(ch) || ch == "." && stream.eat(/\d/)) {
				stream.eatWhile(/[\w.%]/);
				return ret("number", "unit");
			} else if (ch === "-") {
				if (/[\d.]/.test(stream.peek())) {
					stream.eatWhile(/[\w.%]/);
					return ret("number", "unit");
				} else if (stream.match(/^-[\w\\\-]*/)) {
					stream.eatWhile(/[\w\\\-]/);
					if (stream.match(/^\s*:/, false))
						return ret("variable-2", "variable-definition");
					return ret("variable-2", "variable");
				} else if (stream.match(/^\w+-/)) {
					return ret("meta", "meta");
				}
			} else if (/[,+>*\/]/.test(ch)) {
				return ret(null, "select-op");
			} else if (ch == "." && stream.match(/^-?[_a-z][_a-z0-9-]*/i)) {
				return ret("qualifier", "qualifier");
			} else if (/[:;{}\[\]\(\)]/.test(ch)) {
				return ret(null, ch);
			} else if (stream.match(/[\w-.]+(?=\()/)) {
				if (/^(url(-prefix)?|domain|regexp)$/.test(stream.current().toLowerCase())) {
					state.tokenize = tokenParenthesized;
				}
				return ret("variable callee", "variable");
			} else if (/[\w\\\-]/.test(ch)) {
				stream.eatWhile(/[\w\\\-]/);
				return ret("property", "word");
			} else {
				return ret(null, null);
			}
		}

		function tokenString(quote) {
			return function(stream, state) {
				var escaped = false, ch;
				while ((ch = stream.next()) != null) {
					if (ch == quote && !escaped) {
						if (quote == ")") stream.backUp(1);
						break;
					}
					escaped = !escaped && ch == "\\";
				}
				if (ch == quote || !escaped && quote != ")") state.tokenize = null;
				return ret("string", "string");
			};
		}

		function tokenParenthesized(stream, state) {
			stream.next(); // Must be '('
			if (!stream.match(/\s*[\"\')]/, false))
				state.tokenize = tokenString(")");
			else
				state.tokenize = null;
			return ret(null, "(");
		}

		// Context management

		function Context(type, indent, prev) {
			this.type = type;
			this.indent = indent;
			this.prev = prev;
		}

		function pushContext(state, stream, type, indent) {
			state.context = new Context(type, stream.indentation() + (indent === false ? 0 : indentUnit), state.context);
			return type;
		}

		function popContext(state) {
			if (state.context.prev)
				state.context = state.context.prev;
			return state.context.type;
		}

		function pass(type, stream, state) {
			return states[state.context.type](type, stream, state);
		}
		function popAndPass(type, stream, state, n) {
			for (var i = n || 1; i > 0; i--)
				state.context = state.context.prev;
			return pass(type, stream, state);
		}

		// Parser

		function wordAsValue(stream) {
			var word = stream.current().toLowerCase();
			if (valueKeywords.hasOwnProperty(word))
				override = "atom";
			else if (colorKeywords.hasOwnProperty(word))
				override = "keyword";
			else
				override = "variable";
		}

		var states = {};

		states.top = function(type, stream, state) {
			if (type == "{") {
				return pushContext(state, stream, "block");
			} else if (type == "}" && state.context.prev) {
				return popContext(state);
			} else if (supportsAtComponent && /@component/i.test(type)) {
				return pushContext(state, stream, "atComponentBlock");
			} else if (/^@(-moz-)?document$/i.test(type)) {
				return pushContext(state, stream, "documentTypes");
			} else if (/^@(media|supports|(-moz-)?document|import)$/i.test(type)) {
				return pushContext(state, stream, "atBlock");
			} else if (/^@(font-face|counter-style)/i.test(type)) {
				state.stateArg = type;
				return "restricted_atBlock_before";
			} else if (/^@(-(moz|ms|o|webkit)-)?keyframes$/i.test(type)) {
				return "keyframes";
			} else if (type && type.charAt(0) == "@") {
				return pushContext(state, stream, "at");
			} else if (type == "hash") {
				override = "builtin";
			} else if (type == "word") {
				override = "tag";
			} else if (type == "variable-definition") {
				return "maybeprop";
			} else if (type == "interpolation") {
				return pushContext(state, stream, "interpolation");
			} else if (type == ":") {
				return "pseudo";
			} else if (allowNested && type == "(") {
				return pushContext(state, stream, "parens");
			}
			return state.context.type;
		};

		states.block = function(type, stream, state) {
			if (type == "word") {
				var word = stream.current().toLowerCase();
				if (propertyKeywords.hasOwnProperty(word)) {
					override = "property";
					return "maybeprop";
				} else if (nonStandardPropertyKeywords.hasOwnProperty(word)) {
					override = "string-2";
					return "maybeprop";
				} else if (allowNested) {
					override = stream.match(/^\s*:(?:\s|$)/, false) ? "property" : "tag";
					return "block";
				} else {
					override += " error";
					return "maybeprop";
				}
			} else if (type == "meta") {
				return "block";
			} else if (!allowNested && (type == "hash" || type == "qualifier")) {
				override = "error";
				return "block";
			} else {
				return states.top(type, stream, state);
			}
		};

		states.maybeprop = function(type, stream, state) {
			if (type == ":") return pushContext(state, stream, "prop");
			return pass(type, stream, state);
		};

		states.prop = function(type, stream, state) {
			if (type == ";") return popContext(state);
			if (type == "{" && allowNested) return pushContext(state, stream, "propBlock");
			if (type == "}" || type == "{") return popAndPass(type, stream, state);
			if (type == "(") return pushContext(state, stream, "parens");

			if (type == "hash" && !/^#([0-9a-fA-f]{3,4}|[0-9a-fA-f]{6}|[0-9a-fA-f]{8})$/.test(stream.current())) {
				override += " error";
			} else if (type == "word") {
				wordAsValue(stream);
			} else if (type == "interpolation") {
				return pushContext(state, stream, "interpolation");
			}
			return "prop";
		};

		states.propBlock = function(type, _stream, state) {
			if (type == "}") return popContext(state);
			if (type == "word") { override = "property"; return "maybeprop"; }
			return state.context.type;
		};

		states.parens = function(type, stream, state) {
			if (type == "{" || type == "}") return popAndPass(type, stream, state);
			if (type == ")") return popContext(state);
			if (type == "(") return pushContext(state, stream, "parens");
			if (type == "interpolation") return pushContext(state, stream, "interpolation");
			if (type == "word") wordAsValue(stream);
			return "parens";
		};

		states.pseudo = function(type, stream, state) {
			if (type == "meta") return "pseudo";

			if (type == "word") {
				override = "variable-3";
				return state.context.type;
			}
			return pass(type, stream, state);
		};

		states.documentTypes = function(type, stream, state) {
			if (type == "word" && documentTypes.hasOwnProperty(stream.current())) {
				override = "tag";
				return state.context.type;
			} else {
				return states.atBlock(type, stream, state);
			}
		};

		states.atBlock = function(type, stream, state) {
			if (type == "(") return pushContext(state, stream, "atBlock_parens");
			if (type == "}" || type == ";") return popAndPass(type, stream, state);
			if (type == "{") return popContext(state) && pushContext(state, stream, allowNested ? "block" : "top");

			if (type == "interpolation") return pushContext(state, stream, "interpolation");

			if (type == "word") {
				var word = stream.current().toLowerCase();
				if (word == "only" || word == "not" || word == "and" || word == "or")
					override = "keyword";
				else if (mediaTypes.hasOwnProperty(word))
					override = "attribute";
				else if (mediaFeatures.hasOwnProperty(word))
					override = "property";
				else if (mediaValueKeywords.hasOwnProperty(word))
					override = "keyword";
				else if (propertyKeywords.hasOwnProperty(word))
					override = "property";
				else if (nonStandardPropertyKeywords.hasOwnProperty(word))
					override = "string-2";
				else if (valueKeywords.hasOwnProperty(word))
					override = "atom";
				else if (colorKeywords.hasOwnProperty(word))
					override = "keyword";
				else
					override = "error";
			}
			return state.context.type;
		};

		states.atComponentBlock = function(type, stream, state) {
			if (type == "}")
				return popAndPass(type, stream, state);
			if (type == "{")
				return popContext(state) && pushContext(state, stream, allowNested ? "block" : "top", false);
			if (type == "word")
				override = "error";
			return state.context.type;
		};

		states.atBlock_parens = function(type, stream, state) {
			if (type == ")") return popContext(state);
			if (type == "{" || type == "}") return popAndPass(type, stream, state, 2);
			return states.atBlock(type, stream, state);
		};

		states.restricted_atBlock_before = function(type, stream, state) {
			if (type == "{")
				return pushContext(state, stream, "restricted_atBlock");
			if (type == "word" && state.stateArg == "@counter-style") {
				override = "variable";
				return "restricted_atBlock_before";
			}
			return pass(type, stream, state);
		};

		states.restricted_atBlock = function(type, stream, state) {
			if (type == "}") {
				state.stateArg = null;
				return popContext(state);
			}
			if (type == "word") {
				if ((state.stateArg == "@font-face" && !fontProperties.hasOwnProperty(stream.current().toLowerCase())) ||
					(state.stateArg == "@counter-style" && !counterDescriptors.hasOwnProperty(stream.current().toLowerCase())))
					override = "error";
				else
					override = "property";
				return "maybeprop";
			}
			return "restricted_atBlock";
		};

		states.keyframes = function(type, stream, state) {
			if (type == "word") { override = "variable"; return "keyframes"; }
			if (type == "{") return pushContext(state, stream, "top");
			return pass(type, stream, state);
		};

		states.at = function(type, stream, state) {
			if (type == ";") return popContext(state);
			if (type == "{" || type == "}") return popAndPass(type, stream, state);
			if (type == "word") override = "tag";
			else if (type == "hash") override = "builtin";
			return "at";
		};

		states.interpolation = function(type, stream, state) {
			if (type == "}") return popContext(state);
			if (type == "{" || type == ";") return popAndPass(type, stream, state);
			if (type == "word") override = "variable";
			else if (type != "variable" && type != "(" && type != ")") override = "error";
			return "interpolation";
		};

		return {
			startState: function(base) {
				return {tokenize: null,
					state: inline ? "block" : "top",
					stateArg: null,
					context: new Context(inline ? "block" : "top", base || 0, null)};
			},

			token: function(stream, state) {
				if (!state.tokenize && stream.eatSpace()) return null;
				var style = (state.tokenize || tokenBase)(stream, state);
				if (style && typeof style == "object") {
					type = style[1];
					style = style[0];
				}
				override = style;
				if (type != "comment")
					state.state = states[state.state](type, stream, state);
				return override;
			},

			indent: function(state, textAfter) {
				var cx = state.context, ch = textAfter && textAfter.charAt(0);
				var indent = cx.indent;
				if (cx.type == "prop" && (ch == "}" || ch == ")")) cx = cx.prev;
				if (cx.prev) {
					if (ch == "}" && (cx.type == "block" || cx.type == "top" ||
						cx.type == "interpolation" || cx.type == "restricted_atBlock")) {
						// Resume indentation from parent context.
						cx = cx.prev;
						indent = cx.indent;
					} else if (ch == ")" && (cx.type == "parens" || cx.type == "atBlock_parens") ||
						ch == "{" && (cx.type == "at" || cx.type == "atBlock")) {
						// Dedent relative to current context.
						indent = Math.max(0, cx.indent - indentUnit);
					}
				}
				return indent;
			},

			electricChars: "}",
			blockCommentStart: "/*",
			blockCommentEnd: "*/",
			blockCommentContinue: " * ",
			lineComment: lineComment,
			fold: "brace"
		};
	});

	function keySet(array) {
		var keys = {};
		for (var i = 0; i < array.length; ++i) {
			keys[array[i].toLowerCase()] = true;
		}
		return keys;
	}

	var documentTypes_ = [
		"domain", "regexp", "url", "url-prefix"
	], documentTypes = keySet(documentTypes_);

	var mediaTypes_ = [
		"all", "aural", "braille", "handheld", "print", "projection", "screen",
		"tty", "tv", "embossed"
	], mediaTypes = keySet(mediaTypes_);

	var mediaFeatures_ = [
		"width", "min-width", "max-width", "height", "min-height", "max-height",
		"device-width", "min-device-width", "max-device-width", "device-height",
		"min-device-height", "max-device-height", "aspect-ratio",
		"min-aspect-ratio", "max-aspect-ratio", "device-aspect-ratio",
		"min-device-aspect-ratio", "max-device-aspect-ratio", "color", "min-color",
		"max-color", "color-index", "min-color-index", "max-color-index",
		"monochrome", "min-monochrome", "max-monochrome", "resolution",
		"min-resolution", "max-resolution", "scan", "grid", "orientation",
		"device-pixel-ratio", "min-device-pixel-ratio", "max-device-pixel-ratio",
		"pointer", "any-pointer", "hover", "any-hover"
	], mediaFeatures = keySet(mediaFeatures_);

	var mediaValueKeywords_ = [
		"landscape", "portrait", "none", "coarse", "fine", "on-demand", "hover",
		"interlace", "progressive"
	], mediaValueKeywords = keySet(mediaValueKeywords_);

	var propertyKeywords_ = [
		"align-content", "align-items", "align-self", "alignment-adjust",
		"alignment-baseline", "anchor-point", "animation", "animation-delay",
		"animation-direction", "animation-duration", "animation-fill-mode",
		"animation-iteration-count", "animation-name", "animation-play-state",
		"animation-timing-function", "appearance", "azimuth", "backface-visibility",
		"background", "background-attachment", "background-blend-mode", "background-clip",
		"background-color", "background-image", "background-origin", "background-position",
		"background-repeat", "background-size", "baseline-shift", "binding",
		"bleed", "bookmark-label", "bookmark-level", "bookmark-state",
		"bookmark-target", "border", "border-bottom", "border-bottom-color",
		"border-bottom-left-radius", "border-bottom-right-radius",
		"border-bottom-style", "border-bottom-width", "border-collapse",
		"border-color", "border-image", "border-image-outset",
		"border-image-repeat", "border-image-slice", "border-image-source",
		"border-image-width", "border-left", "border-left-color",
		"border-left-style", "border-left-width", "border-radius", "border-right",
		"border-right-color", "border-right-style", "border-right-width",
		"border-spacing", "border-style", "border-top", "border-top-color",
		"border-top-left-radius", "border-top-right-radius", "border-top-style",
		"border-top-width", "border-width", "bottom", "box-decoration-break",
		"box-shadow", "box-sizing", "break-after", "break-before", "break-inside",
		"caption-side", "caret-color", "clear", "clip", "color", "color-profile", "column-count",
		"column-fill", "column-gap", "column-rule", "column-rule-color",
		"column-rule-style", "column-rule-width", "column-span", "column-width",
		"columns", "content", "counter-increment", "counter-reset", "crop", "cue",
		"cue-after", "cue-before", "cursor", "direction", "display",
		"dominant-baseline", "drop-initial-after-adjust",
		"drop-initial-after-align", "drop-initial-before-adjust",
		"drop-initial-before-align", "drop-initial-size", "drop-initial-value",
		"elevation", "empty-cells", "fit", "fit-position", "flex", "flex-basis",
		"flex-direction", "flex-flow", "flex-grow", "flex-shrink", "flex-wrap",
		"float", "float-offset", "flow-from", "flow-into", "font", "font-feature-settings",
		"font-family", "font-kerning", "font-language-override", "font-size", "font-size-adjust",
		"font-stretch", "font-style", "font-synthesis", "font-variant",
		"font-variant-alternates", "font-variant-caps", "font-variant-east-asian",
		"font-variant-ligatures", "font-variant-numeric", "font-variant-position",
		"font-weight", "grid", "grid-area", "grid-auto-columns", "grid-auto-flow",
		"grid-auto-rows", "grid-column", "grid-column-end", "grid-column-gap",
		"grid-column-start", "grid-gap", "grid-row", "grid-row-end", "grid-row-gap",
		"grid-row-start", "grid-template", "grid-template-areas", "grid-template-columns",
		"grid-template-rows", "hanging-punctuation", "height", "hyphens",
		"icon", "image-orientation", "image-rendering", "image-resolution",
		"inline-box-align", "justify-content", "justify-items", "justify-self", "left", "letter-spacing",
		"line-break", "line-height", "line-stacking", "line-stacking-ruby",
		"line-stacking-shift", "line-stacking-strategy", "list-style",
		"list-style-image", "list-style-position", "list-style-type", "margin",
		"margin-bottom", "margin-left", "margin-right", "margin-top",
		"marks", "marquee-direction", "marquee-loop",
		"marquee-play-count", "marquee-speed", "marquee-style", "max-height",
		"max-width", "min-height", "min-width", "mix-blend-mode", "move-to", "nav-down", "nav-index",
		"nav-left", "nav-right", "nav-up", "object-fit", "object-position",
		"opacity", "order", "orphans", "outline",
		"outline-color", "outline-offset", "outline-style", "outline-width",
		"overflow", "overflow-style", "overflow-wrap", "overflow-x", "overflow-y",
		"padding", "padding-bottom", "padding-left", "padding-right", "padding-top",
		"page", "page-break-after", "page-break-before", "page-break-inside",
		"page-policy", "pause", "pause-after", "pause-before", "perspective",
		"perspective-origin", "pitch", "pitch-range", "place-content", "place-items", "place-self", "play-during", "position",
		"presentation-level", "punctuation-trim", "quotes", "region-break-after",
		"region-break-before", "region-break-inside", "region-fragment",
		"rendering-intent", "resize", "rest", "rest-after", "rest-before", "richness",
		"right", "rotation", "rotation-point", "ruby-align", "ruby-overhang",
		"ruby-position", "ruby-span", "shape-image-threshold", "shape-inside", "shape-margin",
		"shape-outside", "size", "speak", "speak-as", "speak-header",
		"speak-numeral", "speak-punctuation", "speech-rate", "stress", "string-set",
		"tab-size", "table-layout", "target", "target-name", "target-new",
		"target-position", "text-align", "text-align-last", "text-decoration",
		"text-decoration-color", "text-decoration-line", "text-decoration-skip",
		"text-decoration-style", "text-emphasis", "text-emphasis-color",
		"text-emphasis-position", "text-emphasis-style", "text-height",
		"text-indent", "text-justify", "text-outline", "text-overflow", "text-shadow",
		"text-size-adjust", "text-space-collapse", "text-transform", "text-underline-position",
		"text-wrap", "top", "transform", "transform-origin", "transform-style",
		"transition", "transition-delay", "transition-duration",
		"transition-property", "transition-timing-function", "unicode-bidi",
		"user-select", "vertical-align", "visibility", "voice-balance", "voice-duration",
		"voice-family", "voice-pitch", "voice-range", "voice-rate", "voice-stress",
		"voice-volume", "volume", "white-space", "widows", "width", "will-change", "word-break",
		"word-spacing", "word-wrap", "z-index",
		// SVG-specific
		"clip-path", "clip-rule", "mask", "enable-background", "filter", "flood-color",
		"flood-opacity", "lighting-color", "stop-color", "stop-opacity", "pointer-events",
		"color-interpolation", "color-interpolation-filters",
		"color-rendering", "fill", "fill-opacity", "fill-rule", "image-rendering",
		"marker", "marker-end", "marker-mid", "marker-start", "shape-rendering", "stroke",
		"stroke-dasharray", "stroke-dashoffset", "stroke-linecap", "stroke-linejoin",
		"stroke-miterlimit", "stroke-opacity", "stroke-width", "text-rendering",
		"baseline-shift", "dominant-baseline", "glyph-orientation-horizontal",
		"glyph-orientation-vertical", "text-anchor", "writing-mode"
	], propertyKeywords = keySet(propertyKeywords_);

	var nonStandardPropertyKeywords_ = [
		"scrollbar-arrow-color", "scrollbar-base-color", "scrollbar-dark-shadow-color",
		"scrollbar-face-color", "scrollbar-highlight-color", "scrollbar-shadow-color",
		"scrollbar-3d-light-color", "scrollbar-track-color", "shape-inside",
		"searchfield-cancel-button", "searchfield-decoration", "searchfield-results-button",
		"searchfield-results-decoration", "zoom"
	], nonStandardPropertyKeywords = keySet(nonStandardPropertyKeywords_);

	var fontProperties_ = [
		"font-family", "src", "unicode-range", "font-variant", "font-feature-settings",
		"font-stretch", "font-weight", "font-style"
	], fontProperties = keySet(fontProperties_);

	var counterDescriptors_ = [
		"additive-symbols", "fallback", "negative", "pad", "prefix", "range",
		"speak-as", "suffix", "symbols", "system"
	], counterDescriptors = keySet(counterDescriptors_);

	var colorKeywords_ = [
		"aliceblue", "antiquewhite", "aqua", "aquamarine", "azure", "beige",
		"bisque", "black", "blanchedalmond", "blue", "blueviolet", "brown",
		"burlywood", "cadetblue", "chartreuse", "chocolate", "coral", "cornflowerblue",
		"cornsilk", "crimson", "cyan", "darkblue", "darkcyan", "darkgoldenrod",
		"darkgray", "darkgreen", "darkkhaki", "darkmagenta", "darkolivegreen",
		"darkorange", "darkorchid", "darkred", "darksalmon", "darkseagreen",
		"darkslateblue", "darkslategray", "darkturquoise", "darkviolet",
		"deeppink", "deepskyblue", "dimgray", "dodgerblue", "firebrick",
		"floralwhite", "forestgreen", "fuchsia", "gainsboro", "ghostwhite",
		"gold", "goldenrod", "gray", "grey", "green", "greenyellow", "honeydew",
		"hotpink", "indianred", "indigo", "ivory", "khaki", "lavender",
		"lavenderblush", "lawngreen", "lemonchiffon", "lightblue", "lightcoral",
		"lightcyan", "lightgoldenrodyellow", "lightgray", "lightgreen", "lightpink",
		"lightsalmon", "lightseagreen", "lightskyblue", "lightslategray",
		"lightsteelblue", "lightyellow", "lime", "limegreen", "linen", "magenta",
		"maroon", "mediumaquamarine", "mediumblue", "mediumorchid", "mediumpurple",
		"mediumseagreen", "mediumslateblue", "mediumspringgreen", "mediumturquoise",
		"mediumvioletred", "midnightblue", "mintcream", "mistyrose", "moccasin",
		"navajowhite", "navy", "oldlace", "olive", "olivedrab", "orange", "orangered",
		"orchid", "palegoldenrod", "palegreen", "paleturquoise", "palevioletred",
		"papayawhip", "peachpuff", "peru", "pink", "plum", "powderblue",
		"purple", "rebeccapurple", "red", "rosybrown", "royalblue", "saddlebrown",
		"salmon", "sandybrown", "seagreen", "seashell", "sienna", "silver", "skyblue",
		"slateblue", "slategray", "snow", "springgreen", "steelblue", "tan",
		"teal", "thistle", "tomato", "turquoise", "violet", "wheat", "white",
		"whitesmoke", "yellow", "yellowgreen"
	], colorKeywords = keySet(colorKeywords_);

	var valueKeywords_ = [
		"above", "absolute", "activeborder", "additive", "activecaption", "afar",
		"after-white-space", "ahead", "alias", "all", "all-scroll", "alphabetic", "alternate",
		"always", "amharic", "amharic-abegede", "antialiased", "appworkspace",
		"arabic-indic", "armenian", "asterisks", "attr", "auto", "auto-flow", "avoid", "avoid-column", "avoid-page",
		"avoid-region", "background", "backwards", "baseline", "below", "bidi-override", "binary",
		"bengali", "blink", "block", "block-axis", "bold", "bolder", "border", "border-box",
		"both", "bottom", "break", "break-all", "break-word", "bullets", "button", "button-bevel",
		"buttonface", "buttonhighlight", "buttonshadow", "buttontext", "calc", "cambodian",
		"capitalize", "caps-lock-indicator", "caption", "captiontext", "caret",
		"cell", "center", "checkbox", "circle", "cjk-decimal", "cjk-earthly-branch",
		"cjk-heavenly-stem", "cjk-ideographic", "clear", "clip", "close-quote",
		"col-resize", "collapse", "color", "color-burn", "color-dodge", "column", "column-reverse",
		"compact", "condensed", "contain", "content", "contents",
		"content-box", "context-menu", "continuous", "copy", "counter", "counters", "cover", "crop",
		"cross", "crosshair", "currentcolor", "cursive", "cyclic", "darken", "dashed", "decimal",
		"decimal-leading-zero", "default", "default-button", "dense", "destination-atop",
		"destination-in", "destination-out", "destination-over", "devanagari", "difference",
		"disc", "discard", "disclosure-closed", "disclosure-open", "document",
		"dot-dash", "dot-dot-dash",
		"dotted", "double", "down", "e-resize", "ease", "ease-in", "ease-in-out", "ease-out",
		"element", "ellipse", "ellipsis", "embed", "end", "ethiopic", "ethiopic-abegede",
		"ethiopic-abegede-am-et", "ethiopic-abegede-gez", "ethiopic-abegede-ti-er",
		"ethiopic-abegede-ti-et", "ethiopic-halehame-aa-er",
		"ethiopic-halehame-aa-et", "ethiopic-halehame-am-et",
		"ethiopic-halehame-gez", "ethiopic-halehame-om-et",
		"ethiopic-halehame-sid-et", "ethiopic-halehame-so-et",
		"ethiopic-halehame-ti-er", "ethiopic-halehame-ti-et", "ethiopic-halehame-tig",
		"ethiopic-numeric", "ew-resize", "exclusion", "expanded", "extends", "extra-condensed",
		"extra-expanded", "fantasy", "fast", "fill", "fixed", "flat", "flex", "flex-end", "flex-start", "footnotes",
		"forwards", "from", "geometricPrecision", "georgian", "graytext", "grid", "groove",
		"gujarati", "gurmukhi", "hand", "hangul", "hangul-consonant", "hard-light", "hebrew",
		"help", "hidden", "hide", "higher", "highlight", "highlighttext",
		"hiragana", "hiragana-iroha", "horizontal", "hsl", "hsla", "hue", "icon", "ignore",
		"inactiveborder", "inactivecaption", "inactivecaptiontext", "infinite",
		"infobackground", "infotext", "inherit", "initial", "inline", "inline-axis",
		"inline-block", "inline-flex", "inline-grid", "inline-table", "inset", "inside", "intrinsic", "invert",
		"italic", "japanese-formal", "japanese-informal", "justify", "kannada",
		"katakana", "katakana-iroha", "keep-all", "khmer",
		"korean-hangul-formal", "korean-hanja-formal", "korean-hanja-informal",
		"landscape", "lao", "large", "larger", "left", "level", "lighter", "lighten",
		"line-through", "linear", "linear-gradient", "lines", "list-item", "listbox", "listitem",
		"local", "logical", "loud", "lower", "lower-alpha", "lower-armenian",
		"lower-greek", "lower-hexadecimal", "lower-latin", "lower-norwegian",
		"lower-roman", "lowercase", "ltr", "luminosity", "malayalam", "match", "matrix", "matrix3d",
		"media-controls-background", "media-current-time-display",
		"media-fullscreen-button", "media-mute-button", "media-play-button",
		"media-return-to-realtime-button", "media-rewind-button",
		"media-seek-back-button", "media-seek-forward-button", "media-slider",
		"media-sliderthumb", "media-time-remaining-display", "media-volume-slider",
		"media-volume-slider-container", "media-volume-sliderthumb", "medium",
		"menu", "menulist", "menulist-button", "menulist-text",
		"menulist-textfield", "menutext", "message-box", "middle", "min-intrinsic",
		"mix", "mongolian", "monospace", "move", "multiple", "multiply", "myanmar", "n-resize",
		"narrower", "ne-resize", "nesw-resize", "no-close-quote", "no-drop",
		"no-open-quote", "no-repeat", "none", "normal", "not-allowed", "nowrap",
		"ns-resize", "numbers", "numeric", "nw-resize", "nwse-resize", "oblique", "octal", "opacity", "open-quote",
		"optimizeLegibility", "optimizeSpeed", "oriya", "oromo", "outset",
		"outside", "outside-shape", "overlay", "overline", "padding", "padding-box",
		"painted", "page", "paused", "persian", "perspective", "plus-darker", "plus-lighter",
		"pointer", "polygon", "portrait", "pre", "pre-line", "pre-wrap", "preserve-3d",
		"progress", "push-button", "radial-gradient", "radio", "read-only",
		"read-write", "read-write-plaintext-only", "rectangle", "region",
		"relative", "repeat", "repeating-linear-gradient",
		"repeating-radial-gradient", "repeat-x", "repeat-y", "reset", "reverse",
		"rgb", "rgba", "ridge", "right", "rotate", "rotate3d", "rotateX", "rotateY",
		"rotateZ", "round", "row", "row-resize", "row-reverse", "rtl", "run-in", "running",
		"s-resize", "sans-serif", "saturation", "scale", "scale3d", "scaleX", "scaleY", "scaleZ", "screen",
		"scroll", "scrollbar", "scroll-position", "se-resize", "searchfield",
		"searchfield-cancel-button", "searchfield-decoration",
		"searchfield-results-button", "searchfield-results-decoration", "self-start", "self-end",
		"semi-condensed", "semi-expanded", "separate", "serif", "show", "sidama",
		"simp-chinese-formal", "simp-chinese-informal", "single",
		"skew", "skewX", "skewY", "skip-white-space", "slide", "slider-horizontal",
		"slider-vertical", "sliderthumb-horizontal", "sliderthumb-vertical", "slow",
		"small", "small-caps", "small-caption", "smaller", "soft-light", "solid", "somali",
		"source-atop", "source-in", "source-out", "source-over", "space", "space-around", "space-between", "space-evenly", "spell-out", "square",
		"square-button", "start", "static", "status-bar", "stretch", "stroke", "sub",
		"subpixel-antialiased", "super", "sw-resize", "symbolic", "symbols", "system-ui", "table",
		"table-caption", "table-cell", "table-column", "table-column-group",
		"table-footer-group", "table-header-group", "table-row", "table-row-group",
		"tamil",
		"telugu", "text", "text-bottom", "text-top", "textarea", "textfield", "thai",
		"thick", "thin", "threeddarkshadow", "threedface", "threedhighlight",
		"threedlightshadow", "threedshadow", "tibetan", "tigre", "tigrinya-er",
		"tigrinya-er-abegede", "tigrinya-et", "tigrinya-et-abegede", "to", "top",
		"trad-chinese-formal", "trad-chinese-informal", "transform",
		"translate", "translate3d", "translateX", "translateY", "translateZ",
		"transparent", "ultra-condensed", "ultra-expanded", "underline", "unset", "up",
		"upper-alpha", "upper-armenian", "upper-greek", "upper-hexadecimal",
		"upper-latin", "upper-norwegian", "upper-roman", "uppercase", "urdu", "url",
		"var", "vertical", "vertical-text", "visible", "visibleFill", "visiblePainted",
		"visibleStroke", "visual", "w-resize", "wait", "wave", "wider",
		"window", "windowframe", "windowtext", "words", "wrap", "wrap-reverse", "x-large", "x-small", "xor",
		"xx-large", "xx-small"
	], valueKeywords = keySet(valueKeywords_);

	var allWords = documentTypes_.concat(mediaTypes_).concat(mediaFeatures_).concat(mediaValueKeywords_)
		.concat(propertyKeywords_).concat(nonStandardPropertyKeywords_).concat(colorKeywords_)
		.concat(valueKeywords_);
	CodeMirror.registerHelper("hintWords", "css", allWords);

	function tokenCComment(stream, state) {
		var maybeEnd = false, ch;
		while ((ch = stream.next()) != null) {
			if (maybeEnd && ch == "/") {
				state.tokenize = null;
				break;
			}
			maybeEnd = (ch == "*");
		}
		return ["comment", "comment"];
	}

	CodeMirror.defineMIME("text/css", {
		documentTypes: documentTypes,
		mediaTypes: mediaTypes,
		mediaFeatures: mediaFeatures,
		mediaValueKeywords: mediaValueKeywords,
		propertyKeywords: propertyKeywords,
		nonStandardPropertyKeywords: nonStandardPropertyKeywords,
		fontProperties: fontProperties,
		counterDescriptors: counterDescriptors,
		colorKeywords: colorKeywords,
		valueKeywords: valueKeywords,
		tokenHooks: {
			"/": function(stream, state) {
				if (!stream.eat("*")) return false;
				state.tokenize = tokenCComment;
				return tokenCComment(stream, state);
			}
		},
		name: "css"
	});

	CodeMirror.defineMIME("text/x-scss", {
		mediaTypes: mediaTypes,
		mediaFeatures: mediaFeatures,
		mediaValueKeywords: mediaValueKeywords,
		propertyKeywords: propertyKeywords,
		nonStandardPropertyKeywords: nonStandardPropertyKeywords,
		colorKeywords: colorKeywords,
		valueKeywords: valueKeywords,
		fontProperties: fontProperties,
		allowNested: true,
		lineComment: "//",
		tokenHooks: {
			"/": function(stream, state) {
				if (stream.eat("/")) {
					stream.skipToEnd();
					return ["comment", "comment"];
				} else if (stream.eat("*")) {
					state.tokenize = tokenCComment;
					return tokenCComment(stream, state);
				} else {
					return ["operator", "operator"];
				}
			},
			":": function(stream) {
				if (stream.match(/\s*\{/, false))
					return [null, null]
				return false;
			},
			"$": function(stream) {
				stream.match(/^[\w-]+/);
				if (stream.match(/^\s*:/, false))
					return ["variable-2", "variable-definition"];
				return ["variable-2", "variable"];
			},
			"#": function(stream) {
				if (!stream.eat("{")) return false;
				return [null, "interpolation"];
			}
		},
		name: "css",
		helperType: "scss"
	});

	CodeMirror.defineMIME("text/x-less", {
		mediaTypes: mediaTypes,
		mediaFeatures: mediaFeatures,
		mediaValueKeywords: mediaValueKeywords,
		propertyKeywords: propertyKeywords,
		nonStandardPropertyKeywords: nonStandardPropertyKeywords,
		colorKeywords: colorKeywords,
		valueKeywords: valueKeywords,
		fontProperties: fontProperties,
		allowNested: true,
		lineComment: "//",
		tokenHooks: {
			"/": function(stream, state) {
				if (stream.eat("/")) {
					stream.skipToEnd();
					return ["comment", "comment"];
				} else if (stream.eat("*")) {
					state.tokenize = tokenCComment;
					return tokenCComment(stream, state);
				} else {
					return ["operator", "operator"];
				}
			},
			"@": function(stream) {
				if (stream.eat("{")) return [null, "interpolation"];
				if (stream.match(/^(charset|document|font-face|import|(-(moz|ms|o|webkit)-)?keyframes|media|namespace|page|supports)\b/i, false)) return false;
				stream.eatWhile(/[\w\\\-]/);
				if (stream.match(/^\s*:/, false))
					return ["variable-2", "variable-definition"];
				return ["variable-2", "variable"];
			},
			"&": function() {
				return ["atom", "atom"];
			}
		},
		name: "css",
		helperType: "less"
	});

	CodeMirror.defineMIME("text/x-gss", {
		documentTypes: documentTypes,
		mediaTypes: mediaTypes,
		mediaFeatures: mediaFeatures,
		propertyKeywords: propertyKeywords,
		nonStandardPropertyKeywords: nonStandardPropertyKeywords,
		fontProperties: fontProperties,
		counterDescriptors: counterDescriptors,
		colorKeywords: colorKeywords,
		valueKeywords: valueKeywords,
		supportsAtComponent: true,
		tokenHooks: {
			"/": function(stream, state) {
				if (!stream.eat("*")) return false;
				state.tokenize = tokenCComment;
				return tokenCComment(stream, state);
			}
		},
		name: "css",
		helperType: "gss"
	});

});
