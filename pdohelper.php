<?php

namespace SQL;

class PDOHelper {

	/**
	 * Format the SQL Query
	 *
	 * @param $sql string
	 *
	 * @return mixed
	 */
	public static function formatSQL($sql = ''){
		// Reserved SQL Keywords Data
		$reserveSqlKey = "select|insert|update|delete|truncate|drop|create|add|except|percent|all|exec|plan|alter|execute|precision|and|exists|primary|any|exit|print|as|fetch|proc|asc|file|procedure|authorization|fillfactor|public|backup|for|raiserror|begin|foreign|read|between|freetext|readtext|break|freetexttable|reconfigure|browse|from|references|bulk|full|replication|by|function|restore|cascade|goto|restrict|case|grant|return|check|group|revoke|checkpoint|having|right|close|holdlock|rollback|clustered|identity|rowcount|coalesce|identity_insert|rowguidcol|collate|identitycol|rule|column|if|save|commit|in|schema|compute|index|select|constraint|inner|session_user|contains|insert|set|containstable|intersect|setuser|continue|into|shutdown|convert|is|some|create|join|statistics|cross|key|system_user|current|kill|table|current_date|left|textsize|current_time|like|then|current_timestamp|lineno|to|current_user|load|top|cursor|national|tran|database|nocheck|transaction|dbcc|nonclustered|trigger|deallocate|not|truncate|declare|null|tsequal|default|nullif|union|delete|of|unique|deny|off|update|desc|offsets|updatetext|disk|on|use|distinct|open|user|distributed|opendatasource|values|double|openquery|varying|drop|openrowset|view|dummy|openxml|waitfor|dump|option|when|else|or|where|end|order|while|errlvl|outer|with|escape|over|writetext|absolute|overlaps|action|pad|ada|partial|external|pascal|extract|position|allocate|false|prepare|first|preserve|float|are|prior|privileges|fortran|assertion|found|at|real|avg|get|global|relative|go|bit|bit_length|both|rows|hour|cascaded|scroll|immediate|second|cast|section|catalog|include|char|session|char_length|indicator|character|initially|character_length|size|input|smallint|insensitive|space|int|sql|collation|integer|sqlca|sqlcode|interval|sqlerror|connect|sqlstate|connection|sqlwarning|isolation|substring|constraints|sum|language|corresponding|last|temporary|count|leading|time|level|timestamp|timezone_hour|local|timezone_minute|lower|match|trailing|max|min|translate|date|minute|translation|day|module|trim|month|true|dec|names|decimal|natural|unknown|nchar|deferrable|next|upper|deferred|no|usage|none|using|describe|value|descriptor|diagnostics|numeric|varchar|disconnect|octet_length|domain|only|whenever|work|end-exec|write|year|output|zone|exception|free|admin|general|after|reads|aggregate|alias|recursive|grouping|ref|host|referencing|array|ignore|result|returns|before|role|binary|initialize|rollup|routine|blob|inout|row|boolean|savepoint|breadth|call|scope|search|iterate|large|sequence|class|lateral|sets|clob|less|completion|limit|specific|specifictype|localtime|constructor|localtimestamp|sqlexception|locator|cube|map|current_path|start|current_role|state|cycle|modifies|statement|data|modify|static|structure|terminate|than|nclob|depth|new|deref|destroy|treat|destructor|object|deterministic|old|under|dictionary|operation|unnest|ordinality|out|dynamic|each|parameter|variable|equals|parameters|every|without|path|postfix|prefix|preorder";
		// convert in array
		$list = explode('|', $reserveSqlKey);
		foreach($list as &$verb){
			$verb = '/\b'.preg_quote($verb, '/').'\b/';
		}
		$regex_sign = array('/\b', '\b/');
		// replace matching words
		return str_replace($regex_sign, '', preg_replace($list, array_map(array(PDOHelper::class, 'highlight_sql'), $list), strtolower($sql)));
	}

	/**
	 * Coloring for MySQL reserved keywords
	 *
	 * @param $param
	 *
	 * @return string
	 */
	public static function highlight_sql($param){
		return "<span style='color:#990099; font-weight:bold; text-transform: uppercase;'>$param</span>";
	}

	/**
	 * Get HTML Table with Data
	 * Send complete array data and get an HTML table with mysql data
	 *
	 * @param array $aColList Result Array data
	 *
	 * @return string HTML Table with data
	 */
	public static function displayHtmlTable($aColList = array()){
		$r = '';
		if(count($aColList) > 0){
			$r .= '<table border="1">';
			$r .= '<thead>';
			$r .= '<tr>';
			foreach($aColList[0] as $k => $v){
				$r .= '<td style="font-weight: bold">'.$k.'</td>';
			}
			$r .= '</tr>';
			$r .= '</thead>';
			$r .= '<tbody>';
			foreach($aColList as $record){
				$r .= '<tr>';
				foreach($record as $data){
					$r .= '<td>'.$data.'</td>';
				}
				$r .= '</tr>';
			}
			$r .= '</tbody>';
			$r .= '<table>';
		}else{
			$r .= '<div class="no-results">No results found for query.</div>';
		}
		return $r;
	}

	/**
	 * Show Error Array Data and stop code execution
	 *
	 * @param array $data
	 */
	public static function errorBox($data = array()){
		$style = "style='color:#333846; border:1px solid #777; padding:2px; background-color: #FFC0CB;'";
		die("<div $style >ERROR:".json_encode($data)."</div>");
	}

    /**
     * Flatten an array
     *
     * @param $array array Array to flatten
     * @return array
     */
    public static function flatten($array){
        $objTmp = (object) array('aFlat' => array());
        array_walk_recursive($array, create_function('&$v, $k, &$t', '$t->aFlat[] = $v;'), $objTmp);
        return $objTmp->aFlat;
    }

}