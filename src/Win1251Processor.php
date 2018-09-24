<?php

namespace Wcactus\TypographRu;

/**
 * HTML-entities-based screen typography processor.
 */
class Win1251Processor {

	// �����, ����� ������� �� �������� �������, � ����� ������� ������ ��� ������.
	protected $typograph_punctuations = ['.', ',', ':', ';', '?', '!'];

	// ����� �������, ������� ������������ ������������������.
	protected $typograph_quotations = [
		"&laquo;",	// ����������� �������-������ (���)
		"&raquo;",	// ����������� �������-������ (���)
		"&bdquo;",	// ����������� ������ �������-����� (���)
		"&ldquo;",	// ����������� ������� �������-����� (���), ��� �� ����������� ������� ������� (����)
		"&rdquo;",	// ����������� ������� ������� (����)
		"&lsquo;",	// ����������� ��������� ������� (����)
		"&rsquo;",	// ����������� ��������� ������� (����)
		"&sbquo;",	// ��������� ������ ������� (����?)
		"&apos;" ,	// ��������
		"&quot;",	// �������
	];

	// �����, ������� ������������� � html entities.
	//todo: �� ��������� ����� ���� &#x0020; ��� &#032; � �.�.
	protected $typograph_entities = [
		' '			=> ["&#x20;", "&#32;"],			// ������� ������ (������������� ����� ���������)
		'%'			=> ["&#x25;", "&#37;"],			// ������� (������������� ����� ���������)
		'&amp;'		=> ["&#x26;", "&#38;"],			// ��������� (������������� ����� ���������)
		'&lt;'		=> ["<", "&#x3C;", "&#60;"],		// ������� �������
		'&gt;'		=> [">", "&#x3E;", "&#62;"],		// ... (��� ����-������� ����� html)

		'&laquo;'	=> ["\xAB", "&#xAB;", "&#171;"],	// ������� (��. ����)
		'&raquo;'	=> ["\xBB", "&#xBB;", "&#187;"],
		'&bdquo;'	=> ["\x84", "&#x84;", "&#132;"],
		'&ldquo;'	=> ["\x93", "&#x93;", "&#147;"],
		'&rdquo;'	=> ["\x94", "&#x94;", "&#148;"],
		'&lsquo;'	=> ["\x91", "&#x91;", "&#145;"],
		'&rsquo;'	=> ["\x92", "&#x92;", "&#146;"],
		'&sbquo;'	=> ["\x82", "&#x82;", "&#130;"],
		'&apos;'	=> ["\x27", "&#x27;", "&#39;"],
		'&quot;'	=> ["\x22", "&#x22;", "&#34;"],

		'&#167;'	=> ["\xA7", "&#xA7;", "&#167;"],	// ��������
		'&#176;'	=> ["\xB0", "&#xB0;", "&#176;"],	// ������ ������
		'&bull;'	=> ["\x95", "&#x95;", "&#149;"],	// ����������� ������
		'&ndash;'	=> ["\x96", "&#x96;", "&#150;"],	// �����
		'&mdash;'	=> ["\x97", "&#x97;", "&#151;"],	// ����
		'&#8470;'	=> ["\xB9", "&#xB9;", "&#185;"],	// �����
		'&plusmn;'	=> ["\xB1", "&#xB1;", "&#177;"],	// ����-�����
		'&hellip;'	=> ["\x85", "&#x85;", "&#133;"],	// ����������
		'&nbsp;'	=> ["\xA0", "&#xA0;", "&#160;"],	// ����������� ������

		'&reg;'		=> ["\xAE", "&#xAE;", "&#174;", "(r)"],		// registered
		'&copy;'	=> ["\xA9", "&#xA9;", "&#169;", "(c)"],		// copyright
		'&trade;'	=> ["\x99", "&#x99;", "&#153;", "(t)", "(tm)"],	// trademark
	];

	// ��������, ������� ���� ���������� � ����������� ������,
	// ������� � ����������, ������� ���� ���������� � �������������� ������.
	protected $typograph_prepositions = [
		'�',
		'��',
		'��',
		'��',
		'��',
		'��',
		'�',
		'��',
		'��',
		'��',
		'��',
		'��',
		'��',
		'��',
		'��',
		'��',
		'���',
		'��',
		'��',
		'��',
		'��',
		'��',
		'���',
		'��-��',
		'��',
		'���',
		'���',
		'���',
		'���',
		'���',
		'���',
		'�',
		'��',
		'��',
		'��',
		'��',
		'��',
		'��',
		'��',
		'�',
		'���',
		'��',
		'���',
		'���',
		'�',
		'���',
		'�',
		'�',
		'��',
		'�',
		'��',
		'�',
		'��',
		'�',
		'��',
		'���',
	];

	protected $typograph_particles = [
		'&mdash;', '%',
		'��',
		'��',
		'��',
		'�',
		'�',
		'��',
		'�.�',
		'�',
		'���',
		'��',
		'��',
		'��',
		'�',
		'�',
		'��',
		'��',
	];

	// �������� � �������� ���� � �������, ������� ���� ��������� � <nobr>.
	protected $typograph_dashword_fulltext = [
		'��-��-��',
		'��-��',
		'��-���',
		'��-����',
		'��-���',
		'��-��',
		'��-��',
		'� �. �.',
		'� �. �.',
		'� ��.',
		'�. �.',
		'�. �.',
		'�. �.',
	];
	protected $typograph_dashword_prefixes = [
		'���-',
		'���-',
		'���-',
		'���-',
		'���-',
		'�-',
		'��-',
		'���-',
		'��-',
		'�����-',
	];
	protected $typograph_dashword_suffixes = [
		'-��',
		'-��',
		'-���',
		'-����',
		'-����',
		'-������',
		'-�������',
		'-��',
		'-�',
		'-��',
		'-��',
		'-��',
	];
	protected $typograph_dashword_augments = [ // ����������: ���+�����(��,��,��,��) * �����(���,���,���,���,���,���) * ����.�����(1..9); � �� ������� ��������.
		"th",
		"nd",
		"rd",
		"st",
		"d",

		"��",		// �� ���			12456789
		"��",		// �� ���			3
		"��",		// �� ���+���+���+���	12456789
		"��",		// �� ���+���+���+���	3
		"��",		// �� ���			12456789
		"��",		// �� ���			3
                    
		"��",		// �� ���			1459
		"��",		// �� ���			2678
		"��",		// �� ���			3
		"���",		// �� ���+���		12456789
		"���",		// �� ���+���		3
		"���",		// �� ���			12456789
		"���",		// �� ���			3
		"��",		// �� ���			12456789
		"��",		// �� ���			3
		"��",		// �� ���			12456789
		"��",		// �� ���			3
                    
		"��",		// �� ���+���		12456789
		"��",		// �� ���+���		3
		"���",		// �� ���			12456789
		"���",		// �� ���			3
		"���",		// �� ���			12456789
		"���",		// �� ���			3
		"��",		// �� ���			12456789
		"��",		// �� ���			3
		"��",		// �� ���			12456789
		"��",		// �� ���			3
                    
		"��",		// �� ���			12456789
		"��",		// �� ���			3
		"��",		// �� ���+���+���	12456789
		"��",		// �� ���+���+���	3
		"��",		// �� ���			12456789
		"��",		// �� ���			3
		"���",		// �� ���			12456789
		"���",		// �� ���			3

		"��",		// =���,���
		"��",		// =���,���
		"��",		// =���,���
		"�",		// =��,��,��,��,��
		"�",		// =��,��
		"�",		// =��,��,��
		"�",		// =��,��
		"�",		// =��,��,��,��
		"�",		// =��,��
		"�",		// =�� (���, ���, �� �� �����������, ��� ����, �� ��� ���� ��� �� ���������)
		"�",		// =(���,���, �� �� �����������, ��� ����, �� ��� ���� ��� �� ���������)
		"�",		// =(���,���, �� �� �����������, ��� ����, �� ��� ���� ��� �� ���������)
	];

	protected $cut_tags_dictionary;
	
	protected $use_mdash;
	
	function __construct($use_mdash = false) {
		$this->use_mdash = $use_mdash;
	}

	/**
	 * ������� �� ������������� �������������� ������.
	 *
	 * @param string $text
	 * @param string $language
	 * @param boolean $force_quotes
	 * @return string
	 */
	public function process($text, $language = null, $force_quotes = true) {
		// ������� �� ������ ��� ����, ������� �� ������������ ������������� (����� �� �������).
		$dictionary = null;// ����� �� ������� ��� ���������� �� ����������.
		$text = $this->cut_tags($text, $dictionary);

		// ����������� ������������ �������, �� ��������, � ����� ��������������� ���������� � html entities.
		$text = $this->decode_entities($text);

		// �������� ������� ���������� ������.
		$text = $this->punctuate($text);

		// ������������ ������� � ������.
		$text = $this->fix_quotes($text, $force_quotes, $language);

		// ����������� ����� (� ��������, � ����� �������� � �������).
		$text = $this->nobr_words($text);

		// ������������ �������.
		$text = $this->optimize_spaces($text);

		// ��������������� � ������ ��� ����� �������� ����.
		$text = $this->put_tags($text, $dictionary);

		// ������� ��������������� ���� (��� ����� �������������� ��������).
		$text = $this->optimize_tags($text);

		// ���������� ������������ �����.
		return $text;
	}

	/**
	 * ������� ��������� (cut) ����� ����������� ������������ ��� ������������
	 * �������������� (put) �� ���� �����������. ��� ��������� ������������
	 * ������� ����� (������-���, ������� �������� �������� ����-�����������,
	 * � ���������� - ���������� ����). ���� ����������� ������� �����,
	 * ��� �� ������� � ����� ������ ����������� �� ������ ��, � ��� ����
	 * � ��������� ������ ���� ���������� ����� �� ���������.
	 *
	 * @param string $text
	 * @param array $dictionary
	 * @return string
	 */
	protected function cut_tags($text, &$dictionary) {
		// ������� �� ������ ��� ����, ������� �� ������������ ������������� (����� �� �������).
		// ��� �������� ���� ����������� �������� ��������� � ��������, � ��� ����� ��� �������
		// �������� ���� ��� ������� ������� ����� ���� ���������� � ��� ��������.
		$this->cut_tags_dictionary = array();
		$text = preg_replace_callback("/< ( (\".*?(?<!\\\\)\") | (\'.*?(?<!\\\\)\') | [^\'\"]*? )+ >/sx", [__CLASS__, 'cut_tags_callback'], $text);

		// ���������� ����� � ��������� ������ (���������� ����-��������� �����),
		// � ����� ������� ���������� ����� ��� ������������ ��� ��������������.
		$dictionary = $this->cut_tags_dictionary;
		return $text;
	}
	protected function cut_tags_callback($matches) {
		$index  = count($this->cut_tags_dictionary);
		$marker = "\x01\x02\x03{$index}\x03\x02\x01";
		$this->cut_tags_dictionary[$marker] = $matches[0];
		return $marker;
	}
	
	/**
	 * ������� �������������� (put) ����� ���������� �����.
	 *
	 * @param string $text
	 * @param array $dictionary
	 * @return string
	 */
	protected function put_tags($text, $dictionary) {
		$text = str_replace(array_keys($dictionary), array_values($dictionary), $text);
		$text = str_replace('&apos;', '\'', $text);
		return $text;
	}

	/**
	 * ������� �� ������ ��������� ����-��������, ���������� �������������
	 * �������������� ������, �� �������� � �������������� ���������
	 * �� html entities.
	 *
	 * @param string $test
	 * @return string
	 */
	protected function decode_entities($text) {
		$regex_patterns = $regex_replaces = array();
		foreach ($this->typograph_entities as $entity => $symbols) {
			$regex_patterns[] = "/" . implode("|", array_map('preg_quote', $symbols)) . "/si";
			$regex_replaces[] = preg_quote($entity);
		}
		$regex_patterns[] = "/&(?![a-z0-9#]+;)/si"; // ��������� ��� html-entity (���������������)
		$regex_replaces[] = "&amp;";
		$text = preg_replace($regex_patterns, $regex_replaces, $text);
		return $text;
	}

	/**
	 * ������� �� ��������� ������ ���������� � ������ (������� �������,
	 * ����������� �������� ����� ���), � ����� �� ��������� �������������������
	 * ������������������� �� ������������ ������� (���� � �.�.).
	 *
	 * @param string $test
	 * @return string
	 */
	protected function punctuate($text) {
		$dash_replacement = $this->use_mdash ? "&mdash;" : "&ndash;";
		
		// ������� ������� ����� ������� ����������.
		$regex = implode("|", array_map('preg_quote', $this->typograph_punctuations));
		$text = preg_replace("/[ \t]+(?={$regex})/si", "", $text);

		// ������� ������� ����� ����������� � �� ����������� ������ (����� �����).
		$text = preg_replace("/([\\(\\[\\<])[ \t]+/si", "\\1", $text);
		$text = preg_replace("/[ \t]+([\\)\\]\\>])/si", "\\1", $text);

		// ������� ���� ������ �������� �� ���� ���������� �� �������� ������,
		// � ��������� -- ������ ���� �� ������ ���������.
		$text = preg_replace("/[ \t]*(?<!-)--(?!-)[ \t]*/si", "&nbsp;{$dash_replacement} ", $text);
		$text = preg_replace("/[ \t]+(-|&ndash;)[ \t]+/si"  , "&nbsp;{$dash_replacement} ", $text);

		// ��� ������ ������ ����� �������� ������������ �����������.
		$text = preg_replace("/\\.\\.\\./si", "&hellip;", $text);

		// ���������� ��� ����������.
		return $text;
	}

	/**
	 * ������� ����������� ������ ������ (�� ����� ����, �������� �� �����
	 * �������). ������������� �� ����������� ������� ������� ��������,
	 * ��� ��� ����-������ ������� �������� (���� ���������� �� ����)
	 * ��� �� ����������� ��� ����� �������.
	 *
	 * @param string $test
	 * @return string
	 */
	protected function detect_language($text) {
		$counts = count_chars($text, 0/* �����: ������, ��� �������, ���� � �������� 0 */);
		$counts_high = array_sum(array_slice($counts, 0x80));
		$percent_high = $text == '' ? 0.0 : 100.0 * $counts_high / strlen($text);
		// 1.0 - ����������� ������� ������� ��������
		return ($percent_high > 1.0) ? TypographRu::LANGUAGE_RU : TypographRu::LANGUAGE_RU;
	}

	/**
	 * ������� ��������� �������.
	 *
	 * @param string $test
	 * @param boolean @forced
	 * @param string $language
	 * @return string
	 */
	protected function fix_quotes($text, $forced = false, $language = null) {
		// ������, ������� �� �������� ������� ��� �������������� ���������, ���� ���� ��� ��������������.
		$marker = "\"";// �� ���� ���������, �� ����� ������ ����� ��������, � ����� �������� ���������.

		// ��������� ����, ���� �� ��� �� ������ ����.
		$language = strtolower($language === null ? $this->detect_language($text) : $language);

		// � ������ ��������������� ������������������, �������� ��� ������� �� ����-�������.
		if ($forced) {
			$regex = implode("|", array_map('preg_quote', $this->typograph_quotations));
			$text = preg_replace("/(?!&apos;s\\b)({$regex})/si", $marker, $text);
		}

		// �������������� � ��������� ���� �������������� ����������� ��������� ��������
		// (� ������ ������ ��� ������� ��������� �������) � ������������ �������.
		$result = ''; $quote_level = 0; 
		$text_len = strlen($text); 
		$marker_len = strlen($marker); $marker_pre = -$marker_len;
		while (($marker_pos = strpos($text, $marker, $marker_pre+$marker_len)) !== false)
		{
			// ���������� ��� ������� (����������� �� ��� �������) �� ��������� � ������.
			// ����� ������������ ��� ������ � ����� ������ �������� ���������.
			$lspace = ($marker_pos == 0                      ) || preg_match("/([ \t]|&nbsp;)\$/", substr($text, 0, $marker_pos));
			$rspace = ($marker_pos == ($text_len-$marker_len)) || preg_match("/^([ \t]|&nbsp;)/" , substr($text, $marker_pos+$marker_len));
			$count = substr_count($text, $marker, $marker_pos+$marker_len);//WARNING: need PHP>=5.1.0

			// ������� �������� �����������, ���� ������ ���� ��� ��������� � ���� ��:
			$opening = !(($quote_level > 0) && (
					// ... ���� ����� �������� ��� ������������ ������
					(!$lspace           ) ||
					// ... ���� ������� �������� ���������
					( $lspace && $rspace) ||
					// ... ���� ������� �������� ������ ����� ����������� ��������
					($count < $quote_level) // ������ ������, ��� ��� �� ������� ��� ����� ������� �������
				));

			// ���������� ������� ����������� ������� (�������� ������), ������ � ������ ����������� �������
			// �� ������� ����������� �� ��������, � � ������ ����������� -- ��������� ����� ���������.
			// �� ������ ������, ���� ������� � ����� ������ �������� � ��������� �������������.
			$quote = ($opening?++$quote_level:$quote_level--) % 2 ?
				// ������� ������� ������ (� ���� �������� �������):
				($language == TypographRu::LANGUAGE_RU ? ($opening ? "&laquo;" : "&raquo;") : ($opening ? "&ldquo;" : "&rdquo;")):
				// ������� ������� ������ (� ���� ������ �������):
				($language == TypographRu::LANGUAGE_RU ? ($opening ? "&bdquo;" : "&ldquo;") : ($opening ? "&lsquo;" : "&rsquo;"));

			// �������� � ��������� ����� ������ �� ���������� �� ������� ������� � ��������� ���� �������.
			$result .= substr($text, $marker_pre+$marker_len, $marker_pos-$marker_pre-$marker_len) . $quote;
			$marker_pre = $marker_pos;
		}

		// �������� � ��������� ����� ������ ����� ��������� ������� (���� ���� �����, ���� �� ����� ����).
		$result .= substr($text, $marker_pre+$marker_len);
		return $result;
	}

	/**
	 * ������� �� ����������� ����������� �������� ����������� � �����������.
	 *
	 * @param string $test
	 * @return string
	 */
	protected function nobr_words($text) {
		$letter = "\\w\\d�-���-ߨ-";
		$litera = "a-zA-Z�-���-ߨ";
		$number = "[\\d]";
		$dash   = "[-]";

		// ����� � �������� (�� �������� � �� ��������).
		$regex1 = implode("|", array_map('preg_quote', $this->typograph_dashword_prefixes));
		$regex2 = implode("|", array_map('preg_quote', $this->typograph_dashword_suffixes));
		$regex3 = implode("|", array_map('preg_quote', $this->typograph_dashword_fulltext));
		$regex4 = implode("|", array_map('preg_quote', $this->typograph_dashword_augments));
		$text = preg_replace("/
			((?<![{$letter}])       ({$regex1})[{$letter}]+({$regex2})  (?![{$letter}])) |
			((?<![{$letter}])       ({$regex1})[{$letter}]+             (?![{$letter}])) |
			((?<![{$letter}])                  [{$letter}]+({$regex2})  (?![{$letter}])) |
			((?<![{$letter}])                   {$regex3}               (?![{$letter}])) |
			((?<![{$letter}]) {$number}{$dash}*({$regex4})               (?!{$letter}))
			/six", "<nobr>\\0</nobr>", $text);

		// ��������.
		$regex = implode("|", array_map('preg_quote', $this->typograph_prepositions));
		$text = preg_replace("/(?<![{$letter}])({$regex})([ \t]|&nbsp;)+/si", "\\1&nbsp;", $text);

		// ������� � ������������ ����������.
		$regex = implode("|", array_map('preg_quote', $this->typograph_particles));
		$text = preg_replace("/([ \t]|&nbsp;)+({$regex})(?![{$letter}])/si" , "&nbsp;\\2", $text);

		// ����� ������ � ����������� ������ ������ ������ ����������� ������,
		// ���������� �� ����, ��� ��� �� ����� (����� ����� ����).
		$text = preg_replace("/((?:^|[\.,\:;\s\t])[\\d]+)([\s\t]|&nbsp;)+(?=[{$litera}]+)/si", "\\1&nbsp;", $text);

		return $text;
	}

	/**
	 * ������� �� ����������� �������� � ������.
	 * ���������� �� optimize_tags() ���, ��� ����������� ��� �� ����,
	 * ��� ������������� �������� ����.
	 *
	 * @param string $test
	 * @return string
	 */
	protected function optimize_spaces($text) {
		// ��������� ����������� ������ �� ���������� ��� ������� ��������.
		$text = preg_replace("/[ \t]*(&nbsp;)[ \t]*/si", "\\1", $text);

		// ����� ������ ������ �������� �������� �����.
		// � ����� ��� ���������� ������� �������� ����� ��������.
		$text = preg_replace("/[ \t]+/", " ", $text);

		return $text;
	}

	/**
	 * ������� �� ����������� ����� � ������.
	 * ���������� �� optimize_spaces() ���, ��� ����������� ��� ����� ����,
	 * ��� ������������� �������� ����.
	 *
	 * @param string $test
	 * @return string
	 */
	protected function optimize_tags($text) {
		// �������� ���� ����������� �������������� �� ���� ����������� ��������������.
		//	$text = preg_replace("/<(b)>(.*?)<\\/\\1>/si", "<strong>\\2</strong>", $text);
		//???	$text = preg_replace("/<(i)>(.*?)<\\/\\1>/si", "<em>\\2</em>", $text);

		// ������� ��� ���� nobr, ������� ��������� ����� ������ ������ ����� nobr.
		// ����� ���� ����� �� ������ ����� ����� ������� ���� ���������� ����� ���������� ����������,
		// � �������� �� ������ ���� ������������� ������ ����� �������� ���������� � ������ ������ �����
		// (����� ��� ��������� � ���� �������� � �������� ��� ��������� ��� �� ���������).
		do {
			$count = null;
			$text = preg_replace("/<(nobr|strong)>(?:<\\1>)+(.*?)(?:<\\/\\1>){2,}/si", "<\\1>\\2</\\1>" , $text, -1, $count);
		} while ($count);

		return $text;
	}

}
?>