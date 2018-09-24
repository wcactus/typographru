<?php

namespace Wcactus\TypographRu;

/**
 * HTML-entities-based screen typography processor.
 */
class Win1251Processor {

	// Знаки, перед которым не ставится пробела, и после которых обычно идёт пробел.
	protected $typograph_punctuations = ['.', ',', ':', ';', '?', '!'];

	// Знаки кавычек, которые подвергаются автоформатированию.
	protected $typograph_quotations = [
		"&laquo;",	// открывающая кавычка-ёлочка (рус)
		"&raquo;",	// закрывающая кавычка-ёлочка (рус)
		"&bdquo;",	// открывающая нижняя кавычка-лапка (рус)
		"&ldquo;",	// закрывающая верхняя кавычка-лапка (рус), она же открывающая двойная кавычка (евро)
		"&rdquo;",	// закрывающая двойная кавычка (евро)
		"&lsquo;",	// открывающая одинарная кавычка (евро)
		"&rsquo;",	// закрывающая одинарная кавычка (евро)
		"&sbquo;",	// одинарная нижняя кавычка (евро?)
		"&apos;" ,	// апостроф
		"&quot;",	// кавычка
	];

	// Знаки, которые транслируются в html entities.
	//todo: не учитывает формы типа &#x0020; или &#032; и т.п.
	protected $typograph_entities = [
		' '			=> ["&#x20;", "&#32;"],			// простой пробел (нетривиальные формы написания)
		'%'			=> ["&#x25;", "&#37;"],			// процент (нетривиальные формы написания)
		'&amp;'		=> ["&#x26;", "&#38;"],			// амперсанд (нетривиальные формы написания)
		'&lt;'		=> ["<", "&#x3C;", "&#60;"],		// угловые кавычки
		'&gt;'		=> [">", "&#x3E;", "&#62;"],		// ... (как спец-символы языка html)

		'&laquo;'	=> ["\xAB", "&#xAB;", "&#171;"],	// кавычки (см. выше)
		'&raquo;'	=> ["\xBB", "&#xBB;", "&#187;"],
		'&bdquo;'	=> ["\x84", "&#x84;", "&#132;"],
		'&ldquo;'	=> ["\x93", "&#x93;", "&#147;"],
		'&rdquo;'	=> ["\x94", "&#x94;", "&#148;"],
		'&lsquo;'	=> ["\x91", "&#x91;", "&#145;"],
		'&rsquo;'	=> ["\x92", "&#x92;", "&#146;"],
		'&sbquo;'	=> ["\x82", "&#x82;", "&#130;"],
		'&apos;'	=> ["\x27", "&#x27;", "&#39;"],
		'&quot;'	=> ["\x22", "&#x22;", "&#34;"],

		'&#167;'	=> ["\xA7", "&#xA7;", "&#167;"],	// параграф
		'&#176;'	=> ["\xB0", "&#xB0;", "&#176;"],	// пустой кружок
		'&bull;'	=> ["\x95", "&#x95;", "&#149;"],	// заполненный кружок
		'&ndash;'	=> ["\x96", "&#x96;", "&#150;"],	// дефис
		'&mdash;'	=> ["\x97", "&#x97;", "&#151;"],	// тире
		'&#8470;'	=> ["\xB9", "&#xB9;", "&#185;"],	// номер
		'&plusmn;'	=> ["\xB1", "&#xB1;", "&#177;"],	// плюс-минус
		'&hellip;'	=> ["\x85", "&#x85;", "&#133;"],	// многоточие
		'&nbsp;'	=> ["\xA0", "&#xA0;", "&#160;"],	// неразрывный пробел

		'&reg;'		=> ["\xAE", "&#xAE;", "&#174;", "(r)"],		// registered
		'&copy;'	=> ["\xA9", "&#xA9;", "&#169;", "(c)"],		// copyright
		'&trade;'	=> ["\x99", "&#x99;", "&#153;", "(t)", "(tm)"],	// trademark
	];

	// Предлоги, которые идут неразрывно с последующим словом,
	// частицы и сокращения, которые идут неразрывно с предшествующим словом.
	protected $typograph_prepositions = [
		'я',
		'ты',
		'мы',
		'вы',
		'он',
		'ее',
		'её',
		'ей',
		'на',
		'не',
		'ни',
		'но',
		'ну',
		'ай',
		'ой',
		'от',
		'ото',
		'ох',
		'до',
		'да',
		'за',
		'из',
		'изо',
		'из-за',
		'по',
		'над',
		'под',
		'при',
		'про',
		'для',
		'без',
		'а',
		'ай',
		'аж',
		'ах',
		'уж',
		'ух',
		'фу',
		'чу',
		'ж',
		'ишь',
		'эк',
		'эка',
		'эко',
		'и',
		'или',
		'у',
		'к',
		'ко',
		'в',
		'во',
		'с',
		'со',
		'о',
		'об',
		'обо',
	];

	protected $typograph_particles = [
		'&mdash;', '%',
		'см',
		'кг',
		'км',
		'м',
		'г',
		'гг',
		'г.г',
		'р',
		'руб',
		'ли',
		'ль',
		'же',
		'ж',
		'б',
		'бы',
		'да',
	];

	// Префиксы и суффиксы слов с дефисом, которые надо заключать в <nobr>.
	protected $typograph_dashword_fulltext = [
		'ей-же-ей',
		'из-за',
		'из-под',
		'из-подо',
		'по-над',
		'по-за',
		'да-да',
		'и т. д.',
		'и т. п.',
		'и пр.',
		'т. е.',
		'т. к.',
		'т. о.',
	];
	protected $typograph_dashword_prefixes = [
		'кое-',
		'кой-',
		'кои-',
		'кто-',
		'чей-',
		'в-',
		'во-',
		'как-',
		'по-',
		'какой-',
	];
	protected $typograph_dashword_suffixes = [
		'-ка',
		'-то',
		'-тка',
		'-таки',
		'-либо',
		'-нибудь',
		'-никакой',
		'-ка',
		'-с',
		'-де',
		'-го',
		'-ой',
	];
	protected $typograph_dashword_augments = [ // комбинации: род+число(же,му,ср,мн) * падеж(име,род,дат,вин,тво,пре) * посл.цифра(1..9); и их краткие варианты.
		"th",
		"nd",
		"rd",
		"st",
		"d",

		"ая",		// же име			12456789
		"ья",		// же име			3
		"ой",		// же род+дат+тво+пре	12456789
		"ей",		// же род+дат+тво+пре	3
		"ую",		// же вин			12456789
		"ью",		// же вин			3
                    
		"ый",		// му име			1459
		"ой",		// му име			2678
		"ий",		// му име			3
		"ого",		// му род+вин		12456789
		"его",		// му род+вин		3
		"ому",		// му дат			12456789
		"ему",		// му дат			3
		"ым",		// му тво			12456789
		"им",		// му тво			3
		"ом",		// му пре			12456789
		"ем",		// му пре			3
                    
		"ое",		// ср име+вин		12456789
		"ье",		// ср име+вин		3
		"ого",		// ср род			12456789
		"его",		// ср род			3
		"ому",		// ср дат			12456789
		"ему",		// ср дат			3
		"ым",		// ср тво			12456789
		"им",		// ср тво			3
		"ом",		// ср пре			12456789
		"ем",		// ср пре			3
                    
		"ые",		// мн име			12456789
		"ьи",		// мн име			3
		"ых",		// мн род+вин+пре	12456789
		"их",		// мн род+вин+пре	3
		"ым",		// мн дат			12456789
		"им",		// мн дат			3
		"ыми",		// мн тво			12456789
		"ими",		// мн тво			3

		"ми",		// =ыми,ими
		"му",		// =ему,ому
		"го",		// =его,ого
		"й",		// =ей,ой,ый,ой,ий
		"я",		// =ая,ья
		"е",		// =ое,ье,ые
		"ю",		// =ую,ью
		"м",		// =ым,им,ом,ем
		"х",		// =ых,их
		"и",		// =ьи (ыми, ими, НО не встречается, ибо бред, от трёх букв так не сокращают)
		"у",		// =(ому,ему, НО не встречается, ибо бред, от трёх букв так не сокращают)
		"о",		// =(его,ого, НО не встречается, ибо бред, от трёх букв так не сокращают)
	];

	protected $cut_tags_dictionary;
	
	protected $use_mdash;
	
	function __construct($use_mdash = false) {
		$this->use_mdash = $use_mdash;
	}

	/**
	 * Функция по типографскому форматированию текста.
	 *
	 * @param string $text
	 * @param string $language
	 * @param boolean $force_quotes
	 * @return string
	 */
	public function process($text, $language = null, $force_quotes = true) {
		// Убираем из текста все теги, замещая их специальными конструкциями (ключи по словарю).
		$dictionary = null;// чтобы не ругался что переменная не определена.
		$text = $this->cut_tags($text, $dictionary);

		// Преобразуем типографские символы, их эмуляции, а также отдельностоящие амперсанды в html entities.
		$text = $this->decode_entities($text);

		// Проводим базовую пунктуацию текста.
		$text = $this->punctuate($text);

		// Обрабатываем кавычки в тексте.
		$text = $this->fix_quotes($text, $force_quotes, $language);

		// Форматируем слова (с дефисами, а также предлоги и частицы).
		$text = $this->nobr_words($text);

		// Оптимизируем пробелы.
		$text = $this->optimize_spaces($text);

		// Восстанавливаем в тексте все ранее убранные теги.
		$text = $this->put_tags($text, $dictionary);

		// Удаляем дуплицирующиеся теги (уже после восстановления исходных).
		$text = $this->optimize_tags($text);

		// Возвращаем обработанный текст.
		return $text;
	}

	/**
	 * Функция замещения (cut) тегов специальной конструкцией для последующего
	 * восстановления (put) из этой конструкции. Для замещения используется
	 * словарь замен (массив-хеш, ключами которого являются спец-конструкции,
	 * а значениями - замещённые теги). Сама конструкция сделана такой,
	 * что по задумке в самом тексте встречаться не должна бы, и при этом
	 * в обработке текста всем алгоритмом никак не участвует.
	 *
	 * @param string $text
	 * @param array $dictionary
	 * @return string
	 */
	protected function cut_tags($text, &$dictionary) {
		// Убираем из текста все теги, замещая их специальными конструкциями (ключи по словарю).
		// При проверке тега учитываются значения атрибутов в кавычках, в том числе что символы
		// закрытия тега или символы кавычек могут быть вложенными в эти значения.
		$this->cut_tags_dictionary = array();
		$text = preg_replace_callback("/< ( (\".*?(?<!\\\\)\") | (\'.*?(?<!\\\\)\') | [^\'\"]*? )+ >/sx", [__CLASS__, 'cut_tags_callback'], $text);

		// Возвращаем текст с убранными тегами (заменёнными спец-маркерами тегов),
		// а также словарь замещённых тегов для последующего его восстановления.
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
	 * Функция восстановления (put) ранее замещённых тэгов.
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
	 * Функция по замене различных спец-символов, касающихся типографского
	 * форматирования текста, их эмуляций и альтернативных написаний
	 * на html entities.
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
		$regex_patterns[] = "/&(?![a-z0-9#]+;)/si"; // амперсанд вне html-entity (отдельностоящий)
		$regex_replaces[] = "&amp;";
		$text = preg_replace($regex_patterns, $regex_replaces, $text);
		return $text;
	}

	/**
	 * Функция по коррекции знаков препинания и скобок (главным образом,
	 * расстановки пробелов около них), а также по замещению общеупотребительных
	 * последовательностей на типографские символы (тире и т.п.).
	 *
	 * @param string $test
	 * @return string
	 */
	protected function punctuate($text) {
		$dash_replacement = $this->use_mdash ? "&mdash;" : "&ndash;";
		
		// Убираем пробелы перед знаками препинания.
		$regex = implode("|", array_map('preg_quote', $this->typograph_punctuations));
		$text = preg_replace("/[ \t]+(?={$regex})/si", "", $text);

		// Убираем пробелы после открывающих и до закрывающих скобок (любых типов).
		$text = preg_replace("/([\\(\\[\\<])[ \t]+/si", "\\1", $text);
		$text = preg_replace("/[ \t]+([\\)\\]\\>])/si", "\\1", $text);

		// Двойной знак минуса замещаем на тире независимо от пробелов вокруг,
		// а одинарный -- только если он окружён пробелами.
		$text = preg_replace("/[ \t]*(?<!-)--(?!-)[ \t]*/si", "&nbsp;{$dash_replacement} ", $text);
		$text = preg_replace("/[ \t]+(-|&ndash;)[ \t]+/si"  , "&nbsp;{$dash_replacement} ", $text);

		// Три подряд идущие точки замещаем типографским многоточием.
		$text = preg_replace("/\\.\\.\\./si", "&hellip;", $text);

		// Возвращаем что получилось.
		return $text;
	}

	/**
	 * Функция определения правил вёрстки (на самом деле, является ли текст
	 * русским). Ориентируется на определённый процент русских символов,
	 * так как пара-другая русских символов (если сравнивать по коду)
	 * ещё не гарантирует что текст русский.
	 *
	 * @param string $test
	 * @return string
	 */
	protected function detect_language($text) {
		$counts = count_chars($text, 0/* режим: массив, все символы, даже с частотой 0 */);
		$counts_high = array_sum(array_slice($counts, 0x80));
		$percent_high = $text == '' ? 0.0 : 100.0 * $counts_high / strlen($text);
		// 1.0 - минимальный процент русских символов
		return ($percent_high > 1.0) ? TypographRu::LANGUAGE_RU : TypographRu::LANGUAGE_RU;
	}

	/**
	 * Функция коррекции кавычек.
	 *
	 * @param string $test
	 * @param boolean @forced
	 * @param string $language
	 * @return string
	 */
	protected function fix_quotes($text, $forced = false, $language = null) {
		// Маркер, которым мы замещаем кавычки при принудительном реформате, либо ищем при автоматическом.
		$marker = "\"";// по сути константа, но можно просто здесь заменить, и будет работать правильно.

		// Проверяем язык, если он нам не указан явно.
		$language = strtolower($language === null ? $this->detect_language($text) : $language);

		// В случае принудительного переформатирования, замещаем все кавычки на спец-маркеры.
		if ($forced) {
			$regex = implode("|", array_map('preg_quote', $this->typograph_quotations));
			$text = preg_replace("/(?!&apos;s\\b)({$regex})/si", $marker, $text);
		}

		// Подготавливаем и выполняем цикл преобразования специальных скобочных маркеров
		// (в данном случае это двойные системные кавычки) в типографские кавычки.
		$result = ''; $quote_level = 0; 
		$text_len = strlen($text); 
		$marker_len = strlen($marker); $marker_pre = -$marker_len;
		while (($marker_pos = strpos($text, $marker, $marker_pre+$marker_len)) !== false)
		{
			// Определяем тип кавычки (открывающая ли это кавычка) по контексту в строке.
			// Мнимо предполагаем что строка с обеих сторон окружена пробелами.
			$lspace = ($marker_pos == 0                      ) || preg_match("/([ \t]|&nbsp;)\$/", substr($text, 0, $marker_pos));
			$rspace = ($marker_pos == ($text_len-$marker_len)) || preg_match("/^([ \t]|&nbsp;)/" , substr($text, $marker_pos+$marker_len));
			$count = substr_count($text, $marker, $marker_pos+$marker_len);//WARNING: need PHP>=5.1.0

			// Кавычка является закрывающей, если вообще есть что закрывать и одно из:
			$opening = !(($quote_level > 0) && (
					// ... если перед кавычкой идёт непробельный символ
					(!$lspace           ) ||
					// ... если кавычка окружена пробелами
					( $lspace && $rspace) ||
					// ... если кавычек осталось только чтобы позакрывать открытые
					($count < $quote_level) // строго меньше, так как мы считаем без учёта текущей кавычки
				));

			// Определяем уровень вложенности кавычки (чётность уровня), причем в случае открывающей кавычки
			// мы уровень увеличиваем ДО проверки, а в случае закрывающей -- уменьшаем ПОСЛЕ сравнения.
			// На основе уровня, типа кавычки и языка текста выбираем её текстовое представление.
			$quote = ($opening?++$quote_level:$quote_level--) % 2 ?
				// кавычки первого уровня (и всех нечётных уровней):
				($language == TypographRu::LANGUAGE_RU ? ($opening ? "&laquo;" : "&raquo;") : ($opening ? "&ldquo;" : "&rdquo;")):
				// кавычки второго уровня (и всех чётных уровней):
				($language == TypographRu::LANGUAGE_RU ? ($opening ? "&bdquo;" : "&ldquo;") : ($opening ? "&lsquo;" : "&rsquo;"));

			// Копируем в результат кусок текста от предыдущей до текущей кавычки и добавляем саму кавычку.
			$result .= substr($text, $marker_pre+$marker_len, $marker_pos-$marker_pre-$marker_len) . $quote;
			$marker_pre = $marker_pos;
		}

		// Копируем в результат кусок текста после последней кавычки (либо весь текст, если ни одной нету).
		$result .= substr($text, $marker_pre+$marker_len);
		return $result;
	}

	/**
	 * Функция по превращению специальных языковых конструкций в неразрывные.
	 *
	 * @param string $test
	 * @return string
	 */
	protected function nobr_words($text) {
		$letter = "\\w\\dа-яёА-ЯЁ-";
		$litera = "a-zA-Zа-яёА-ЯЁ";
		$number = "[\\d]";
		$dash   = "[-]";

		// Слова с дефисами (по префиксу и по суффиксу).
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

		// Предлоги.
		$regex = implode("|", array_map('preg_quote', $this->typograph_prepositions));
		$text = preg_replace("/(?<![{$letter}])({$regex})([ \t]|&nbsp;)+/si", "\\1&nbsp;", $text);

		// Частицы и общепринятые сокращения.
		$regex = implode("|", array_map('preg_quote', $this->typograph_particles));
		$text = preg_replace("/([ \t]|&nbsp;)+({$regex})(?![{$letter}])/si" , "&nbsp;\\2", $text);

		// Между числом и последующим словом должен стоять неразрывный пробел,
		// независимо от того, что это за слово (какая часть речи).
		$text = preg_replace("/((?:^|[\.,\:;\s\t])[\\d]+)([\s\t]|&nbsp;)+(?=[{$litera}]+)/si", "\\1&nbsp;", $text);

		return $text;
	}

	/**
	 * Функция по оптимизации пробелов в тексте.
	 * Отличается от optimize_tags() тем, что выполняется ещё до того,
	 * как восстановлены исходные теги.
	 *
	 * @param string $test
	 * @return string
	 */
	protected function optimize_spaces($text) {
		// Избавляем неразрывный пробел от окружающих его простых пробелов.
		$text = preg_replace("/[ \t]*(&nbsp;)[ \t]*/si", "\\1", $text);

		// Много подряд идущих пробелов замещаем одним.
		// А также все пробельные символы замещаем одним пробелом.
		$text = preg_replace("/[ \t]+/", " ", $text);

		return $text;
	}

	/**
	 * Функция по оптимизации тегов в тексте.
	 * Отличается от optimize_spaces() тем, что выполняется уже после того,
	 * как восстановлены исходные теги.
	 *
	 * @param string $test
	 * @return string
	 */
	protected function optimize_tags($text) {
		// Заменяем теги физического форматирования на теги логического форматирования.
		//	$text = preg_replace("/<(b)>(.*?)<\\/\\1>/si", "<strong>\\2</strong>", $text);
		//???	$text = preg_replace("/<(i)>(.*?)<\\/\\1>/si", "<em>\\2</em>", $text);

		// Удаляем все теги nobr, которые находятся сразу внутри других тегов nobr.
		// Такой цикл нужен на случай когда новые двойные пары образуются после сокращения предыдущих,
		// и особенно на случай если повторяющиеся группы тегов являются вложенными в группы других тегов
		// (тогда они выступают в роли контента и повторно под выражение уже не подпадают).
		do {
			$count = null;
			$text = preg_replace("/<(nobr|strong)>(?:<\\1>)+(.*?)(?:<\\/\\1>){2,}/si", "<\\1>\\2</\\1>" , $text, -1, $count);
		} while ($count);

		return $text;
	}

}
?>