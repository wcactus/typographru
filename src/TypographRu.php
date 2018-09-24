<?php

namespace Wcactus\TypographRu;

/**
 * The entry point of module.
 */
class TypographRu
{
	const LANGUAGE_RU = 'ru';
	const LANGUAGE_EN = 'en';
	
    /**
     * @var Wcactus\TypographRu\UnicodeProcessor
     */
	protected $unicodeProcessor;
	
    /**
     * @var Wcactus\TypographRu\Win1251Processor
     */
	protected $win1251Processor;

	/**
	 * Applies screen typography to unicode string.
	 * It is assumed that all special characters in the source string
	 * are represented by unicode characters, rather than HTML entities.
	 * If some HTML entities are used as special characters, they will
	 * be converted to unicode characters (except of non-typography-specific
	 * entities).
	 * All changes in the returned string are performed using unicode characters.
	 * 
	 * @param string $text
	 * @param string $language Optional. Can be TypographRu::LANGUAGE_RU, TypographRu::LANGUAGE_EN or null (i.e. auto-detect russian language).
	 * @param boolean $forceQuotes Optional. If true, the previously placed "correct" quotes will be re-placed again.
	 * @return string
	 */
	public function typograph($text, $language = null, $forceQuotes = true) {
		if (!isset($this->unicodeProcessor)) {
			$useMdash = !is_null(config('typographru.use_mdash')) ? config('typographru.use_mdash') : false;
			$this->unicodeProcessor = new UnicodeProcessor($useMdash);
		}
		
		return $this->unicodeProcessor->process($text, $language, $forceQuotes);
	}

	/**
	 * Applies screen typography to win-1251-encoded string.
	 * It is assumed that all special characters in the source string are
	 * represented by HTML entities due to the limitations of Win-1251 encoding.
	 * All changes in the returned string are performed using HTML entities.
	 * 
	 * @param string $text
	 * @param string $language Optional. Can be TypographRu::LANGUAGE_RU, TypographRu::LANGUAGE_EN or null (i.e. auto-detect russian language).
	 * @param boolean $forceQuotes Optional. If true, the previously placed "correct" quotes will be re-placed again.
	 * @return string
	 */
	public function typographWin1251($text, $language = null, $forceQuotes = true) {
		if (!isset($this->win1251Processor)) {
			$useMdash = !is_null(config('typographru.use_mdash')) ? config('typographru.use_mdash') : false;
			$this->win1251Processor = new Win1251Processor($useMdash);
		}

		return $this->win1251Processor->process($text, $language, $forceQuotes);
	}

}