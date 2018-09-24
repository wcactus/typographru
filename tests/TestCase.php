<?php

namespace Wcactus\TypographRu\Test;

use PHPUnit\Framework\TestCase;
use Wcactus\TypographRu\UnicodeProcessor;
use Wcactus\TypographRu\Win1251Processor;

class TestTypographRu extends TestCase
{
	public function testUnicodeProcessor() {
		$source = file_get_contents(__DIR__ . '/source_utf8.txt');
		
		$processor = new UnicodeProcessor;
		$result = $processor->process($source);
		
		$expected = file_get_contents(__DIR__ . '/expected_utf8.txt');
		$this->assertEquals($expected, $result);
	}
	
	public function testWin1251Processor() {
		$source = file_get_contents(__DIR__ . '/source_win1251.txt');
		
		$processor = new Win1251Processor;
		$result = $processor->process($source);
		
		$expected = file_get_contents(__DIR__ . '/expected_win1251.txt');
		$this->assertEquals($expected, $result);
	}
}